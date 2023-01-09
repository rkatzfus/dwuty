<?php

namespace App;

class database_tools
{
    function __construct(
        $debug = false,
        $credentials = array()
    ) {
        $this->debug = $debug;
        if (empty($credentials)) {
            $this->host = getenv('HOST');
            $this->user = getenv('MYSQL_USER');
            $this->pass = getenv('MYSQL_PASSWORD');
            $this->database = getenv('MYSQL_DATABASE');
        } else {
            $this->host = $credentials["host"];
            $this->user = $credentials["user"];
            $this->pass = $credentials["pass"];
            $this->database = $credentials["database"];
        }
        $this->username = get_current_user();
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
    }
    function __destruct()
    {
        $this->mysqli_conn->close();
    }
    private function build_conn()
    {
        $this->mysqli_conn = new \mysqli($this->host,  $this->user, $this->pass, $this->database);
        if ($this->mysqli_conn->connect_error) {
            echo ("Connection failed: " . $this->mysqli_conn->connect_error);
            $this->mysqli_conn = false;
            die();
        }
    }
    public function sql_getfield(
        $sql = ""
    ) {
        $result = false;
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        $result = $this->chk_stmnt($sql) ? trim(mysqli_query($this->mysqli_conn, $sql)->fetch_row()[0]) ?? false : "";
        if ($this->debug == true) {
            echo "<b>sql_getfield</b>";
            var_dump($result);
            echo "<hr>";
        }
        return $result;
    }
    public function sql2array(
        $sql = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if ($this->chk_stmnt($sql)) {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value) {
                $result[] = $value;
            }
            if ($this->debug == true) {
                echo "<b>sql2array</b>";
                var_dump($result);
                echo "<hr>";
            }
        } else {
            $result = false;
        }
        return $result;
    }
    public function sql2array_pk(
        $sql = "",
        $pk = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if ($this->chk_stmnt($sql)) {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value) {
                $result[$value[$pk]] = $value;
            }
            if ($this->debug == true) {
                echo "<b>sql2array_pk</b>";
                var_dump($result);
                echo "<hr>";
            }
        } else {
            $result = false;
        }
        return $result;
    }
    public function sql2array_pk_value(
        $sql = "",
        $pk = "",
        $value = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if ($this->chk_stmnt($sql)) {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value_key) {
                $result[$value_key[$pk]] = $value_key[$value];
            }
            if ($this->debug == true) {
                echo "<b>sql2array_pk_value</b>";
                var_dump($result);
                echo "<hr>";
            }
        } else {
            $result = false;
        }
        return $result;
    }
    public function sql_exec_result_id(
        $sql = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if (isset($sql) && !empty($sql)) {
            if ($this->debug == true) {
                echo "<b>sql_exec_result_id</b>";
                var_dump($sql);
                echo "<hr>";
            } else {
                mysqli_query($this->mysqli_conn, $sql);
                $identity =  mysqli_insert_id($this->mysqli_conn);
                if ($identity) {
                    return $identity;
                }
            }
        }
    }
    public function chk_stmnt(
        $sql = ""
    ) {
        $result = false;
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if (isset($sql) && !empty($sql)) {
            $result = (mysqli_num_rows(mysqli_query($this->mysqli_conn, $sql)) <> 0) ? true : false;
        }
        return $result;
    }
    public function rmv_alias(
        $alias_value = "",
        $type = "field"
    ) {
        switch ($type) {
            case 'field':
                $find = strpos($alias_value, ".");
                $result = $find ? substr($alias_value, $find + 1) : $alias_value;
                break;
            case 'table':
                $find = strpos($alias_value, " ");
                $result = $find ? substr($alias_value, 0, $find) : $alias_value;
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }
}
