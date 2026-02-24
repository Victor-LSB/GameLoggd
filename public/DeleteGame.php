<?php
session_start();

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Game.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/Login.php");
    exit();
}

$db = new Database();
$conn = $db->connect();
$game = new Game($conn);

$user_id = $_SESSION['user_id'];
$game_id = $_POST['game_id'] ?? '';
if (empty($game_id)) {
    header("Location: index.php");
    exit();
}
$game->deleteGameFromUser($user_id, $game_id);
header("Location: index.php");
?>