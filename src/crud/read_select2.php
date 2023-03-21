<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use App\tools;
use App\webutility_ssp;
use App\database_tools;

$obj_tools = new tools(false); // debug mode
$obj_webutility_ssp = new webutility_ssp(false); // debug mode
$obj_database_tools = new database_tools();
$data = json_decode($_POST["select2"], true);
$search = (isset($_POST['search'])) ? true : false;
$aryColumns = $data["columns"];
$obj_webutility_ssp->set_length(-1); // remove length & paging
$obj_webutility_ssp->set_select($aryColumns);
$obj_webutility_ssp->set_from($data["from"]);
(isset($data["where"])) ? $obj_webutility_ssp->set_where($data["where"]) : "";
$sql = $obj_webutility_ssp->set_data_sql();
if ($search) {
    $sql = "select * from (" . $sql . ") as source where text like '%" . $obj_database_tools->escape($_POST["search"]) . "%'";
}
$sql .= " order by text";
echo $obj_tools->post_encode($obj_database_tools->sql2array($sql));
