<?php

$res = "\033[0m";
$hitam = "\033[0;30m";
$abu2 = "\033[1;30m";
$putih = "\033[0;37m";
$putih2 = "\033[1;37m";
$red = "\033[0;31m";
$red2 = "\033[1;31m";
$green = "\033[0;32m";
$green2 = "\033[1;32m";
$yellow = "\033[0;33m";
$yellow2 = "\033[1;33m";
$blue = "\033[0;34m";
$blue2 = "\033[1;34m";
$purple = "\033[0;35m";
$purple2 = "\033[1;35m";
$lblue = "\033[0;36m";
$lblue2 = "\033[1;36m";

require_once('useragent.php');
$config = require_once('config.php');

$agent = new userAgent();
$ua = $agent->generate('mobile');

function get($url, $header)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip deflate');
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function post($url, $header, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip deflate');
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function verify($ua, $curr, $address)
{
    $url = 'http://uptocoin.tk/fp/verify.php';
    $header = [
        'Host: uptocoin.tk',
        'Origin: http://uptocoin.tk',
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: ' . $ua,
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Referer: http://uptocoin.tk/fp/',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cookie: '
    ];
    $data = 'address=' . $address . '&currency=' . $curr;

    return post($url, $header, $data);

}

function back($ua)
{
    $url = 'http://uptocoin.tk/fp/';
    $header = [
        'Host: uptocoin.tk',
        'Origin: http://uptocoin.tk',
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: ' . $ua,
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cookie: '
    ];

    return get($url, $header);
}

$iteration = 1;
while (true) {
    echo "Iterasi ke: $iteration\n";
    foreach ($config['wallets'] as $wallet) {
        $verify = verify($ua, $config['currency'], $wallet);
        $msg = explode('<div class="alert alert-success">', $verify);
        if (isset($msg[1])) {
            $msg = $msg[1];
            $msg = explode('<a', $msg)[0];
            $msg2 = explode('href="https://faucetpay.io/page/balance/?address=', $verify)[1];
            $msg2 = explode('">your', $msg2)[0];
            echo "$blue" . $msg . "$lblue2" . $msg2 . "\n";
        } else {
            echo "Gagal: $wallet\n";
        }
    }

    for ($i = 60; $i >= 0; $i--) {
        echo "\r            \r";
        echo "{$red}Menunggu {$putih}$i detik";
        sleep(1);
    }
    echo "\r\r";

    $back = back($ua);

    sleep(1);
    system('clear');
    $iteration++;
}
