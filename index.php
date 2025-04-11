<?php
$servername = getenv('MYSQL_HOST') ?: "172.17.0.2";
$username = getenv('MYSQL_USER') ?: "root";
$password = getenv('MYSQL_PASSWORD') ?: "rootpassword";
$dbname = getenv('MYSQL_DATABASE') ?: "cms_db";
$port = getenv('MYSQL_PORT') ?: 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Welcome to CMS!";
$conn->close();
?>