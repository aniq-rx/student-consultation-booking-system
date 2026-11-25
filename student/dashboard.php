<?php
session_start();

if (isset($_POST['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['studentID'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Welcome to student dashboard</h1>

    <p>
        <a href="view_consult.php">
            <button type="button">View Consultations</button>
        </a>
    </p>

    <form method="post">
        <button type="submit" name="logout" value="1">Exit</button>
    </form>
</body>
</html>