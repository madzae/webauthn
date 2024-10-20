<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

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

if ($webauthnStatus === "active" && isset($googleId)) {

    echo "Hallo,";
    echo "<br />";
    echo $googleName;

} elseif ($webauthnStatus !== "active" && isset($googleId)) {
    header("Location: process.php"); // Redirect to process.php
    exit;
} elseif ($webauthnStatus !== "active" && !isset($googleId)) {
    header("Location: index.php"); // Redirect to index.php
    exit;
} elseif ($webauthnStatus === "active" && !isset($googleId)) {
    header("Location: index.php"); // Redirect to index.php
    exit;
}

?>


<br />
<br />
<br />
<br />
<a href="destroy.php">Sign Out</a>
<br />

</body>
</html>
