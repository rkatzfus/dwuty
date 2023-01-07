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


// DEFINE("DT_EDIT_PERCENT_v2", "9");
// DEFINE("DT_EDIT_CURRENCY_v2", "10");
// DEFINE("DT_EDIT_MODAL_BUTTON_v2", "11");
// DEFINE("DT_EDIT_NUMBER_v2", "12");
// DEFINE("DT_EDIT_FILE_v2", "14");
// DEFINE("tel", "14");




class webutility
{
    private $columns = array();
    private $tbl_ID;
    function __construct(
        $tbl_ID = "",
        $ajax  = array(),
        $pkfield  = ""
    ) {
        $this->obj_tools = new tools(false); // debug Mode
        $this->obj_database_tools = new database_tools();
        $this->webutility_ssp = new webutility_ssp(false); // debug Mode
        $this->ajax_read_where = "";
        $this->tbl_ID = $tbl_ID;
        $this->pkfield = $pkfield;
        if (isset($ajax)) {
            $read = false;
            $this->button_column = false;
            foreach ($ajax as $ajax_key => $ajax_value) {
                switch ($ajax_key) {
                    case "read":
                        $this->ajax_read_url = $ajax_value["url"];
                        $this->ajax_read_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $read = true;
                        break;
                    case "create":
                        $this->ajax_create_url = $ajax_value["url"];
                        $this->ajax_create_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->button_column = true;
                        break;
                    case "update":
                        $this->ajax_update_url =  $ajax_value["url"];
                        $this->ajax_update_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->ajax_update_dropdown_multi = (isset($ajax_value["dropdown_multi"])) ? $this->obj_tools->post_encode($ajax_value["dropdown_multi"]) : false;
                        $this->ajax_update_bypass = (isset($ajax_value["bypass"])) ? $this->obj_tools->post_encode($ajax_value["bypass"]) : false;
                        break;
                    case "delete":
                        $this->ajax_delete_url = $ajax_value["url"];
                        $this->ajax_delete_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->ajax_delete_dropdown_multi = (isset($ajax_value["dropdown_multi"])) ? $this->obj_tools->post_encode($ajax_value["dropdown_multi"]) : false;
                        $this->button_column = true;
                        break;
                    default:
                        throw new \Exception("AJAX ERROR occured!");
                        exit();
                }
            }
            if ($read != true) {
                throw new \Exception("no AJAX SOURCE defined");
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
                        echo (isset($this->ajax_create_url)) ? "<div class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' title='Datensatz anlegen' id='add_" . $this->tbl_ID . "' style='box-shadow: none; width: 80px;' data-ajaxdefault=''><b>anlegen</b></button></div>" : "";
                        echo "</th>";
                    }
                    ?>
                </tr>
            </thead>
        </table>
    <?php
    }
    public function config(
        $default_order = "",
        $default_order_dir = "asc",
        $additional_options = ""
    ) {
        $ary_SearchSelect2 = array();
        foreach ($this->columns as $column) {
            if ($column["TYP"] == 6 or $column["TYP"] == 7) {
                $ary_SearchSelect2[$column["SQLNAME"]] = $column["JSON"];
                $columnjson = $column["JSON"];
            }
        }
        foreach ($this->columns as $columns_key => $columns_value) {
            if ($columns_value["TYP"] != 11) { // MODAL_BUTTON
                if ($columns_value["TYP"] == 7) { // DROPDOWN_MULTI_FIELD
                    $columns_value["SQLNAME"] = $columns_value["SQLNAMETABLE"];
                    unset($columns_value["SQLNAMETABLE"]);
                    $columnsdata[$columns_key] = $columns_value;
                } else {
                    $columnsdata[$columns_key] = $columns_value;
                }
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
            <script src="/vendor/select2/select2/dist/js/i18n/de.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    function read_data_<?= $this->tbl_ID; ?>() {
                        var table = $("#<?= $this->tbl_ID; ?>").DataTable({
                            language: {
                                "emptyTable": "Keine Daten in der Tabelle vorhanden",
                                "info": "_START_ bis _END_ von _TOTAL_ Einträgen",
                                "infoEmpty": "Keine Daten vorhanden",
                                "infoFiltered": "(gefiltert von _MAX_ Einträgen)",
                                "infoThousands": ".",
                                "loadingRecords": "Wird geladen ..",
                                "processing": "Bitte warten ..",
                                "paginate": {
                                    "first": "Erste",
                                    "previous": "Zurück",
                                    "next": "Nächste",
                                    "last": "Letzte"
                                },
                                "aria": {
                                    "sortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                                    "sortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                                },
                                "select": {
                                    "rows": {
                                        "_": "%d Zeilen ausgewählt",
                                        "1": "1 Zeile ausgewählt"
                                    },
                                    "cells": {
                                        "1": "1 Zelle ausgewählt",
                                        "_": "%d Zellen ausgewählt"
                                    },
                                    "columns": {
                                        "1": "1 Spalte ausgewählt",
                                        "_": "%d Spalten ausgewählt"
                                    }
                                },
                                "buttons": {
                                    "print": "Drucken",
                                    "copy": "Kopieren",
                                    "copyTitle": "In Zwischenablage kopieren",
                                    "copySuccess": {
                                        "_": "%d Zeilen kopiert",
                                        "1": "1 Zeile kopiert"
                                    },
                                    "collection": "Aktionen <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
                                    "colvis": "Spaltensichtbarkeit",
                                    "colvisRestore": "Sichtbarkeit wiederherstellen",
                                    "copyKeys": "Drücken Sie die Taste <i>ctrl<\/i> oder <i>⌘<\/i> + <i>C<\/i> um die Tabelle<br \/>in den Zwischenspeicher zu kopieren.<br \/><br \/>Um den Vorgang abzubrechen, klicken Sie die Nachricht an oder drücken Sie auf Escape.",
                                    "csv": "CSV",
                                    "excel": "Excel",
                                    "pageLength": {
                                        "-1": "Alle Zeilen anzeigen",
                                        "_": "%d Zeilen anzeigen"
                                    },
                                    "pdf": "PDF"
                                },
                                "autoFill": {
                                    "cancel": "Abbrechen",
                                    "fill": "Alle Zellen mit <i>%d<i> füllen<\/i><\/i>",
                                    "fillHorizontal": "Alle horizontalen Zellen füllen",
                                    "fillVertical": "Alle vertikalen Zellen füllen"
                                },
                                "decimal": ",",
                                "search": "Suche:",
                                "searchBuilder": {
                                    "add": "Bedingung hinzufügen",
                                    "button": {
                                        "0": "Such-Baukasten",
                                        "_": "Such-Baukasten (%d)"
                                    },
                                    "condition": "Bedingung",
                                    "conditions": {
                                        "date": {
                                            "after": "Nach",
                                            "before": "Vor",
                                            "between": "Zwischen",
                                            "empty": "Leer",
                                            "not": "Nicht",
                                            "notBetween": "Nicht zwischen",
                                            "notEmpty": "Nicht leer",
                                            "equals": "Gleich"
                                        },
                                        "number": {
                                            "between": "Zwischen",
                                            "empty": "Leer",
                                            "equals": "Entspricht",
                                            "gt": "Größer als",
                                            "gte": "Größer als oder gleich",
                                            "lt": "Kleiner als",
                                            "lte": "Kleiner als oder gleich",
                                            "not": "Nicht",
                                            "notBetween": "Nicht zwischen",
                                            "notEmpty": "Nicht leer"
                                        },
                                        "string": {
                                            "contains": "Beinhaltet",
                                            "empty": "Leer",
                                            "endsWith": "Endet mit",
                                            "equals": "Entspricht",
                                            "not": "Nicht",
                                            "notEmpty": "Nicht leer",
                                            "startsWith": "Startet mit",
                                            "notContains": "enthält nicht",
                                            "notStarts": "startet nicht mit",
                                            "notEnds": "endet nicht mit"
                                        },
                                        "array": {
                                            "equals": "ist gleich",
                                            "empty": "ist leer",
                                            "contains": "enthält",
                                            "not": "ist ungleich",
                                            "notEmpty": "ist nicht leer",
                                            "without": "aber nicht"
                                        }
                                    },
                                    "data": "Daten",
                                    "deleteTitle": "Filterregel entfernen",
                                    "leftTitle": "Äußere Kriterien",
                                    "logicAnd": "UND",
                                    "logicOr": "ODER",
                                    "rightTitle": "Innere Kriterien",
                                    "title": {
                                        "0": "Such-Baukasten",
                                        "_": "Such-Baukasten (%d)"
                                    },
                                    "value": "Wert",
                                    "clearAll": "Alle löschen"
                                },
                                "searchPanes": {
                                    "clearMessage": "Leeren",
                                    "collapse": {
                                        "0": "Suchmasken",
                                        "_": "Suchmasken (%d)"
                                    },
                                    "countFiltered": "{shown} ({total})",
                                    "emptyPanes": "Keine Suchmasken",
                                    "loadMessage": "Lade Suchmasken..",
                                    "title": "Aktive Filter: %d",
                                    "showMessage": "zeige Alle",
                                    "collapseMessage": "Alle einklappen",
                                    "count": "{total}"
                                },
                                "thousands": ".",
                                "zeroRecords": "Keine passenden Einträge gefunden",
                                "lengthMenu": "_MENU_ Zeilen anzeigen",
                                "datetime": {
                                    "previous": "Vorher",
                                    "next": "Nachher",
                                    "hours": "Stunden",
                                    "minutes": "Minuten",
                                    "seconds": "Sekunden",
                                    "unknown": "Unbekannt",
                                    "weekdays": [
                                        "Sonntag",
                                        "Montag",
                                        "Dienstag",
                                        "Mittwoch",
                                        "Donnerstag",
                                        "Freitag",
                                        "Samstag"
                                    ],
                                    "months": [
                                        "Januar",
                                        "Februar",
                                        "März",
                                        "April",
                                        "Mai",
                                        "Juni",
                                        "Juli",
                                        "August",
                                        "September",
                                        "Oktober",
                                        "November",
                                        "Dezember"
                                    ]
                                },
                            },
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
                                    switch ($columns_value["TYP"]) {
                                        case 6: // DROPDOWN
                                            (isset($this->ajax_update_url) && $column["ACTION"] == 2) ? $classname[] = "update_" . $this->tbl_ID : "";
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                            (isset($this->ajax_update_url) && $column["ACTION"] == 2) ? $classname[] = "update_" . $this->tbl_ID : "";
                                            break;
                                    }
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
                                url: "<?= $this->ajax_read_url; ?>",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                    datasource: <?= $this->obj_tools->post_encode($this->ajax_read_datasource); ?>,
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
                                    echo "data: \"" . $column["NAME"] . "\", ";
                                    echo "celltype: \"" . $column["TYP"] . "\", ";
                                    switch ($column["TYP"]) {
                                        case 0: // TEXT
                                ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["input"].value = data;
                                                html_default["input"].title = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 1: // EMAIL
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["input"].value = data;
                                                html_default["input"].title = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 2: // CHECKBOX
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                outer = create_element("div", html_default["div"]);
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if (data == true) {
                                                    $(inner).attr("checked", "true");
                                                }
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
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
                                                html_default["a"].href = data;
                                                html_default["a"].title = data;
                                                outer = create_element("a", html_default["a"]);
                                                html_default["input"].value = data;
                                                inner = create_element("input", html_default["input"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(inner).attr("disabled", "true");
                                                }
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
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
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 6: // DROPDOWN
                                        ?> render: function(data) {
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['NAME']); ?>];
                                                outer = create_element("select", html_default["select"]);
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(outer).attr("disabled", "true");
                                                }
                                                if (data) {
                                                    html_default["option"].value = data;
                                                    html_default["option"].createTextNode = <?= $this->obj_tools->post_encode($column['JSON']); ?>[data];
                                                    inner = create_element("option", html_default["option"]);
                                                    outer.appendChild(inner);
                                                }
                                                return outer.outerHTML;
                                            }
                                        <?php
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                        ?> render: function(data) {
                                                aryJson = <?= $this->obj_tools->post_encode($columnjson); ?>;
                                                html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                                html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['NAME']); ?>];
                                                outer = create_element("select", html_default["select"]);
                                                $(outer).attr("multiple", "true");
                                                if (content(<?= $column["ACTION"]; ?>)) {
                                                    $(outer).attr("disabled", "true");
                                                }
                                                if (data) {
                                                    var myData = data.split(",");
                                                    myData.forEach(function(myDataElement) {
                                                        html_default["option"].value = myDataElement;
                                                        html_default["option"].createTextNode = aryJson[myDataElement];
                                                        inner = create_element("option", html_default["option"]);
                                                        $(inner).attr("selected", "true");
                                                        outer.appendChild(inner);
                                                    });
                                                }
                                                return outer.outerHTML;
                                            },
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
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
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
                                                if ("<?= isset($this->ajax_update_url) ? true : false; ?>") {
                                                    $(inner).addClass("update_<?= $this->tbl_ID ?>");
                                                }
                                                return inner.outerHTML;
                                            }
                                    <?php
                                            break;
                                    }
                                    echo "},";
                                }
                                if (isset($this->ajax_delete_url)) {
                                    ?> {
                                        orderable: false,
                                        searchable: false,
                                        className: "text-center align-middle",
                                        render: function(data) {
                                            return '<button class="btn btn-outline-danger btn-sm" style="box-shadow:none;width: 80px;" id="delete_<?= $this->tbl_ID; ?>"><b>löschen</b></button>';
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
                                        create_select2(<?= $this->obj_tools->post_encode($column["NAME"]); ?>, <?= $this->obj_tools->post_encode($column["AJAX"]); ?>, <?= $this->obj_tools->post_encode(($column["TYP"] == 6) ? $this->obj_tools->post_encode($column["SELECT2"]) : $this->obj_tools->post_encode($column["SUBSELECT2"])); ?>);
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
                    if (isset($this->ajax_create_url)) {
                    ?> $("#add_<?= $this->tbl_ID; ?>").click(function() {
                            tr = create_element("tr");
                            <?php
                            foreach ($this->columns as $column) {
                                switch ($column["TYP"]) {
                                    case 0: // TEXT
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
                                    case 1: // EMAIL
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
                                    case 3: // LINK
                                    ?>
                                        html_default = <?= $this->obj_tools->post_encode($this->html_default($column["TYP"])); ?>;
                                        object = {
                                            class: html_default["alignment"]
                                        };
                                        td = create_element("td", object);
                                        outer = create_element("a", html_default["a"]);
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
                                        html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['NAME']); ?>];
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
                                        html_default["select"].class = ["SELECT2_" + <?= $this->obj_tools->post_encode($column['NAME']); ?>];
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
                                createTextNode: "speichern",
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
                                    create_select2(<?= $this->obj_tools->post_encode($column["NAME"]); ?>, <?= $this->obj_tools->post_encode($column["AJAX"]); ?>, <?= $this->obj_tools->post_encode(($column["TYP"] == 6) ? $this->obj_tools->post_encode($column["SELECT2"]) : $this->obj_tools->post_encode($column["SUBSELECT2"])); ?>);
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
                                        value = $("input[type='text']", this).val();
                                        if (value) {
                                            objInsert[dataid] = {
                                                "columntype": columntype,
                                                "sqlname": data.attr("data-sqlname"),
                                                "value": value
                                            };
                                        }
                                        break;
                                    case 1: // EMAIL
                                        value = $("input[type='email']", this).val();
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
                                        value = $("input[type='url']", this).val();
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
                                datasource: <?= $this->obj_tools->post_encode($this->ajax_create_datasource); ?>,
                                data: JSON.stringify(objInsert)
                            };
                            $.ajax({
                                url: "<?= $this->ajax_create_url; ?>",
                                type: "POST",
                                dataType: "json",
                                data: insertdata,
                            });
                            $("#<?= $this->tbl_ID; ?>").DataTable().destroy();
                            read_data_<?= $this->tbl_ID; ?>();
                        });
                    <?php
                    }
                    if (isset($this->ajax_delete_url)) {
                    ?> $(document).on("click", "#delete_<?= $this->tbl_ID; ?>", function() {
                            if (confirm("Willst du diesen Datensatz wirklich löschen?")) {
                                $.ajax({
                                    url: "<?= $this->ajax_delete_url; ?>",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                        pkvalue: $(this).closest("tr").attr("id").replace("row_", ""),
                                        datasource: <?= $this->obj_tools->post_encode($this->ajax_delete_datasource); ?>,
                                        dropdown_multi: <?= $this->obj_tools->post_encode($this->ajax_delete_dropdown_multi); ?>
                                    }
                                });
                                $("#<?= $this->tbl_ID; ?>").DataTable().destroy();
                                read_data_<?= $this->tbl_ID; ?>();
                            }
                        });
                    <?php
                    }
                    if (isset($this->ajax_update_url)) {
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
                                    url: "<?= $this->ajax_update_url; ?>",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        pkfield: <?= $this->obj_tools->post_encode($this->pkfield); ?>,
                                        pkvalue: rowid,
                                        field: colName,
                                        value: value,
                                        celltype: colCelltype,
                                        colData: colData,
                                        datasource: <?= $this->obj_tools->post_encode($this->ajax_update_datasource); ?>,
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
                    return parseInt(object) != 2 || Boolean(<?= isset($this->ajax_update_url) ? "false" : "true"; ?>);
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
                    select2_url = "",
                    select2_data = ""
                ) {
                    $(".SELECT2_" + select2_name).select2({
                        width: "100%",
                        language: "de",
                        placeholder: "Auswahl",
                        dropdownAutoWidth: true,
                        allowClear: true,
                        ajax: {
                            url: select2_url,
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
        $Name = "",
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

        $this->columns[] = array( //default4all
            "SQLNAME" => $SqlName, "NAME" => $Name, "DISPLAYNAME" => $Displayname, "ACTION" => $Action, "TYP" => $Typ, "ORDERABLE" => $orderable, "SEARCHABLE" => $searchable, "DT_CONFIG" => $dtconfig, "REQUIRED" => $required, "DEFAULT" => $default, "ENCRYPTION" => $encryption, "INPUT_RESTRICTIONS" => $input_restrictions
        );
        foreach ($this->columns as $column_key => $column_value) {
            if ($this->columns[$column_key]["NAME"] == $Name && !empty($arySetting)) {
                foreach ($arySetting as $arySetting_key => $arySetting_value) {
                    switch ($arySetting_key) {
                        case "MODAL":
                            $this->columns[$column_key]["SQLNAME"] = "concat('" . $this->obj_tools->post_encode($arySetting["MODAL"]) . "')";
                            $this->columns[$column_key]["MODAL"] = $this->obj_tools->post_encode($arySetting["MODAL"]);
                            break;
                        case "SELECT2": // // setting 4 select2 dropdown
                            switch ($Typ) {
                                case 6: // DROPDOWN
                                    $aryColumns = $arySetting["SELECT2"]["columns"];
                                    $this->webutility_ssp->set_length(-1); // remove length & paging
                                    $this->webutility_ssp->set_select($aryColumns);
                                    $this->webutility_ssp->set_from($arySetting["SELECT2"]["from"]);
                                    (isset($arySetting["SELECT2"]["where"])) ? $this->webutility_ssp->set_where($arySetting["SELECT2"]["where"]) : $this->webutility_ssp->set_where();
                                    $sql = $this->webutility_ssp->set_data_sql();
                                    $ary_Select2Initial = $this->obj_database_tools->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $ary_Select2Initial;
                                    break;
                                case 7: // DT_EDIT_DROPDOWN_MULTI_v2
                                    $this->columns[$column_key]["SQLNAME"] = "(select group_concat(distinct " . $arySetting["SELECT2"]["columns"]["text"] . " separator ',') from " . $arySetting["SELECT2"]["from"] . " where " . $arySetting["SELECT2"]["columns"]["id"] . " = " . $this->pkfield . " and " . $arySetting["SELECT2"]["where"] . ")";
                                    $this->columns[$column_key]["SQLNAMETABLE"] = $SqlName;
                                    $this->columns[$column_key]["SELECT2_PKFIELD"] =  $arySetting["SELECT2"]["columns"]["id"];
                                    $this->columns[$column_key]["SELECT2_VALUEKEY"] = $arySetting["SELECT2"]["columns"]["text"];
                                    $this->columns[$column_key]["SELECT2_DATASOURCE"] = $arySetting["SELECT2"]["from"];
                                    $aryColumns = $arySetting["SUBSELECT2"]["columns"];
                                    $this->webutility_ssp->set_length(-1); // remove length & paging
                                    $this->webutility_ssp->set_select($aryColumns);
                                    $this->webutility_ssp->set_from($arySetting["SUBSELECT2"]["from"]);
                                    (isset($arySetting["SUBSELECT2"]["where"])) ? $this->webutility_ssp->set_where($arySetting["SUBSELECT2"]["where"]) : $this->webutility_ssp->set_where();
                                    $sql = $this->webutility_ssp->set_data_sql();
                                    $ary_Select2Initial = $this->obj_database_tools->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $ary_Select2Initial;
                                    $this->columns[$column_key]["SUBSELECT2"] = $arySetting["SUBSELECT2"];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            $this->columns[$column_key]["AJAX"] = $arySetting["AJAX"];
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
                $result["input"] = array(
                    "type" => "text",
                    "class" => array("form-control"),
                    "style" => array("border: none", "background: transparent", "box-shadow: none")
                );
                break;
            case 1: // EMAIL
                $result["alignment"] = array("align-middle");
                $result["input"] = array(
                    "type" => "email",
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
                $result["input"] = array(
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