<?php
echo "available PDO driver:";
var_dump((PDO::getAvailableDrivers()));
echo "<hr>";

$serverName = getenv("POSTGRESQL_HOST");
$database = getenv("POSTGRESQL_DATABASE");
$uid = getenv("POSTGRESQL_USER");
$pwd = getenv("POSTGRESQL_PASSWORD");

try {
    $conn = new PDO(
        "pgsql:host=$serverName;dbname=$database;",
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

echo "<p>Connected to SQL Server</p>\n";

echo "<p>PDO::ATTR_PERSISTENT value:</p>\n";

echo "<pre>";
echo var_export($conn->getAttribute(PDO::ATTR_PERSISTENT), true);
echo "</pre>";

echo "<p>PDO::ATTR_DRIVER_NAME value:</p>\n";

echo "<pre>";
echo var_export($conn->getAttribute(PDO::ATTR_DRIVER_NAME), true);
echo "</pre>";

echo "<p>PDO::ATTR_CLIENT_VERSION value:</p>\n";

echo "<pre>";
echo var_export($conn->getAttribute(PDO::ATTR_CLIENT_VERSION), true);
echo "</pre>";

$query = 'select * from root_table';
$stmt = $conn->query($query);

echo "<pre>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
echo "</pre>";

// Free statement and connection resources.
$stmt = null;
$conn = null;

phpinfo();
