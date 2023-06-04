<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use App\database_tools;
?>

<head>
</head>

<body>
    <?php
    $database_tools = array(
        "debug" => array("database_tools" => true), "database" => array(
            "type" => "mysql", "credentials" => array(
                "host" => getenv('HOST'),
                "user" => getenv('MYSQL_USER'),
                "pass" => getenv('MYSQL_PASSWORD'),
                "database" => getenv('MYSQL_DATABASE'),
            )
        )
    );
    $obj_database_tools = new database_tools($database_tools);
    $sql = "select distinct count(*) from root_table;";
    $obj_database_tools->sql_getfield($sql);
    $sql = "select ID, DEL, TEXT, CHECKBOX from root_table;";
    $obj_database_tools->sql2array($sql);
    $obj_database_tools->sql2array_pk($sql, "TEXT");
    $obj_database_tools->sql2array_group($sql, "CHECKBOX");
    $sql = "select ID, TEXT from dropdown_lookup_table;";
    $obj_database_tools->sql2array_pk_value($sql, "ID", "TEXT");
    $sql = "update root_table set del = 1 where ID = 1";
    $obj_database_tools->sql_exec_result_id($sql);
    ?>
</body>

</html>