<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/models/User.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($this->isLoggedIn() && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identifier = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->authenticate($identifier, $password);
            if ($user && $user['role'] == 'user') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                session_regenerate_id(true);
                $this->redirect('/');
            } else {
                $error = "Invalid email or password";
                $this->render('auth/login', ['error' => $error]);
                return;
            }
        }

        $this->render('auth/login');
    }

    public function signup() {
        if ($this->isLoggedIn() && (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin')) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['username'] ?? '';
            $contact = $_POST['contact'] ?? '';
            $password = $_POST['password'] ?? '';
            $address = $_POST['address'] ?? '';
            $pincode = $_POST['pincode'] ?? '';

            // Map contact to email column (contact may be email or phone)
            $email = $contact;

            // Basic validation
            if (empty($name) || empty($email) || empty($password)) {
                $error = "All fields are required";
                $this->render('auth/signup', ['error' => $error]);
                return;
            }

            // Check if email exists
            $existing = $this->userModel->findAll(['email' => $email]);
            if ($existing) {
                $error = "Email already exists";
                $this->render('auth/signup', ['error' => $error]);
                return;
            }

            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'address' => $address,
                'pincode' => $pincode,
                'role' => 'user'
            ]);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['role'] = 'user';
                session_regenerate_id(true);
                $this->redirect('/');
            } else {
                $error = "Registration failed";
                $this->render('auth/signup', ['error' => $error]);
                return;
            }
        }

        $this->render('auth/signup');
    }

    public function forgotPassword() {
        if ($this->isLoggedIn()) {
            $this->redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($username) || empty($newPassword)) {
                $error = "All fields are required";
                $this->render('auth/forgot_password', ['error' => $error]);
                return;
            }

            // Find user by name or email
            $users = $this->userModel->findAll(['name' => $username]);
            if (empty($users)) {
                $users = $this->userModel->findAll(['email' => $username]);
            }

            if (!empty($users)) {
                $user = $users[0];
                $this->userModel->updatePassword($user['id'], $newPassword);
                $success = "Password updated successfully. You can now login with your new password.";
                $this->render('auth/forgot_password', ['success' => $success]);
                return;
            } else {
                $error = "User not found";
                $this->render('auth/forgot_password', ['error' => $error]);
                return;
            }
        }

        $this->render('auth/forgot_password');
    }

    public function profile() {
        $this->requireLogin();

        $user = $this->userModel->find($_SESSION['user_id']);
        $purchases = $this->userModel->getPurchaseHistory($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? '';
            $pincode = $_POST['pincode'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            $updateData = [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'pincode' => $pincode
            ];

            // Check if changing password
            if (!empty($newPassword)) {
                if (password_verify($currentPassword, $user['password'])) {
                    $updateData['password'] = $newPassword;
                    $this->userModel->updatePassword($user['id'], $newPassword);
                } else {
                    $error = "Current password is incorrect";
                    $this->render('auth/profile', ['user' => $user, 'purchases' => $purchases, 'error' => $error]);
                    return;
                }
            }

            $this->userModel->update($user['id'], $updateData);
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $success = "Profile updated successfully";
            $this->render('auth/profile', ['user' => $user, 'purchases' => $purchases, 'success' => $success]);
            return;
        }

        $this->render('auth/profile', ['user' => $user, 'purchases' => $purchases]);
    }

    public function logout() {
        $isAdmin = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'editor']);
        session_destroy();
        if ($isAdmin) {
            $this->redirect('/admin/login');
        } else {
            $this->redirect('/');
        }
    }
}
?>