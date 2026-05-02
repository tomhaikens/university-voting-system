<?php
class MonitorController extends Controller {
    
    public function show() {
        $posts = Post::getAll();
        $stats = [];
        
        foreach($posts as $post) {
            $stats[$post['id']] = Vote::getResultsByPost($post['id']);
        }
        
        $this->view('user/monitor', ['posts' => $posts, 'stats' => $stats]);
    }
    
    public function getStats() {
        $posts = Post::getAll();
        $data = [];
        
        foreach($posts as $post) {
            $candidates = Candidate::getByPost($post['id']);
            $postData = [];
            foreach($candidates as $candidate) {
                $voteCount = Vote::countByCandidate($candidate['id']);
                $postData[$candidate['name']] = $voteCount;
            }
            $data[$post['name']] = $postData;
        }
        
        echo json_encode($data);
    }
}
?>