<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;

$obj_database_tools = new database_tools(false); // debug mode
$pkfield = $_POST["pkfield"];
$pkvalue = $_POST["pkvalue"];
$ds = json_decode($_POST["datasource"], true);
$dropdownmulti = isset($_POST["dropdown_multi"]) ? json_decode($_POST["dropdown_multi"], true) : "";
$sql = "update " . $ds . " set del = 1 where " . $pkfield . " = " . $pkvalue;
$obj_database_tools->sql_exec_no_result($sql);
if (!empty($dropdownmulti)) {
    foreach ($dropdownmulti as $dropdownmulti_value) {
        $sql = "update " . $dropdownmulti_value["datasource"] . " set del = 1 where " . $dropdownmulti_value["primarykey"] . " = " . $pkvalue;
        $obj_database_tools->sql_exec_no_result($sql);
    }
}
