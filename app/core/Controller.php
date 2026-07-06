<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Controller
{
    protected $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../app/views');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('base_url', BASE_URL);
        $this->twig->addGlobal('session', $_SESSION);
    }

    protected function loadModel($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    protected function renderView($viewPath, $data = [], $title = "Suplement Store")
    {
        $data['title'] = $title;
        echo $this->twig->render($viewPath . '.twig', $data);
    }

    public function isValidId($id)
    {
        return isset($id) && ctype_digit(strval($id)) && (int)$id > 0;
    }

    protected function validateId($id)
    {
        if (!$this->isValidId($id)) {
            http_response_code(404);
            echo "<h1>404 - Stranica nije pronađena</h1>";
            exit;
        }
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    protected function isManager()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'manager';
    }

    protected function isUser()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    protected function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }
    }

    protected function requireManager()
    {
        $this->requireLogin();
        if (!$this->isAdmin() && !$this->isManager()) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }
    }

    protected function log($action)
    {
        $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] [{$user}] {$action}" . PHP_EOL;
        file_put_contents('../logs/actions.log', $logMessage, FILE_APPEND);
    }
}