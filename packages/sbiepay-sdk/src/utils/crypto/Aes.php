<?php

namespace Sbiepay\utils\crypto;

class Aes
{
    public const GCM_IV_LENGTH = 16;   // in bytes
    public const GCM_TAG_LENGTH = 16;  // in bytes (128 bits)

    public static function encrypt(string $base64Key, string $value): string
    {
        $key = base64_decode($base64Key);
        $iv = random_bytes(self::GCM_IV_LENGTH);

        $ciphertext = \openssl_encrypt(
            $value,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            "",
            self::GCM_TAG_LENGTH
        );

        $encrypted = $iv . $ciphertext . $tag;

        return base64_encode($encrypted);
    }

    public static function decrypt(string $base64Key, string $encryptedBase64): string | null
    {
        $key = base64_decode($base64Key);
        $raw = base64_decode($encryptedBase64);

        $iv = substr($raw, 0, self::GCM_IV_LENGTH);
        $ciphertext = substr($raw, self::GCM_IV_LENGTH, -self::GCM_TAG_LENGTH);
        $tag = substr($raw, -self::GCM_TAG_LENGTH);

        $decrypted = \openssl_decrypt(
            $ciphertext,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if($decrypted === false){
            return null;
        }

        return $decrypted;
    }
}

