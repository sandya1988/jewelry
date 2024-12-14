<?php
function getGoldPriceFromGoldAPI($currency = 'USD')
{
    $apiKey = 'goldapi-bxej2msm4blj86b-io';

    $url = "https://www.goldapi.io/api/XAU/$currency";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "x-access-token: $apiKey",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode === 200 && $response) {
        $data = json_decode($response, true);
        if (!empty($data)) {
            return $data;
        } else {
            throw new Exception("Gold price not found in the response.");
        }
    } else {
        throw new Exception("Failed to fetch gold price. HTTP Code: $httpCode");
    }
}
