<?php

use GuzzleHttp\Client;

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

function calcResult($is_valid, $is_exist){
    $ok = 0;
    $ng = 0;
    $unknown = 0;
    if($is_valid == 0 && $is_exist == 1){
        $ng = 1;
    }else if($is_valid == 1 && $is_exist == 2){
        $ok = 1;
    }else if($is_valid == 1 && $is_exist == 1){
        $ng = 1;
    }else if($is_valid == 1 && $is_exist == 0){
        $unknown = 1;
    }
    return [$ok, $ng, $unknown];
}

function pushNotiToDevice($fcmToken)
{
    $serverKey = config('services.push_noti.server_key');
    $endPoint = config('services.push_noti.endpoint');
    $message = [
        'to' => $fcmToken,
        'notification' => [
            'title' => 'チェックが完了しました！',
            'body' => '結果タブでチェック結果をご確認ください。',
        ],
        'data' => [
            'type' => 'completed_checking'
        ]
    ];
    $client = new Client([
        'headers' => [
            'Authorization' => 'key=' . $serverKey,
            'Accept' => 'application/json'
        ],
    ]);
    $response = $client->post(
        $endPoint,
        [
            'json' => $message,
        ]
    );
    $response = json_decode($response->getBody()->getContents());
    if ($response->success) {
        return true;
    }
    return false;
}