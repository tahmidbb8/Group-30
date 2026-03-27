<?php
include('../db.php');

// Run when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Escape inputs
    $nameEscaped  = mysqli_real_escape_string($conn, $name);
    $emailEscaped = mysqli_real_escape_string($conn, $email);

    // ---------------- CHECK IF EMAIL EXISTS ----------------
    $checkQuery = "
        SELECT COUNT(*) AS count 
        FROM users 
        WHERE LOWER(email) = LOWER('$emailEscaped')
    ";

    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Database error: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($checkResult);

    if ($row['count'] > 0) {
        $error = "That email is already registered. Please log in instead.";
    } else {

        // ---------------- INSERT USER ----------------
        $insertQuery = "
            INSERT INTO users (name, email, password)
            VALUES ('$nameEscaped', '$emailEscaped', '$password')
        ";

        if (mysqli_query($conn, $insertQuery)) {
            $success = true;
        } else {
            // Handle duplicate key or other errors
            if (mysqli_errno($conn) == 1062) {
                $error = "This email is already in use.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up · Student Course Hub</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

  <style>
    /* 
       RESET & BASE
        */
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: "Poppins", sans-serif;
      min-height: 100vh;
      background: #f0f2f5;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 20px;
    }

    a { text-decoration: none; }

    /* 
       OUTER CARD — two columns side by side using CSS Grid
        */
    .signup-card {
      display: grid;
      /* Left panel is slightly wider than right form panel */
      grid-template-columns: 1.1fr 1fr;
      width: 100%;
      max-width: 900px;
      min-height: 580px;
      border-radius: 20px;
      overflow: hidden;                        /* keeps rounded corners */
      box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    /* 
       LEFT PANEL — dark blue branding side
        */
    .left-panel {
      background:
        linear-gradient(160deg, rgba(0,33,71,0.92) 0%, rgba(10,50,100,0.96) 100%),
        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80')
        center/cover no-repeat;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;      /* stack items vertically */
      justify-content: space-between;
      color: #fff;
    }

    /* Brand name at the top */
    .left-brand {
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .left-brand span {
      background: #1a73e8;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    /* Big heading in the middle */
    .left-content h1 {
      font-size: 2rem;
      font-weight: 700;
      line-height: 1.25;
      margin-bottom: 16px;
    }

    .left-content h1 em {
      font-style: normal;
      color: #64b5f6;   /* light blue highlight */
    }

    .left-content p {
      font-size: 14px;
      color: rgba(255,255,255,0.7);
      line-height: 1.7;
      margin-bottom: 32px;
    }

    /* Three feature bullet points */
    .features {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 14px;
      color: rgba(255,255,255,0.85);
    }

    /* Circle icon for each feature */
    .feature-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: rgba(255,255,255,0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }

    /* Small text at the bottom of left panel */
    .left-footer {
      font-size: 12px;
      color: rgba(255,255,255,0.4);
    }

    /* 
       RIGHT PANEL — white form side
        */
    .right-panel {
      background: #fff;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Small back link at top */
    .back-link {
      font-size: 13px;
      color: #1a73e8;
      font-weight: 500;
      margin-bottom: 28px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .back-link:hover { text-decoration: underline; }

    .right-panel h2 {
      font-size: 1.5rem;
      font-weight: 700;
      color: #002147;
      margin-bottom: 6px;
    }

    .subtitle {
      font-size: 13px;
      color: #888;
      margin-bottom: 28px;
    }

    /* 
       FORM ELEMENTS
       */
    .signup-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    /* Each label + input is wrapped in a .field div */
    .field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .field label {
      font-size: 12px;
      font-weight: 600;
      color: #444;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .field input {
      padding: 12px 14px;
      border: 1.5px solid #e0e4ea;
      border-radius: 8px;
      font-size: 14px;
      font-family: "Poppins", sans-serif;
      color: #333;
      background: #f7f9fc;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }

    /* Blue border when user clicks into the field */
    .field input:focus {
      border-color: #1a73e8;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
    }

    /* Two fields side by side (Name and Email on same row) */
    .field-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    /* Submit button */
    .btn-submit {
      background: #1a73e8;
      color: #fff;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      font-family: "Poppins", sans-serif;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
      margin-top: 4px;
    }

    .btn-submit:hover { background: #1558b0; transform: translateY(-1px); }
    .btn-submit:active { transform: translateY(0); }

    /* 
       ERROR & SUCCESS MESSAGES
       */
    .alert-error {
      background: #fdecea;
      border: 1px solid #f5c2c2;
      color: #c0392b;
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 16px;
    }

    .alert-success {
      background: #e8f5e9;
      border: 1px solid #a5d6a7;
      color: #2e7d32;
      padding: 20px;
      border-radius: 8px;
      font-size: 14px;
      text-align: center;
      line-height: 1.8;
    }

    .alert-success a {
      color: #1a73e8;
      font-weight: 700;
    }

    /* Bottom "already have account" link */
    .signin-link {
      text-align: center;
      font-size: 13px;
      color: #888;
      margin-top: 18px;
    }

    .signin-link a {
      color: #1a73e8;
      font-weight: 600;
    }

    .signin-link a:hover { text-decoration: underline; }

    /* 
       DIVIDER LINE with "or" text
        */
    .divider {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 4px 0;
      color: #bbb;
      font-size: 12px;
    }

    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: #e0e4ea;
    }

    /* 
       RESPONSIVE — stack columns on small screens
        */
    @media (max-width: 700px) {
      .signup-card {
        grid-template-columns: 1fr;  /* single column */
      }

      /* Hide the left panel on very small screens */
      .left-panel {
        display: none;
      }

      .right-panel {
        padding: 36px 24px;
      }

      .field-row {
        grid-template-columns: 1fr;  /* stack name and email */
      }
    }
  </style>
</head>
<body>

  <!-- 
       MAIN CARD — two columns: left branding + right form
        -->
  <div class="signup-card">

    <!-- 
         LEFT PANEL — branding and feature highlights
         -->
    <div class="left-panel">

      <!-- Brand logo -->
      <div class="left-brand">
        <span>✦</span>
        Student Course Hub
      </div>

      <!-- Main message -->
      <div class="left-content">
        <h1>Start Your <em>Academic</em> Journey Today</h1>
        <p>
          Create a free account to explore programmes, register your interest,
          and track your applications all in one place.
        </p>

        <!-- Three feature highlights -->
        <div class="features">
          <div class="feature-item">
            <div class="feature-icon">🎓</div>
            <span>Browse all undergraduate &amp; postgraduate programmes</span>
          </div>
          <div class="feature-item">
            <div class="feature-icon">📋</div>
            <span>Register your interest in any programme</span>
          </div>
          <div class="feature-item">
            <div class="feature-icon">🔔</div>
            <span>Get updates from programme leaders</span>
          </div>
        </div>
      </div>

      <!-- Bottom small print -->
      <div class="left-footer">
        &copy; <?= date('Y') ?> Student Course Hub. All rights reserved.
      </div>

    </div>


    <!-- 
         RIGHT PANEL — the actual signup form
          -->
    <div class="right-panel">

      <!-- Back to home link -->
      <a href="../public/index.php" class="back-link">← Back to programmes</a>

      <h2>Create Account</h2>
      <p class="subtitle">Fill in your details below to get started</p>

      <!-- Error message (shown if something went wrong) -->
      <?php if (!empty($error)): ?>
        <div class="alert-error">⚠ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <!-- Success message after account is created -->
        <div class="alert-success">
          <strong>🎉 Account created successfully!</strong><br>
          You can now sign in with your email and password.<br><br>
          <a href="login.php">Sign in now →</a>
        </div>

      <?php else: ?>

        <!-- SIGNUP FORM -->
        <form method="POST" class="signup-form">

          <!-- Name and Email side by side on one row -->
          <div class="field-row">
            <div class="field">
              <label for="name">Full Name</label>
              <input
                type="text"
                id="name"
                name="name"
                placeholder="e.g. Jane Smith"
                required
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
              >
            </div>
            <div class="field">
              <label for="email">Email Address</label>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="you@example.com"
                required
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              >
            </div>
          </div>

          <!-- Password field (full width) -->
          <div class="field">
            <label for="password">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="At least 6 characters"
              required
              minlength="6"
            >
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn-submit">
            Create My Account →
          </button>

        </form>

        <!-- Link to login page -->
        <p class="signin-link">
          Already have an account? <a href="login.php">Sign in here</a>
        </p>

      <?php endif; ?>

    </div><!-- end right panel -->

  </div><!-- end signup-card -->

</body>
</html>