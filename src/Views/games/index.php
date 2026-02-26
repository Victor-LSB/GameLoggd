<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameLoggd - Meus Jogos</title>
</head>
<body>

    <h1>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2>Meus Jogos</h2>

    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="home">
        <input type="text" name="search" placeholder="Pesquisar jogos..." value="<?php echo htmlspecialchars($search_query ?? ''); ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="home">
        <select name="filter_status" onchange="this.form.submit()">
            <option value="">Filtrar por status</option>
            <option value="Backlog" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Backlog') echo 'selected'; ?>>Backlog</option>
            <option value="Jogando" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Jogando') echo 'selected'; ?>>Jogando</option>
            <option value="Completo" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Completo') echo 'selected'; ?>>Completo</option>
            <option value="Dropado" <?php if (isset($_GET['filter_status']) && $_GET['filter_status'] == 'Dropado') echo 'selected'; ?>>Dropado</option>
        </select>
    </form>

    <hr>

    <?php if (!empty($userGames) && count($userGames) > 0): ?>
        <ul>
        <?php foreach ($userGames as $game): ?>
        <div class="gameItem" id="game-<?php echo $game['id']; ?>">
            <h3>
                <a href="index.php?action=details&id=<?php echo $game['id']; ?>">
                    <?php echo htmlspecialchars($game['title']); ?>
                </a>
            </h3>
            
            <?php if (!empty($game['cover_image'])): ?>
                <img src="<?php echo htmlspecialchars($game['cover_image']); ?>" alt="<?php echo htmlspecialchars($game['title']); ?>" style="width:150px;">
            <?php endif; ?>

            <p class="gameStatus">Status: <?php echo htmlspecialchars($game['status']); ?></p>
            <p>Avaliação: <?php echo isset($game['rating']) ? htmlspecialchars($game['rating']) : 'Não avaliado'; ?></p>
            
            <form action="index.php?action=change_status" method="post" style="display:inline;">
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

            <form class="formStatus" action="index.php?action=change_status" method="post" style="display:inline;">
                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
                <input type="hidden" name="status" value="Jogando">
                <button type="submit">▶️ Jogando</button>
            </form>

            <form class="formStatus" action="index.php?action=change_status" method="post" style="display:inline;">
                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
                <input type="hidden" name="status" value="Completo">
                <button type="submit">✅ Completo</button>
            </form>

            <form class="formStatus" action="index.php?action=change_status" method="post" style="display:inline;">
                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                <input type="hidden" name="rating" value="<?php echo htmlspecialchars($game['rating']); ?>">
                <input type="hidden" name="status" value="Dropado">
                <button type="submit">❌ Dropado</button>
            </form>

            <form action="index.php?action=delete_game" method="post" style="display:inline;">
                <input type="hidden" name="game_id" value="<?php echo $game['id']; ?>">
                <button type="submit">🗑️ Remover</button>
            </form>
        </div>
        <hr>
    <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Você ainda não adicionou nenhum jogo.</p>
    <?php endif; ?>

    <br>
    <a href='index.php?action=search'>Adicionar Jogo</a><br>
    <a href='index.php?action=logout'>Sair</a>

<script src="./assets/js/status.js"></script>
</body>
</html>