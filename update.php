<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;

$obj_database_tools = new database_tools(false); // debug mode
$pkfield = isset($_POST["pkfield"]) ? $_POST["pkfield"] : "";
$pkvalue = isset($_POST["pkvalue"]) ? intval($_POST["pkvalue"]) : "";
$field = isset($_POST["field"]) ? $_POST["field"] : "";
$value = isset($_POST["value"]) ? $_POST["value"] : "";
$celltype = isset($_POST["celltype"]) ? intval($_POST["celltype"]) : "";
$colData = isset($_POST["colData"]) ? $_POST["colData"] : "";
$datasource = isset($_POST["datasource"]) ? json_decode($_POST["datasource"], true) : "";
if ($celltype === 7) {
    $dropdown_multi = isset($_POST["dropdown_multi"]) ? json_decode($_POST["dropdown_multi"], true)[$colData] : "";
    $dropdown_multi_datasource = $dropdown_multi["datatsource"];
    $dropdown_multi_primarykey = $dropdown_multi["primarykey"];
    $dropdown_multi_valuekey = $dropdown_multi["valuekey"];
    echo "blub";
} else {
    if ($celltype != 2) {
        $value = "'" . $value . "'";
    }
    $sql = "update " . $datasource . " set " . $field . " = " . $value . " where " . $pkfield . " = " . $pkvalue;
    $obj_database_tools->sql_exec_no_result($sql);
}
