<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Game.php';
require_once __DIR__ . '/../config/ConfigAPI.php';
require_once __DIR__ . '/../app/models/services/GameAPI.php';
require_once __DIR__ . '/../app/models/User.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/Login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD' === 'POST']) {
    $database = new Database();
    $db = $database->connect();
    $game = new Game($db);

    $game_id = $_POST['game_id'] ?? '';
    $review = $_POST['review'] ?? '';
    $user_id = $_SESSION['user_id'];

    if ($game->updateReview($user_id, $game_id, $review)) {
        header("Location: Details.php?id=" . $game_id);
        exit();
    } else {
        echo "Erro ao salvar a resenha. Por favor, tente novamente.";
    }
}



?>