<!DOCTYPE html>
<html>
    <?php
        require_once __DIR__ . '/vendor/autoload.php';
        use App\database_tools;
    ?>
    <head>
    </head>
    <body>
        <?php
            $obj_mysqli = new database_tools();
            $sql = "
                select
                    ID
                    , DEL
                    , TEXT
                    , CHECKBOX
                from
                    MYSQL_DATABASE.root_table;
            ";
            echo "<b>sql2array</b>";
            var_dump($obj_mysqli->sql2array($sql));
            echo "<hr>";
            echo "<b>sql2array_pk</b>";
            var_dump($obj_mysqli->sql2array_pk($sql,"TEXT"));
            echo "<hr>";
            $sql = "
                select
                    distinct count(*) 
                from 
                    MYSQL_DATABASE.root_table; 
            ";
            echo "<b>sql_getfield</b>";
            var_dump($obj_mysqli->sql_getfield($sql));
            echo "<hr>";
            $sql = "
                select
                    ID
                    , TEXT
                from 
                    MYSQL_DATABASE.dropdown_lookup_table; 
            ";
            echo "<b>sql2array_pk_value</b>";
            var_dump($obj_mysqli->sql2array_pk_value($sql,"ID","TEXT"));
            echo "<hr>";
        ?>
    </body>
</html>
