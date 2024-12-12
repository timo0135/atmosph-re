<?php

function getAddrIpClient() {
    $ip = $_SERVER['REMOTE_ADDR'] === '::1' ? null : $_SERVER['REMOTE_ADDR'];
    if ($ip === null) {
        $url = "https://ipapi.co/xml/";
    }else {
        $url = "https://ipapi.co/{$ip}/xml/";
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($ch, CURLOPT_PROXY, 'www-cache:3128');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $xmlContent = curl_exec($ch);

    curl_close($ch);
    $xml = simplexml_load_string($xmlContent);
    return $xml;
}

