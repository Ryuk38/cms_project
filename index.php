<?php
$servername = getenv('MYSQL_HOST') ?: "172.21.0.2"; // Fallback IP
$username = getenv('MYSQL_USER') ?: "root";
$password = getenv('MYSQL_PASSWORD') ?: "rootpassword";
$dbname = getenv('MYSQL_DATABASE') ?: "cms_db";
$port = getenv('MYSQL_PORT') ?: 3307; // Default to 3307 to match host port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Welcome to CMS!";
$conn->close();
?>