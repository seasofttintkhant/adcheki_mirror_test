<?php

function sendRequest($url, $payload, $json_decode=true){
    $header = [
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $result = curl_exec($ch);
    if($json_decode){
        $result = json_decode($result, true);
    }    
    curl_close($ch);
    return $result;
}