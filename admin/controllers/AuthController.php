<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    // Login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Please fill in all fields";
                header('Location: ../../login.php');
                exit();
            }
            
            $user = $this->userModel->login($username, $password);
            
            if ($user) {

                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['logged_in'] = true;


                if (!empty($_SESSION['after_login'])) {
                    $after = $_SESSION['after_login'];
                    unset($_SESSION['after_login']);
                    // Prevent open redirect
                    if (strpos($after, 'http') === 0) {
                        header('Location: ' . BASE_URL);
                    } else {
                        header('Location: ' . BASE_URL . $after);
                    }
                    exit();
                }
                if ($user['role'] === 'admin') {
                    header('Location: ../views/dashboard.php');
                } else {
                    header('Location: ' . BASE_URL);
                }

                exit();
            } else {
                $_SESSION['error'] = "Invalid username or password";
                header('Location: ../../login.php');
                exit();
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $email = trim($_POST['email'] ?? '');
            $role = 'user';

            $password_confirm = $_POST['password_confirm'] ?? '';

            if (empty($username) || empty($password) || empty($email) || empty($password_confirm)) {
                $_SESSION['error'] = "Please fill in all fields";
                header('Location: ../../register.php');
                exit();
            }

            if ($password !== $password_confirm) {
                $_SESSION['error'] = "Passwords do not match";
                header('Location: ../../register.php');
                exit();
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Password must be at least 6 characters";
                header('Location: ../../register.php');
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email address";
                header('Location: ../../register.php');
                exit();
            }
            $stmt = Database::getInstance()->getConnection()->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                $_SESSION['error'] = "Username or email already exists";
                header('Location: ../../register.php');
                exit();
            }

            $newId = $this->userModel->create($username, $password, $email, $role);

            if ($newId) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $newId;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['email'] = $email;
                $_SESSION['logged_in'] = true;

                $_SESSION['success'] = "Account created successfully";
                header('Location: ' . BASE_URL);
                exit();
            } else {
                $_SESSION['error'] = "Failed to create account";
                header('Location: ../../register.php');
                exit();
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destroy the session
        session_destroy();
        
        header('Location: ../../login.php');
        exit();
    }
    
    // Check if user is logged in
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    // Check if user is admin
    public static function isAdmin() {
        return self::isLoggedIn() && $_SESSION['role'] === 'admin';
    }
    
    // Require login
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            // Remember requested path so we can return after successful login
            $_SESSION['after_login'] = ltrim($_SERVER['REQUEST_URI'], '/');
            header('Location: ' . BASE_URL . 'login.php');
            exit();
        }
    }
    
    // Require admin
    public static function requireAdmin() {
        self::requireLogin();
        if (!self::isAdmin()) {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: ../views/dashboard.php');
            exit();
        }
    }
}

// Handle login/logout/register actions
if (isset($_GET['action'])) {
    $controller = new AuthController();
    
    if ($_GET['action'] == 'login') {
        $controller->login();
    } elseif ($_GET['action'] == 'logout') {
        $controller->logout();
    } elseif ($_GET['action'] == 'register') {
        $controller->register();
    }
}
?>