<?php
$servername = getenv('MYSQL_HOST') ?: "db"; // Use 'db' as service name
$username = getenv('MYSQL_USER') ?: "root";
$password = getenv('MYSQL_PASSWORD') ?: "root";
$dbname = getenv('MYSQL_DATABASE') ?: "cms";
$port = getenv('MYSQL_PORT') ?: 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Welcome to CMS!";
$conn->close();
?>