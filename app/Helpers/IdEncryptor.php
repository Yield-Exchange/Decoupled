<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class IdEncryptor
{
    /**
     * Encrypt a primary ID.
     *
     * @param  int|string  $id
     * @return string|null
     */
    public static function encrypt($id)
    {
        return Crypt::encryptString($id);
    }

    /**
     * Decrypt an encrypted primary ID.
     *
     * @param  string  $encryptedId
     * @return int|string|null
     */
    public static function decrypt($encryptedId)
    {
        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Handle decryption failure (e.g., invalid encrypted ID)
            return null;
        }
    }
}
