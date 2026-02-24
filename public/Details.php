<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/models/Game.php';
require_once __DIR__ . '/../app/models/services/GameAPI.php';
require_once __DIR__ . '/../config/ConfigAPI.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/Login.php");
    exit();
}

$gameId = $_GET['id'] ?? null;
if (!$gameId) {
    header("Location: index.php");
    exit();
}

$db = new Database();
$conn = $db->connect();
$game = new Game($conn);
$api = new GameAPI();



$userGameInfo = $game->getUserGameInfo($_SESSION['user_id'], $gameId);
if (!$userGameInfo) {
    header("Location: index.php");
    exit();
}

$gameDetails = $api->getGameDetails($userGameInfo['external_id']);

if (!is_array($gameDetails)) {
    $gameDetails = [];
}

$imagePath = "";
if (!empty($gameDetails['background_image'])) {
    $imagePath = $gameDetails['background_image'];
} elseif (!empty($gameDetails['cover_image'])) {
    $imagePath = $gameDetails['cover_image'];
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($gameDetails['name']); ?> - Detalhes</title>
</head>
<body>
    <a href="index.php">Voltar</a>

    <h1><?php echo htmlspecialchars($gameDetails['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Capa do jogo" style="width:200px;"><br>

    <h3>Sobre o jogo:</h3>
    <div><?php echo $gameDetails['description']; ?></div>

    <hr>

    <h3> Minha Resenha </h3>
    <form action="SaveReview.php" method="post">
        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($gameId); ?>">
        <textarea name="review" rows="5" cols="50" placeholder="Escreva o que achou do jogo..."><?php echo isset($userGameInfo['review']) ? htmlspecialchars($userGameInfo['review']) : ''; ?></textarea><br>
        <button type="submit">Salvar Resenha</button>
    </form>
</body>
</html>