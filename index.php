<?php
$encryptionKey = "yourstrongkey";
$encryptedCookie = $_COOKIE["MadzaeWebauthn"];
$decryptedData = openssl_decrypt($encryptedCookie, "AES-256-CBC", $encryptionKey);
$dataArray = unserialize($decryptedData);
$webauthnStatus = $dataArray['webauthn'];

$encryptionKeyGoogle = "yourstrongkey";
$encryptedCookieGoogle = $_COOKIE["Madzae"];
$decryptedDataGoogle = openssl_decrypt($encryptedCookieGoogle, "AES-256-CBC", $encryptionKeyGoogle);
$dataArrayGoogle = unserialize($decryptedDataGoogle);
$googleName = $dataArrayGoogle['googleName'];
$googleId = $dataArrayGoogle['googleId'];

if ($webauthnStatus !== "active" && !isset($googleId)) {
    // Stay on this page
} elseif ($webauthnStatus === "active" && isset($googleId)) {
    header("Location: main.php"); // Redirect to main.php
    exit;
} elseif ($webauthnStatus !== "active" && isset($googleId)) {
    header("Location: process.php"); // Redirect to process.php
    exit;
} elseif ($webauthnStatus === "active" && !isset($googleId)) {
    header("Location: destroy.php"); // Redirect to destroy.php
    exit;
}

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<center>

  <a href="https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=YOUR_CLIENT&redirect_uri=YOUR_URI&scope=email+profile">
    <img src="btn_google_signin_light_focus_web@2x.png" alt="Sign in with Google" width="200" height="auto">
  </a>

</center>


</body>
</html>
