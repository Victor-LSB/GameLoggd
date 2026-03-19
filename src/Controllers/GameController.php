<?php
namespace Victi\GameLoggd\Controllers;

use Victi\GameLoggd\Models\Game;
require_once __DIR__ . '/../Services/GameAPI.php'; 

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
        
        $this->api = new \GameAPI();
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
        $filter_status = $_GET['filter_status'] ?? '';
        $search_query = $_GET['search'] ?? '';
        $userGames = $this->gameModel->getGamesByUserId($user_id, $filter_status, $search_query);

        include __DIR__ . '/../Views/games/index.php';
    }

    public function search() {
        $this->checkAuth();
        $results = [];
        if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
            $data = $this->api->searchGames($_GET['q']);
            $results = $data['results'] ?? [];
        }
        include __DIR__ . '/../Views/games/search.php'; 
    }

    public function ajaxSearch() {
        $this->checkAuth();
        header('Content-Type: application/json'); 
        $query = $_GET['q'] ?? '';
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
        $gameId = $_GET['id'] ?? null;
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
        $external_id = $_POST['external_id'] ?? '';
        if (empty($external_id) || empty($_POST['title'])) {
            header("Location: index.php?action=home");
            exit();
        }
        $title = $_POST['title'] ?? '';
        $platform = $_POST['platform'] ?? '';
        $user_id = $_SESSION['user_id'];
        $cover = $_POST['cover'] ?? '';
        $release_date = $_POST['release_date'] ?? '';
        $genre = $_POST['genre'] ?? '';

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
        $game_id = $_POST['game_id'] ?? '';
        $status = $_POST['status'] ?? 'Backlog';
        $rating = (isset($_POST['rating']) && $_POST['rating'] !== '') ? (int)$_POST['rating'] : null;

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

    public function delete() {
        $this->checkAuth();
        $user_id = $_SESSION['user_id'];
        $game_id = $_POST['game_id'] ?? '';
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

        $game_id = $_POST['game_id'] ?? '';
        $review = $_POST['review'] ?? '';
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