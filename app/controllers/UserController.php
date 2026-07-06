<?php

class UserController extends Controller
{
    public function index()
    {
        $this->requireManager();
        $userModel = $this->loadModel('User');
        $this->renderView('users/index', [
            'users' => $userModel->getAllUsers()
        ], 'Korisnici');
    }

    public function show($id)
    {
        $this->requireManager();
        $this->validateId($id);

        $userModel = $this->loadModel('User');
        $user = $userModel->getUserById($id);

        if (!$user) {
            http_response_code(404);
            echo "<h1>404 - Korisnik nije pronađen</h1>";
            exit;
        }

        $this->renderView('users/show', [
            'user' => $user
        ], 'Detalji korisnika');
    }

    public function update($id)
    {
        $this->requireManager();
        $this->validateId($id);

        $userModel = $this->loadModel('User');
        $user = $userModel->getUserById($id);

        if (!$user) {
            http_response_code(404);
            echo "<h1>404 - Korisnik nije pronađen</h1>";
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $role = $_POST['role'];

            if (empty($username) || empty($email)) {
                $error = 'Sva polja su obavezna.';
            } else {
                $userModel->updateUser($id, $username, $email, $role);
                header('Location: ' . BASE_URL . 'users');
                exit;
            }
        }

        $this->renderView('users/update', [
            'user' => $user,
            'error' => $error
        ], 'Izmeni korisnika');
    }

    public function delete($id)
    {
        $this->requireManager();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->loadModel('User');
            $userModel->deleteUser($id);
        }

        header('Location: ' . BASE_URL . 'users');
        exit;
    }
}
