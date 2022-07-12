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
            echo $this->draw = intval($draw);
        }
    }
    public function set_length(
        $length = 10
    ) {
        $this->intlength = intval($length);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_length</b><br>";
            echo $this->intlength = intval($length);
        }
    }
    public function set_start(
        $intstart = 0
    ) {
        $this->intstart = intval($intstart);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_start</b><br>";
            echo $this->intstart = intval($intstart);
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
            echo $this->strsqlOrder = " order by " . implode(", ", $sql_order);
        }
    }
    private function set_recordsTotal()
    {
        if (!isset($this->recordsTotal) && $this->recordsTotal < 1) {
            $sql = "select count(*) " . $this->strSqlFrom . $this->strGroupBy;
            $this->recordsTotal = intval($this->obj_mysqli->sql_getfield($sql));
        }
    }
    private function set_recordsFiltered()
    {
        if (!isset($this->recordsFiltered) && $this->recordsFiltered < 1) {
            $strWhereblock = "";
            if (!empty($this->strSqlWhere)) {
                $strWhereblock .= "(" . $this->strSqlWhere . ")";
            }
            if (!empty($this->strSqlSearch)) {
                if (empty($strWhereblock)) {
                    $strWhereblock .= "(" . $this->strSqlSearch . ")";
                } else {
                    $strWhereblock .= " and (" . $this->strSqlSearch . ")";
                }
            }
            if (!empty($this->strSqlSearchColumn)) {
                if (empty($strWhereblock)) {
                    $strWhereblock .= "(" . $this->strSqlSearchColumn . ")";
                } else {
                    $strWhereblock .= " and (" . $this->strSqlSearchColumn . ")";
                }
            }
            if (!empty($strWhereblock)) {
                $strWhereblock = " WHERE " . $strWhereblock;
            }
            $sql = "select count(*) " . $this->strSqlFrom . $strWhereblock . $this->strGroupBy;
            $this->recordsFiltered = intval($this->obj_mysqli->sql_getfield($sql));
        }
    }
    public function set_Select(
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
            echo "<b>function set_Select</b><br>";
            echo $this->strsqlSelect;
            var_dump($ary_Select);
        }
    }
    public function set_From(
        $strSqlFrom = ""
    ) {
        $this->strSqlFrom = " from " . $strSqlFrom;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_From</b><br>";
            echo $this->strSqlFrom = " from " . $strSqlFrom;
        }
    }
    public function set_GroupBy(
        $aryGroupBy = array()
    ) {
        $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_GroupBy</b><br>";
            echo $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        }
    }
    public function set_Where(
        $strSqlWhere = ""
    ) {
        $this->strSqlWhere = $strSqlWhere;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_Where</b><br>";
            echo $this->strSqlWhere = $strSqlWhere;
        }
    }
    public function set_Search(
        $strSqlSearch
    ) {
        $this->strSqlSearch = $strSqlSearch;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_Search</b><br>";
            echo $this->strSqlSearch = $strSqlSearch;
        }
    }
    public function set_SearchColumn(
        $strSqlSearchColumn
    ) {
        $this->strSqlSearchColumn = $strSqlSearchColumn;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_SearchColumn</b><br>";
            echo $this->strSqlSearchColumn = $strSqlSearchColumn;
        }
    }
    public function set_Columns(
        $arycolumns = array()
    ) {
        $this->arycolumns = $arycolumns;
        foreach ($arycolumns as $column) {
            $this->arycolumns_id[$column["dt"]] = $column["db"];
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function set_Columns</b><br>";
            var_dump($this->arycolumns_id);
        }
    }
    public function set_data_sql()
    {
        $strWhereblock = "";
        if (!empty($this->strSqlWhere)) {
            $strWhereblock .= "(" . $this->strSqlWhere . ")";
        }
        if (!empty($this->strSqlSearch)) {
            if (empty($strWhereblock)) {
                $strWhereblock .= "(" . $this->strSqlSearch . ")";
            } else {
                $strWhereblock .= " and (" . $this->strSqlSearch . ")";
            }
        }
        if (!empty($this->strSqlSearchColumn)) {
            if (empty($strWhereblock)) {
                $strWhereblock .= "(" . $this->strSqlSearchColumn . ")";
            } else {
                $strWhereblock .= " and (" . $this->strSqlSearchColumn . ")";
            }
        }
        if (!empty($strWhereblock)) {
            $strWhereblock = " where " . $strWhereblock;
        }
        $sql = $this->strsqlSelect . $this->strSqlFrom . $strWhereblock . $this->strGroupBy . $this->strsqlOrder . $this->length_and_paging();
        $result = $this->obj_mysqli->sql2array($sql);
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
            echo $this->data = ($data);
        }
    }
    public function fetch()
    {
        $result["draw"] = $this->draw;
        $this->set_recordsTotal();
        $result["recordsTotal"] = $this->recordsTotal;
        $this->set_recordsFiltered();
        $result["recordsFiltered"] = $this->recordsFiltered;
        $result["data"] = $this->data;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>function fetch</b><br>";
        }
        echo json_encode($result);
    }
}
