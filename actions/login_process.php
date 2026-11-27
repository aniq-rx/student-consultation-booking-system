<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// LOGIN PROCESS
if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST['form_type']) &&
    $_POST['form_type'] == "login") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];


    // CHECK STUDENTS TABLE
    $sql_student = "SELECT * FROM students WHERE username = ?";
    $stmt_student = mysqli_prepare($conn, $sql_student);

    mysqli_stmt_bind_param($stmt_student, "s", $username);
    mysqli_stmt_execute($stmt_student);

    $result_student = mysqli_stmt_get_result($stmt_student);
    $student = mysqli_fetch_assoc($result_student);

    mysqli_stmt_close($stmt_student);


    // CHECK LECTURERS TABLE
    $sql_lecturer = "SELECT * FROM lecturers WHERE username = ?";
    $stmt_lecturer = mysqli_prepare($conn, $sql_lecturer);

    mysqli_stmt_bind_param($stmt_lecturer, "s", $username);
    mysqli_stmt_execute($stmt_lecturer);

    $result_lecturer = mysqli_stmt_get_result($stmt_lecturer);
    $lecturer = mysqli_fetch_assoc($result_lecturer);

    mysqli_stmt_close($stmt_lecturer);


    // CHECK USER ACCOUNT AND PASSWORD

    if ($student && $lecturer) {

        $message = "Duplicate username. Please contact administrator.";

    }

    // STUDENT LOGIN
    elseif ($student && $password === $student['password']) {

        session_regenerate_id(true);

        $_SESSION['studentID'] = $student['student_id'];
        $_SESSION['student_name'] = $student['full_name'];
        $_SESSION['student_initials'] = $student['initials'];
        $_SESSION['matric_no'] = $student['matric_no'];
        $_SESSION['role'] = 'student';

        header("Location: ../student/dashboard.php");
        exit();

    }

    // LECTURER LOGIN
    elseif ($lecturer && $password === $lecturer['password']) {

        session_regenerate_id(true);

        $_SESSION['lecturerID'] = $lecturer['lecturer_id'];
        $_SESSION['lecturer_name'] = $lecturer['full_name'];
        $_SESSION['staff_id'] = $lecturer['staff_id'];
        $_SESSION['role'] = 'lecturer';
        header("Location: ../lecturer/dashboard.php");
        exit();

    }

    else {

        $message = "Invalid username or password.";

    }
}
$_SESSION['login_error'] = $message ?? "Invalid username or password.";
header("Location: ../login.php");
exit;