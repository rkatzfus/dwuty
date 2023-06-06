<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/.environment.php";

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
                "host" => "env HOST",
                "user" => "env USER",
                "pass" => "env PASS",
                "database" => "env DATABASE"
            )
        )
    );
    $obj_tools->post_encode($post_encode);
    $obj_tools->post_encode($post_encode, array("pass" => getenv('API_KEY')));
    ?>
</body>

</html>