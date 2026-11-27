<?php
$host = 'localhost';
$dbname = 'consultation_booking';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
	die('Database connection failed: ' . $conn->connect_error);
}

// echo 'Database connected successfully.';
