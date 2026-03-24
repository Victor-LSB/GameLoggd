<?php
namespace Victi\GameLoggd\Controllers;

use Victi\GameLoggd\Models\Game;
use Victi\GameLoggd\Services\GameAPI;

class GameController {
    private $db;
    private $gameModel;
    private $api;

    public function __construct() {
        require_once __DIR__ . '/../../config/Database.php';
        $database = new \Database();
        $this->db = $database->connect();
        
        // Proteção: Se a BD falhar, não tentamos criar o Model para evitar Fatal Error
        if ($this->db) {
            $this->gameModel = new Game($this->db);
        }
        
        $this->api = new GameAPI();
    }

    private function checkAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function index() {
        $this->checkAuth();
        
        $user_id = $_SESSION['user_id'];
        $filter_status = filter_input(INPUT_GET, 'filter_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $search_query = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $userGames = $this->gameModel->getGamesByUserId($user_id, $filter_status, $search_query);

        include __DIR__ . '/../Views/games/index.php';
    }

    public function search() {
        $this->checkAuth();
        $results = [];
        $q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        if (!empty(trim($q))) {
            $data = $this->api->searchGames($q);
            $results = $data['results'] ?? [];
        }
        include __DIR__ . '/../Views/games/search.php'; 
    }

    public function ajaxSearch() {
        $this->checkAuth();
        header('Content-Type: application/json'); 
        $query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        if (!empty(trim($query))) {
            $data = $this->api->searchGames($query);
            echo json_encode(['results' => $data['results'] ?? []]);
        } else {
            echo json_encode(['results' => []]);
        }
        exit(); 
    }

    public function details() {
        $this->checkAuth();
        $gameId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? null;
        if (!$gameId) {
            header("Location: index.php?action=home");
            exit();
        }

        $userGameInfo = $this->gameModel->getUserGameInfo($_SESSION['user_id'], $gameId);
        if (!$userGameInfo) {
            header("Location: index.php?action=home");
            exit();
        }

        // Lógica de cache da descrição para performance
        if (!empty($userGameInfo['description'])) {
            $gameDetails = [
                'name' => $userGameInfo['title'],
                'description' => $userGameInfo['description'],
                'background_image' => $userGameInfo['cover_image']
            ];
            $imagePath = $userGameInfo['cover_image'];
        } else {
            $gameDetails = $this->api->getGameDetails($userGameInfo['external_id']);
            if (!is_array($gameDetails)) { $gameDetails = []; }

            if (isset($gameDetails['description']) && !empty($gameDetails['description'])) {
                $translatedText = $this->api->translateHTML($gameDetails['description']);
                $gameDetails['description'] = $translatedText;
                
                // Só tentamos atualizar se o método existir no model
                if (method_exists($this->gameModel, 'updateGameDescription')) {
                    $this->gameModel->updateGameDescription($gameId, $translatedText);
                }
            }
            $imagePath = $gameDetails['background_image'] ?? $gameDetails['cover_image'] ?? '';
        }

        include __DIR__ . '/../Views/games/details.php'; 
    }

    public function add() {
        $this->checkAuth();
        $external_id = filter_input(INPUT_POST, 'external_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        if (empty($external_id) || empty($title)) {
            header("Location: index.php?action=home");
            exit();
        }
        $platform = filter_input(INPUT_POST, 'platform', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $user_id = $_SESSION['user_id'];
        $cover = filter_input(INPUT_POST, 'cover', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $release_date = filter_input(INPUT_POST, 'release_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';

        $existingGame = $this->gameModel->findGameByExternalId($external_id);
        if (!$existingGame) {
            $this->gameModel->addGame($external_id, $title, $platform, $genre, $release_date, $cover);
            $game_id = $this->db->lastInsertId();
        } else {
            $game_id = $existingGame['id'];
        }

        $alreadyAdded = $this->gameModel->checkUserGame($user_id, $game_id);
        if (!$alreadyAdded) {
            $this->gameModel->addGameToUser($user_id, $game_id);
        }
        header("Location: index.php?action=home");
        exit();
    }

    public function changeStatus() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];
        $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'Backlog';
        $rating = (filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== null) ? (int)filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        $allowedStatuses = ['Backlog', 'Jogando', 'Completo', 'Dropado'];
        if (!in_array($status, $allowedStatuses) || empty($game_id)) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
                exit();
            }
            header("Location: index.php?action=home");
            exit();
        }

        $this->gameModel->updateGameStatus($user_id, $game_id, $status, $rating);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }

        header("Location: index.php?action=home");
        exit();
    }

    public function changeRating() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];
        $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $rating = (filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_FULL_SPECIAL_CHARS) !== null) ? (int)filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        if (empty($game_id)) {
            header("Location: index.php?action=home");
            exit();
        }

        $this->gameModel->updateGameStatus($user_id, $game_id, $rating);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        }
    }

    public function delete() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];
        $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        if (empty($game_id)) {
            header("Location: index.php?action=home");
            exit();
        }
        $this->gameModel->deleteGameFromUser($user_id, $game_id);
        header("Location: index.php?action=home");
        exit();
    }

    public function saveReview() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=home");
            exit();
        }

        $game_id = filter_input(INPUT_POST, 'game_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
        $user_id = $_SESSION['user_id'];

        if ($game_id) {
            if ($this->gameModel->updateReview($review, $user_id, $game_id)) {
                $_SESSION['review_success'] = true; 
                header("Location: index.php?action=details&id=" . $game_id);
                exit();
            }
        }
        header("Location: index.php?action=home");
        exit();
    }
}