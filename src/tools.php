<?php

namespace App;

class tools
{
    function __construct($debug = false)
    {
        $this->debug = $debug;
    }
    function __destruct()
    {
    }
    public function post_encode(
        $aryIncoming = array()
    ) {
        if (!empty($aryIncoming)) {
            $result = json_encode($aryIncoming, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        } else {
            $result = false;
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function post_encode</b><br> ";
            var_dump($result);
        } else {
            return $result;
        }
    }
    public function encrypt(
        $data = "",
        $password = ""
    ) {
        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($password);
        $salt = sha1(mt_rand());
        $saltWithPassword = hash("sha256", $password . $salt);
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $saltWithPassword,
            0,
            $iv
        );
        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        return $msg_encrypted_bundle;
    }
    public function decrypt(
        $msg_encrypted_bundle = "",
        $password = ""
    ) {
        $password = sha1($password);
        $components = explode(":", $msg_encrypted_bundle);
        $iv            = $components[0];
        $salt          = hash("sha256", $password . $components[1]);
        $encrypted_msg = $components[2];
        $decrypted_msg = openssl_decrypt(
            $encrypted_msg,
            "aes-256-cbc",
            $salt,
            0,
            $iv
        );
        if ($decrypted_msg === false)
            return false;
        return $decrypted_msg;
    }
}
