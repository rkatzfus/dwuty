<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$pdo_drv = PDO::getAvailableDrivers();
echo "<b>available PDO driver:</b>";
var_dump($pdo_drv);
echo "<hr><hr>";
foreach ($pdo_drv as $key_pdo_drv => $value_pdo_drv) {
    $serverName = $_ENV[$value_pdo_drv . "_HOST"] ?? null;
    $database = $_ENV[$value_pdo_drv . "_DATABASE"] ?? null;
    $uid = $_ENV[$value_pdo_drv . "_USER"] ?? null;
    $pwd = $_ENV[$value_pdo_drv . "_PASSWORD"] ?? null;
    if (!empty($serverName) && !empty($database) && !empty($uid) && !empty($pwd)) {
        switch ($value_pdo_drv) {
            case "mysql":
                $server_host = "host";
                $database_dbname = "dbname";
                $TrustServerCertificate = "";
                break;
            case "sqlsrv":
                $server_host = "server";
                $database_dbname = "Database";
                $TrustServerCertificate = "TrustServerCertificate=true";
                break;
            case "pgsql":
                $server_host = "host";
                $database_dbname = "dbname";
                $TrustServerCertificate = "";
                break;
        }
        try {
            $conn = new PDO(
                "$value_pdo_drv:$server_host=$serverName;$database_dbname=$database;$TrustServerCertificate",
                $uid,
                $pwd,
                array(
                    //PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        } catch (PDOException $e) {
            die("Error connecting to SQL Server: " . $e->getMessage());
        }
        echo "<p>Connected to SQL Server <b>(" . $value_pdo_drv . ")</b></p>\n";
        echo "<p>PDO::ATTR_PERSISTENT value:</p>\n";
        echo "<pre>" . var_export($conn->getAttribute(PDO::ATTR_PERSISTENT), true) . "</pre>";
        echo "<p>PDO::ATTR_DRIVER_NAME value:</p>\n";
        echo "<pre>" . var_export($conn->getAttribute(PDO::ATTR_DRIVER_NAME), true) . "</pre>";
        echo "<p>PDO::ATTR_CLIENT_VERSION value:</p>\n";
        echo "<pre>" . var_export($conn->getAttribute(PDO::ATTR_CLIENT_VERSION), true) . "</pre>";
        // echo "<hr>";
        // $query = 'select * from root_table';
        // $stmt = $conn->query($query);
        // echo "<pre>";
        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     var_dump($row);
        // }
        // echo "</pre>";
        // $stmt = null;
        $conn = null;
        echo "<hr><hr>";
    }
}
// phpinfo();
