<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

use App\tools;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], $_ENV['API_KEY']), true);
$obj_database_tools = new database_tools($config);
$dbmapping = $obj_database_tools->db_mapping($config);
$pkfield = $_POST["pkfield"];
$pkvalue = $_POST["pkvalue"];
$ds = $_POST["datasource"];
$dropdownmulti = isset($_POST["dropdown_multi"]) ? json_decode($_POST["dropdown_multi"], true) : "";
$sql = "update " . $obj_database_tools->alias($ds, "table") . " set del = " . $dbmapping["del"]["true"] . " where " . $obj_database_tools->alias($pkfield, "field", "rmv") . " = " . $pkvalue;
$obj_database_tools->sql_exec_result_id($sql);
if (!empty($dropdownmulti)) {
    foreach ($dropdownmulti as $dropdownmulti_value) {
        $sql = "update " . $obj_database_tools->alias($dropdownmulti_value["datasource"], "table") . " set del = " . $dbmapping["del"]["true"] . " where " .  $obj_database_tools->alias($dropdownmulti_value["primarykey"], "field", "rmv") . " = " . $pkvalue;
        $obj_database_tools->sql_exec_result_id($sql);
    }
}
