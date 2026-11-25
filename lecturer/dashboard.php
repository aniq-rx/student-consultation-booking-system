<?php
session_start();

if (!isset($_SESSION['lecturerID'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
</head>
<body>
    <h1>Welcome to lecturer dashboard</h1>
</body>
</html>
