<?php
session_start();
include('../db.php');

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Escape email for safety
    $emailEscaped = mysqli_real_escape_string($conn, $email);

    // Get user from database
    $query = "SELECT * FROM users WHERE email = '$emailEscaped'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Database error: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($result);

    // Check password
    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email']
        ];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = $user 
            ? "Incorrect password. Please try again." 
            : "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Login · Student Course Hub</title>

  <!-- style3.css handles everything: dark background, white login box, inputs, button -->
  <link rel="stylesheet" href="../css/style3.css">
</head>
<body>

  <!-- LOGIN BOX — white card centered on dark background (from style3.css .login-box) -->
  <div class="login-box">

    <!-- Icon and title -->
    <div style="font-size:2rem; margin-bottom:10px;">🎓</div>
    <h2>Welcome Back</h2>
    <p>Sign in to explore programmes &amp; manage your interests</p>

    <!-- Error message — only shows if login failed (.error from style3.css) -->
    <?php if (!empty($error)): ?>
      <div class="error">⚠️ <?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- LOGIN FORM — flex column layout handled by style3.css form styles -->
    <form method="POST">

      <label>Email Address</label>
      <input type="email" name="email" placeholder="you@example.com" required
             value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">

      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>

      <!-- Submit button — .btn from style3.css (navy, full width, hover turns blue) -->
      <input type="submit" value="Sign In →" class="btn">

      <!-- Link to signup page -->
      <p>Don't have an account? <a href="signup.php">Create one</a></p>

    </form>

  </div><!-- end .login-box -->

</body>
</html>