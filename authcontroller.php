<?php
class AuthController extends Controller {
    
    public function showLogin() {
        $this->view('auth/login');
    }
    
    public function showRegister() {
        $this->view('auth/register');
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $user = User::findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['faculty'] = $user['faculty'];
                $_SESSION['course'] = $user['course'];
                $_SESSION['address'] = $user['address_type'];
                
                header('Location: index.php?page=dashboard');
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                header('Location: index.php?page=login');
            }
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if passwords match
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $_SESSION['error'] = 'Passwords do not match';
                header('Location: index.php?page=register');
                return;
            }
            
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'faculty' => $_POST['faculty'],
                'course' => $_POST['course'],
                'address_type' => $_POST['address']
            ];
            
            if (User::create($data)) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: index.php?page=login');
            } else {
                $_SESSION['error'] = 'Registration failed. Email may already exist or invalid course selection.';
                header('Location: index.php?page=register');
            }
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
    }
}
?>