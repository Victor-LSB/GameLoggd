<?php
session_start();
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Game.php';
require_once __DIR__ . '/../app/models/User.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: auth/Login.php");
    exit();
}


$db = new Database();
$conn = $db->connect();



echo "<h1>Bem-vindo, " . $_SESSION['username'] . "!</h1>";

$game = new Game($conn);
$games = $game->getAllGames();

echo "<h2>Meus Jogos</h2>";
if (count($games) > 0) {
    echo "<ul>";
    foreach ($games as $game) {
        echo "<li>" . htmlspecialchars($game['title']) . " - " . htmlspecialchars($game['platform']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Você ainda não adicionou nenhum jogo.</p>";
}
?>