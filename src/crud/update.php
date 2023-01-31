<?php
require_once __DIR__ . "./../../../../autoload.php";

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
    $dropdown_multi_datasource = $dropdown_multi["datasource"];
    $dropdown_multi_primarykey = $dropdown_multi["primarykey"];
    $dropdown_multi_valuekey = $dropdown_multi["valuekey"];
    $sql = "select " . $dropdown_multi_valuekey . " as '" . $dropdown_multi_valuekey . "' from " . $dropdown_multi_datasource . " where " . $dropdown_multi_primarykey . " = " . $pkvalue;
    $inDb = $obj_database_tools->sql2array_pk($sql, $dropdown_multi_valuekey);
    if ($inDb) { // clear all
        $sql2del = "update " . $dropdown_multi_datasource . " set DEL = 1 where " . $dropdown_multi_primarykey . " = " . $pkvalue . " and " . $dropdown_multi_valuekey . " = ";
        foreach ($inDb as $inDb_key => $inDb_value) {
            $obj_database_tools->sql_exec_result_id($sql2del . $inDb_value[$dropdown_multi_valuekey]);
        }
    }
    if ($value) { // check new settings
        $sql4check = $sql . " and " . $dropdown_multi_valuekey . " = ";
        foreach ($value as $toDo) {
            if ($obj_database_tools->chk_stmnt($sql4check . $toDo)) { // check if value already exists in database
                $sql = "update " . $dropdown_multi_datasource . " set DEL = 0 where " . $dropdown_multi_primarykey . '=' . $pkvalue . ' and ' .     $dropdown_multi_valuekey  . '=' . $toDo;
            } else {
                $sql = "insert into " .  $obj_database_tools->rmv_alias($dropdown_multi_datasource, "table") . " (" . $obj_database_tools->rmv_alias($dropdown_multi_primarykey, "field") . ", " . $obj_database_tools->rmv_alias($dropdown_multi_valuekey, "field")  . ") values (" . $pkvalue . ", " . $toDo . ")";
            }
            $obj_database_tools->sql_exec_result_id($sql);
        }
    }
} else {
    if ($celltype != 2) {
        $value = "'" . $value . "'";
    }
    $sql = "update " . $datasource . " set " . $field . " = " . $value . " where " . $pkfield . " = " . $pkvalue;
    $obj_database_tools->sql_exec_result_id($sql);
}
