<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/.api_key.php";

use App\tools;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], getenv('API_KEY')), true);
$obj_database_tools = new database_tools($config); // debug mode
$pkfield = isset($_POST["pkfield"]) ? $_POST["pkfield"] : "";
$pkvalue = isset($_POST["pkvalue"]) ? intval($_POST["pkvalue"]) : "";
$field = isset($_POST["field"]) ? $_POST["field"] : "";
$value = isset($_POST["value"]) ? $obj_database_tools->escape($_POST["value"]) : "NULL";
$celltype = isset($_POST["celltype"]) ? intval($_POST["celltype"]) : "";
$colData = isset($_POST["colData"]) ? $_POST["colData"] : "";
$datasource = isset($_POST["datasource"]) ? $_POST["datasource"] : "";
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
    if (is_array($value)) { // check new settings
        $sql4check = $sql . " and " . $dropdown_multi_valuekey . " = ";
        foreach ($value as $toDo) {
            if ($obj_database_tools->chk_stmnt($sql4check . $toDo)) { // check if value already exists in database
                $sql = "update " . $dropdown_multi_datasource . " set DEL = 0 where " . $dropdown_multi_primarykey . '=' . $pkvalue . ' and ' .     $dropdown_multi_valuekey  . '=' . $toDo;
            } else {
                $sql = "insert into " .  $obj_database_tools->alias($dropdown_multi_datasource, "table") . " (" . $obj_database_tools->alias($dropdown_multi_primarykey, "field") . ", " . $obj_database_tools->alias($dropdown_multi_valuekey, "field")  . ") values (" . $pkvalue . ", " . $toDo . ")";
            }
            $obj_database_tools->sql_exec_result_id($sql);
        }
    }
} else {
    if ($celltype != 2 && $celltype != 6) {
        $value = "'" . $value . "'";
    }
    $sql = "update " . $datasource . " set " . $field . " = " . $value . " where " . $pkfield . " = " . $pkvalue;
    $obj_database_tools->sql_exec_result_id($sql);
}
