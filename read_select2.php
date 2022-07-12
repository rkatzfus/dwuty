<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\webutility_ssp;
use App\database_tools;

$obj_ssp = new webutility_ssp($debug = false);
$obj_mysqli = new database_tools();
$data = json_decode($_POST['select2'], true);
$search = (isset($_POST['search']))? true:false;
$aryColumns = $data['columns'];
$obj_ssp->set_length(-1); // remove length & paging
$obj_ssp->set_Select($aryColumns);
$obj_ssp->set_From($data['from']);
(isset($data['where']))?$obj_ssp->set_Where($data['where']):'';
$sql = $obj_ssp->set_data_sql();
if ($search) { 
    $sql = '
        select
            *
        from (
            '.$sql. '
        ) as source
        where 
            text like \'%' . $_POST['search'] . '%\'';
}
$sql .= ' order by text';
echo json_encode($obj_mysqli->sql2array($sql), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

?>