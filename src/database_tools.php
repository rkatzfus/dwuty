<?php

namespace App;

class database_tools
{
    private $debug;
    private $dbtype;
    private $host;
    private $user;
    private $pass;
    private $database;
    private $username;
    private $mysqli_conn;


    function __construct(
        $config = array()
    ) {
        $this->debug = !isset($config["debug"]["database_tools"]) ? false : $config["debug"]["database_tools"];
        $this->dbtype = !isset($config["database"]["type"]) ? "mysql" : $config["database"]["type"]; // set default db type
        $this->host = !isset($config["database"]["credentials"]["host"]) ? "unknown hostname" : getenv($config["database"]["credentials"]["host"]);
        $this->user = !isset($config["database"]["credentials"]["user"]) ? "unknown database username" : getenv($config["database"]["credentials"]["user"]);
        $this->pass = !isset($config["database"]["credentials"]["pass"]) ? "unknown database password" : getenv($config["database"]["credentials"]["pass"]);
        $this->database = !isset($config["database"]["credentials"]["database"]) ? "unknown database" : getenv($config["database"]["credentials"]["database"]);
        $this->username = get_current_user();
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
    }
    function __destruct()
    {
        $this->mysqli_conn->close();
    }
    private function build_conn()
    {
        try {
            $this->mysqli_conn = new \PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->pass);
            // $this->mysqli_conn = new \PDO("mysql:host=$this->host;dbname=$database", $this->user, $this->pass);
            // set the PDO error mode to exception
            $this->mysqli_conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            $this->mysqli_conn = false;
            die();
        }

        // $this->mysqli_conn = new \mysqli($this->host,  $this->user, $this->pass, $this->database);
        // if ($this->mysqli_conn->connect_error) {
        //     echo ("Connection failed: " . $this->mysqli_conn->connect_error);
        //     $this->mysqli_conn = false;
        //     die();
        // }
    }
    public function escape(
        $encode = ""
    ) {
        if (!is_array($encode) && !empty($encode)) {
            return htmlentities($encode, ENT_QUOTES, 'UTF-8');
        } else if (is_array($encode) && !empty($encode)) {
            $encode = array_map(function ($v) {
                return $v ?? '';
            }, $encode);
            return array_map("htmlentities", $encode);
        } else {
            return false;
        }
    }
    public function decode_escape(
        $decode = ""
    ) {
        if (!is_array($decode) && !empty($decode)) {
            return html_entity_decode($decode, ENT_QUOTES, 'UTF-8');
        } else if (is_array($decode) && !empty($decode)) {
            $decode = array_map(function ($v) {
                return $v ?? '';
            }, $decode);
            return array_map("html_entity_decode", $decode);
        } else {
            return false;
        }
    }
    public function sql_getfield(
        $sql = ""
    ) {
        $result = false;
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        $result = $this->chk_stmnt($sql) ? trim($this->decode_escape(mysqli_query($this->mysqli_conn, $sql)->fetch_row()[0])) ?? false : "";
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql_getfield</b>";
            var_dump($result);
        }
        return $result;
    }
    public function sql2array(
        $sql = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if ($this->chk_stmnt($sql)) {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value) {
                $result[] = $this->decode_escape($value);
            }
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>DATABASE TOOLS: sql2array</b>";
                var_dump($result);
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
                $result[$value[$pk]] = $this->decode_escape($value);
            }
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>DATABASE TOOLS: sql2array_pk</b>";
                var_dump($result);
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
                $result[$value_key[$pk]] = $this->decode_escape($value_key[$value]);
            }
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>DATABASE TOOLS: sql2array_pk_value</b>";
                var_dump($result);
            }
        } else {
            $result = false;
        }
        return $result;
    }
    public function sql2array_group(
        $sql = "",
        $group = ""
    ) {
        (!isset($this->mysqli_conn) || $this->mysqli_conn === false) ? $this->build_conn() : "";
        if ($this->chk_stmnt($sql)) {
            foreach ($this->mysqli_conn->query($sql)->fetch_all(MYSQLI_ASSOC) as $value_key) {
                $result[$value_key[$group]][] =  $this->decode_escape($value_key);
            }
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>DATABASE TOOLS: sql2array_pk_value</b>";
                var_dump($result);
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
                echo "<hr>";
                echo "<b>DATABASE TOOLS: sql_exec_result_id</b>";
                var_dump($sql);
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
    public function alias(
        $alias_value = "",
        $type = "field",
        $action = "rmv"
    ) {
        switch ($type) {
            case 'field':
                $find = strpos($alias_value, ".");
                switch ($action) {
                    case 'rmv':
                        $result = $find ? substr($alias_value, $find + 1) : $alias_value;
                        break;
                    case 'get':
                        $result = $find ? substr($alias_value, 0, $find) : $alias_value;
                        break;
                }
                break;
            case 'table':
                $find = strpos($alias_value, " ");
                switch ($action) {
                    case 'rmv':
                        $result = $find ? substr($alias_value, 0, $find) : $alias_value;
                        break;
                    case 'get':
                        $result = $find ? substr($alias_value, $find + 1) : $alias_value;
                        break;
                }
                break;
            default:
                $result = false;
                break;
        }
        return $result;
    }
}
