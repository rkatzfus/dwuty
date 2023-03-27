<?php

namespace App;

define("UNSELECT", "0");
define("VIEW", "1");
define("EDIT", "2");
//------------------
define("TEXT", "0");
define("EMAIL", "1");
define("CHECKBOX", "2");
define("LINK", "3");
define("LINK_BUTTON", "4");
define("COLOR", "5");
define("DROPDOWN", "6");
define("DROPDOWN_MULTI", "7");
define("DATE", "8");
define("DATETIME", "9");

class webutility
{
    private $obj_tools;
    private $obj_database_tools;
    private $webutility_ssp;
    private $ajax_read_where;
    private $tbl_ID;
    private $datasource;
    private $pkfield;
    private $language;
    private $language_dwuty;
    private $crud_path;

    private $create;
    private $read;
    private $update;
    private $delete;

    private $button_column;

    private $ajax_update_dropdown_multi;
    private $ajax_delete_dropdown_multi;

    private $columns = array();

    function __construct(
        $tabledata = array()
    ) {
        $this->obj_tools = new tools(false); // debug Mode
        $this->obj_database_tools = new database_tools();
        $this->webutility_ssp = new webutility_ssp(false); // debug Mode
        $this->ajax_read_where = "";
        $this->tbl_ID = $this->obj_tools->uniqueid();
        $this->datasource = $this->obj_tools->post_encode($tabledata["datasource"]);
        $this->pkfield = $tabledata["primarykey"];
        $this->language = isset($tabledata["lang_iso_639_1"]) ? $tabledata["lang_iso_639_1"] : "de"; // set default
        $this->language_dwuty = json_decode(file_get_contents(__DIR__ . "/dwuty_i18n/" . $this->language . ".json"), true);
        $this->crud_path = getenv('PATH_CRUD') ? getenv('PATH_CRUD') : '/vendor/datatableswebutility/dwuty/src/crud';
        if (isset($tabledata["crud"])) {
            $this->create = false;
            $this->read = true;
            $this->update = false;
            $this->delete = false;
            $this->button_column = false;
            foreach ($tabledata["crud"] as $crud_key => $crud_value) {
                switch ($crud_key) {
                    case "create":
                        if ($crud_value["activ"] == true) {
                            $this->create = true;
                            $this->button_column = true;
                        }
                        break;
                    case "update":
                        if ($crud_value["activ"] == true) {
                            $this->update = true;
                            $this->ajax_update_dropdown_multi = (isset($crud_value["dropdown_multi"])) ? $this->obj_tools->post_encode($crud_value["dropdown_multi"]) : false;
                        }
                        break;
                    case "delete":
                        if ($crud_value["activ"] == true) {
                            $this->delete = true;
                            $this->ajax_delete_dropdown_multi = (isset($crud_value["dropdown_multi"])) ? $this->obj_tools->post_encode($crud_value["dropdown_multi"]) : false;
                            $this->button_column = true;
                        }
                        break;
                    default:
                        throw new \Exception("ERROR occured!");
                        exit();
                }
            }
        }
    }
    public function table_header()
    {
?>
        <table id="<?= $this->tbl_ID; ?>" class="table table-striped table-hover table-bordered" style="width:100%">
            <thead>
                <tr>
                    <?php
                    foreach ($this->columns as $column) {
                        echo "<th>" . $column["DISPLAYNAME"] . "</th>";
                    }
                    if ($this->button_column == true) {
                        echo "<th>";
                        echo (($this->create)) ? "<div class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' id='add_" . $this->tbl_ID . "' style='box-shadow: none; width: 80px;' data-ajaxdefault=''><b>" . $this->language_dwuty["buttons"]["create"] . "</b></button></div>" : "";
                        echo "</th>";
                    }
                    ?>
                </tr>
            </thead>
        </table>
    <?php
    }
    public function config(
        $ary_config = array()
    ) {
        $ary_order = isset($ary_config["default_order"]) ? $ary_config["default_order"] : "";
        if (!empty($ary_order)) {
            $default_order = isset($ary_order["column_no"]) ? $ary_order["column_no"] : "";
            $default_order_dir = isset($ary_order["direction"]) ? $ary_order["direction"] : "asc";
        } else { // default
            $default_order = "";
            $default_order_dir = "asc";
        }
        $ary_ext = isset($ary_config["datatables_ext"]) ? $ary_config["datatables_ext"] : "";
        if (!empty($ary_ext)) {
            foreach ($ary_ext as $ext_key => $ext_value) {
                $ary_tmp[] = $ext_key . ":" . $ext_value;
            }
            $additional_options = implode(",", $ary_tmp);
            unset($ary_tmp);
        } else {
            $additional_options = "";
        }
        $ary_SearchSelect2 = array();
        foreach ($this->columns as $column) {
            if ($column["TYP"] == 6 or $column["TYP"] == 7) {
                $ary_SearchSelect2[$column["SQLNAME"]] = $column["JSON"];
            }
        }
        foreach ($this->columns as $columns_key => $columns_value) {
            if ($columns_value["TYP"] == 7) { // DROPDOWN_MULTI_FIELD
                $columns_value["SQLNAME"] = $columns_value["SQLNAMETABLE"];
                unset($columns_value["SQLNAMETABLE"]);
                $columnsdata[$columns_key] = $columns_value;
            } else {
                $columnsdata[$columns_key] = $columns_value;
            }
        }
    ?>
        <footer>
            <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
            <script src="/vendor/datatables.net/datatables.net/js/jquery.dataTables.min.js"></script>
            <script src="/vendor/datatables.net/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
            <script src="/vendor/datatables.net/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
            <script src="/vendor/datatables.net/datatables.net-fixedheader-bs5/js/fixedHeader.bootstrap5.min.js"></script>
            <script src="/vendor/select2/select2/dist/js/select2.min.js"></script>
            <script src="/vendor/select2/select2/dist/js/i18n/<?= $this->language; ?>.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    function read_data_<?= $this->tbl_ID; ?>() {
                        var table = $("#<?= $this->tbl_ID; ?>").DataTable({
                            language: <?= file_get_contents(__DIR__ . "/datatables_i18n/" . $this->language . ".json"); ?>,
                            stateSave: true,
                            processing: true,
                            cache: false,
                            searchDelay: 1000,
                            serverSide: true,
                            columnDefs: [
                                <?php
                                $aryColumndef = array();
                                foreach ($this->columns as $columns_key => $columns_value) {
                                    $classname = $this->html_default($columns_value["TYP"])["alignment"];
                                    $aryColumndef[] = array(
                                        "targets: " . $columns_key, ($columns_value["ORDERABLE"] == 1) ? "orderable: true" : "orderable: false", ($columns_value["SEARCHABLE"] == 1) ? "searchable: true" : "searchable: false", (isset($classname)) ? "className: '" . implode(" ", $classname) . "'" : ""
                                    );
                                    unset($classname);
                                }
                                foreach ($aryColumndef as $row) {
                                    echo "{" . implode(", ", $row) . "},";
                                }
                                ?>
                            ],
                            ajax: {
                                url: "<?= $this->crud_path; ?>/read.php",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                    datasource: <?= $this->datasource; ?>,
                                    where: <?= $this->obj_tools->post_encode($this->ajax_read_where); ?>,
                                    columnsdata: JSON.stringify(<?= $this->obj_tools->post_encode($columnsdata); ?>)
                                }
                            },
                            rowId: "DT_RowId",
                            order: [
                                [
                                    "<?= intval($default_order); ?>",
                                    "<?= $default_order_dir; ?>"
                                ]
                            ],
                            columns: [
                                <?php
                                foreach ($this->columns as $column) {
                                    echo "{";
                                    echo "name: \"" . $column["SQLNAME"] . "\", ";
                                    echo "data: \"" . $column["UNIQUE_ID"] . "\", ";
                                    echo "celltype: \"" . $column["TYP"] . "\", ";
                                    switch ($column["TYP"]) {
                                        case 0: // TEXT
                                        case 1: // EMAIL
                                ?>
                                            render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                inner = create_element("div", html_default["div"]);
                                                if (data) {
                                                    inner.innerHTML += data;
                                                }
                                                if (!content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("contenteditable", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 2: // CHECKBOX
                                        ?>
                                            render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                outer = create_element("div", html_default["div"]);
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if (data == true) {
                                                    $(inner).attr("checked", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                outer.appendChild(inner);
                                                return outer.outerHTML;
                                            },
                                        <?php
                                            break;
                                        case 3: // LINK
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                if (data) {
                                                    html_default["a"].href = data;
                                                    html_default["a"].title = data;
                                                }
                                                outer = create_element("a", html_default["a"]);
                                                inner = create_element("div", html_default["div"]);
                                                if (data) {
                                                    inner.innerHTML += data;
                                                }
                                                if (!content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("contenteditable", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                outer.appendChild(inner);
                                                return outer.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 4: // LINK_BUTTON
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["a"].href = data;
                                                html_default["a"].title = data;
                                                inner = create_element("a", html_default["a"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).addClass("disabled");
                                                }
                                                if (data) {
                                                    return inner.outerHTML;
                                                } else {
                                                    return '';
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 5: // COLOR
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["input"].value = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 6: // DROPDOWN
                                        ?>
                                            render: function(data) {
                                                initialvalues = JSON.parse(<?= $this->obj_tools->post_encode($column['JSON']); ?>);
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['UNIQUE_ID']); ?>];
                                                outer = create_element("select", html_default["select"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(outer).attr("disabled", "true");
                                                }
                                                if (data) {
                                                    html_default["option"].value = data;
                                                    html_default["option"].createTextNode = initialvalues[data];
                                                    inner = create_element("option", html_default["option"]);
                                                    outer.appendChild(inner);
                                                }
                                                return outer.outerHTML;
                                            },
                                            createdCell: function(td) {
                                                if ("<?= ($this->update && $column["ACTION"] == 2) ? true : false; ?>") {
                                                    $(td).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                            }
                                        <?php
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                        ?> render: function(data) {
                                                initialvalues = JSON.parse(<?= $this->obj_tools->post_encode($column['JSON']); ?>);
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['UNIQUE_ID']); ?>];
                                                outer = create_element("select", html_default["select"]);
                                                $(outer).attr("multiple", "true");
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(outer).attr("disabled", "true");
                                                }
                                                if (data) {
                                                    var myData = data.split(",");
                                                    myData.forEach(function(myDataElement) {
                                                        html_default["option"].value = myDataElement;
                                                        html_default["option"].createTextNode = initialvalues[myDataElement];
                                                        inner = create_element("option", html_default["option"]);
                                                        $(inner).attr("selected", "true");
                                                        outer.appendChild(inner);
                                                    });
                                                }
                                                return outer.outerHTML;
                                            },
                                            createdCell: function(td) {
                                                if ("<?= ($this->update && $column["ACTION"] == 2) ? true : false; ?>") {
                                                    $(td).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                            }
                                        <?php
                                            break;
                                        case 8: // DATE
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["input"].value = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 9: // DATETIME
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["input"].value = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= $this->update ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                    <?php
                                            break;
                                    }
                                    echo "},";
                                }
                                if ($this->delete) {
                                    ?> {
                                        orderable: false,
                                        searchable: false,
                                        className: "text-center align-middle",
                                        render: function(data) {
                                            return '<button class="btn btn-outline-danger btn-sm" style="box-shadow:none;width: 80px;" id="delete_<?= $this->tbl_ID; ?>"><b><?= $this->language_dwuty["buttons"]["delete"]; ?></b></button>';
                                        }
                                    }
                                <?php
                                }
                                ?>
                            ],
                            drawCallback: function() {
                                <?php
                                foreach ($this->columns as $column) {
                                    if ($column["TYP"] == 6 || $column["TYP"] == 7) {
                                ?>
                                        create_select2(<?= $this->obj_tools->post_encode($column["UNIQUE_ID"]); ?>, <?= $this->obj_tools->post_encode(($column["TYP"] == 6) ? $this->obj_tools->post_encode($column["SELECT2"]) : $this->obj_tools->post_encode($column["SUBSELECT2"])); ?>);
                                <?php
                                    }
                                }
                                ?>
                            },
                            <?= $additional_options; ?>
                        });
                    }
                    read_data_<?= $this->tbl_ID; ?>();
                    <?php
                    if ($this->create) {
                    ?> $("#add_<?= $this->tbl_ID; ?>").click(function() {
                            tr = create_element("tr");
                            <?php
                            foreach ($this->columns as $column) {
                                switch ($column["TYP"]) {
                                    case 0: // TEXT
                                    case 1: // EMAIL
                                    case 3: // LINK
                            ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        inner = create_element("div", html_default["div"]);
                                        if (!content(<?= $column["ACTION"]; ?>)) {
                                            $(inner).attr("contenteditable", "true");
                                            $(inner).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(inner).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                        }
                                        td.appendChild(inner);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 2: // CHECKBOX
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        outer = create_element("div", html_default["div"]);
                                        inner = create_element("input", html_default["input"]);
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(inner).attr("disabled", "true");
                                        } else {
                                            $(outer).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(outer).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                        }
                                        outer.appendChild(inner);
                                        td.appendChild(outer);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 4: // LINK_BUTTON
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        inner = create_element("a", html_default["a"]);
                                        $(inner).addClass("disabled");
                                        td.appendChild(inner);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 5: // COLOR
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        inner = create_element("input", html_default["input"]);
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(inner).attr("disabled", "true");
                                        } else {
                                            $(inner).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(inner).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                        }
                                        td.appendChild(inner);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 6: // DROPDOWN
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['UNIQUE_ID']); ?>];
                                        outer = create_element("select", html_default["select"]);
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(outer).attr("disabled", "true");
                                        } else {
                                            $(outer).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(outer).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                        }
                                        inner = create_element("option");
                                        outer.appendChild(inner);
                                        td.appendChild(outer);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 7: // DROPDOWN_MULTI
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['UNIQUE_ID']); ?>];
                                        outer = create_element("select", html_default["select"]);
                                        $(outer).attr("multiple", "true");
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(outer).attr("disabled", "true");
                                        } else {
                                            $(outer).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(outer).attr("data-select2_pkfield", <?= $this->obj_tools->post_encode($column["SELECT2_PKFIELD"]); ?>);
                                            $(outer).attr("data-select2_valuekey", <?= $this->obj_tools->post_encode($column["SELECT2_VALUEKEY"]); ?>);
                                            $(outer).attr("data-select2_datasource", <?= $this->obj_tools->post_encode($column["SELECT2_DATASOURCE"]); ?>);
                                        }
                                        inner = create_element("option");
                                        outer.appendChild(inner);
                                        td.appendChild(outer);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 8: // DATE
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        inner = create_element("input", html_default["input"]);
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(inner).attr("disabled", "true");
                                        } else {
                                            $(inner).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(inner).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                            $(outer).attr("data-name", name);
                                        }
                                        td.appendChild(inner);
                                        tr.appendChild(td);
                                    <?php
                                        break;
                                    case 9: // DATETIME
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        inner = create_element("input", html_default["input"]);
                                        if (content(<?= $column["ACTION"]; ?>)) {
                                            $(inner).attr("disabled", "true");
                                        } else {
                                            $(inner).attr("data-columntype", <?= $column["TYP"]; ?>);
                                            $(inner).attr("data-sqlname", <?= $this->obj_tools->post_encode($column["SQLNAME"]); ?>);
                                        }
                                        td.appendChild(inner);
                                        tr.appendChild(td);
                            <?php
                                        break;
                                }
                            }
                            ?>
                            object = {
                                class: ["text-center", "align-middle"]
                            };
                            button_td = create_element("td", object);
                            object = {
                                type: "button",
                                class: [
                                    "btn",
                                    "btn-outline-success",
                                    "btn-sm"
                                ],
                                style: [
                                    "box-shadow: none",
                                    "width: 80px"
                                ],
                                createTextNode: "<?= $this->language_dwuty["buttons"]["update"]; ?>",
                                id: "create_" + <?= $this->obj_tools->post_encode($this->tbl_ID); ?>
                            };
                            button = create_element("button", object);
                            button_td.appendChild(button);
                            tr.appendChild(button_td);
                            $("#<?= $this->tbl_ID; ?> tbody").prepend(tr);
                            <?php
                            foreach ($this->columns as $column) {
                                if ($column["TYP"] == 6 || $column["TYP"] == 7) {
                            ?>
                                    create_select2(<?= $this->obj_tools->post_encode($column["UNIQUE_ID"]); ?>, <?= $this->obj_tools->post_encode(($column["TYP"] == 6) ? $this->obj_tools->post_encode($column["SELECT2"]) : $this->obj_tools->post_encode($column["SUBSELECT2"])); ?>);
                            <?php
                                }
                            }
                            ?>
                        });
                        $(document).on("click", "#create_<?= $this->tbl_ID; ?>", function() {
                            currentRow = $(this).closest("tr");
                            const objInsert = {};
                            $(currentRow).find('td').each(function(dataid) {
                                data = $(this.innerHTML);
                                columntype = parseInt(data.attr("data-columntype"));
                                switch (columntype) {
                                    case 0: // TEXT
                                        value = $("div[type='text']", this).text().trim();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 1: // EMAIL
                                        value = $("div[type='email']", this).text().trim();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 2: // CHECKBOX
                                        value = $(":checkbox", this)[0].checked;
                                        if (value) {
                                            value = 1;
                                        } else {
                                            value = 0;
                                        }
                                        objInsert[dataid] = {
                                            "columntype": columntype,
                                            "sqlname": data.attr("data-sqlname"),
                                            "value": value
                                        };
                                        break;
                                    case 3: // LINK
                                        value = $("div[type='url']", this).text().trim();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 5: // COLOR
                                        value = $("input[type='color']", this).val();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 6: // DROPDOWN
                                        value = $("select", this).select2('data')[0].id;
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 7: // DROPDOWN_MULTI
                                        value_multi = $("select", this).select2('data');
                                        value = [];
                                        if (value_multi.length > 0) {
                                            value_multi.forEach(function(item) {
                                                value.push(item.id);
                                            });
                                        }
                                        if (value.length != 0) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "select2_pkfield": data.attr("data-select2_pkfield"),
                                                "select2_valuekey": data.attr("data-select2_valuekey"),
                                                "select2_datasource": data.attr("data-select2_datasource"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 8: // DATE
                                        value = $("input[type='date']", this).val();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 9: // DATETIME
                                        value = $("input[type='datetime-local']", this).val();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value.replace("T", " ")
                                            }
                                        }
                                        break;
                                }
                            });
                            insertdata = {
                                datasource: <?= $this->datasource ?>,
                                data: JSON.stringify(objInsert)
                            };
                            $.ajax({
                                url: "<?= $this->crud_path; ?>/create.php",
                                type: "POST",
                                dataType: "json",
                                data: insertdata,
                            });
                            $("#<?= $this->tbl_ID; ?>").DataTable().destroy();
                            read_data_<?= $this->tbl_ID; ?>();
                        });
                    <?php
                    }
                    if ($this->delete) {
                    ?> $(document).on("click", "#delete_<?= $this->tbl_ID; ?>", function() {
                            if (confirm("<?= $this->language_dwuty["deleteRecord"]; ?>")) {
                                $.ajax({
                                    url: "<?= $this->crud_path; ?>/delete.php",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                        pkvalue: $(this).closest("tr").attr("id").replace("row_", ""),
                                        datasource: <?= $this->datasource; ?>,
                                        dropdown_multi: <?= $this->obj_tools->post_encode($this->ajax_delete_dropdown_multi); ?>
                                    }
                                });
                                $("#<?= $this->tbl_ID; ?>").DataTable().destroy();
                                read_data_<?= $this->tbl_ID; ?>();
                            }
                        });
                    <?php
                    }
                    if ($this->update) {
                    ?> $(document).on("blur", ".update_<?= $this->tbl_ID; ?>", function() {
                            rowid = $(this).closest("tr").attr("id").replace("row_", "");
                            td = $(this).closest("td");
                            if (rowid) {
                                var table = $("#<?= $this->tbl_ID; ?>").DataTable();
                                columns = table.settings().init().columns;
                                colIndex = table.cell(td).index().columnVisible;
                                colName = columns[colIndex].name;
                                colData = columns[colIndex].data;
                                colCelltype = parseInt(columns[colIndex].celltype);
                                switch (colCelltype) {
                                    case 0: // TEXT
                                    case 1: // EMAIL
                                    case 3: // LINK
                                        value = $(this).text().trim();
                                        break;
                                    case 5: // COLOR
                                    case 8: // DATE
                                        value = $(this).val().trim();
                                        break;
                                    case 2: // CHECKBOX
                                        if (this.checked == true) {
                                            value = true;
                                        } else {
                                            value = false;
                                        }
                                        break;
                                    case 6: // DROPDOWN
                                        value = $(this).find("option:selected").val();
                                        break;
                                    case 7: // DROPDOWN_MULTI
                                        value = $(this).children(":first").val();
                                        break;
                                    case 9: // DATETIME
                                        value = $(this).val().replace("T", " ");
                                        break;
                                    default:
                                        break;
                                }
                                $.ajax({
                                    url: "<?= $this->crud_path; ?>/update.php",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                        pkvalue: rowid,
                                        field: colName,
                                        value: value,
                                        celltype: colCelltype,
                                        colData: colData,
                                        datasource: <?= $this->datasource; ?>,
                                        dropdown_multi: <?= $this->obj_tools->post_encode($this->ajax_update_dropdown_multi); ?>,
                                    }
                                });

                            }
                        });
                    <?php
                    }
                    ?>
                });

                function content(object = '') {
                    return parseInt(object) != 2 || Boolean(<?= $this->update ? "false" : "true"; ?>);
                }

                function create_element(
                    $element = '',
                    object = ''
                ) {
                    result = document.createElement($element);
                    if (object) {
                        for (const [key, value] of Object.entries(object)) {
                            switch (key) {
                                case 'type':
                                case 'id':
                                case 'value':
                                case 'rel':
                                case 'target':
                                case 'role':
                                case 'href':
                                case 'title':
                                case 'step':
                                    $(result).attr(key, value);
                                    break;
                                case 'class':
                                    $(result).addClass(value.join(" "));
                                    break;
                                case 'style':
                                    $(result).attr('style', value.join(";"));
                                    break;
                                case 'createTextNode':
                                    $(result).append(document.createTextNode(value));
                                    break;
                                default:
                                    break;
                            }
                        }

                    }
                    return result;
                }

                function create_select2(
                    select2_name = "",
                    select2_data = "",
                ) {
                    $(".SELECT2_" + select2_name).select2({
                        width: "100%",
                        language: "<?= $this->language; ?>",
                        dropdownAutoWidth: true,
                        allowClear: true,
                        placeholder: "<?= $this->language_dwuty["select2"]["placeholder"]; ?>",
                        ajax: {
                            url: "<?= $this->crud_path; ?>/read_select2.php",
                            type: "POST",
                            delay: 100,
                            dataType: "json",
                            theme: "bootstrap-5",
                            cache: false,
                            data: function(params) {
                                query = {
                                    search: params.term,
                                    type: "public",
                                    select2: select2_data,
                                }
                                return query;
                            },
                            processResults: function(response) {
                                return {
                                    results: response
                                };
                            },
                        },
                    });
                }
            </script>
        </footer>
