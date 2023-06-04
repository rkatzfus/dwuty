<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/.environment.php";

use App\tools;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], getenv('API_KEY')), true);
$obj_database_tools = new database_tools($config);
$pkfield = $_POST["pkfield"];
$pkvalue = $_POST["pkvalue"];
$ds = $_POST["datasource"];
$dropdownmulti = isset($_POST["dropdown_multi"]) ? json_decode($_POST["dropdown_multi"], true) : "";
$sql = "update " . $ds . " set " . $obj_database_tools->alias($pkfield, "field", "get") . ".del = 1 where " . $pkfield . " = " . $pkvalue;
$obj_database_tools->sql_exec_result_id($sql);
if (!empty($dropdownmulti)) {
    foreach ($dropdownmulti as $dropdownmulti_value) {
        $sql = "update " . $dropdownmulti_value["datasource"] . " set del = 1 where " . $dropdownmulti_value["primarykey"] . " = " . $pkvalue;
        $obj_database_tools->sql_exec_result_id($sql);
    }
}
