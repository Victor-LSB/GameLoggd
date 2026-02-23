<?php
class Game {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function addGame($title, $platform, $genre, $release_date, $cover_image) {
        $sql = "INSERT INTO games (title, platform, genre, release_date, cover_image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$title, $platform, $genre, $release_date, $cover_image]);
    }


    public function getAllGames() {
        $sql = "SELECT * FROM games";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGameById($id) {
        $sql = "SELECT * FROM games WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function findGameByTitle($title) {
        $sql = "SELECT * FROM games WHERE title LIKE ? LIMIT 20";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['%' . $title . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}




?>