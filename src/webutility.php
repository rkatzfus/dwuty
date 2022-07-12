<?php

namespace App;

define("UNSELECT", "0");
define("VIEW", "1");
define("EDIT", "2");
//------------------
define('TEXT', '0');
define('EMAIL', '1');
define('CHECKBOX', '2');
define('LINK', '3');
define('LINK_BUTTON', '4');
define('COLOR', '5');
define('DROPDOWN', '6');
define('DROPDOWN_MULTI', '7');
define('DATE', '8');
define('DATETIME', '9');


// define('TEXT', '0');
// define('CHECKBOX', '1');
// define('DROPDOWN', '2');
// define('DROPDOWN_MULTI', 'X');
// define('LINK', '3');
// define('LINK_BUTTON', '4');
// define('DATE', '5');
// define('DATETIME', '6');
// define('COLOR', '7');
// define('EMAIL', '9');



// DEFINE('DT_EDIT_STATE_v2', '3');



// DEFINE('DT_EDIT_DROPDOWN_MULTI_v2', '8');
// DEFINE('DT_EDIT_PERCENT_v2', '9');
// DEFINE('DT_EDIT_CURRENCY_v2', '10');
// DEFINE('DT_EDIT_MODAL_BUTTON_v2', '11');
// DEFINE('DT_EDIT_NUMBER_v2', '12');
// DEFINE('DT_EDIT_FILE_v2', '14');
// DEFINE('tel', '14');




