<?php

namespace App;

class database_tools
{
    function __construct($debug = false)
    {
        $this->debug = $debug;
        $this->username = get_current_user();
        $host = "dwuty-db";
        $user = "MYSQL_USER";
        $pass = "MYSQL_PASSWORD";
        $this->mysqli_conn = new \mysqli($host, $user, $pass);
        if ($this->mysqli_conn->connect_error) {
            echo ("Connection failed: " . $this->mysqli_conn->connect_error);
            $this->mysqli_conn = false;
            die();
        }
    }
    function __destruct()
    {
        $this->mysqli_conn->close();
    }
    public function post_encode(
        $aryIncoming = array()
    ) {
        if (!empty($aryIncoming)) {
            return json_encode($aryIncoming, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        } else {
            return "no matching data delivered";
        }
    }
    public function sql_getfield(
        $sql = ""
    ) {
        $result = false;
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            $result = trim(mysqli_query($this->mysqli_conn, $sql)->fetch_row()[0]) ?? false;
        }
        if ($this->debug == true) {
            echo "<b>sql_getfield</b>";
            var_dump($result);
            echo "<hr>";
        } else {
            return $result;
        }
    }
    public function sql2array(
        $sql = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value) {
                $result[] = $value;
            }
            if ($this->debug == true) {
                echo "<b>sql2array</b>";
                var_dump($result);
                echo "<hr>";
            } else {
                return $result;
            }
        }
    }
    public function sql2array_pk(
        $sql = "",
        $pk = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value) {
                $result[$value[$pk]] = $value;
            }
            if ($this->debug == true) {
                echo "<b>sql2array_pk</b>";
                var_dump($result);
                echo "<hr>";
            } else {
                return $result;
            }
        }
    }
    public function sql2array_pk_value(
        $sql = "",
        $pk = "",
        $value = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value_key) {
                $result[$value_key[$pk]] = $value_key[$value];
            }
            if ($this->debug == true) {
                echo "<b>sql2array_pk_value</b>";
                var_dump($result);
                echo "<hr>";
            } else {
                return $result;
            }
        }
    }
    public function sql_exec_no_result(
        $sql = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            if ($this->debug == true) {
                echo "<b>sql_exec_no_result</b>";
                var_dump($sql);
                echo "<hr>";
            } else {
                mysqli_query($this->mysqli_conn, $sql);
            }
        }
    }
}
