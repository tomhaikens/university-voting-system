<?php
class AdminController extends Controller {
    
    // DASHBOARD METHOD - ADD THIS
    public function dashboard() {
        $stats = [
            'total_users' => User::count(),
            'total_votes' => Vote::count(),
            'total_candidates' => Candidate::count(),
            'voter_turnout' => $this->getVoterTurnout()
        ];
        
        $this->view('admin/dashboard', $stats);
    }
    
    public function manageCandidates() {
        $posts = Post::getAll();
        $candidates = [];
        
        foreach($posts as $post) {
            $candidates[$post['id']] = Candidate::getByPost($post['id']);
        }
        
        $this->view('admin/manage_candidates', ['posts' => $posts, 'candidates' => $candidates]);
    }
    
    public function addCandidate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'post_id' => $_POST['post_id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'course' => $_POST['course'],
                'faculty' => $_POST['faculty'],
                'manifesto' => $_POST['manifesto']
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "assets/uploads/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image_url'] = $targetFile;
                }
            }
            
            if (Candidate::create($data)) {
                $_SESSION['success'] = 'Candidate added successfully';
            } else {
                $_SESSION['error'] = 'Error adding candidate';
            }
            
            header('Location: index.php?page=admin&action=manage_candidates');
        }
    }
    
    public function editCandidate() {
        $candidateId = $_GET['id'];
        $candidate = Candidate::getWithDetails($candidateId);
        $posts = Post::getAll();
        
        $this->view('admin/edit_candidate', [
            'candidate' => $candidate,
            'posts' => $posts
        ]);
    }
    
    public function updateCandidate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $candidateId = $_POST['candidate_id'];
            $data = [];
            
            // Get current candidate to check existing image
            $currentCandidate = Candidate::findById($candidateId);
            
            // Update text fields
            $data['name'] = $_POST['name'];
            $data['email'] = $_POST['email'];
            $data['course'] = $_POST['course'];
            $data['faculty'] = $_POST['faculty'];
            $data['manifesto'] = $_POST['manifesto'];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "assets/uploads/";
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                // Delete old image if exists
                if ($currentCandidate['image_url'] && file_exists($currentCandidate['image_url'])) {
                    unlink($currentCandidate['image_url']);
                }
                
                // Upload new image
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image_url'] = $targetFile;
                }
            }
            
            if (Candidate::update($candidateId, $data)) {
                $_SESSION['success'] = 'Candidate updated successfully!';
            } else {
                $_SESSION['error'] = 'Error updating candidate';
            }
            
            header('Location: index.php?page=admin&action=manage_candidates');
        }
    }
    
    public function deleteCandidateImage() {
        $candidateId = $_GET['id'];
        
        if (Candidate::deleteImage($candidateId)) {
            $_SESSION['success'] = 'Candidate image removed successfully';
        } else {
            $_SESSION['error'] = 'Error removing image';
        }
        
        header('Location: index.php?page=admin&action=edit_candidate&id=' . $candidateId);
    }
    
    public function removeCandidate() {
        $candidateId = $_GET['id'];
        if (Candidate::delete($candidateId)) {
            $_SESSION['success'] = 'Candidate removed successfully';
        } else {
            $_SESSION['error'] = 'Error removing candidate';
        }
        header('Location: index.php?page=admin&action=manage_candidates');
    }
    
    public function manageUsers() {
        $users = User::getAllUsers();
        $this->view('admin/manage_users', ['users' => $users]);
    }
    
    public function toggleAdmin() {
        $userId = $_GET['id'];
        $user = User::findById($userId);
        $newRole = $user['role'] == 'admin' ? 'user' : 'admin';
        
        if (User::updateRole($userId, $newRole)) {
            $_SESSION['success'] = 'User role updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating user role';
        }
        
        header('Location: index.php?page=admin&action=manage_users');
    }
    
    public function blockUser() {
        $userId = $_GET['id'];
        $user = User::findById($userId);
        $newStatus = !$user['is_blocked'];
        
        if (User::updateBlockStatus($userId, $newStatus)) {
            $_SESSION['success'] = 'User status updated successfully';
        } else {
            $_SESSION['error'] = 'Error updating user status';
        }
        
        header('Location: index.php?page=admin&action=manage_users');
    }
    
    public function reports() {
        $stats = User::getVotingStats();
        $this->view('admin/reports', $stats);
    }
    
    public function downloadReport() {
        $type = $_GET['type'];
        $stats = User::getVotingStats();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="voting_report_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        if ($type == 'general') {
            fputcsv($output, ['Metric', 'Value']);
            fputcsv($output, ['Total Registered Users', $stats['total_users']]);
            fputcsv($output, ['Total Voters (General)', $stats['general_voters']]);
            fputcsv($output, ['Voter Turnout', round(($stats['general_voters'] / $stats['total_users']) * 100, 2) . '%']);
        } elseif ($type == 'faculty') {
            fputcsv($output, ['Faculty', 'Total Students', 'Voters', 'Turnout %']);
            foreach($stats['faculty_voting'] as $faculty) {
                $turnout = ($faculty['total_students'] > 0) ? round(($faculty['voted'] / $faculty['total_students']) * 100, 2) : 0;
                fputcsv($output, [$faculty['faculty'], $faculty['total_students'], $faculty['voted'], $turnout . '%']);
            }
        } elseif ($type == 'course') {
            fputcsv($output, ['Course', 'Faculty', 'Total Students', 'Voters', 'Turnout %']);
            foreach($stats['course_voting'] as $course) {
                $turnout = ($course['total_students'] > 0) ? round(($course['voted'] / $course['total_students']) * 100, 2) : 0;
                fputcsv($output, [$course['course'], $course['faculty'], $course['total_students'], $course['voted'], $turnout . '%']);
            }
        } elseif ($type == 'regional') {
            fputcsv($output, ['Location', 'Total Students', 'Voters', 'Turnout %']);
            foreach($stats['regional_voting'] as $region) {
                $turnout = ($region['total_students'] > 0) ? round(($region['voted'] / $region['total_students']) * 100, 2) : 0;
                fputcsv($output, [$region['address_type'], $region['total_students'], $region['voted'], $turnout . '%']);
            }
        }
        
        fclose($output);
        exit();
    }
    
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            
            if (ElectionSettings::update($startTime, $endTime)) {
                $_SESSION['success'] = 'Election settings updated successfully';
            } else {
                $_SESSION['error'] = 'Error updating settings';
            }
        }
        
        $settings = ElectionSettings::getCurrent();
        $this->view('admin/settings', ['settings' => $settings]);
    }
    
    private function getVoterTurnout() {
        $totalUsers = User::count();
        $totalVoters = Vote::getUniqueVoters();
        return $totalUsers > 0 ? round(($totalVoters / $totalUsers) * 100, 2) : 0;
    }
}
?>