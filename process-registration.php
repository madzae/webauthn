<?php
// Process the form submission and store the registration data in the MySQL database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the registration data from the form
  $rpName = $_POST['rpName'];
  $rpId = $_POST['rpId'];
  $userId = $_POST['userId'];
  $userName = $_POST['userName'];
  $userDisplayName = $_POST['userDisplayName'];
  $challenge = $_POST['challenge'];
  $credentialId = $_POST['credentialId'];

  $encryptionKey = "yourstrongkey";
  $encryptedCookie = $_COOKIE["MadzaeWebauthn"];
  $decryptedData = openssl_decrypt($encryptedCookie, "AES-256-CBC", $encryptionKey);
  $dataArray = unserialize($decryptedData);
  $webauthnStatus = $dataArray['webauthn'];

  $encryptionKeyGoogle = "yourstrongkey";
  $encryptedCookieGoogle = $_COOKIE["Madzae"];
  $decryptedDataGoogle = openssl_decrypt($encryptedCookieGoogle, "AES-256-CBC", $encryptionKeyGoogle);
  $dataArrayGoogle = unserialize($decryptedDataGoogle);

  $googleId = $dataArrayGoogle['googleId'];

  if (!isset($googleId)) {
      header("Location: index.php");
      exit;
  }


  // Create a new MySQLi connection
  $conn = new mysqli("localhost", "user", "pass", "database");

  // Check the connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepare the SQL statement to insert the registration data into the table
  $sql = "INSERT INTO registrations (rpName, rpId, userId, userName, userDisplayName, challenge, credentialId, webauthnID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

  // Prepare the statement
  $stmt = $conn->prepare($sql);

  // Bind the parameters
  $stmt->bind_param("ssssssss", $rpName, $rpId, $userId, $userName, $userDisplayName, $challenge, $credentialId, $googleId);

  // Execute the statement
  if ($stmt->execute()) {
    // Registration data inserted successfully
    header("Location: process.php");
  } else {
    // Error occurred while inserting data
    echo "Error: " . $stmt->error;
  }

  // Close the statement and the database connection
  $stmt->close();
  $conn->close();
}
?>
