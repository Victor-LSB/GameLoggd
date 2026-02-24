<?php
class Game {
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }

    public function addGame($external_id,$title, $platform, $genre, $release_date, $cover_image) {
        $sql = "INSERT INTO games (external_id, title, platform, genre, release_date, cover_image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$external_id, $title, $platform, $genre, $release_date, $cover_image]);
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

    public function addGameToUser($user_id, $game_id, $status = 'backlog', $rating = null) {
        $sql = "INSERT INTO user_games (user_id, game_id, status, rating) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $game_id, $status, $rating]);
    }

    public function getGamesByUserId($user_id, $status = null, $search = null) {
        $sql = "SELECT g.*, ug.status, ug.rating FROM games g JOIN user_games ug ON g.id = ug.game_id WHERE ug.user_id = ?";

        $params = [$user_id];

        if (!empty($search)) {
            $sql .= " AND g.title LIKE ?";
            $params[] = '%' . $search . '%';
        }

        if (!empty($status)) {
            $sql .= " AND ug.status = ?";
            $params[] = $status;
            
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findGameByExternalId($external_id) {
        $sql = "SELECT id FROM games WHERE external_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$external_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } 

    public function checkUserGame($user_id, $game_id) {
        $sql = "SELECT 1 FROM user_games WHERE user_id = ? AND game_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $game_id]);
        return $stmt->fetch() !== false;
    }


    public function updateGameStatus($user_id, $game_id, $status, $rating) {
        $sql = "UPDATE user_games SET status = ?, rating = ? WHERE user_id = ? AND game_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $rating, $user_id, $game_id]);
    }

    public function deleteGameFromUser($user_id, $game_id) {
        $sql = "DELETE FROM user_games WHERE user_id = ? AND game_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$user_id, $game_id]);
    }

    public function getUserGameInfo($user_id, $game_id) {
        $sql = "SELECT * FROM user_games WHERE user_id = ? AND game_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id, $game_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateReview($user_id, $game_id, $review) {
        $sql = "UPDATE user_games SET review = ? WHERE user_id = ? AND game_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$review, $user_id, $game_id]);
    }

}




?>