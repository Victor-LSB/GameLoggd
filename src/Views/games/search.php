<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameLoggd - Buscar Jogos</title>
</head>
<body>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="search">
        <input type="text" name="q" placeholder="Buscar por jogos..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <hr>

    <?php if (!empty($results)): ?>
        <?php foreach ($results as $game): ?>
            <?php $cover = $game['background_image'] ?? ''; ?>
            <h3><?php echo htmlspecialchars($game['name']); ?></h3>
            
            <div>
                <?php if (!empty($cover)): ?>
                    <img src="<?php echo htmlspecialchars($cover); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" style="width:200px;">
                <?php endif; ?>
                
                <form action="index.php?action=add_game" method="post">
                    <input type="hidden" name="external_id" value="<?php echo htmlspecialchars($game['id']); ?>">
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($game['name']); ?>">
                    <input type="hidden" name="cover" value="<?php echo htmlspecialchars($cover); ?>">
                    
                    <input type="hidden" name="platform" value="<?php
                    $platforms = [];
                    if (!empty($game['platforms'])) {
                        foreach ($game['platforms'] as $platform) {
                            $platforms[] = $platform['platform']['name'];
                        }
                    }
                    echo htmlspecialchars(implode(', ', $platforms));
                    ?>">
                    
                    <input type="hidden" name="genre" value="<?php
                    $genres = [];
                    if (!empty($game['genres'])) {
                        foreach ($game['genres'] as $genre) {
                            $genres[] = $genre['name'];
                        }
                    }
                    echo htmlspecialchars(implode(', ', $genres));
                    ?>">
                    
                    <input type="hidden" name="release_date" value="<?php echo htmlspecialchars($game['released'] ?? ''); ?>">
                    <button type="submit">Adicionar à minha coleção</button>
                </form>
            </div>
            <br>
        <?php endforeach; ?>
    <?php elseif (isset($_GET['q'])): ?>
        <p>Nenhum jogo encontrado para "<?php echo htmlspecialchars($_GET['q']); ?>"</p>
    <?php endif; ?>

    <br><hr>
    <a href="index.php?action=home">Voltar para a página inicial</a>
</body>
</html>