<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\webutility_ssp;

$pkfield = $_POST["pkfield"];
$obj_ssp = new webutility_ssp($debug = false);
$arySearchColumn = array();
$strSqlSearchColumn = "";
$aryColumns[] =
    array(
        "db" => $pkfield, "dt" => "DT_RowId", "celltype" => "primary_key"
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
$strSqlFrom = json_decode($_POST["datasource"], true);
$ary_Columnsdata = $_POST["columnsdata"];
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
    $searchString = $_POST["search"]["value"];
    foreach ($_POST["columns"] as $columns_value) {
        if (
            $columns_value["searchable"] == "true"
        ) {
            $ary2search_complete[] = $columns_value["data"];
        }
    }
    foreach ($ary2search_complete as $complete_value) {
        foreach ($ary_Columnsdata as $Columnsdata_value) {
            if ($complete_value == $Columnsdata_value["NAME"]) {
                if (!in_array($Columnsdata_value["TYP"], array("6", "7"), true)) {
                    $ary_sqlSearch[] = $Columnsdata_value["SQLNAME"] . " like '%" . $searchString . "%'";
                } else {
                    foreach ($ary_select2 as $select2_value) {
                        if ($select2_value["SQLNAME"] == $Columnsdata_value["SQLNAME"]) {
                            switch ($Columnsdata_value["TYP"]) {
                                case 6:
                                    $sql = "select " . $Columnsdata_value["SELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SELECT2"]["from"] . " where DEL <> 1 and " . $Columnsdata_value["SELECT2"]["columns"]["text"] . " like '%" . $searchString . "%'";
                                    $ary_sqlSearch[] = $Columnsdata_value["SQLNAME"] . " in (" . $sql . ")";
                                    break;
                                case 7:
                                    $subsql = "select " . $Columnsdata_value["SUBSELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SUBSELECT2"]["from"] . " where DEL <> 1 and " . $Columnsdata_value["SUBSELECT2"]["columns"]["text"] . " like '%" . $searchString . "%'";
                                    $sql = "select " . $Columnsdata_value["SELECT2"]["columns"]["id"] . " from " . $Columnsdata_value["SELECT2"]["from"] . " where DEL <> 1 and " . $Columnsdata_value["SELECT2"]["columns"]["text"] . " in (" . $subsql . ")";
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
$obj_ssp->set_length($_POST["length"]);
$obj_ssp->set_draw($_POST["draw"]);
$obj_ssp->set_order($_POST["order"], $_POST["columns"], "");
$obj_ssp->set_Columns($aryColumns);
$obj_ssp->set_Select($aryColumns);
$obj_ssp->set_From($strSqlFrom);
$obj_ssp->set_GroupBy($aryGroupBy);
$obj_ssp->set_Where(json_decode($_POST["where"], true));
$obj_ssp->set_Search($strSqlSearch);
$obj_ssp->set_SearchColumn($strSqlSearchColumn);
$obj_ssp->set_start($_POST["start"]);
$obj_ssp->set_data_sql();
$obj_ssp->fetch();
