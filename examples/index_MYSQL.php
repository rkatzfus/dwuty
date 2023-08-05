<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

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
		$config_tools = array(
			"debug" => array("tools" => false)
		);
		$obj_tools = new tools($config_tools);
		$datREF_ROOT_ID_mysql = $obj_tools->uniqueid();
		$config_webutility_mysql = array(
			"debug" => array(
				"database_tools" => false,
				"webutility_ssp" => false,
				"tools" => false
			), "database" => array(
				"type" => "mysql", "credentials" => array(
					"host" => "mysql_HOST",
					"database" => "mysql_DATABASE",
					"user" => "mysql_USER",
					"pass" => "mysql_PASSWORD"
				)
			), "crud" => array(
				"create" => array(
					"activ" => true
				),
				"update" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID_mysql => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
						)
					)
				),
				"delete" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID_mysql => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.REF_ROOT", "valuekey" => "ref.REF_DROPDOWN_MULTI"
						)
					)
				),
			),
			"datasource" => "root_table root left join ref_root_ref_dropdown_multi_table ref on root.ID = ref.REF_ROOT",
			"primarykey" => "root.ID",
			"lang_iso_639_1" => "de"
		);

		$obj_webutility_mysql = new webutility($config_webutility_mysql);
		$arySetting_CHECKBOX = array(
			"ORDERABLE" => false, "SEARCHABLE" => false
		);
		$arySetting_DROPDOWN = array(
			"SELECT2" => array(
				"columns" => array(
					"id" => "id", "text" => "id_text"
				), "datasource" => "dropdown_lookup_table"
			)
		);
		$arySetting_REF_DROPDOWN_MULTI_mysql = array(
			"UNIQUE_ID" => $datREF_ROOT_ID_mysql,
			"SELECT2" => array(
				"columns" => array(
					"id" => "REF_ROOT", "text" => "REF_DROPDOWN_MULTI"
				), "datasource" => "ref_root_ref_dropdown_multi_table"
			), "SUBSELECT2" => array(
				"columns" => array(
					"id" => "ID", "text" => "ID_TEXT"
				), "datasource" => "dropdown_multi_lookup_table"
			)
		);
		$obj_webutility_mysql->new_column("root.TEXT_FIELD", "column: TEXT", EDIT, TEXT);
		$obj_webutility_mysql->new_column("root.EMAIL", "column: EMAIL", EDIT, EMAIL);
		$obj_webutility_mysql->new_column("root.CHECKBOX", "column: CHECKBOX", EDIT, CHECKBOX, $arySetting_CHECKBOX);
		$obj_webutility_mysql->new_column("root.LINK", "column: LINK", EDIT, LINK);
		$obj_webutility_mysql->new_column("root.LINK_BUTTON", "column: LINK_BUTTON", EDIT, LINK_BUTTON);
		$obj_webutility_mysql->new_column("root.COLOR", "column: COLOR", EDIT, COLOR);
		$obj_webutility_mysql->new_column("root.REF_DROPDOWN", "column: DROPDOWN", EDIT, DROPDOWN, $arySetting_DROPDOWN);
		$obj_webutility_mysql->new_column("root.REF_DROPDOWN_MULTI", "column: DROPDOWN_MULTI", EDIT, DROPDOWN_MULTI, $arySetting_REF_DROPDOWN_MULTI_mysql);
		$obj_webutility_mysql->new_column("root.DATE_FIELD", "column: DATE", EDIT, DATE);
		$obj_webutility_mysql->new_column("root.DATETIME_FIELD", "column: DATETIME", EDIT, DATETIME);
		echo "<h1>PDO mysql</h1><hr>";
		$obj_webutility_mysql->table_header();
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
	$obj_webutility_mysql->config($ary_config);
	?>
</body>

</html>