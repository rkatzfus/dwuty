<?php
require_once __DIR__ . "./../../../../autoload.php";

use App\database_tools;

$obj_database_tools = new database_tools(false); // debug mode
$datasource = isset($_POST["datasource"]) ? json_decode($_POST["datasource"], true) : "";
$data = isset($_POST["data"]) ? json_decode($_POST["data"], true) : "";
if ($datasource && $data) {
    foreach ($data as $data_value) {
        if ($data_value["columntype"] != 7) {
            $ary_column[] = $obj_database_tools->rmv_alias($data_value["sqlname"], "field");
            if (($data_value["columntype"] == 2)) {
                $ary_value[] = $data_value["value"];
            } else {
                $ary_value[] = "'" . $data_value["value"] . "'";
            }
        }
        if ($data_value["columntype"] == 7) {
            $ary_dropdownmulti[] = $data_value;
        } else {
            $ary_dropdownmulti = "";
        }
    }

    $sql = "insert into " . $obj_database_tools->rmv_alias($datasource, "table") . " (" . implode(",", $ary_column) . ") values (" . implode(",", $ary_value) . ");";
    $identity = $obj_database_tools->sql_exec_result_id($sql);
    if ($ary_dropdownmulti) {
        foreach ($ary_dropdownmulti as $dropdownmulti_value) {
            foreach ($dropdownmulti_value["value"] as $value) {
                $ary_values[] = "(" . $identity . ", " . $value . ")";
            }
            $sql = "insert into " . $obj_database_tools->rmv_alias($dropdownmulti_value["select2_datasource"], "table") . " (" . $obj_database_tools->rmv_alias($dropdownmulti_value["select2_pkfield"], "field") . "," . $obj_database_tools->rmv_alias($dropdownmulti_value["select2_valuekey"], "field") . ") values " . implode(",", $ary_values) . ";";
            $obj_database_tools->sql_exec_result_id($sql);
        }
    }
    return $identity;
} else {
    return false;
}
