<?php
// Start session to store logged-in student info
session_start();

// Connect to the database
include('../config/db.php');

// Handle form submission when student clicks Sign In
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Find the user in the database by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Save user info in session and redirect to dashboard
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email']
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        // Different error messages depending on what went wrong
        $error = $user ? "Incorrect password. Please try again." : "No account found with that email.";
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