class webutility
{
    private $columns = array();
    private $tbl_ID;
    function __construct(
        $tbl_ID = "",
        $ajax  = array(),
        $pkfield  = ""
    ) {
        $this->obj_mysqli = new database_tools();
        $this->obj_ssp = new webutility_ssp($debug = false);
        $this->ajax_fetch_where = "";
        $this->tbl_ID = $tbl_ID;
        $this->pkfield = $pkfield;
        if (isset($ajax)) {
            $fetch = false;
            $this->button_column = false;
            foreach ($ajax as $key => $value) {
                switch ($key) {
                    case 'fetch':
                        $this->ajax_fetch_url = $value['url'];
                        $this->ajax_fetch_datasource = $this->post_encode($value['datasource']);
                        $fetch = true;
                        break;
                    case 'insert':
                        $this->ajax_insert_url = $value['url'];
                        $this->ajax_insert_datasource = $this->post_encode($value['datasource']);
                        (isset($value['fade_out'])) ? $this->ajax_insert_fade_out = $this->post_encode($value['fade_out']) : "";
                        (isset($value['dropdown_multi'])) ? $this->ajax_insert_dropdown_multi = $this->post_encode($value['dropdown_multi']) : "";
                        (isset($value['check'])) ? $this->ajax_insert_check = $this->post_encode($value['check']) : $this->ajax_insert_check = 'false';
                        $this->button_column = true;
                        break;
                    case 'update':
                        $this->ajax_update_url =  $value['url'];
                        $this->ajax_update_datasource = $this->post_encode($value['datasource']);
                        (isset($value['dropdown_multi'])) ? $this->ajax_update_dropdown_multi = $this->post_encode($value['dropdown_multi']) : "";
                        break;
                    case 'delete':
                        $this->ajax_delete_url = $value['url'];
                        $this->ajax_delete_datasource = $this->post_encode($value['datasource']);
                        $this->button_column = true;
                        break;
                    default:
                        throw new \Exception('Es ist ein AJAX ERROR aufgetreten!');
                        exit();
                }
            }
            if ($fetch != true) {
                throw new \Exception('Es wurde keine AJAX Quelle eingetragen');
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
                        echo "<th>" . $column['DISPLAYNAME'] . "</th>";
                    }
                    if ($this->button_column == true) {
                        echo "<th>";
                        if (isset($this->ajax_insert_url)) {
                    ?>
                            <div class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm" title="Datensatz anlegen" name="add_<?= $this->tbl_ID; ?>" id="add_<?= $this->tbl_ID; ?>" style="box-shadow: none;width: 80px;" data-ajaxdefault="">
                                    <b>anlegen</b>
                                </button>
                            </div>
                    <?php
                        }
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
            if ($column['TYP'] == 6 or $column['TYP'] == 7) {
                $ary_SearchSelect2[$column['SQLNAME']] = $column['JSON'];
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
            <script type="text/javascript">
                $(document).ready(function() {
                    function fetch_data_<?= $this->tbl_ID; ?>() {
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
                                    ($columns_value['ACTION'] == 2 && isset($this->ajax_update_url)) ? $classname[] = "contenteditable" : "";
                                    switch ($columns_value["TYP"]) {
                                        case 2: // CHECKBOX
                                            $classname[] = "text-center";
                                            break;
                                        case 4: // LINK_BUTTON
                                            $classname[] = "text-center";
                                            break;
                                        case 5: // COLOR
                                            $classname[] = "text-center";
                                            break;
                                        case 6: // DROPDOWN
                                            $classname[] = "text-center";
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                            $classname[] = "text-center";
                                            break;
                                        case 8: // DATE
                                            $classname[] = "text-center";
                                            break;
                                        case 9: // DATETIME
                                            $classname[] = "text-center";
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
                                url: "<?= $this->ajax_fetch_url; ?>",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    pkfield: <?= $this->post_encode($this->pkfield); ?>,
                                    datasource: <?= $this->post_encode($this->ajax_fetch_datasource); ?>,
                                    where: <?= $this->post_encode($this->ajax_fetch_where); ?>,
                                    columnsdata: <?= $this->post_encode($columnsdata); ?>
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
                                    switch ($column['TYP']) {
                                        case 0: // TEXT
                                ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<input type='text' class='form-control' style='border: none; background: transparent; box-shadow: none;' value='" + data + "' title='" + data + "'>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 1: // EMAIL
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<input type='email' class='form-control' style='border: none; background: transparent; box-shadow: none;' value='" + data + "' title='" + data + "'>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 2: // CHECKBOX
                                        ?> render: function(data) {
                                                is_checked = (data == true) ? 'checked' : '';
                                                return "<div class='form-switch'><input class='form-check-input' type='checkbox' style='box-shadow: none;' " + is_checked + "></div>";
                                            },
                                        <?php
                                            break;
                                        case 3: // LINK
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<a href='" + data + "' title='" + data + "' target='_blank' rel='noopener'><input type='url' class='form-control' style='border: none; background: transparent; box-shadow: none;' value='" + data + "'></a>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 4: // LINK_BUTTON
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<a class='btn btn-outline-primary form-control' href='" + data + "' title='" + data + "' target='_blank' rel='noopener' role='button' style='box-shadow: none;'>Link</a>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 5: // COLOR
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<input type='color' style='box-shadow: none;' value='" + data + "'>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 6: // DROPDOWN
                                        ?> render: function(data) {
                                                aryJson = <?= $this->post_encode($column['JSON']); ?>;
                                                select = $('<select class="SELECT2_<?= $column['NAME']; ?>"></select>', {})
                                                if (data != 0 && data != null) {
                                                    option = $("<option>" + aryJson[data] + "</option>");
                                                    option.attr("selected", "selected")
                                                    select.append(option);
                                                    option.attr("value", data);
                                                    select.append(option);
                                                    select.attr("data-search", data);
                                                }
                                                return select.prop("outerHTML");
                                            },
                                        <?php
                                            break;
                                        case 7: // DROPDOWN_MULTI
                                        ?> render: function(data) {
                                                aryJson = <?= $this->post_encode($column['JSON']); ?>;
                                                select = $('<select class="SELECT2_<?= $column['NAME']; ?>" multiple></select>', {})
                                                if (data != 0 && data != null) {
                                                    option = $("<option>" + aryJson[data] + "</option>");
                                                    option.attr("selected", "selected")
                                                    select.append(option);
                                                    option.attr("value", data);
                                                    select.append(option);
                                                    select.attr("data-search", data);
                                                }
                                                return select.prop("outerHTML");
                                            },
                                        <?php
                                            break;
                                        case 8: // DATE
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<input type='date' class='form-control' style='text-align: right; box-shadow: none;' value='" + data + "'>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                        <?php
                                            break;
                                        case 9: // DATETIME
                                        ?> render: function(data) {
                                                if (data !== null) {
                                                    return "<input type='datetime-local' class='form-control' style='text-align: right; box-shadow: none;' value='" + data.replace(" ", "T") + "' step='1'>";
                                                } else {
                                                    return "";
                                                }
                                            }
                                    <?php
                                            break;
                                        default:
                                            # code...
                                            break;
                                    }
                                    // isset($column["DT_CONFIG"])?$column["DT_CONFIG"]:"";
                                    echo "},";
                                }
                                if (isset($this->ajax_delete_url)) {
                                    ?> {
                                        orderable: false,
                                        searchable: false,
                                        className: "align-middle",
                                        // render: delete_button_
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
                                            disabled: false,
                                            // disabled: <? #= (!isset($this->ajax_update_url))?"true":"false"; 
                                                            ?>,
                                            width: "100%",
                                            language: "de",
                                            placeholder: "Auswahl",
                                            dropdownAutoWidth: true,
                                            allowClear: true,
                                            ajax: {
                                                url: "<?= $column["AJAX"]; ?>",
                                                type: "POST",
                                                delay: 100,
                                                dataType: 'json',
                                                theme: 'bootstrap-5',
                                                cache: false,
                                                data: function(params) {
                                                    query = {
                                                        search: params.term,
                                                        type: "public",
                                                        select2: <?= $this->post_encode($select2data); ?>,
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
                    fetch_data_<?= $this->tbl_ID; ?>();
                });
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
        if ($Typ == 2 || $Typ == 8) {
            $Action = 0;
        }
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
                            $this->columns[$column_key]["SQLNAME"] = "concat('" . $this->post_encode($arySetting["MODAL"]) . "')";
                            $this->columns[$column_key]["MODAL"] = $this->post_encode($arySetting["MODAL"]);
                            break;
                        case "SELECT2": // // setting 4 select2 dropdown
                            switch ($Typ) {
                                case 6: // DROPDOWN
                                    $aryColumns = $arySetting["SELECT2"]["columns"];
                                    $this->obj_ssp->set_length(-1); // remove length & paging
                                    $this->obj_ssp->set_Select($aryColumns);
                                    $this->obj_ssp->set_From($arySetting["SELECT2"]["from"]);
                                    (isset($arySetting["SELECT2"]["where"])) ? $this->obj_ssp->set_Where($arySetting["SELECT2"]["where"]) : $this->obj_ssp->set_Where();
                                    $sql = $this->obj_ssp->set_data_sql();
                                    $ary_Select2Initial = $this->obj_mysqli->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $ary_Select2Initial;
                                    break;
                                case 7: // DT_EDIT_DROPDOWN_MULTI_v2
                                    $this->columns[$column_key]["SQLNAME"] = "group_concat(distinct " . $arySetting["SELECT2"]["columns"]["text"] . " separator ',')";
                                    // $this->columns[$column_key]["SQLNAME"] = "group_concat(distinct " . $arySetting["SELECT2"]["columns"]["text"] . " separator ',') as " . $this->columns[$column_key]["NAME"];
                                    // $this->columns[$column_key]["SQLNAME"] = "substring((select \',\' + cast(" . $arySetting["SELECT2"]["columns"]["text"] . " as varchar) from " . $arySetting["SELECT2"]["from"] . " where " . $this->pkfield . " = " . $arySetting["SELECT2"]["columns"]["id"] . " and " . $arySetting["SELECT2"]["where"] . " for xml path(\'\')), 2, 1000000)";
                                    $this->columns[$column_key]["SQLNAMETABLE"] = $SqlName;
                                    $aryColumns = $arySetting["SUBSELECT2"]["columns"];
                                    $this->obj_ssp->set_length(-1); // remove length & paging
                                    $this->obj_ssp->set_Select($aryColumns);
                                    $this->obj_ssp->set_From($arySetting["SUBSELECT2"]["from"]);
                                    (isset($arySetting["SUBSELECT2"]["where"])) ? $this->obj_ssp->set_Where($arySetting["SUBSELECT2"]["where"]) : $this->obj_ssp->set_Where();
                                    $sql = $this->obj_ssp->set_data_sql();
                                    $ary_Select2Initial = $this->obj_mysqli->sql2array_pk_value($sql, "id", "text");
                                    $this->columns[$column_key]["JSON"] = $ary_Select2Initial;
                                    $this->columns[$column_key]["SUBSELECT2"] = $arySetting["SUBSELECT2"];
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            $this->columns[$column_key]['AJAX'] = $arySetting['AJAX'];
                            $this->columns[$column_key]['SELECT2'] = $arySetting['SELECT2'];
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
        $this->ajax_fetch_where = $strsqlwhere;
    }
    public function post_encode(
        $aryIncoming = array()
    ) {
        if (!empty($aryIncoming)) {
            return json_encode($aryIncoming, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        } else {
            return "no matching data delivered";
        }
    }
    public function encrypt(
        $data = "",
        $password = ""
    ) {
        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($password);
        $salt = sha1(mt_rand());
        $saltWithPassword = hash('sha256', $password . $salt);
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $saltWithPassword,
            0,
            $iv
        );
        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        return $msg_encrypted_bundle;
    }
    public function decrypt(
        $msg_encrypted_bundle = "",
        $password = ""
    ) {
        $password = sha1($password);
        $components = explode(':', $msg_encrypted_bundle);
        $iv            = $components[0];
        $salt          = hash("sha256", $password . $components[1]);
        $encrypted_msg = $components[2];
        $decrypted_msg = openssl_decrypt(
            $encrypted_msg,
            "aes-256-cbc",
            $salt,
            0,
            $iv
        );
        if ($decrypted_msg === false)
            return false;
        return $decrypted_msg;
    }
}
?>