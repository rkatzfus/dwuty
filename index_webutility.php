<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use App\tools;
use App\webutility;

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
		$obj_tools = new tools(false); // debug Mode
		$datREF_ROOT_ID = $obj_tools->uniqueid();
		$ary_webutility_config = array(
			"crud" => array(
				"create" => array(
					"activ" => true
				),
				"read" => array(
					"activ" => true
				),
				"update" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
						)
					)
				),
				"delete" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
						)
					)
				),
			),
			"datasource" => "root_table root left join ref_root_ref_dropdown_multi_table ref on root.ID = ref.REF_ROOT",
			"primarykey" => "root.ID",
			"lang_iso_639_1" => "de"
		);
		$obj_webutility = new webutility($ary_webutility_config);
		$strsqlWhere_xxxTESTxxx = "root.DEL <> 1";
		$obj_webutility->set_where($strsqlWhere_xxxTESTxxx);
		$arySetting_CHECKBOX = array(
			"ORDERABLE" => false, "SEARCHABLE" => false
		);
		$arySetting_DROPDOWN = array(
			"SELECT2" => array(
				"columns" => array(
					"id" => "dropdown.ID", "text" => "dropdown.TEXT"
				), "from" => "dropdown_lookup_table dropdown", "where" => "dropdown.DEL<>1"
			)
		);
		$arySetting_REF_DROPDOWN_MULTI = array(
			"UNIQUE_ID" => $datREF_ROOT_ID,
			"SELECT2" => array(
				"columns" => array(
					"id" => "ref.REF_ROOT", "text" => "ref.REF_DROPDOWN_MULTI"
				), "from" => "ref_root_ref_dropdown_multi_table ref", "where" => "ref.DEL<>1"
			), "SUBSELECT2" => array(
				"columns" => array(
					"id" => "dropdown_multi.ID", "text" => "dropdown_multi.TEXT"
				), "from" => "dropdown_multi_lookup_table dropdown_multi", "where" => "dropdown_multi.DEL<>1"
			)
		);
		$obj_webutility->new_column("root.TEXT", "column: TEXT", EDIT, TEXT);
		$obj_webutility->new_column("root.EMAIL", "column: EMAIL", EDIT, EMAIL);
		$obj_webutility->new_column("root.CHECKBOX", "column: CHECKBOX", EDIT, CHECKBOX, $arySetting_CHECKBOX);
		$obj_webutility->new_column("root.LINK", "column: LINK", EDIT, LINK);
		$obj_webutility->new_column("root.LINK_BUTTON", "column: LINK_BUTTON", EDIT, LINK_BUTTON);
		$obj_webutility->new_column("root.COLOR", "column: COLOR", EDIT, COLOR);
		$obj_webutility->new_column("root.REF_DROPDOWN", "column: DROPDOWN", EDIT, DROPDOWN, $arySetting_DROPDOWN);
		$obj_webutility->new_column("root.REF_DROPDOWN_MULTI", "column: DROPDOWN_MULTI", EDIT, DROPDOWN_MULTI, $arySetting_REF_DROPDOWN_MULTI);
		$obj_webutility->new_column("root.DATE", "column: DATE", EDIT, DATE);
		$obj_webutility->new_column("root.DATETIME", "column: DATETIME", EDIT, DATETIME);
		$obj_webutility->table_header();
		?>
	</div>
	<?php
	$ary_config = array(
		"default_order" => array(
			"column_no" => 0, "direction" => "asc"
		),
		"datatables_ext" => array(
			"fixedHeader" => "true"
		)

	);
	$obj_webutility->config($ary_config);
	?>
</body>

</html>