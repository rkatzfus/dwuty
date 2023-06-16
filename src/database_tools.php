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
    private $dbh;
    private $server_host = "";
    private $database_dbname = "";
    private $TrustServerCertificate;

    function __construct(
        $config = array()
    ) {
        $this->debug = !isset($config["debug"]["database_tools"]) ? false : $config["debug"]["database_tools"];
        $this->dbtype = !isset($config["database"]["type"]) ? "mysql" : $config["database"]["type"]; // set default db type
        $this->host = !isset($config["database"]["credentials"]["host"]) ? "unknown hostname" : getenv($config["database"]["credentials"]["host"]);
        $this->user = !isset($config["database"]["credentials"]["user"]) ? "unknown database username" : getenv($config["database"]["credentials"]["user"]);
        $this->pass = !isset($config["database"]["credentials"]["pass"]) ? "unknown database password" : getenv($config["database"]["credentials"]["pass"]);
        $this->database = !isset($config["database"]["credentials"]["database"]) ? "unknown database" : getenv($config["database"]["credentials"]["database"]);
        $this->TrustServerCertificate = !isset($config["database"]["TrustServerCertificate"]) ? false : $config["database"]["TrustServerCertificate"]; // set default = false
        $this->username = get_current_user();
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
    }
    function __destruct()
    {
        $this->dbh = null;
    }
    private function build_conn()
    {
        switch ($this->dbtype) {
            case "mysql":
                $server_host = "host";
                $database_dbname = "dbname";
                break;
            case "sqlsrv":
                $server_host = "server";
                $database_dbname = "Database";
                break;
            default:
                $server_host = "host";
                $database_dbname = "dbname";
                break;
        }
        try {
            $this->dbh = new \PDO("$this->dbtype:$server_host=$this->host;$database_dbname=$this->database;TrustServerCertificate=$this->TrustServerCertificate", $this->user, $this->pass);
            // set the PDO error mode to exception
            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            $this->dbh = null;
            die();
        }
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
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        $result = $sth->fetch()[0];
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
        $result = array();
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $value) {
            $result[] = $this->decode_escape($value);
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql2array</b>";
            var_dump($result);
        }
        return $result;
    }
    public function sql2array_pk(
        $sql = "",
        $pk = ""
    ) {
        $result = array();
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $value) {
            $result[$value[$pk]] = $this->decode_escape($value);
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql2array_pk</b>";
            var_dump($result);
        }
        return $result;
    }
    public function sql2array_pk_value(
        $sql = "",
        $pk = "",
        $value = ""
    ) {
        $result = array();
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $value_key) {
            $result[$value_key[$pk]] = $this->decode_escape($value_key[$value]);
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql2array_pk_value</b>";
            var_dump($result);
        }
        return $result;
    }
    public function sql2array_group(
        $sql = "",
        $group = ""
    ) {
        $result = array();
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        foreach ($sth->fetchAll(\PDO::FETCH_ASSOC) as $value_key) {
            $result[$value_key[$group]][] =  $this->decode_escape($value_key);
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql2array_group</b>";
            var_dump($result);
        }
        return $result;
    }
    public function sql_exec_result_id(
        $sql = ""
    ) {
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>DATABASE TOOLS: sql_exec_result_id</b>";
            var_dump($sql);
        } else {
            $identity =  $this->dbh->lastInsertId();
            if ($identity) {
                return $identity;
            }
        }

        // ggf. testen
        // https://stackoverflow.com/questions/10680943/pdo-get-the-last-id-inserted


        // if (isset($sql) && !empty($sql)) {
        //     if ($this->debug == true) {
        //         echo "<hr>";
        //         echo "<b>DATABASE TOOLS: sql_exec_result_id</b>";
        //         var_dump($sql);
        //     } else {
        //         mysqli_query($this->dbh, $sql);
        //         $identity =  mysqli_insert_id($this->dbh);
        //         if ($identity) {
        //             return $identity;
        //         }
        //     }
        // }
    }
    public function chk_stmnt(
        $sql = ""
    ) {
        (!isset($this->dbh) || $this->dbh === false) ? $this->build_conn() : "";
        $sth =  $this->dbh->prepare($sql);
        $sth->execute();
        $number_of_rows = $sth->fetchColumn();
        $result = $number_of_rows <> 0 ? true : false;
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
