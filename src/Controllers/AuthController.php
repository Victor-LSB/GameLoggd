<?php
namespace Victi\GameLoggd\Controllers;

use Victi\GameLoggd\Models\User;

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        require_once __DIR__ . '/../../config/Database.php';
        $database = new \Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        $this->startSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Email e senha são obrigatórios.';
                include __DIR__ . '/../Views/auth/login.php';
                return;
            }

            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php?action=home");
                exit();
            } else {
                $error = 'Email ou senha inválidos.';
                include __DIR__ . '/../Views/auth/login.php';
                return;
            }
        }

        include __DIR__ . '/../Views/auth/login.php';
    }

    public function register() {
        $this->startSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $passwordConfirm = filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
                $error = 'Todos os campos são obrigatórios.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if ($password !== $passwordConfirm) {
                $error = '';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if (strlen($password) < 6) {
                $error = 'A senha deve ter no mínimo 6 caracteres.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Email inválido.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if ($this->userModel->emailExists($email)) {
                $error = 'Este email já está registrado.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if ($this->userModel->usernameExists($username)) {
                $error = 'Este nome de usuário já está em uso.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }

            if ($this->userModel->register($username, $email, $password)) {
                $success = 'Usuário registrado com sucesso! Faça login para continuar.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            } else {
                $error = 'Erro ao registrar usuário. Tente novamente.';
                include __DIR__ . '/../Views/auth/register.php';
                return;
            }
        }

        include __DIR__ . '/../Views/auth/register.php';
    }

    public function logout() {
        $this->startSession();
        session_destroy();
        header("Location: index.php?action=login"); 
        exit();
    }
}
?>