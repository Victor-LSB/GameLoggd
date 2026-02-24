<?php

class GameAPI {
    private $apiKey = '5fb48f677bbc4d0395cbbb8606b1e551';
    private $baseUrl = 'https://api.rawg.io/api/games';


    public function searchGames($query) {
        $url = "https://api.rawg.io/api/games?key={$this->apiKey}&search=" . urlencode($query);
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}


?>