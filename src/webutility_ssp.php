<?php

namespace App;

class webutility_ssp
{
    private $debug;
    private $draw;
    private $intlength;
    private $intstart;
    private $strsqlOrder;
    private $strGroupBy;
    private $data;

    private $obj_database_tools;
    private $obj_tools;

    private $recordsTotal;
    private $recordsFiltered;
    private $strsqlSelectStart;
    private $ary_sqlSelectInline;
    private $strsqlSelect;
    private $strSqlFrom;
    private $strSqlWhere;
    private $strSqlSearch;
    private $strSqlSearchColumn;
    private $arycolumns;
    private $arycolumns_id;

    public function __construct(
        $config = array()
    ) {
        $ary_database = array(
            "debug" => $config["debug"], "database" => $config["database"]
        );
        $this->debug = !isset($config["debug"]["webutility_ssp"]) ? false : $config["debug"]["webutility_ssp"];
        $this->draw = 0;
        $this->intlength = 10;
        $this->intstart = 0;
        $this->strsqlOrder = "";
        $this->strGroupBy = "";
        $this->data = array();
        $this->obj_database_tools = new database_tools($ary_database);
        $this->obj_tools = new tools(array("debug" => $config["debug"]));
    }
    public function set_draw(
        $draw = 0
    ) {
        $this->draw = intval($draw);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_draw</b><br>";
            echo $this->draw;
        }
    }
    public function set_length(
        $length = 10
    ) {
        $this->intlength = intval($length);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_length</b><br>";
            echo $this->intlength;
        }
    }
    public function set_start(
        $intstart = 0
    ) {
        $this->intstart = intval($intstart);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_start</b><br>";
            echo $this->intstart;
        }
    }
    private function length_and_paging($type = "")
    {
        if ($this->intlength == -1) {
            return "";
        } else {
            switch ($type) {
                case 'mysql':
                    return " limit " . $this->intstart . ", " . $this->intlength;
                    break;
                case 'sqlsrv':
                    return " offset " . $this->intstart . " rows fetch next " . $this->intlength . " rows only";
                    break;
                case 'pgsql':
                    return " offset " . $this->intstart . " limit " . $this->intlength;
                    break;

                default:
                    return " limit " . $this->intstart . ", " . $this->intlength;
                    break;
            }
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
            echo "<b>WEBUTILITY_SSP: function set_order</b><br>";
            echo $this->strsqlOrder;
        }
    }
    private function set_recordsTotal()
    {
        if (!isset($this->recordsTotal) && $this->recordsTotal < 1) {
            $sql = $this->count_records(false);
            $this->recordsTotal = intval($this->obj_database_tools->sql_getfield($sql));
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>WEBUTILITY_SSP: function set_recordsTotal</b><br>";
                echo $this->recordsTotal;
                echo "<br>" . $sql;
            }
        }
    }
    private function set_recordsFiltered()
    {
        if (!isset($this->recordsFiltered) && $this->recordsFiltered < 1) {
            $sql = $this->count_records(true);
            $this->recordsFiltered = intval($this->obj_database_tools->sql_getfield($sql));
            if ($this->debug == true) {
                echo "<hr>";
                echo "<b>WEBUTILITY_SSP: function set_recordsFiltered</b><br>";
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
        if (array_search('dt_rowid', array_column($ary_Select, 'dt')) !== false) {
            foreach ($ary_Select as $Select_value) {
                if ($Select_value['dt'] == 'dt_rowid') {
                    $this->strsqlSelectStart .= "concat('row_', " . $Select_value['db'] . ") as dt_rowid";
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
            echo "<b>WEBUTILITY_SSP: an error has occured!</b>";
        }
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_select</b><br>";
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
            echo "<b>WEBUTILITY_SSP: function set_from</b><br>";
            echo $this->strSqlFrom;
        }
    }
    public function set_groupBy(
        $aryGroupBy = array()
    ) {
        $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_groupBy</b><br>";
            echo $this->strGroupBy = " group by  " . implode(",", $aryGroupBy);
        }
    }
    public function set_where(
        $strSqlWhere = ""
    ) {
        $this->strSqlWhere = $strSqlWhere;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_where</b><br>";
            echo $this->strSqlWhere;
        }
    }
    public function set_search(
        $strSqlSearch
    ) {
        $this->strSqlSearch = $strSqlSearch;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_search</b><br>";
            echo $this->strSqlSearch;
        }
    }
    public function set_searchColumn(
        $strSqlSearchColumn
    ) {
        $this->strSqlSearchColumn = $strSqlSearchColumn;
        if ($this->debug == true) {
            echo "<hr>";
            echo "<b>WEBUTILITY_SSP: function set_searchColumn</b><br>";
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
            echo "<b>WEBUTILITY_SSP: function set_columns</b><br>";
            var_dump($this->arycolumns_id);
        }
    }
    public function set_data_sql($type = "mysql")
    {
        $ary_sql[] = $this->strsqlSelect;
        $ary_sql[] = $this->strSqlFrom;
        !empty($this->build_where()) ? $ary_sql[] = "where " . implode(" ", $this->build_where()) : "";
        $ary_sql[] = $this->strGroupBy;
        $ary_sql[] = $this->strsqlOrder;
        $ary_sql[] = $this->length_and_paging($type);
        $sql = implode(" ", $ary_sql);
        $result = ($this->obj_database_tools->chk_stmnt($sql) == true) ? $this->obj_database_tools->sql2array($sql) : "";
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
            echo "<b>WEBUTILITY_SSP: function set_data_sql (final sql)</b><br>";
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
            echo "<b>WEBUTILITY_SSP: function set_data</b><br>";
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
            echo "<b>WEBUTILITY_SSP: function read</b><br>";
        }
        echo $this->obj_tools->post_encode($result);
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
            return "select count(distinct " . $this->arycolumns_id["dt_rowid"] . ")" . $this->strSqlFrom . " " . $where;
        } else {
            return "select count(distinct " . $this->arycolumns_id["dt_rowid"] . ")" . $this->strSqlFrom;
        }
    }
}
