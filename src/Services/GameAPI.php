<?php
require_once __DIR__ . '/../../config/ConfigAPI.php';

class GameAPI {
    private $apiKey = RAWG_API_KEY;
    private $baseUrl = 'https://api.rawg.io/api/games';

    public function searchGames($query) {
        
        $url = "https://api.rawg.io/api/games?key={$this->apiKey}&search=" . urlencode($query) . "&page_size=20";
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
       //otimização
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); 
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_TCP_NODELAY, true);
        curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true); 
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); 
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, false); 
        curl_setopt($ch, CURLOPT_FORBID_REUSE, false); 
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0); 
        
        // Headers otimizados
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'User-Agent: GameLoggd/1.0'
        ]);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => $error];
        }
        
        curl_close($ch);
        return json_decode($response, true);
    }


    public function getGameDetails($gameID) {
    $url = "https://api.rawg.io/api/games/" . $gameID . "?key=" . $this->apiKey;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'GameLoggd');
    $response = curl_exec($ch);
    curl_close($ch);

    // IMPORTANTE: Decodificar para array associativo
    return json_decode($response, true);
    }

    public function translateHTML($htmlText) {
    if (empty($htmlText)) return $htmlText;


    $authKey = $_ENV['DEEPL_API_KEY'];
    $url = 'https://api-free.deepl.com/v2/translate';

    $data = http_build_query([
        'auth_key' => $authKey,
        'text' => $htmlText,
        'target_lang' => 'PT-BR',
        'tag_handling' => 'html'
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    return $result['translations'][0]['text'] ?? $htmlText;
    }
}

?>