<?php
$url = 'http://localhost/perikanan/storage/articles/1773380095_perikanan_indo.jpg';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$response = curl_exec($ch);
echo "Response for $url:\n";
echo $response;
