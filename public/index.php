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

$user_id = $_SESSION['user_id'];
$game = new Game($conn);
$userGames = $game->getGamesByUserId($user_id);

echo "<h2>Meus Jogos</h2>";
if (count($userGames) > 0) {
    echo "<ul>";
    foreach ($userGames as $game): ?>
    <div>
        <h3><?php echo htmlspecialchars($game['title']); ?></h3>

        <p>Status: <?php echo htmlspecialchars($game['status']); ?></p>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="backlog">
            <button type="submit">📚 Backlog</button>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="playing">
            <button type="submit">▶️ Jogando</button>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="completed">
            <button type="submit">✅ Completo</button>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="dropped">
            <button type="submit">❌ Dropado</button>
        </form>
    </div>
<?php endforeach;
    echo "</ul>";
} else {
    echo "<p>Você ainda não adicionou nenhum jogo.</p>";
}
?>
<a href='Search.php'>Adicionar Jogo</a><br>
<a href='auth/Logout.php'>Sair</a>