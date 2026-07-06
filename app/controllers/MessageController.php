<?php

class MessageController extends Controller
{
    public function index()
    {
        $this->requireLogin();
        $messageModel = $this->loadModel('Message');

        if ($this->isAdmin() || $this->isManager()) {
            $messages = $messageModel->getAllMessages();
        } else {
            $messages = $messageModel->getMessagesByUser($_SESSION['user_id']);
        }

        $this->renderView('messages/index', [
            'messages' => $messages
        ], 'Poruke');
    }

    public function show($id)
    {
        $this->requireLogin();
        $this->validateId($id);

        $messageModel = $this->loadModel('Message');
        $message = $messageModel->getMessageById($id);

        if (!$message) {
            http_response_code(404);
            echo "<h1>404 - Poruka nije pronađena</h1>";
            exit;
        }

        if (!$this->isAdmin() && !$this->isManager() && $message['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }

        $this->renderView('messages/show', [
            'message' => $message
        ], 'Detalji poruke');
    }

    public function send()
    {
        $this->requireLogin();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);

            if (empty($subject) || empty($message)) {
                $error = 'Sva polja su obavezna.';
            } else {
                $messageModel = $this->loadModel('Message');
                $messageModel->sendMessage($_SESSION['user_id'], $subject, $message);
                header('Location: ' . BASE_URL . 'messages');
                exit;
            }
        }

        $this->renderView('messages/send', [
            'error' => $error
        ], 'Pošalji poruku');
    }

    public function reply($id)
    {
        $this->requireManager();
        $this->validateId($id);

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = trim($_POST['response']);

            if (empty($response)) {
                $error = 'Odgovor ne može biti prazan.';
            } else {
                $messageModel = $this->loadModel('Message');
                $messageModel->replyToMessage($id, $response);
                header('Location: ' . BASE_URL . 'messages');
                exit;
            }
        }

        $messageModel = $this->loadModel('Message');
        $message = $messageModel->getMessageById($id);

        $this->renderView('messages/reply', [
            'message' => $message,
            'error' => $error
        ], 'Odgovori na poruku');
    }

    public function delete($id)
    {
        $this->requireManager();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = $this->loadModel('Message');
            $messageModel->deleteMessage($id);
        }

        header('Location: ' . BASE_URL . 'messages');
        exit;
    }
}
