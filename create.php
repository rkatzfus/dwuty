<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;

$obj_database_tools = new database_tools(false); // debug mode
$datasource = isset($_POST["datasource"]) ? json_decode($_POST["datasource"], true) : "";
$data = isset($_POST["data"]) ? json_decode($_POST["data"], true) : "";
if ($datasource && $data) {
    // var_dump($data);
    foreach ($data as $data_value) {
        if ($data_value["columntype"] != 7) {
            $ary_column[] = $obj_database_tools->rmv_alias($data_value["sqlname"], "field");
            if (($data_value["columntype"] == 2)) {
                $ary_value[] = $data_value["value"];
            } else {
                $ary_value[] = "'" . $data_value["value"] . "'";
            }
        }
    }

    $sql = "insert into " . $obj_database_tools->rmv_alias($datasource, "table") . " (" . implode(",", $ary_column) . ") values (" . implode(",", $ary_value) . ");";
    $obj_database_tools->sql_exec_no_result($sql);
    return true;
    // return ID?!?
} else {
    return false;
}
