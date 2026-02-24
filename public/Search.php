<?php 

session_start();

require_once __DIR__ . '/../app/services/GameAPI.php';

$results = [];

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $api = new GameAPI();
    $data = $api->searchGames($_GET['q']);
    $results = $data['results'] ?? []; 
}


$cover = $game['background_image'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
</head>
<body>
    <form action="" method="get">
        <input type="text" name="q" placeholder="Buscar por jogos..." required>
        <button type="submit">Buscar</button>
    </form>

    <?php foreach ($results as $game): ?>
    
    <h3><?php echo htmlspecialchars($game['name']); ?></h3>
        <div>
            <?php if (!empty($game['background_image'])): ?>
                <img src="<?php echo htmlspecialchars($game['background_image']); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>" style="width:200px;">
                <?php endif; ?>
            <form action="add_game.php" method="post">
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
                $platformsString = implode(', ', $platforms);
                echo htmlspecialchars($platformsString);
                ?>">
                <?php
                $genres = [];
                if (!empty($game['genres'])) {
                    foreach ($game['genres'] as $genre) {
                        $genres[] = $genre['name'];
                    }
                }
                $genresString = implode(', ', $genres);
                ?>
                <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genresString); ?>">
                <input type="hidden" name="release_date" value="<?php echo htmlspecialchars($game['released']); ?>">
                <button type="submit">Adicionar à minha coleção</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>