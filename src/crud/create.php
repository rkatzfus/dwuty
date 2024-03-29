<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

use App\tools;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], $_ENV['API_KEY']), true);
$obj_database_tools = new database_tools($config);
$dbmapping = $obj_database_tools->db_mapping($config);
$datasource = isset($_POST["datasource"]) ? $_POST["datasource"] : "";
$data = isset($_POST["data"]) ? json_decode($_POST["data"], true) : "";
$ary_dropdownmulti = array();
if ($datasource && $data) {
    foreach ($data as $data_value) {
        if ($data_value["columntype"] != 7) {
            $ary_column[] = $obj_database_tools->alias($data_value["sqlname"], "field");
            if (($data_value["columntype"] == 2)) {
                $ary_value[] =  $dbmapping["tf"][$data_value["value"]];
            } else {
                $ary_value[] = "'" . $obj_database_tools->escape($data_value["value"]) . "'";
            }
        }
        if ($data_value["columntype"] == 7) {
            $ary_dropdownmulti[] = $data_value;
        }
    }
    $sql = "insert into " . $obj_database_tools->alias($datasource, "table") . " (" . implode(",", $ary_column) . ") values (" . implode(",", $ary_value) . ");";
    $aryIdent = array("id" => true, "config" => array("database" => array("type" => $config["database"]["type"]), "table" => $obj_database_tools->alias($datasource, "table")));
    $identity = $obj_database_tools->sql_exec_result_id($sql, $aryIdent);
    if (!empty($ary_dropdownmulti)) {
        foreach ($ary_dropdownmulti as $value) {
            foreach ($value["value"] as $v_key => $v_value) {
                $value["value"][$v_key] = "(" . $identity . "," . $v_value . ")";
            }
            $sql = "insert into " . $obj_database_tools->alias($value["select2_datasource"], "table") . " (" . $obj_database_tools->alias($value["select2_pkfield"], "field") . "," . $obj_database_tools->alias($value["select2_valuekey"], "field") . ") values " . implode(",",  $value["value"]) . ";";
            $obj_database_tools->sql_exec_result_id($sql);
        }
    }
    echo $obj_tools->post_encode($identity);
} else {
    echo  $obj_tools->post_encode(false);
}
