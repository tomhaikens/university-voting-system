<?php
class VoteController extends Controller {
    
    public function dashboard() {
        // Check if user is blocked
        $user = User::findById($_SESSION['user_id']);
        if ($user['is_blocked']) {
            $_SESSION['error'] = 'You have been blocked by an administrator';
            header('Location: index.php?page=logout');
            return;
        }
        
        $data = [
            'has_voted_general' => $user['has_voted_general'],
            'has_voted_faculty' => $user['has_voted_faculty'],
            'has_voted_regional' => $user['has_voted_regional']
        ];
        
        $this->view('user/dashboard', $data);
    }
    
    public function showVotingPage($category) {
        // Check voting time
        if (!$this->isVotingTime()) {
            $_SESSION['error'] = 'Voting is only allowed between 9:00 AM and 4:00 PM';
            header('Location: index.php?page=dashboard');
            return;
        }
        
        $user = User::findById($_SESSION['user_id']);
        
        // Check if user has already voted for this category
        $hasVoted = false;
        $posts = [];
        $votedPosts = [];
        
        if ($category == 'general') {
            $hasVoted = $user['has_voted_general'];
            $posts = Post::getGeneralPosts();
            // Get which posts the user has already voted for
            foreach($posts as $post) {
                if (Vote::hasVoted($_SESSION['user_id'], $post['id'])) {
                    $votedPosts[] = $post['id'];
                }
            }
        } elseif ($category == 'faculty') {
            $hasVoted = $user['has_voted_faculty'];
            $posts = Post::getFacultyPosts($user['course']);
            foreach($posts as $post) {
                if (Vote::hasVoted($_SESSION['user_id'], $post['id'])) {
                    $votedPosts[] = $post['id'];
                }
            }
        } elseif ($category == 'regional') {
            $hasVoted = $user['has_voted_regional'];
            $posts = Post::getRegionalPosts($user['address_type']);
            foreach($posts as $post) {
                if (Vote::hasVoted($_SESSION['user_id'], $post['id'])) {
                    $votedPosts[] = $post['id'];
                }
            }
        }
        
        // If all posts in this category have been voted, mark category as voted
        if (count($votedPosts) == count($posts) && count($posts) > 0) {
            $this->updateUserVotingStatus($_SESSION['user_id'], $category);
            $_SESSION['error'] = 'You have already voted in all ' . $category . ' elections';
            header('Location: index.php?page=dashboard');
            return;
        }
        
        // Get candidates for each post
        $candidatesByPost = [];
        foreach ($posts as $post) {
            $candidatesByPost[$post['id']] = Candidate::getByPost($post['id']);
        }
        
        $this->view('user/voting', [
            'category' => $category,
            'posts' => $posts,
            'candidatesByPost' => $candidatesByPost,
            'votedPosts' => $votedPosts
        ]);
    }
    
    public function castVote() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check voting time
            if (!$this->isVotingTime()) {
                echo json_encode(['success' => false, 'message' => 'Voting is only allowed between 9:00 AM and 4:00 PM']);
                return;
            }
            
            $postId = $_POST['post_id'];
            $candidateId = $_POST['candidate_id'];
            $userId = $_SESSION['user_id'];
            
            // Get post details
            $post = Post::findById($postId);
            $user = User::findById($userId);
            
            // Validate user can vote for this post
            if (!$this->canVoteForPost($user, $post)) {
                echo json_encode(['success' => false, 'message' => 'You are not eligible to vote for this post']);
                return;
            }
            
            // Check if already voted for this post
            if (Vote::hasVoted($userId, $postId)) {
                echo json_encode(['success' => false, 'message' => 'You have already voted for this post']);
                return;
            }
            
            // Cast vote
            if (Vote::create($userId, $candidateId, $postId)) {
                // Check if all votes in this category are now complete
                $categoryComplete = $this->checkCategoryVotesComplete($userId, $post['category']);
                
                // If all votes in category are complete, update user's voting status
                if ($categoryComplete) {
                    $this->updateUserVotingStatus($userId, $post['category']);
                }
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Vote cast successfully',
                    'category_complete' => $categoryComplete
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error casting vote']);
            }
        }
    }
    
    private function isVotingTime() {
        $settings = ElectionSettings::getCurrent();
        $currentTime = date('H:i:s');
        return $currentTime >= $settings['start_time'] && $currentTime <= $settings['end_time'];
    }
    
    private function canVoteForPost($user, $post) {
        if ($post['category'] == 'general') {
            return true;
        } elseif ($post['category'] == 'faculty') {
            return $post['course_filter'] == $user['course'];
        } elseif ($post['category'] == 'regional') {
            return $post['location_filter'] == $user['address_type'];
        }
        return false;
    }
    
    private function updateUserVotingStatus($userId, $category) {
        $field = '';
        switch($category) {
            case 'general':
                $field = 'has_voted_general';
                break;
            case 'faculty':
                $field = 'has_voted_faculty';
                break;
            case 'regional':
                $field = 'has_voted_regional';
                break;
        }
        
        if ($field) {
            User::updateVotingStatus($userId, $field);
        }
    }
    
    private function checkCategoryVotesComplete($userId, $category) {
        $user = User::findById($userId);
        $posts = [];
        
        if ($category == 'general') {
            // If user already marked as completed, return true
            if ($user['has_voted_general']) {
                return true;
            }
            $posts = Post::getGeneralPosts();
        } elseif ($category == 'faculty') {
            if ($user['has_voted_faculty']) {
                return true;
            }
            $posts = Post::getFacultyPosts($user['course']);
        } elseif ($category == 'regional') {
            if ($user['has_voted_regional']) {
                return true;
            }
            $posts = Post::getRegionalPosts($user['address_type']);
        }
        
        // If no posts in this category, return false
        if (empty($posts)) {
            return false;
        }
        
        // Check if user has voted for all posts in this category
        $votedCount = 0;
        foreach ($posts as $post) {
            if (Vote::hasVoted($userId, $post['id'])) {
                $votedCount++;
            }
        }
        
        return $votedCount == count($posts);
    }
    
    // Get voting progress for a user in a specific category
    public function getVotingProgress() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not logged in']);
            return;
        }
        
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);
        $posts = [];
        
        if ($category == 'general') {
            $posts = Post::getGeneralPosts();
        } elseif ($category == 'faculty') {
            $posts = Post::getFacultyPosts($user['course']);
        } elseif ($category == 'regional') {
            $posts = Post::getRegionalPosts($user['address_type']);
        } else {
            echo json_encode(['error' => 'Invalid category']);
            return;
        }
        
        $votedPosts = [];
        $totalPosts = count($posts);
        $votedCount = 0;
        
        foreach ($posts as $post) {
            if (Vote::hasVoted($userId, $post['id'])) {
                $votedCount++;
                $votedPosts[] = $post['id'];
            }
        }
        
        echo json_encode([
            'total' => $totalPosts,
            'voted' => $votedCount,
            'remaining' => $totalPosts - $votedCount,
            'percentage' => $totalPosts > 0 ? round(($votedCount / $totalPosts) * 100) : 0,
            'voted_posts' => $votedPosts
        ]);
    }
}
?>