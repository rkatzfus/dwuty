<?php
namespace App;
class database_tools
{
    function __construct()
    {
        $this->username = get_current_user();
        $host = 'db';
        $user = 'MYSQL_USER';
        $pass = 'MYSQL_PASSWORD';
        $this->mysqli_conn = new \mysqli($host, $user, $pass);
        if ($this->mysqli_conn->connect_error) {
            echo("Connection failed: " . $this->mysqli_conn->connect_error);
            $this->mysqli_conn = false;
            die();
        } 
    }
    function __destruct() {
        $this->mysqli_conn->close();
    }
    public function sql_getfield(
        $sql = ""
    ) {
        $result = false;
        if($this->mysqli_conn != false && isset($sql) && $sql != ""){
            $result = trim(mysqli_query($this->mysqli_conn, $sql)->fetch_row()[0]) ?? false;
        }
        return $result;
    }
    public function sql2array(
        $sql = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn -> query($sql) -> fetch_all(MYSQLI_ASSOC) as $value) {
                $result[] = $value;
            }
            return $result;
        }
    }
    public function sql2array_pk(
        $sql = ""
        , $pk = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn -> query($sql) -> fetch_all(MYSQLI_ASSOC) as $value) {
                $result[$value[$pk]] = $value;
            }
            return $result;
        }
    }
    public function sql2array_pk_value(
        $sql = ""
        , $pk = ""
        , $value = ""
    ) {
        if ($this->mysqli_conn != false && isset($sql) && $sql != "") {
            foreach ($this->mysqli_conn -> query($sql) -> fetch_all(MYSQLI_ASSOC) as $value_key) {
                $result[$value_key[$pk]] = $value_key[$value];
            }
            return $result;
        }
    }
}
?>