<?php

namespace App;

class webutility_ssp
{
    private $draw;
    private $recordsTotal;
    private $recordsFiltered;
    private $intlength;
    private $intstart;
    private $strsqlOrder;
    private $data;

    public function __construct(
        $debug = false
    ) {
        $this->debug = $debug;
        $this->draw = 0;
        $this->intlength = 10;
        $this->intstart = 0;
        $this->strsqlOrder = "";
        $this->strGroupBy = "";
        $this->data = array();
        $this->obj_mysqli = new database_tools();
    }
    public function set_draw(
        $draw = 0
    ) {
        $this->draw = intval($draw);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_draw</b><br>";
            echo $this->draw;
        }
    }
    public function set_length(
        $length = 10
    ) {
        $this->intlength = intval($length);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_length</b><br>";
            echo $this->intlength;
        }
    }
    public function set_start(
        $intstart = 0
    ) {
        $this->intstart = intval($intstart);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_start</b><br>";
            echo $this->intstart;
        }
    }
    private function length_and_paging()
    {
        if ($this->intlength == -1) {
            return "";
        } else {
            return " limit " . $this->intstart . ", " . $this->intlength;
        }
    }
    public function set_order(
        $orders = array(),
        $columns = array()
    ) {
        $sql_order = array();
        foreach ($orders as $ordersValue) {
            array_push($sql_order, $columns[$ordersValue["column"]]["data"] . " " . $ordersValue["dir"]);
        }
        $this->strsqlOrder = " order by " . implode(", ", $sql_order);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_order</b><br>";
            echo $this->strsqlOrder;
        }
    }
    private function set_recordsTotal()
    {
        if (!isset($this->recordsTotal) && $this->recordsTotal < 1) {
            $sql = $this->count_records(false);
            $this->recordsTotal = intval($this->obj_mysqli->sql_getfield($sql));
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>function set_recordsTotal</b><br>";
                echo $this->recordsTotal;
                echo "<br>" . $sql;
            }
        }
    }
    private function set_recordsFiltered()
    {
        if (!isset($this->recordsFiltered) && $this->recordsFiltered < 1) {
            $sql = $this->count_records(true);
            $this->recordsFiltered = intval($this->obj_mysqli->sql_getfield($sql));
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>function set_recordsFiltered</b><br>";
                echo $this->recordsFiltered;
                echo "<br>" . $sql;
            }
        }
    }
    public function set_select(
        $ary_Select = array()
    ) {
        $this->strsqlSelectStart = "select ";
        $this->ary_sqlSelectInline = array();
        if (array_search('DT_RowId', array_column($ary_Select, 'dt')) !== false) {
            foreach ($ary_Select as $Select_value) {
                if ($Select_value['dt'] == 'DT_RowId') {
                    $this->strsqlSelectStart .= "concat('row_', " . $Select_value['db'] . ") as DT_RowId";
                } else {
                    $this->ary_sqlSelectInline[] = $Select_value['db'] . " as " . $Select_value['dt'];
                }
            }
            $this->strsqlSelect = $this->strsqlSelectStart . ", " . implode(",", $this->ary_sqlSelectInline);
        } elseif (!empty($ary_Select)) {
            foreach ($ary_Select as $Select_key => $Select_value) {
                $this->ary_sqlSelectInline[] = $Select_value . " as " . $Select_key;
            }
            $this->strsqlSelect =  $this->strsqlSelectStart . implode(",", $this->ary_sqlSelectInline);
        } else {
            $this->debug = true;
            echo "<hr>";
            echo "<b>an error has occured!</b>";
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_select</b><br>";
            echo $this->strsqlSelect;
            var_dump($ary_Select);
        }
    }
    public function set_from(
        $strSqlFrom = ""
    ) {
        $this->strSqlFrom = "from " . $strSqlFrom;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_from</b><br>";
            echo $this->strSqlFrom;
        }
    }
    public function set_groupBy(
        $aryGroupBy = array()
    ) {
        $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_groupBy</b><br>";
            echo $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        }
    }
    public function set_where(
        $strSqlWhere = ""
    ) {
        $this->strSqlWhere = $strSqlWhere;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_where</b><br>";
            echo $this->strSqlWhere;
        }
    }
    public function set_search(
        $strSqlSearch
    ) {
        $this->strSqlSearch = $strSqlSearch;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_search</b><br>";
            echo $this->strSqlSearch;
        }
    }
    public function set_searchColumn(
        $strSqlSearchColumn
    ) {
        $this->strSqlSearchColumn = $strSqlSearchColumn;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_searchColumn</b><br>";
            echo $this->strSqlSearchColumn;
        }
    }
    public function set_columns(
        $arycolumns = array()
    ) {
        $this->arycolumns = $arycolumns;
        foreach ($arycolumns as $column) {
            $this->arycolumns_id[$column["dt"]] = $column["db"];
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_columns</b><br>";
            var_dump($this->arycolumns_id);
        }
    }
    public function set_data_sql()
    {
        $ary_sql[] = $this->strsqlSelect;
        $ary_sql[] = $this->strSqlFrom;
        !empty($this->build_where()) ? $ary_sql[] = "where " . implode(" ", $this->build_where()) : "";
        $ary_sql[] = $this->strGroupBy;
        $ary_sql[] = $this->strsqlOrder;
        $ary_sql[] = $this->length_and_paging();
        $sql = implode(" ", $ary_sql);
        $result = ($this->obj_mysqli->chk_stmnt($sql) == true) ? $this->obj_mysqli->sql2array($sql) : "";
        if ($result != false && isset($this->arycolumns)) {
            foreach ($result as $value_query) {
                $rowdata = array();
                foreach ($this->arycolumns as $column) {
                    $rowdata[$column["dt"]] = $value_query[$column["dt"]];
                }
                array_push($this->data, $rowdata);
            }
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_data_sql (final sql)</b><br>";
            echo $sql;
        }
        return  $sql;
    }
    public function set_data(
        $data = ""
    ) {
        $this->data = ($data);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_data</b><br>";
            echo $this->data;
        }
    }
    public function read()
    {
        $result["draw"] = $this->draw;
        $this->set_recordsTotal();
        $result["recordsTotal"] = $this->recordsTotal;
        $this->set_recordsFiltered();
        $result["recordsFiltered"] = $this->recordsFiltered;
        $result["data"] = $this->data;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function read</b><br>";
        }
        echo json_encode($result);
    }
    private function build_where()
    {
        $ary_where = array();
        (!empty($this->strSqlWhere)) ? (empty($ary_where) ? $ary_where[] = "(" . $this->strSqlWhere . ")" : $ary_where[] = "and (" . $this->strSqlWhere . ")") : "";
        (!empty($this->strSqlSearch)) ? (empty($ary_where) ? $ary_where[] = "(" . $this->strSqlSearch . ")" : $ary_where[] = "and (" . $this->strSqlSearch . ")") : "";
        (!empty($this->strSqlSearchColumn)) ? (empty($ary_where) ? $ary_where[] = "(" . $this->strSqlSearchColumn . ")" : $ary_where[] = "and (" . $this->strSqlSearchColumn . ")") : "";
        return $ary_where;
    }
    private function count_records(
        $filter = false
    ) {
        if ($filter) {
            $where = !empty($this->build_where()) ? $ary_sql[] = "where " . implode(" ", $this->build_where()) : "";
            return "select count(*) from (select distinct (" . $this->arycolumns_id["DT_RowId"] . ") " . $this->strSqlFrom . " " . $where . ") tmp";
        } else {
            return "select count(*) from (select distinct (" . $this->arycolumns_id["DT_RowId"] . ") " . $this->strSqlFrom . ") tmp";
        }
    }
}
