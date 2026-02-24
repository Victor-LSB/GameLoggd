<?php
require_once __DIR__ . '/../../../config/ConfigAPI.php';

class GameAPI {
    private $apiKey = RAWG_API_KEY;
    private $baseUrl = 'https://api.rawg.io/api/games';


    public function searchGames($query) {
        $url = "https://api.rawg.io/api/games?key={$this->apiKey}&search=" . urlencode($query);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa a verificação SSL

        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Define um tempo limite para a requisição

        $response = curl_exec($ch);
        return json_decode($response, true);
    }
}


?>