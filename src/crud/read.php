<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

use App\tools;
use App\webutility_ssp;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], $_ENV['API_KEY']), true);
$obj_webutility_ssp = new webutility_ssp($config);
$obj_database_tools = new database_tools($config);
$dbmapping = $obj_database_tools->db_mapping();
$pkfield = $_POST["pkfield"];
$arySearchColumn = array();
$strSqlSearchColumn = "";
$aryColumns[] =
    array(
        "db" => $pkfield, "dt" => "dt_rowid", "celltype" => "primary_key"
    );
foreach ($_POST["columns"] as $key => $value) {
    if (!empty($value["name"])) {
        $aryColumns[] = array(
            "db" => $value["name"], "dt" => $value["data"]
        );
        if (!empty($value["search"]["value"])) {
            $arySearchColumn[] = $value["name"] . " like '" . $value["search"]["value"] . "'";
        }
    }
}
$strSqlFrom = $_POST["datasource"];
$ary_Columnsdata = json_decode($_POST["columnsdata"], true);
$aryGroupBy[] = $pkfield;
foreach ($ary_Columnsdata as $value) {
    if (array_key_exists("SELECT2", $value)) {
        $ary_select2[] = array(
            "SQLNAME" => $value["SQLNAME"], "SELECT2" => $value["SELECT2"]
        );
    }
    if ($value["TYP"] != 7) {
        $aryGroupBy[] = $value["SQLNAME"];
    }
}
// set search
$strSqlSearch = "";
if (!empty($_POST["search"]["value"])) {
    $searchString = $obj_database_tools->escape($_POST["search"]["value"]);
    foreach ($_POST["columns"] as $columns_value) {
        if (
            $columns_value["searchable"] == "true"
        ) {
            $ary2search_complete[] = $columns_value["data"];
        }
    }
    foreach ($ary2search_complete as $complete_value) {
        foreach ($ary_Columnsdata as $Columnsdata_value) {
            if ($complete_value == $Columnsdata_value["UNIQUE_ID"]) {
                if (!in_array($Columnsdata_value["TYP"], array("6", "7"), true)) {
                    $ary_sqlSearch[] = $Columnsdata_value["SQLNAME"] . " like '%" . $searchString . "%'";
                } else {
                    foreach ($ary_select2 as $select2_value) {
                        if ($select2_value["SQLNAME"] == $Columnsdata_value["SQLNAME"]) {
                            switch ($Columnsdata_value["TYP"]) {
                                case 6:
                                    $sql = "select " . $Columnsdata_value["SELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SELECT2"]["datasource"] . " where del <> " . $dbmapping["del"]["true"] . " and " . $Columnsdata_value["SELECT2"]["columns"]["text"] . " like '%" . $searchString . "%'";
                                    $ary_sqlSearch[] = $Columnsdata_value["SQLNAME"] . " in (" . $sql . ")";
                                    break;
                                case 7:
                                    $subsql = "select " . $Columnsdata_value["SUBSELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SUBSELECT2"]["datasource"] . " where del <> " . $dbmapping["del"]["true"] . " and " . $Columnsdata_value["SUBSELECT2"]["columns"]["text"] . " like '%" . $searchString . "%'";
                                    $sql = "select " . $Columnsdata_value["SELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SELECT2"]["datasource"] . " where del <> " . $dbmapping["del"]["true"] . " and " . $Columnsdata_value["SELECT2"]["columns"]["text"] . " in (" . $subsql . ")";
                                    $ary_sqlSearch[] = $pkfield . " in (" . $sql . ")";
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
    $strSqlSearch = implode(" or ", $ary_sqlSearch);
}
// special column search
if (!empty($arySearchColumn)) {
    $strSqlSearchColumn = implode(" and ", $arySearchColumn);
}
$obj_webutility_ssp->set_length($_POST["length"]);
$obj_webutility_ssp->set_draw($_POST["draw"]);
$obj_webutility_ssp->set_order($_POST["order"], $_POST["columns"], "");
$obj_webutility_ssp->set_columns($aryColumns);
$obj_webutility_ssp->set_select($aryColumns);
$obj_webutility_ssp->set_from($strSqlFrom);
$obj_webutility_ssp->set_groupBy($aryGroupBy);
$obj_webutility_ssp->set_where($_POST["where"]);
$obj_webutility_ssp->set_search($strSqlSearch);
$obj_webutility_ssp->set_searchColumn($strSqlSearchColumn);
$obj_webutility_ssp->set_start($_POST["start"]);
$obj_webutility_ssp->set_data_sql($config["database"]["type"]);
$obj_webutility_ssp->read();
