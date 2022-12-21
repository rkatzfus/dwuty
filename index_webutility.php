<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;
use App\webutility;

$obj_database_tools = new database_tools();
?>

<head>
	<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/vendor/datatables.net/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
	<link rel="stylesheet" type="text/css" href="/vendor/datatables.net/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" />
	<link rel="stylesheet" type="text/css" href="/vendor/select2/select2/dist/css/select2.min.css" />
	<script src="/vendor/components/jquery/jquery.min.js"></script>
</head>

<body>
	<div class="container-fluid mt-1">
		<?php
		$pkfield_xxxTESTxxx = "root.ID";
		$datREF_ROOT_ID = "REF_ROOT_ID";
		$array_AJAX_xxxTESTxxx = array();
		$array_AJAX_xxxTESTxxx["read"] = array(
			"url" => "/vendor/datatableswebutility/dwuty/read.php", "datasource" => "MYSQL_DATABASE.root_table root left join MYSQL_DATABASE.ref_root_ref_dropdown_multi_table ref on ref.REF_ROOT = root.ID"
		);
		$array_AJAX_xxxTESTxxx["delete"] = array(
			"url" => "/vendor/datatableswebutility/dwuty/delete.php", "datasource" => "MYSQL_DATABASE.root_table root", "dropdown_multi" => array(
				$datREF_ROOT_ID => array(
					"datasource" => "MYSQL_DATABASE.ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
				)
			)
		);
		$array_AJAX_xxxTESTxxx["update"] = array(
			"url" => "/vendor/datatableswebutility/dwuty/update.php", "datasource" => "MYSQL_DATABASE.root_table root", "dropdown_multi" => array(
				$datREF_ROOT_ID => array(
					"datasource" => "MYSQL_DATABASE.ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
				)
			)
		);
		$obj_webutility = new webutility("dte_xxxTESTxxx", $array_AJAX_xxxTESTxxx, $pkfield_xxxTESTxxx);
		$strsqlWhere_xxxTESTxxx = "root.DEL <> 1";
		$obj_webutility->set_where($strsqlWhere_xxxTESTxxx);
		$arySetting_CHECKBOX = array(
			"ORDERABLE" => false, "SEARCHABLE" => false
		);
		$arySetting_DROPDOWN = array(
			"AJAX" => "/vendor/datatableswebutility/dwuty/read_select2.php", "SELECT2" => array(
				"columns" => array(
					"id" => "dropdown.ID", "text" => "dropdown.TEXT"
				), "from" => "MYSQL_DATABASE.dropdown_lookup_table dropdown"
			)
		);
		$arySetting_REF_DROPDOWN_MULTI = array(
			"AJAX" => "/vendor/datatableswebutility/dwuty/read_select2.php", "SELECT2" => array(
				"columns" => array(
					"id" => "ref.REF_ROOT", "text" => "ref.REF_DROPDOWN_MULTI"
				), "from" => "MYSQL_DATABASE.ref_root_ref_dropdown_multi_table ref", "where" => "ref.DEL<>1"
			), "SUBSELECT2" => array(
				"columns" => array(
					"id" => "dropdown_multi.ID", "text" => "dropdown_multi.TEXT"
				), "from" => "MYSQL_DATABASE.dropdown_multi_lookup_table dropdown_multi", "where" => "dropdown_multi.DEL<>1"
			)
		);
		$obj_webutility->new_column("root.TEXT", "TEXT", "column: TEXT", EDIT, TEXT);
		$obj_webutility->new_column("root.EMAIL", "EMAIL", "column: EMAIL", EDIT, EMAIL);
		$obj_webutility->new_column("root.CHECKBOX", "CHECKBOX", "column: CHECKBOX", EDIT, CHECKBOX, $arySetting_CHECKBOX);
		$obj_webutility->new_column("root.LINK", "LINK", "column: LINK", EDIT, LINK);
		$obj_webutility->new_column("root.LINK_BUTTON", "LINK_BUTTON", "column: LINK_BUTTON", EDIT, LINK_BUTTON);
		$obj_webutility->new_column("root.COLOR", "COLOR", "column: COLOR", EDIT, COLOR);
		$obj_webutility->new_column("root.REF_DROPDOWN", "DROPDOWN", "column: DROPDOWN", EDIT, DROPDOWN, $arySetting_DROPDOWN);
		$obj_webutility->new_column("root.REF_DROPDOWN_MULTI", "DROPDOWN_MULTI", "column: DROPDOWN_MULTI", EDIT, DROPDOWN_MULTI, $arySetting_REF_DROPDOWN_MULTI);
		$obj_webutility->new_column("root.DATE", "DATE", "column: DATE", EDIT, DATE);
		$obj_webutility->new_column("root.DATETIME", "DATETIME", "column: DATETIME", EDIT, DATETIME);
		$defOrderby_xxxTESTxxx = 0;
		$obj_webutility->table_header();
		?>
	</div>
	<?php
	$obj_webutility->config(
		$defOrderby_xxxTESTxxx,
		"asc"
	);
	?>
</body>

</html>