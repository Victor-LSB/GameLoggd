<?php
require_once __DIR__ . '/../../../config/ConfigAPI.php';

class GameAPI {
    private $apiKey = RAWG_API_KEY;
    private $baseUrl = 'https://api.rawg.io/api/games';

    public function searchGames($query) {
        // Usa IP direto para evitar DNS lookup (mais rápido)
        $url = "https://api.rawg.io/api/games?key={$this->apiKey}&search=" . urlencode($query) . "&page_size=20";
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        // Otimizações agressivas
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Timeout muito curto
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // Conexão rápida
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); // Múltiplas compressões
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_TCP_NODELAY, true);
        curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true); // TCP Fast Open
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Não segue redirects
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, false); // Reutiliza conexões
        curl_setopt($ch, CURLOPT_FORBID_REUSE, false); // Permite reutilização
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0); // Zero redirects
        
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
}

?>