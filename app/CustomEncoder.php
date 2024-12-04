<?php

namespace App;

class CustomEncoder{
    protected static $encrypt_method = 'aes-128-ctr';
    protected static $encrypt_key = 'yield2020&%$';

    public function __construct(){
        self::$encrypt_key = openssl_digest(self::$encrypt_key, 'SHA256', TRUE); // convert ASCII keys to binary format
    }

    private static function base64_url_encode($input) {
        return strtr(base64_encode($input), '+/=', '._-');
    }

    private static function base64_url_decode($input) {
        return base64_decode(strtr($input, '._-', '+/='));
    }

    public static function urlValueEncrypt($value){
        $value = str_replace(" ","",$value);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$encrypt_method));
        return self::base64_url_encode(bin2hex($iv) . openssl_encrypt($value, self::$encrypt_method, self::$encrypt_key, 0, $iv));
    }

    public static function urlValueDecrypt($value){
        $value = self::base64_url_decode($value);
        $iv_strlen = 2  * openssl_cipher_iv_length(self::$encrypt_method);
        if(preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $value, $regs)) {
            list(, $iv, $crypted_string) = $regs;
            if(ctype_xdigit($iv) && strlen($iv) % 2 == 0) {
                return openssl_decrypt($crypted_string, self::$encrypt_method, self::$encrypt_key, 0, hex2bin($iv));
            }
        }
        return ''; // failed to decrypt
    }
}