<?php
    }
    public function new_column(
        $SqlName = "",
        $Displayname = "",
        $Action = 0,
        $Typ = 0,
        $arySetting = array()
    ) {
        $orderable = isset($arySetting["ORDERABLE"]) ? $arySetting["ORDERABLE"] : true;
        $searchable = isset($arySetting["SEARCHABLE"]) ? $arySetting["SEARCHABLE"] : true;
        $dtconfig = isset($arySetting["DT_CONFIG"]) ? $arySetting["DT_CONFIG"] : "";
        $required = isset($arySetting["REQUIRED"]) ? $arySetting["REQUIRED"] : "";
        $default = isset($arySetting["DEFAULT"]) ? $arySetting["DEFAULT"] : "";
        $encryption = isset($arySetting["ENCRYPTION"]) ? $arySetting["ENCRYPTION"] : false;
        $input_restrictions = isset($arySetting["INPUT_RESTRICTIONS"]) ? $arySetting["INPUT_RESTRICTIONS"] : false;
        $Name = $this->obj_tools->uniqueid();

        $this->columns[] = array( //default4all
            "SQLNAME" => $SqlName, "UNIQUE_ID" => $Name, "DISPLAYNAME" => $Displayname, "ACTION" => $Action, "TYP" => $Typ, "ORDERABLE" => $orderable, "SEARCHABLE" => $searchable, "DT_CONFIG" => $dtconfig, "REQUIRED" => $required, "DEFAULT" => $default, "ENCRYPTION" => $encryption, "INPUT_RESTRICTIONS" => $input_restrictions
        );
        foreach ($this->columns as $column_key => $column_value) {
            if ($this->columns[$column_key]["UNIQUE_ID"] == $Name && !empty($arySetting)) {
                foreach ($arySetting as $arySetting_key => $arySetting_value) {
                    switch ($arySetting_key) {
                        case "SELECT2": // // setting 4 select2 dropdown
                            switch ($Typ) {
                                case 6: // DROPDOWN
                                    $aryColumns = $arySetting["SELECT2"]["columns"];
                                    $this->webutility_ssp->set_length(-1); // remove length & paging
                                    $this->webutility_ssp->set_select($aryColumns);
                                    $this->webutility_ssp->set_from($arySetting["SELECT2"]["datasource"]);
                                    $this->webutility_ssp->set_where("DEL<>1");
                                    $sql = $this->webutility_ssp->set_data_sql();
                                    $ary_Select2Initial =  $this->obj_database_tools->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $this->obj_tools->post_encode($ary_Select2Initial);
                                    break;
                                case 7: // DT_EDIT_DROPDOWN_MULTI_v2
                                    $this->columns[$column_key]["SQLNAME"] = "(select group_concat(distinct " . $arySetting["SELECT2"]["columns"]["text"] . " separator ',') from " . $arySetting["SELECT2"]["datasource"] . " where " . $arySetting["SELECT2"]["columns"]["id"] . " = " . $this->pkfield . " and DEL<>1)";
                                    $this->columns[$column_key]["UNIQUE_ID"] = $arySetting["UNIQUE_ID"];
                                    $this->columns[$column_key]["SQLNAMETABLE"] = $SqlName;
                                    $this->columns[$column_key]["SELECT2_PKFIELD"] =  $arySetting["SELECT2"]["columns"]["id"];
                                    $this->columns[$column_key]["SELECT2_VALUEKEY"] = $arySetting["SELECT2"]["columns"]["text"];
                                    $this->columns[$column_key]["SELECT2_DATASOURCE"] = $arySetting["SELECT2"]["datasource"];
                                    $aryColumns = $arySetting["SUBSELECT2"]["columns"];
                                    $this->webutility_ssp->set_length(-1); // remove length & paging
                                    $this->webutility_ssp->set_select($aryColumns);
                                    $this->webutility_ssp->set_from($arySetting["SUBSELECT2"]["datasource"]);
                                    $this->webutility_ssp->set_where("DEL<>1");
                                    $sql = $this->webutility_ssp->set_data_sql();
                                    $ary_Select2Initial = $this->obj_database_tools->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $this->obj_tools->post_encode($ary_Select2Initial);
                                    $this->columns[$column_key]["SUBSELECT2"] = $arySetting["SUBSELECT2"];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            $this->columns[$column_key]["SELECT2"] = $arySetting["SELECT2"];
                            break;
                        default:
                            # code...
                            break;
                    }
                }
            }
        }
    }
    public function set_where(
        $strsqlwhere = ""
    ) {
        $this->ajax_read_where = $strsqlwhere;
    }
    private function html_default(
        $typ = ""
    ) {
        switch (intval($typ)) {
            case 0: // TEXT
                $result["alignment"] = array("align-middle");
                $result["div"] = array(
                    "type" => "text",
                    "class" => array("form-control"),
                    "style" => array("border: none", "background: transparent", "box-shadow: none")
                );
                break;
            case 1: // EMAIL
                $result["alignment"] = array("align-middle");
                $result["div"] = array(
                    "type" =>  array("email"),
                    "class" => array("form-control"),
                    "style" => array("border: none", "background: transparent", "box-shadow: none")
                );
                break;
            case 2: // CHECKBOX
                $result["alignment"] = array("text-center", "align-middle");
                $result["input"] = array(
                    "type" => "checkbox",
                    "class" => array("form-check-input"),
                    "style" => array("box-shadow: none")
                );
                $result["div"] = array(
                    "class" => array("form-switch"),
                );
                break;
            case 3: // LINK
                $result["alignment"] = array("align-middle");
                $result["div"] = array(
                    "type" => "url",
                    "class" => array("form-control"),
                    "style" => array("border: none", "background: transparent", "box-shadow: none")
                );
                $result["a"] = array(
                    "target" => "_blank",
                    "rel" => "noopener"
                );
                break;
            case 4: // LINK_BUTTON
                $result["alignment"] = array("text-center", "align-middle");
                $result["a"] = array(
                    "class" => array("btn", "btn-outline-primary", "form-control"),
                    "style" => array("box-shadow: none"),
                    "target" => "_blank",
                    "rel" => "noopener",
                    "role" => "button",
                    "createTextNode" => "Link"
                );
                break;
            case 5: // COLOR
                $result["alignment"] = array("text-center", "align-middle");
                $result["input"] = array(
                    "type" => "color",
                    "style" => array("box-shadow: none")
                );
                break;
            case 6: // DROPDOWN
                $result["alignment"] = array("text-center", "align-middle");
                $result["select"] = array();
                $result["option"] = array();
                break;
            case 7: // DROPDOWN_MULTI
                $result["alignment"] = array("text-center", "align-middle");
                $result["select"] = array();
                $result["option"] = array();
                break;
            case 8: // DATE
                $result["alignment"] = array("text-center", "align-middle");
                $result["input"] = array(
                    "type" => "date",
                    "class" => array("form-control"),
                    "style" => array("text-align: right", "box-shadow: none")
                );
                break;
            case 9: // DATETIME
                $result["alignment"] = array("text-center", "align-middle");
                $result["input"] = array(
                    "type" => "datetime-local",
                    "class" => array("form-control"),
                    "style" => array("text-align: right", "box-shadow: none"),
                    "step" => "1"
                );
                break;
            default:
                $result["alignment"] = array("text-center", "align-middle");
                $result[] = "";
                break;
        }
        return $result;
    }
}
?>