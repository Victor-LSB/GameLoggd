<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title><?php echo htmlspecialchars($gameDetails['name']); ?> - Detalhes</title>
</head>
<body>
    <a href="index.php?action=home">Voltar</a>

    <h1><?php echo htmlspecialchars($gameDetails['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Capa do jogo" style="width:200px;"><br>

    <h3>Sobre o jogo:</h3>
    <div><?php echo $gameDetails['description']; ?></div>

    <hr>

    <h3> Minha Resenha </h3>
    <form action="index.php?action=save_review" method="post">
        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($gameId); ?>">
        <textarea name="review" rows="5" cols="50" placeholder="Escreva o que achou do jogo..."><?php echo isset($userGameInfo['review']) ? htmlspecialchars($userGameInfo['review']) : ''; ?></textarea><br>
        <button type="submit">Salvar Resenha</button>
    </form>
</body>
</html>