<?php

require_once 'config/Database.php';
require_once 'app/models/Game.php';
require_once 'app/models/User.php';
$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "Database connection successful!";

    // Test Game model
    $gameModel = new Game($conn);
    $gameModel->addGame("Test Game", "PC", "Action", "2024-01-01", "test_cover.jpg");
    $games = $gameModel->getAllGames();
    print_r($games);

    // Test User model
    $userModel = new User($conn);
    $userModel->register("testuser","test@example.com", "testpassword");
}


?>