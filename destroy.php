<?php
// Set expiration time in the past to delete the cookies
$expirationTime = time() - 3600; // Adjust the expiration time as needed

// Destroy the "MadzaeWebauthn" cookie
setcookie("MadzaeWebauthn", "", $expirationTime, "/");

// Destroy the "Madzae" cookie
setcookie("Madzae", "", $expirationTime, "/");
?>

<?php
header("Location: index.php");
die();
 ?>
