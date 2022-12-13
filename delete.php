<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;

$ds = json_decode($_POST["datasource"], true);
$pkfield = $_POST["pkfield"];
$pkvalue = $_POST["pkvalue"];
$dropdownmulti = isset($_POST["dropdown_multi"]) ? json_decode($_POST["dropdown_multi"], true) : "";
$obj_mysqli = new database_tools(false); // debug mode
$sql = "update " . $ds . " set del = 1 where " . $pkfield . " = " . $pkvalue;
$obj_mysqli->sql_exec_no_result($sql);
if (!empty($dropdownmulti)) {
    foreach ($dropdownmulti as $dropdownmulti_value) {
        $sql = "update " . $dropdownmulti_value["datasource"] . " set del = 1 where " . $dropdownmulti_value["primarykey"] . " = " . $pkvalue;
        $obj_mysqli->sql_exec_no_result($sql);
    }
}
