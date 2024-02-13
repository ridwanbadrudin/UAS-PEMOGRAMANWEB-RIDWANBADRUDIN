<?php
$hostname = "localhost";
$username = "id21865680_siakadlite";
$password = "Siakadlite123#";
$db_name = "id21865680_siakadlite";
$port = 3306;

try {
    $db_connection = new PDO("mysql:host=$hostname;dbname=$db_name", $username, $password);
} catch (PDOException $e) {
    die($e->getMessage());
}
