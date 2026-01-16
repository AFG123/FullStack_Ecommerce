<?php
require_once __DIR__ . '/Controller.php';

class SupportController extends Controller {
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->requireLogin();

            $subject = $_POST['subject'] ?? '';
            $message = $_POST['message'] ?? '';

            if (empty($subject) || empty($message)) {
                $error = "All fields are required";
                $this->render('support/contact', ['error' => $error]);
                return;
            }

            // Insert support ticket
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO support_tickets (user_id, subject, message) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $subject, $message]);

            $success = "Your message has been sent. We'll get back to you soon.";
            $this->render('support/contact', ['success' => $success]);
            return;
        }

        $this->render('support/contact');
    }

    public function faq() {
        $db = Database::getInstance()->getConnection();
        $faqs = $db->query("SELECT * FROM faqs ORDER BY created_at DESC")->fetchAll();

        $this->render('support/faq', ['faqs' => $faqs]);
    }
}
?>