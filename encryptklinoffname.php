<?php
function getKeyFromFile($filename) {
    $key = trim(file_get_contents($filename));
    if ($key === false) {
        echo "Failed to read key from file.<br>";
    }
    // Ensure the key is exactly 32 bytes long
    if (strlen($key) < 32) {
        $key = str_pad($key, 32, "\0");  // Pad with null bytes to reach 32 bytes
    } elseif (strlen($key) > 32) {
        $key = substr($key, 0, 32);      // Truncate to 32 bytes if too long
    }
    return $key;
}

function encryptString($plaintext) {
    $key = getKeyFromFile('key.klinoff');
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);  // This should be 16 bytes for AES-256-CBC
    $iv = openssl_random_pseudo_bytes($ivlen);  // Generate a 16-byte IV
    if ($iv === false) {
        echo "Failed to generate IV.<br>";
        return false;
    }
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
    if ($ciphertext === false) {
        echo "Encryption failed: " . openssl_error_string() . "<br>";
        return false;
    }
    // Prepend the IV to the ciphertext and base64 encode it
    return base64_encode($iv . $ciphertext);
}

function decryptString($ciphertext_base64) {
    $key = getKeyFromFile('key.klinoff');
    $cipher = "aes-256-cbc";
    $ciphertext = base64_decode($ciphertext_base64);
    if ($ciphertext === false) {
        echo "Failed to decode base64 ciphertext.<br>";
        return false;
    }
    $ivlen = openssl_cipher_iv_length($cipher);  // 16 bytes for AES-256-CBC
    $iv = substr($ciphertext, 0, $ivlen);         // Extract the first 16 bytes as IV
    $ciphertext_raw = substr($ciphertext, $ivlen); // The rest is the actual ciphertext
    $decrypted = openssl_decrypt($ciphertext_raw, $cipher, $key, 0, $iv);
    if ($decrypted === false) {
        echo "Decryption failed: " . openssl_error_string() . "<br>";
        return false;
    }
    return $decrypted;
}

$key = getKeyFromFile('key.klinoff');
?>
