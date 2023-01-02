<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;
?>

<head>
</head>

<body>
    <?php
    $obj_database_tools = new database_tools(true);
    $sql = "select distinct count(*) from MYSQL_DATABASE.root_table;";
    $obj_database_tools->sql_getfield($sql);
    $sql = "select ID, DEL, TEXT, CHECKBOX from MYSQL_DATABASE.root_table;";
    $obj_database_tools->sql2array($sql);
    $obj_database_tools->sql2array_pk($sql, "TEXT");
    $sql = "select ID, TEXT from  MYSQL_DATABASE.dropdown_lookup_table;";
    $obj_database_tools->sql2array_pk_value($sql, "ID", "TEXT");
    $sql = "update MYSQL_DATABASE.root_table set del = 1 where ID = 1";
    $obj_database_tools->sql_exec_no_result($sql);
    ?>
</body>

</html>