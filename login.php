<?php
session_start();

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — Student Consultation Booking System</title>
  <link rel="stylesheet" href="css/base.css" />
  <link rel="stylesheet" href="css/login.css" />
</head>
<body class="login-body">
  <main class="login-card">
    <h1>Consultation Booking Portal</h1>
    <p class="login-subtitle">Sign in as a Student, Lecturer, or Administrator to access your dashboard.</p>

    <?php if ($error !== ''): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="actions/login_process.php" method="POST">
      <input type="hidden" name="form_type" value="login" />
      <div class="form-group">
        <label for="user_id_input">Username</label>
        <input type="text" id="user_id_input" name="username" value="" placeholder="e.g. abu_samad" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" value="" placeholder="Enter password" required />
      </div>
      <button type="submit" class="btn btn-primary btn-full">Sign In</button>
    </form>
  </main>

  <script src="js/ui.js"></script>
</body>
</html>