<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . "/vendor/autoload.php";

use App\database_tools;
?>

<head>
</head>

<body>
    <?php
    $ary_credentials = array(
        "host" => getenv('dwuty_lang_HOST'),
        "user" => getenv('dwuty_lang_USER'),
        "pass" => getenv('dwuty_lang_PASSWORD'),
        "database" => getenv('dwuty_lang_DATABASE'),
    );

    $obj_database_tools = new database_tools(true, $ary_credentials);
    $sql = "select ID, DEL, REF_LANG, REF_SITE, HTML_ID, CONTENT from tbl_content;";
    $obj_database_tools->sql2array($sql);

    ?>
</body>

</html>