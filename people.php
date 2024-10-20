<?php

$token = $_GET['token'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/userinfo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = "Authorization: Bearer $token";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$data = json_decode($result, true);

$encryptionKey = "yourstrongkey";
$googleId = $data['sub'];
$googleName = $data['name'];

$dataArray = array(
  'googleId' => $googleId,
  'googleName' => $googleName
);

$serializedData = serialize($dataArray);
$encryptedData = openssl_encrypt($serializedData, "AES-256-CBC", $encryptionKey);

$cookieName = "Madzae";
$expiration = time() + (90 * 24 * 60 * 60); // 90 days in seconds
$secureFlag = true;

setcookie($cookieName, $encryptedData, $expiration, '/', '', $secureFlag);

$redirectURL = 'process.php';
header('Location: ' . $redirectURL);
exit;
?>
