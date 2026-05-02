<?php
session_start();
require_once 'core/Database.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Auto-load models and controllers
spl_autoload_register(function ($class) {
    if (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    } elseif (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    }
});

$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Simple routing
switch($page) {
    case 'login':
        $auth = new AuthController();
        if($action == 'login') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;
        
    case 'register':
        $auth = new AuthController();
        if($action == 'register') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;
        
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
        
    case 'dashboard':
        if(!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        if($_SESSION['role'] == 'admin') {
            $admin = new AdminController();
            $admin->dashboard();
        } else {
            $user = new VoteController();
            $user->dashboard();
        }
        break;
        
    case 'vote':
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
            header('Location: index.php?page=login');
            exit();
        }
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $vote = new VoteController();
        if($action == 'cast') {
            $vote->castVote();
        } elseif($category) {
            $vote->showVotingPage($category);
        } else {
            header('Location: index.php?page=dashboard');
        }
        break;
        
    case 'monitor':
        $monitor = new MonitorController();
        if($action == 'get_stats') {
            $monitor->getStats();
        } else {
            $monitor->show();
        }
        break;
        
    case 'admin':
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: index.php?page=login');
            exit();
        }
        $admin = new AdminController();
        
        // Admin actions routing
        if($action == 'manage_candidates') {
            $admin->manageCandidates();
        } elseif($action == 'add_candidate') {
            $admin->addCandidate();
        } elseif($action == 'edit_candidate') {
            $admin->editCandidate();
        } elseif($action == 'update_candidate') {
            $admin->updateCandidate();
        } elseif($action == 'delete_candidate_image') {
            $admin->deleteCandidateImage();
        } elseif($action == 'remove_candidate') {
            $admin->removeCandidate();
        } elseif($action == 'manage_users') {
            $admin->manageUsers();
        } elseif($action == 'toggle_admin') {
            $admin->toggleAdmin();
        } elseif($action == 'block_user') {
            $admin->blockUser();
        } elseif($action == 'reports') {
            $admin->reports();
        } elseif($action == 'download_report') {
            $admin->downloadReport();
        } elseif($action == 'settings') {
            $admin->settings();
        } else {
            $admin->dashboard();
        }
        break;
        
    case 'api':
        header('Content-Type: application/json');
        
        if($action == 'check_time') {
            $settings = ElectionSettings::getCurrent();
            $currentTime = date('H:i:s');
            echo json_encode([
                'can_vote' => ($currentTime >= $settings['start_time'] && $currentTime <= $settings['end_time']),
                'start_time' => $settings['start_time'],
                'end_time' => $settings['end_time']
            ]);
        } elseif($action == 'get_stats') {
            $monitor = new MonitorController();
            $monitor->getStats();
        } elseif($action == 'voting_progress') {
            $vote = new VoteController();
            $vote->getVotingProgress();
        } elseif($action == 'get_candidates') {
            $postId = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
            $candidates = Candidate::getByPost($postId);
            echo json_encode($candidates);
        } elseif($action == 'check_voted') {
            $postId = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
            $userId = $_SESSION['user_id'];
            $hasVoted = Vote::hasVoted($userId, $postId);
            echo json_encode(['voted' => $hasVoted]);
        } else {
            echo json_encode(['error' => 'Invalid API action']);
        }
        break;
        
    default:
        header('Location: index.php?page=login');
        exit();
}
?>