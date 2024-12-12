<?php
require_once 'ip.php';

function getMeteo() {
    $xml = getAddrIpClient();
    $latitude = $xml->latitude;
    $longitude = $xml->longitude;
    $url = "https://www.infoclimat.fr/public-api/gfs/xml?_ll={$latitude},{$longitude}&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
    curl_setopt($ch, CURLOPT_PROXY, 'www-cache:3128');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


    $xmlContent = curl_exec($ch);

    // Charger le fichier XSL
    $xsl = new DOMDocument;
    $xsl->load(__DIR__.'/../meteo.xsl');

    // Charger le XML
    $xmlDoc = new DOMDocument;
    $xmlDoc->loadXML($xmlContent);

    // Configurer le transformateur XSLT
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);

    // Transformer le XML
    $newXml = $proc->transformToXML($xmlDoc);

    return $newXml;
}
