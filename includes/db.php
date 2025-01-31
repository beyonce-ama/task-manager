<?php
$host = 'localhost';
$username = 'u993755557_taskmanager'; 
$password = 'Taskmanager2025';     
$database = 'u993755557_taskmanager';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
