<?php
function getKeyFromFile($filename) {
    $key = trim(file_get_contents($filename));
    if ($key === false) {
        echo "Failed to read key from file.<br>";
    }
    return $key;
}

function encryptString($plaintext, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    if ($iv === false) {
        echo "Failed to generate IV.<br>";
    }
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
    if ($ciphertext === false) {
        echo "Encryption failed: " . openssl_error_string() . "<br>";
    }
    return base64_encode($iv . $ciphertext);
}

function decryptString($ciphertext_base64, $key) {
    $cipher = "aes-256-cbc";
    $ciphertext = base64_decode($ciphertext_base64);
    if ($ciphertext === false) {
        echo "Failed to decode base64 ciphertext.<br>";
    }
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($ciphertext, 0, $ivlen);
    $ciphertext_raw = substr($ciphertext, $ivlen);
    $decrypted = openssl_decrypt($ciphertext_raw, $cipher, $key, 0, $iv);
    if ($decrypted === false) {
        echo "Decryption failed: " . openssl_error_string() . "<br>";
    }
    return $decrypted;
}

$key = getKeyFromFile('key.klinoff');
?>