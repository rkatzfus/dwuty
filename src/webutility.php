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
                    case "insert":
                        $this->ajax_insert_url = $ajax_value["url"];
                        $this->ajax_insert_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->ajax_insert_fade_out = (isset($ajax_value["fade_out"])) ? $this->obj_tools->post_encode($ajax_value["fade_out"]) : "";
                        $this->ajax_insert_dropdown_multi = (isset($ajax_value["dropdown_multi"])) ? $this->obj_tools->post_encode($ajax_value["dropdown_multi"]) : "";
                        $this->ajax_insert_check = (isset($ajax_value["check"])) ? $this->obj_tools->post_encode($ajax_value["check"]) : false;
                        $this->button_column = true;
                        break;
                    case "update":
                        $this->ajax_update_url =  $ajax_value["url"];
                        $this->ajax_update_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->ajax_update_dropdown_multi = (isset($ajax_value["dropdown_multi"])) ? $this->obj_tools->post_encode($ajax_value["dropdown_multi"]) : "";
                        break;
                    case "delete":
                        $this->ajax_delete_url = $ajax_value["url"];
                        $this->ajax_delete_datasource = $this->obj_tools->post_encode($ajax_value["datasource"]);
                        $this->ajax_delete_dropdown_multi = (isset($ajax_value["dropdown_multi"])) ? $this->obj_tools->post_encode($ajax_value["dropdown_multi"]) : "";
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
                        (isset($this->ajax_insert_url)) ? "<div class='text-center'><button type='button' class='btn btn-outline-primary btn-sm' title='Datensatz anlegen' name='add_" . $this->tbl_ID . "' id='add_" . $this->tbl_ID . "' style='box-shadow: none' data-ajaxdefault=''><b>anlegen</b></button></div>" : "";
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
                                    // isset($this->ajax_update_url) ? $classname[] = "update_" . $this->tbl_ID : "";
                                    switch ($columns_value["TYP"]) {
                                        case 2: // CHECKBOX
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 4: // LINK_BUTTON
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 5: // COLOR
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 6: // DROPDOWN
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 8: // DATE
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        case 9: // DATETIME
                                            $classname[] = "text-center";
                                            $classname[] = "align-middle";
                                            break;
                                        default:
                                            // code
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
                                    columnsdata: <?= $this->obj_tools->post_encode($columnsdata); ?>
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
                                                object = {
                                                    Html: "<input type='text' class='form-control' style='border: none; background: transparent; box-shadow: none;'>",
                                                    Attr: ["value", "title"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 1: // EMAIL
                                        ?> render: function(data) {
                                                object = {
                                                    Html: "<input type='email' class='form-control' style='border: none; background: transparent; box-shadow: none;'>",
                                                    Attr: ["value", "title"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 2: // CHECKBOX
                                        ?> render: function(data) {
                                                outer_object = {
                                                    Html: "<div class='form-switch'></div>",
                                                    Attr: ["value", "title"],
                                                    data: data
                                                };
                                                inner_object = {
                                                    Html: "<input class='form-check-input' type='checkbox' style='box-shadow: none;'>",
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                innerHtml = build_html(inner_object);
                                                outerHtml = build_html(outer_object);
                                                if (content_edit) {
                                                    innerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        innerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                if (data == true) {
                                                    innerHtml.attr("checked", "true");
                                                }
                                                outerHtml.append(innerHtml);
                                                return outerHtml.prop("outerHTML");
                                            },
                                        <?php
                                            break;
                                        case 3: // LINK
                                        ?> render: function(data) {
                                                outer_object = {
                                                    Html: "<a target='_blank' rel='noopener'></a>",
                                                    Attr: ["href", "title"],
                                                    data: data
                                                };
                                                inner_object = {
                                                    Html: "<input type='url' class='form-control' style='border: none; background: transparent; box-shadow: none;'>",
                                                    Attr: ["value"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                innerHtml = build_html(inner_object);
                                                outerHtml = build_html(outer_object);
                                                if (content_edit) {
                                                    innerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        innerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                outerHtml.append(innerHtml);
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 4: // LINK_BUTTON
                                        ?> render: function(data) {
                                                object = {
                                                    Html: "<a class='btn btn-outline-primary form-control' target='_blank' rel='noopener' role='button' style='box-shadow: none;'>Link</a>",
                                                    Attr: ["href", "title"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.addClass("disabled");
                                                }
                                                if (data) {
                                                    return outerHtml.prop("outerHTML");
                                                } else {
                                                    return '';
                                                }
                                            }
                                        <?php
                                            break;
                                        case 5: // COLOR
                                        ?> render: function(data) {
                                                object = {
                                                    Html: "<input type='color' style='box-shadow: none;'>",
                                                    Attr: ["value"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 6: // DROPDOWN
                                        ?> render: function(data) {
                                                outer_object = {
                                                    Html: "<select class='SELECT2_<?= $column['NAME']; ?>'></select>",
                                                    data: data
                                                };
                                                inner_object = {
                                                    Html: "<option>" + <?= $this->obj_tools->post_encode($column['JSON']); ?>[data] + "</option>",
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                innerHtml = build_html(inner_object);
                                                outerHtml = build_html(outer_object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                if (data) {
                                                    outerHtml.append(innerHtml);
                                                }
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                        ?> render: function(data) {
                                                outer_object = {
                                                    Html: "<select class='SELECT2_<?= $column['NAME']; ?>' multiple></select>",
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                aryJson = <?= $this->obj_tools->post_encode($column["JSON"]); ?>;
                                                outerHtml = build_html(outer_object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                if (data) {
                                                    var myData = data.split(",");
                                                    myData.forEach(function(myDataElement) {
                                                        innerHtml = $("<option selected>" + aryJson[myDataElement] + "</option>");
                                                        innerHtml.attr("value", myDataElement);
                                                        outerHtml.append(innerHtml);
                                                    });
                                                }
                                                return outerHtml.prop("outerHTML");
                                            },
                                        <?php
                                            break;
                                        case 8: // DATE
                                        ?> render: function(data) {
                                                object = {
                                                    Html: "<input type='date' class='form-control' style='text-align: right; box-shadow: none;'>",
                                                    Attr: ["value"],
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                return outerHtml.prop("outerHTML");
                                            }
                                        <?php
                                            break;
                                        case 9: // DATETIME
                                        ?> render: function(data) {
                                                object = {
                                                    Html: "<input type='datetime-local' class='form-control' style='text-align: right; box-shadow: none;' step='1'>",
                                                    data: data
                                                };
                                                content_edit = content(<?= $column["ACTION"]; ?>);
                                                outerHtml = build_html(object);
                                                if (content_edit) {
                                                    outerHtml.attr("disabled", "true");
                                                } else {
                                                    url = "<?= isset($this->ajax_update_url) ? true : false; ?>";
                                                    if (url) {
                                                        outerHtml.addClass("update_<?= $this->tbl_ID ?>");
                                                    }
                                                }
                                                if (data) {
                                                    outerHtml.attr("value", data.replace(" ", "T"));
                                                }
                                                return outerHtml.prop("outerHTML");
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
                                            return '<button class="btn btn-outline-danger btn-sm" style="box-shadow:none;" id="delete_<?= $this->tbl_ID; ?>"><b>löschen</b></button>';
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
                                        switch ($column["TYP"]) {
                                            case 6: // DT_EDIT_DROPDOWN_v2
                                                $select2data = json_encode($column["SELECT2"], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                                                break;
                                            case 7: // DT_EDIT_DROPDOWN_MULTI_v2
                                                $select2data = json_encode($column["SUBSELECT2"], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                                                break;
                                            default:
                                                # code...
                                                break;
                                        }
                                ?>
                                        $(".SELECT2_<?= $column["NAME"]; ?>").select2({
                                            width: "100%",
                                            language: "de",
                                            placeholder: "Auswahl",
                                            dropdownAutoWidth: true,
                                            allowClear: true,
                                            ajax: {
                                                url: "<?= $column["AJAX"]; ?>",
                                                type: "POST",
                                                delay: 100,
                                                dataType: "json",
                                                theme: "bootstrap-5",
                                                cache: false,
                                                data: function(params) {
                                                    query = {
                                                        search: params.term,
                                                        type: "public",
                                                        select2: <?= $this->obj_tools->post_encode($select2data); ?>,
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
                    ?>
                        $(document).on("change", ".update_<?= $this->tbl_ID; ?>", function() {
                            rowid = $(this).closest("tr").attr("id").replace("row_", "");
                            // var table = $("#<#?= $this->tbl_ID; ?>").DataTable();
                            // console.log(table);
                            // if (rowid) {
                            //     alert(rowid);
                            // }

                            // if (confirm("Willst du diesen Datensatz wirklich löschen?")) {
                            //     $.ajax({
                            //         url: "<#?= $this->ajax_delete_url; ?>",
                            //         type: "POST",
                            //         dataType: "json",
                            //         data: {
                            //             pkfield: <#?= $this->obj_tools->post_encode($this->pkfield); ?>,
                            //             pkvalue: $(this).closest("tr").attr("id").replace("row_", ""),
                            //             datasource: <#?= $this->obj_tools->post_encode($this->ajax_delete_datasource); ?>,
                            //             dropdown_multi: <#?= $this->obj_tools->post_encode($this->ajax_delete_dropdown_multi); ?>
                            //         }
                            //     });
                            //     $("#<#?= $this->tbl_ID; ?>").DataTable().destroy();
                            //     read_data_<#?= $this->tbl_ID; ?>();
                            // }
                        });
                    <?php
                    }
                    ?>
                });

                function content(object = '') {
                    return parseInt(object) != 2 || Boolean(<?= isset($this->ajax_update_url) ? "false" : "true"; ?>);
                }

                function build_html(object = '') {
                    html = $(object["Html"]);
                    attr = object["Attr"];
                    data = object["data"];
                    if (data && attr) {
                        const iterator = attr.values();
                        for (const value of iterator) {
                            html.attr(value, data);
                        }
                    }
                    return html;
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
                                    $this->columns[$column_key]["SQLNAME"] = "group_concat(distinct " . $arySetting["SELECT2"]["columns"]["text"] . " separator ',')";
                                    $this->columns[$column_key]["SQLNAMETABLE"] = $SqlName;
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
}
?>