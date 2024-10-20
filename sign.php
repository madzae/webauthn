<?php

$code = $_GET['code'];
$clientID = 'YOUR_CLIENT';
$clientSecret = 'YOU_SECRET';
$redirectUri = 'YOUR_URI';


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "code=$code&client_id=$clientID&client_secret=$clientSecret&redirect_uri=$redirectUri&grant_type=authorization_code");

$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$data = json_decode($result, true);

$tokek = $data['access_token'];

header("Location: https://yourdomain.com?token=$tokek");

//echo "<pre>";
//var_dump($result);
//echo "<pre>";

?>
