<?php

namespace App;

class tools
{
    private $debug;
    function __construct($config = array())
    {
        $this->debug = !isset($config["debug"]["tools"]) ? false : $config["debug"]["tools"];
    }
    function __destruct()
    {
    }
    public function post_encode(
        $aryIncoming = array(),
        $aryEnc = array()
    ) {
        $result = "false";
        // $aryEnc = array("pass" => "sdfds");
        if (!empty($aryIncoming)) {
            if (empty($aryEnc)) {
                $result = json_encode($aryIncoming, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            } else {
                $result = $this->encrypt(json_encode($aryIncoming, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), $aryEnc["pass"]);
            }
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>TOOLS: function post_encode</b><br>";
            if (empty($aryEnc)) {
                echo "<b>encryption: off<br>";
            } else {
                echo "<b>encryption: on, pass: " . $aryEnc["pass"];
            }
            var_dump($result);
        } else {
            return $result;
        }
    }
    public function uniqueid()
    {
        $return = "a" . uniqid();
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>TOOLS: function uniqueid</b><br> ";
            var_dump($return);
        } else {
            return $return;
        }
    }
    private function encrypt(
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
    private function decrypt(
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
