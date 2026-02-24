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



echo "<h2>Meus Jogos</h2>"; ?>

<form action="index.php" method="post">
    <input type="text" name="search" placeholder="Pesquisar jogos...">
    <button type="submit">Pesquisar</button>
</form>
<form action ="index.php" method="GET">
    <select name="filter_status" onchange="this.form.submit()">
        <option value="">Filtrar por status</option>
        <option value="Backlog" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'backlog') echo 'selected'; ?>>Backlog</option>
        <option value="Jogando" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Jogando') echo 'selected'; ?>>Jogando</option>
        <option value="Completo" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Completo') echo 'selected'; ?>>Completo</option>
        <option value="Dropado" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Dropado') echo 'selected'; ?>>Dropado</option>
    </select>
</form>

<?php if (count($userGames) > 0) {
    echo "<ul>";
    foreach ($userGames as $game): ?>
    <div>
        <h3><?php echo htmlspecialchars($game['title']); ?></h3>
        <?php if (!empty($game['cover_image'])): ?>
            <img src="<?php echo htmlspecialchars($game['cover_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" style="width:150px;">
        <?php endif; ?>

        <p>Status: <?php echo htmlspecialchars($game['status']); ?></p>
        <p>Avaliação: <?php echo isset($game['rating']) ? htmlspecialchars($game['rating']) : 'Não avaliado'; ?></p>
        
        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($game['status']); ?>">
            
            <select name="rating" onchange="this.form.submit()">
                <option value="">Avaliação</option>
                <option value="1" <?php if (isset($game['rating']) && $game['rating'] == 1) echo 'selected'; ?>>1</option>
                <option value="2" <?php if (isset($game['rating']) && $game['rating'] == 2) echo 'selected'; ?>>2</option>
                <option value="3" <?php if (isset($game['rating']) && $game['rating'] == 3) echo 'selected'; ?>>3</option>
                <option value="4" <?php if (isset($game['rating']) && $game['rating'] == 4) echo 'selected'; ?>>4</option>
                <option value="5" <?php if (isset($game['rating']) && $game['rating'] == 5) echo 'selected'; ?>>5</option>
            </select>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($game['status']); ?>">
            <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
            <input type="hidden" name="status" value="Jogando">
            <button type="submit">▶️ Jogando</button>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($game['status']); ?>">
            <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
            <input type="hidden" name="status" value="Completo">
            <button type="submit">✅ Completo</button>
        </form>

        <form action="ChangeStatus.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($game['status']); ?>">    
            <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
            <input type="hidden" name="status" value="Dropado">
            <button type="submit">❌ Dropado</button>
        </form>
        <form action="DeleteGame.php" method="post" style="display:inline;">
            <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
            <button type="submit">🗑️ Remover</button>
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