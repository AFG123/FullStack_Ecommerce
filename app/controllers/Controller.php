<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View $view not found");
        }
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin() {
        return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'editor']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/admin/login');
        }
    }

    protected function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>