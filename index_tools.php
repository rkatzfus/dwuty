<!DOCTYPE html>
<html>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
Dotenv\Dotenv::createImmutable(__DIR__)->load();

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
                "host" => "HOST",
                "database" => "DATABASE",
                "user" => "USER",
                "pass" => "PASS"
            )
        )
    );
    $obj_tools->post_encode($post_encode);
    $obj_tools->post_encode($post_encode, array("pass" => $_ENV['API_KEY']));
    ?>
</body>

</html>