<?php
namespace Victi\GameLoggd\Models;

use PDO;

class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExists($email) {
        $sql = "SELECT 1 FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
       return $stmt->fetch() !== false;
    }

    public function usernameExists($username) {
        $sql = "SELECT 1 FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }

    public function register($username, $email, $password) {
        if ($this->emailExists($email) || $this->usernameExists($username)) {
            return false;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO " . $this->table . " (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$username, $email, $hashed_password]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return false;
        }
    }

    // NOVO: Buscar usuário pelo Username (para URL pública)
    public function getUserByUsername($username) {
        $sql = "SELECT id, username, email, display_name, bio, avatar, banner FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NOVO: Buscar usuário pelo ID
    public function getUserById($id) {
        $sql = "SELECT id, username, email, display_name, bio, avatar, banner FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // NOVO: Atualizar perfil
    public function updateProfile($id, $displayName, $bio, $avatar, $banner) {
        $sql = "UPDATE " . $this->table . " SET display_name = ?, bio = ?, avatar = ?, banner = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$displayName, $bio, $avatar, $banner, $id]);
    }
}
?>