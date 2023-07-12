<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/.environment.php";

use App\tools;
use App\webutility_ssp;
use App\database_tools;

$obj_tools = new tools();
$config = json_decode($obj_tools->decrypt($_POST["sec"], getenv('API_KEY')), true);
$obj_webutility_ssp = new webutility_ssp($config);
$obj_database_tools = new database_tools($config);
$dbmapping = $obj_database_tools->db_mapping();
$data = json_decode($_POST["select2"], true);
$search = (isset($_POST['search'])) ? true : false;
$aryColumns = $data["columns"];
$obj_webutility_ssp->set_length(-1); // remove length & paging
$obj_webutility_ssp->set_select($aryColumns);
$obj_webutility_ssp->set_from($data["datasource"]);
$obj_webutility_ssp->set_where("del <> " . $dbmapping["del"]["true"]);
$sql = $obj_webutility_ssp->set_data_sql($config["database"]["type"]);
if ($search) {
    $sql = "select * from (" . $sql . ") as source where text like '%" . $obj_database_tools->escape($_POST["search"]) . "%'";
}
$sql .= " order by id";
echo $obj_tools->post_encode($obj_database_tools->sql2array($sql));
