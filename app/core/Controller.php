<?php

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Bazni kontroler koji sadrži metode koje nasleđuju svi kontroleri.
 * Zadužen je za učitavanje modela, renderovanje prikaza i proveru pristupa.
 */
class Controller
{
    /**
     * @var \Twig\Environment Twig instanca za renderovanje view-ova
     */
    protected $twig;

    /**
     * Inicijalizuje Twig templating sistem.
     */
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

    /**
     * Učitava model na osnovu prosleđenog naziva.
     *
     * @param string $model Naziv modela
     * @return object Instanca modela
     */
    protected function loadModel($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Renderuje view na osnovu prosleđene putanje.
     *
     * @param string $viewPath Putanja do view fajla
     * @param array $data Podaci koji se prosleđuju view-u
     * @param string $title Naslov stranice
     * @return void
     */
    protected function renderView($viewPath, $data = [], $title = "Suplement Store")
    {
        $data['title'] = $title;
        echo $this->twig->render($viewPath . '.twig', $data);
    }

    /**
     * Proverava da li je vrednost validan pozitivan broj.
     *
     * @param mixed $id Vrednost koja se proverava
     * @return bool True ako je validan ID, false ako nije
     */
    public function isValidId($id)
    {
        return isset($id) && ctype_digit(strval($id)) && (int)$id > 0;
    }

    /**
     * Proverava ID i prekida izvrsavanje ako nije validan.
     *
     * @param mixed $id Vrednost koja se proverava
     * @return void
     */
    protected function validateId($id)
    {
        if (!$this->isValidId($id)) {
            http_response_code(404);
            echo "<h1>404 - Stranica nije pronađena</h1>";
            exit;
        }
    }

    /**
     * Proverava da li je korisnik prijavljen.
     *
     * @return bool True ako je korisnik prijavljen
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Proverava da li je korisnik admin.
     *
     * @return bool True ako je korisnik admin
     */
    protected function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Proverava da li je korisnik menadžer.
     *
     * @return bool True ako je korisnik menadžer
     */
    protected function isManager()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'manager';
    }

    /**
     * Proverava da li je korisnik obican korisnik.
     *
     * @return bool True ako je korisnik obican korisnik
     */
    protected function isUser()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
    }

    /**
     * Preusmerava na login stranicu ako korisnik nije prijavljen.
     *
     * @return void
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    /**
     * Prekida izvrsavanje ako korisnik nije admin.
     *
     * @return void
     */
    protected function requireAdmin()
    {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }
    }

    /**
     * Prekida izvrsavanje ako korisnik nije admin ili menadžer.
     *
     * @return void
     */
    protected function requireManager()
    {
        $this->requireLogin();
        if (!$this->isAdmin() && !$this->isManager()) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }
    }

    /**
     * Loguje korisničku akciju u log fajl.
     *
     * @param string $action Opis akcije
     * @return void
     */
    protected function log($action)
    {
        $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'guest';
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] [{$user}] {$action}" . PHP_EOL;
        file_put_contents('../logs/actions.log', $logMessage, FILE_APPEND);
    }
}