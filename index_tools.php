<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

use App\tools;
?>

<head>
</head>

<body>
    <?php
    $config_tools = array(
        "debug" => array("tools" => true)
    );
    $obj_tools = new tools($config_tools);
    $obj_tools->uniqueid();
    $post_encode = array(
        "debug" => array("tools" => true), "database" => array(
            "type" => "mysql", "credentials" => array(
                "host" => getenv('HOST'),
                "user" => getenv('MYSQL_USER'),
                "pass" => getenv('MYSQL_PASSWORD'),
                "database" => getenv('MYSQL_DATABASE'),
            )
        )
    );
    $obj_tools->post_encode($post_encode);
    ?>
</body>

</html>