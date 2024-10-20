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

if (!isset($googleId)) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>WebAuthn Registration</title>
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
  <p><?php echo $googleName; ?>, silakan registrasikan Touch ID atau Face ID Anda</p>
  <form id="registrationForm">
    <input type="hidden" id="nameInput" value="<?php echo $googleName; ?>">
    <img width="80" height="80" src="https://img.icons8.com/ios/100/face-id--v2.png" alt="face-id--v2"/>
    <br>
    <center><button type="submit">Register</button></center>
  </form>
</div>
  <script>
    // Check if WebAuthn is supported in the browser
    if (!window.PublicKeyCredential) {
      console.log('WebAuthn is not supported in this browser.');
    } else {
      // Generate a random ID for the user
      const generateUserId = () => {
        const userId = document.getElementById('nameInput').value;
        const encoder = new TextEncoder();
        const encodedUserId = encoder.encode(userId);
        return new Uint8Array(encodedUserId);
      };

      // Generate a random challenge
      const generateChallenge = () => {
        const array = new Uint8Array(16);
        crypto.getRandomValues(array);
        return array;
      };

      // Handle form submission
      const form = document.getElementById('registrationForm');
      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const nameInput = document.getElementById('nameInput');
        const name = nameInput.value;

        var userID = 'Kosv9fPtkDoh4Oz7Yq/pVgWHS8HhdlCto5cR0aBoVMw='
        var id = Uint8Array.from(window.atob(userID), c=>c.charCodeAt(0))

        try {
          const publicKey = {
            rp: {
              name: 'madzae.com',
              id: 'madzae.com',
            },
            user: {
              id: id,
              name: name,
              displayName: name,
            },
            challenge: generateChallenge(),
            pubKeyCredParams: [
              { type: 'public-key', alg: -7 },
              { type: 'public-key', alg: -257 },
            ],
          };

          const newCredential = await navigator.credentials.create({ publicKey });

          const base64Id = btoa(String.fromCharCode.apply(null, new Uint8Array(newCredential.rawId)));

          // Prepare the data for POST request
          const postData = {
            rpName: publicKey.rp.name,
            rpId: publicKey.rp.id,
            userId: base64Id,
            userName: publicKey.user.name,
            userDisplayName: publicKey.user.displayName,
            challenge: Array.from(publicKey.challenge),
            credentialId: newCredential.id,
          };

          // Create a hidden form and submit it with the registration data
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'process-registration.php';

          for (const key in postData) {
            if (postData.hasOwnProperty(key)) {
              const input = document.createElement('input');
              input.type = 'hidden';
              input.name = key;
              input.value = postData[key];
              form.appendChild(input);
            }
          }

          document.body.appendChild(form);
          form.submit();
        } catch (error) {
          console.error('WebAuthn registration failed:', error);
        }
      });
    }
  </script>
</body>
</html>
