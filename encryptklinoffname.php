<?php

// nyt on wild
if (!function_exists('getKeyFromFile')) {
    function getKeyFromFile($filename) {
        return file_get_contents($filename);
    }
}

if (!function_exists('encryptString')) {
    function encryptString($string) {
        $key = getKeyFromFile('key.klinoff');
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($string, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }
}

if (!function_exists('decryptString')) {
    function decryptString($string) {
        $key = getKeyFromFile('key.klinoff');
        $data = base64_decode($string);
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
?>
