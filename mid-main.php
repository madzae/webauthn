<?php
// Retrieve the payload data
$status = $_POST['status'];

// Display the status value
//echo "Status: " . $status;


$encryptionKey = "yourstrongkey";
$MadzaeWebauthn = $_POST['status'];

$dataArray = array(
  'webauthn' => $MadzaeWebauthn
);

$serializedData = serialize($dataArray);
$encryptedData = openssl_encrypt($serializedData, "AES-256-CBC", $encryptionKey);

$cookieName = "MadzaeWebauthn";
$expiration = time() + (1 * 24 * 60 * 60); // 90 days in seconds
$secureFlag = true;

setcookie($cookieName, $encryptedData, $expiration, '/', '', $secureFlag);

$redirectURL = 'main.php';
header('Location: ' . $redirectURL);
exit;


?>
