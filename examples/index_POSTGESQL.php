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
		$datREF_ROOT_ID_pgsql = $obj_tools->uniqueid();
		$config_webutility_pgsql = array(
			"debug" => array(
				"database_tools" => false,
				"webutility_ssp" => false,
				"tools" => false
			), "database" => array(
				"type" => "pgsql", "credentials" => array(
					"host" => "pgsql_HOST",
					"database" => "pgsql_DATABASE",
					"user" => "pgsql_USER",
					"pass" => "pgsql_PASSWORD"
				)
			), "crud" => array(
				"create" => array(
					"activ" => true
				),
				"update" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID_pgsql => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.ref_root", "valuekey" => "ref.ref_dropdown_multi"
						)
					)
				),
				"delete" => array(
					"activ" => true, "dropdown_multi" => array(
						$datREF_ROOT_ID_pgsql => array(
							"datasource" => "ref_root_ref_dropdown_multi_table ref", "primarykey" => "ref.ref_root", "valuekey" => "ref.ref_dropdown_multi"
						)
					)
				),
			),
			"datasource" => "root_table root left join ref_root_ref_dropdown_multi_table ref on root.id = ref.ref_root",
			"primarykey" => "root.id",
			"lang_iso_639_1" => "de"
		);

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
		$arySetting_REF_DROPDOWN_MULTI_pgsql = array(
			"UNIQUE_ID" => $datREF_ROOT_ID_pgsql,
			"SELECT2" => array(
				"columns" => array(
					"id" => "ref_root", "text" => "ref_dropdown_multi"
				), "datasource" => "ref_root_ref_dropdown_multi_table"
			), "SUBSELECT2" => array(
				"columns" => array(
					"id" => "id", "text" => "id_text"
				), "datasource" => "dropdown_multi_lookup_table"
			)
		);

		$obj_webutility_pgsql = new webutility($config_webutility_pgsql);
		$obj_webutility_pgsql->new_column("root.text_field", "column: TEXT", EDIT, TEXT);
		$obj_webutility_pgsql->new_column("root.email", "column: EMAIL", EDIT, EMAIL);
		$obj_webutility_pgsql->new_column("root.checkbox", "column: CHECKBOX", EDIT, CHECKBOX, $arySetting_CHECKBOX);
		$obj_webutility_pgsql->new_column("root.link", "column: LINK", EDIT, LINK);
		$obj_webutility_pgsql->new_column("root.link_button", "column: LINK_BUTTON", EDIT, LINK_BUTTON);
		$obj_webutility_pgsql->new_column("root.color", "column: COLOR", EDIT, COLOR);
		$obj_webutility_pgsql->new_column("root.ref_dropdown", "column: DROPDOWN", EDIT, DROPDOWN, $arySetting_DROPDOWN);
		$obj_webutility_pgsql->new_column("root.ref_dropdown_multi", "column: DROPDOWN_MULTI", EDIT, DROPDOWN_MULTI, $arySetting_REF_DROPDOWN_MULTI_pgsql);
		$obj_webutility_pgsql->new_column("root.date_field", "column: DATE", EDIT, DATE);
		$obj_webutility_pgsql->new_column("root.datetime_field", "column: DATETIME", EDIT, DATETIME);
		echo "<h1>PDO pgsql</h1><hr>";
		$obj_webutility_pgsql->table_header();
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
	$obj_webutility_pgsql->config($ary_config);
	?>
</body>

</html>