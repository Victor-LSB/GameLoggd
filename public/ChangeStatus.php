<?php

session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Game.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'] ?? '';
$status = $_POST['status'] ?? '';
$rating = $_POST['rating'] ?? null;

$allowedStatuses = ['backlog', 'playing', 'completed', 'dropped'];

if (!in_array($status, $allowedStatuses) || empty($game_id)) {
    header("Location: index.php");
    exit();
}

$db = new Database();
$conn = $db->connect();
$game = new Game($conn);

$game->updateGameStatus($user_id, $game_id, $status, $rating);
header("Location: index.php");
exit();

?>