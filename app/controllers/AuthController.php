<?php

class AuthController extends Controller
{
    public function login()
    {
        if ($this->isLoggedIn()) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $error = 'Sva polja su obavezna.';
            } else {
                $userModel = $this->loadModel('User');
                $user = $userModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    header('Location: ' . BASE_URL);
                    exit;
                } else {
                    $error = 'Pogrešan email ili lozinka.';
                }
            }
        }

        $this->renderView('auth/login', ['error' => $error], 'Prijava');
    }

    public function register()
    {
        if ($this->isLoggedIn()) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);

            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $error = 'Sva polja su obavezna.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Lozinke se ne poklapaju.';
            } elseif (strlen($password) < 6) {
                $error = 'Lozinka mora imati najmanje 6 karaktera.';
            } else {
                $userModel = $this->loadModel('User');

                if ($userModel->emailExists($email)) {
                    $error = 'Email adresa je već registrovana.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $userModel->register($username, $email, $hashedPassword);
                    header('Location: ' . BASE_URL . 'auth/login');
                    exit;
                }
            }
        }

        $this->renderView('auth/register', ['error' => $error], 'Registracija');
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }
}