<?php
$encryptionKey = "yourstrongkey";
$encryptedCookie = $_COOKIE["Madzae"];

if ($encryptedCookie) {
    $decryptedData = openssl_decrypt($encryptedCookie, "AES-256-CBC", $encryptionKey);
    $dataArray = unserialize($decryptedData);
    $googleId = $dataArray['googleId'];
    $googleName = $dataArray['googleName'];


    $conn = new mysqli("localhost", "user", "pass", "database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $valueToCheck = $googleId;
    $query = "SELECT * FROM registrations WHERE webauthnID = '$valueToCheck'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $webauthnID = $googleId;
    } else {
        header("Location: sign-webauthn.php");
        die();
    }

    $conn->close();

} else {
    header("Location: index.php");
    die();
}
 ?>

 <?php
 $conn = new mysqli("localhost", "madzaec1_admin", "leminerale1botol", "madzaec1_blog");

 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }

 $sql = "SELECT * FROM registrations WHERE webauthnID = $webauthnID";
 $result = $conn->query($sql);

 if ($result->num_rows > 0) {
 $row = $result->fetch_assoc();

 $userId = $row['userId'];
 $challenge = $row['challenge'];
 $credentialId = $row['credentialId'];
 } else {
 echo "No registration data found for the specified ID.";
 }

 $conn->close();
 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <title>Madzae</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <style>
     /* CSS styles for the container and the centered div */
     body {
       display: flex;
       align-items: center;
       justify-content: center;
       height: 100vh;
       margin: 0;
     }

     .centered-div {
       padding: 20px;
       text-align: center;
       max-width: 300px;
       width: 100%;
     }
   </style>
 </head>
 <body>

   <div class="centered-div">
     <p><?php echo $googleName; ?>, silakan gunakan Touch ID atau Face ID Anda</p>

     <img width="80" height="80" src="https://img.icons8.com/ios/100/face-id--v2.png" alt="face-id--v2"/>
     <p> </p>
     <center><button id="authButton">Authenticate</button></center>
   </div>

   <script>
     // Utility function to convert a base64 URL to a Uint8Array
     function base64URLToUint8Array(base64URL) {
       const base64 = base64URL.replace(/-/g, "+").replace(/_/g, "/");
       const rawData = window.atob(base64);
       const buffer = new Uint8Array(rawData.length);

       for (let i = 0; i < rawData.length; ++i) {
         buffer[i] = rawData.charCodeAt(i);
       }

       return buffer;
     }

     // Event listener for the authentication button
     document.addEventListener('DOMContentLoaded', () => {
       const authButton = document.getElementById("authButton");
       authButton.addEventListener("click", () => {
         // Step 1: Collect necessary data
         const userID = "<?php echo $userId; ?>";
         const challenge = new Uint8Array([<?php echo $challenge; ?>]);
         const credentialID = "<?php echo $credentialId; ?>";

         // Step 2: Get the user's registered credentials
         // Retrieve the registered credentials for the given userID from your server or a database

         // Step 3: Start the WebAuthn authentication process
         // Send the challenge and request the client to provide an authentication assertion
         // This can be done by making an API call to your server and initiating the authentication process

         // Step 4: Client-side steps
         navigator.credentials.get({
           publicKey: {
             challenge: challenge,
             allowCredentials: [{
               id: base64URLToUint8Array(credentialID),
               type: "public-key",
             }],
           },
         }).then((assertion) => {
           // Step 5: Send the authentication assertion to the server
           // Send the assertion to your server for verification
           // This can be done by making an API call to your server

           // Step 6: Server-side verification
           // On the server, verify the authentication assertion using the registered credential data and the provided challenge
           // This verification process typically involves cryptographic operations and comparing values

           // Step 7: Validate the result
           // After verifying the authentication assertion on the server-side, validate the result
           // If the assertion is valid, the user is authenticated
           // Redirect to a new URL
           // window.location.href = "https://madzae.com/api";
           // Storing payload data in session storage
           //sessionStorage.setItem('active', 'yes');
           //window.location.href = 'main.php';


           // Create a form dynamically
           const form = document.createElement('form');
           form.method = 'post';
           form.action = 'mid-main.php';

           // Create an input field for the "status" key-value pair
           const input = document.createElement('input');
           input.type = 'hidden';
           input.name = 'status';
           input.value = 'active';
           form.appendChild(input);

           // Add the form to the document body and submit it
           document.body.appendChild(form);
           form.submit();






         }).catch((error) => {
           // Handle any errors that occur during the authentication process
           window.location.href = "error.php";
         });
       });
     });
   </script>
 </body>
 </html>
