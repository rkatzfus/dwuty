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
    echo "<h1>PDO mysql</h1>";
    $config_database_tools = array(
        "debug" => array("database_tools" => true), "database" => array(
            "type" => "mysql", "credentials" => array(
                "host" => "mysql_HOST",
                "database" => "mysql_DATABASE",
                "user" => "mysql_USER",
                "pass" => "mysql_PASSWORD",
            )
        )
    );
    $obj_database_tools = new database_tools($config_database_tools);
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

    echo "<h1>PDO sqlsrv</h1>";
    $config_database_tools = array(
        "debug" => array("database_tools" => true), "database" => array(
            "type" => "sqlsrv", "credentials" => array(
                "host" => "sqlsrv_HOST",
                "database" => "sqlsrv_DATABASE",
                "user" => "sqlsrv_USER",
                "pass" => "sqlsrv_PASSWORD"
            ), "TrustServerCertificate" => true
        )
    );
    $obj_database_tools = new database_tools($config_database_tools);
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

    echo "<h1>PDO pgsql</h1>";
    $config_database_tools = array(
        "debug" => array("database_tools" => true), "database" => array(
            "type" => "pgsql", "credentials" => array(
                "host" => "pgsql_HOST",
                "database" => "pgsql_DATABASE",
                "user" => "pgsql_USER",
                "pass" => "pgsql_PASSWORD"
            )
        )
    );
    $obj_database_tools = new database_tools($config_database_tools);
    $sql = "select distinct count(*) from root_table;";
    $obj_database_tools->sql_getfield($sql);
    $sql = "select id, del, \"TEXT\", checkbox from root_table;";
    $obj_database_tools->sql2array($sql);
    $obj_database_tools->sql2array_pk($sql, "TEXT");
    $obj_database_tools->sql2array_group($sql, "checkbox");
    $sql = "select id, \"TEXT\" from dropdown_lookup_table;";
    $obj_database_tools->sql2array_pk_value($sql, "id", "TEXT");
    $sql = "update root_table set del = '1' where id = 1";
    $obj_database_tools->sql_exec_result_id($sql);
    ?>
</body>

</html>