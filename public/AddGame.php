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

$external_id = $_POST['external_id'] ?? '';
if (empty($external_id) || empty($_POST['title'])){
    header("Location: index.php");
    exit();
}
$title = $_POST['title'] ?? '';
$platform = $_POST['platform'] ?? '';
$user_id = $_SESSION['user_id'];
$cover = $_POST['cover'] ?? '';
$release_date = $_POST['release_date'] ?? '';
$genre = $_POST['genre'] ?? '';

$existingGame = $game->findGameByExternalId($external_id);

if (!$existingGame) {
    $game->addGame($external_id, $title, $platform, $genre, $release_date, $cover);
    $game_id = $conn->lastInsertId();
}else {
    $game_id = $existingGame['id'];
}

$alreadyAdded = $game->checkUserGame($user_id, $game_id);

if (!$alreadyAdded) {
    $game->addGameToUser($user_id, $game_id);
}

header("Location: index.php");
exit();



?>