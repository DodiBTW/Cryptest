<?php

function getTokens() {
    $file = 'db/tokens.json';
    if (!file_exists($file)) {
        return [];
    }
    
    $data = json_decode(file_get_contents($file), true);
    return array_map(fn($item) => [$item['id'], $item['address']], $data);
}

function getPrices() {
    $file = 'db/prices.json';
    if (!file_exists($file)) {
        return [];
    }
    
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

function fetchPrice($tokenAddress) {
    $url = "https://api.dexscreener.com/latest/dex/tokens/" . $tokenAddress;
    $response = file_get_contents($url);
    
    if ($response === false) {
        return null;
    }
    
    $data = json_decode($response, true);
    return $data['pairs'][0]['priceUsd'] ?? null;
}

function savePrices($data) {
    file_put_contents('db/prices.json', json_encode($data, JSON_PRETTY_PRINT));
}

$tokens = getTokens();

while (true) {
    $data = getPrices();
    
    foreach ($tokens as $token) {
        $price = fetchPrice($token[1]);
        
        if ($price !== null) {
            $tokenId = str_replace('.', '', microtime(true));
            $newPrice = [
                "id" => (int) $tokenId,
                "token_id" => $token[0],
                "date" => date('c'),
                "price" => $price
            ];
            
            echo json_encode($newPrice, JSON_PRETTY_PRINT) . "\n";
            
            $data[] = $newPrice;
            savePrices($data);
        }
        
    }
    sleep(60);
}
