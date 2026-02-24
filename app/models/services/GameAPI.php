<?php
require_once __DIR__ . '/../../../config/ConfigAPI.php';

class GameAPI {
    private $apiKey = RAWG_API_KEY;
    private $baseUrl = 'https://api.rawg.io/api/games';


    public function searchGames($query) {
        $url = "https://api.rawg.io/api/games?key={$this->apiKey}&search=" . urlencode($query);
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}


?>