<?php
// Start session to access logged-in user data
session_start();

// Connect to the database
include('../config/db.php');

// Get programme ID from the URL — stop if missing
if (!isset($_GET['id'])) {
  die("Programme not specified.");
}

// Convert ID to safe integer to prevent SQL injection
$programme_id = intval($_GET['id']);

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php?redirect=register_interest.php?id=$programme_id");
  exit;
}

// Store the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch the programme from the database — only if published
try {
  $stmt = $pdo->prepare("SELECT * FROM Programmes WHERE ProgrammeID = ? AND IsPublished = 1");
  $stmt->execute([$programme_id]);
  $programme = $stmt->fetch(PDO::FETCH_ASSOC);

  // Stop if no programme found
  if (!$programme) {
    die("Programme not found or unpublished.");
  }
} catch (PDOException $e) {
  die("Database error: " . $e->getMessage());
}

// Empty message variables — filled after form is submitted
$success = $error = "";

// Handle form submission — runs when user clicks the register button
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Save interest to the database
    // ON DUPLICATE KEY UPDATE: if already registered, just refresh the timestamp
    $stmt = $pdo->prepare("INSERT INTO user_interests (UserID, ProgrammeID)
                           VALUES (?, ?)
                           ON DUPLICATE KEY UPDATE CreatedAt = CURRENT_TIMESTAMP");
    $stmt->execute([$user_id, $programme_id]);
    $success = "You have successfully registered your interest!";
  } catch (PDOException $e) {
    $error = "Error saving your interest: " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Page title shows the programme name from the database -->
  <title>Register Interest — <?= htmlspecialchars($programme['ProgrammeName']); ?></title>

  <!-- Only your existing CSS file — all classes defined in style5.css -->
  <link rel="stylesheet" href="../css/style5.css">
</head>
<body>

<!-- NAVBAR — same structure used across all admin/public pages -->
<div class="navbar">
  <div class="nav-left">
    <a href="../public/index.php" class="brand">🎓 Student Course Hub</a>
    <a href="../public/index.php"> Home</a>
    <a href="../public/staff.php"> Staff</a>
    <a href="../user/login.php"> Login</a>
  </div>
  <div class="nav-right">
    <!-- Returns user back to the programme detail page -->
    <a href="programme.php?id=<?= $programme_id; ?>">← Back to Programme</a>
  </div>
</div>

<!-- PAGE TITLE — centred heading above the stat boxes (.page-title in style5.css) -->
<div class="page-title">
  <h2>Register Interest</h2>
  <p>You are registering interest in <strong><?= htmlspecialchars($programme['ProgrammeName']); ?></strong></p>
</div>

<!-- SUCCESS / ERROR MESSAGES — .success and .error classes from style5.css -->
<?php if ($success): ?>
  <div class="page-title">
    <p class="success"> <?= htmlspecialchars($success); ?></p>
  </div>
<?php elseif ($error): ?>
  <div class="page-title">
    <p class="error"> <?= htmlspecialchars($error); ?></p>
  </div>
<?php endif; ?>

<!-- STAT ROW — 3 boxes in a grid (.stat-row and .stat-box from style5.css) -->
<div class="stat-row">

  <div class="stat-box">
    <span class="stat-icon">📘</span>
    <div class="stat-label">Programme</div>
    <!-- Truncate name to 22 chars if too long -->
    <div class="stat-value">
      <?= htmlspecialchars(substr($programme['ProgrammeName'], 0, 22)); ?>
      <?= strlen($programme['ProgrammeName']) > 22 ? '…' : ''; ?>
    </div>
  </div>

  <div class="stat-box">
    <span class="stat-icon"></span>
    <div class="stat-label">Status</div>
    <!-- Green colour for open status -->
    <div class="stat-value" style="color:#1a7a4a;">Open</div>
  </div>

  <div class="stat-box">
    <span class="stat-icon"></span>
    <div class="stat-label">Response Time</div>
    <div class="stat-value">48 Hours</div>
  </div>

</div><!-- end .stat-row -->

<!-- MAIN CARD — white card with shadow (.container from style5.css) -->
<div class="container">

  <!-- Programme name and short description from the database -->
  <h3>About This Programme</h3>
  <p>
    <?php
      // Show first 200 characters of the description then "..."
      $desc = $programme['Description'] ?? 'No description available.';
      echo htmlspecialchars(substr($desc, 0, 200)) . '…';
    ?>
  </p>

  <!-- BENEFITS GRID — 2 columns, 4 boxes (.benefits-grid and .benefit-box from style5.css) -->
  <h3>What You Get By Registering</h3>
  <div class="benefits-grid">

    <div class="benefit-box">
      <span class="b-icon"></span>
      <div>
        <h4>Stay Informed</h4>
        <p>Receive updates on open days and key deadlines.</p>
      </div>
    </div>

    <div class="benefit-box">
      <span class="b-icon"></span>
      <div>
        <h4>No Commitment</h4>
        <p>This does not lock you into applying or enrolling.</p>
      </div>
    </div>

    <div class="benefit-box">
      <span class="b-icon"></span>
      <div>
        <h4>Instant Confirmation</h4>
        <p>Your interest is saved to the database right away.</p>
      </div>
    </div>

    <div class="benefit-box">
      <span class="b-icon"></span>
      <div>
        <h4>Priority Access</h4>
        <p>Get early access to application portals and events.</p>
      </div>
    </div>

  </div><!-- end .benefits-grid -->

  <!-- FORM — .admin-form from style5.css handles spacing and layout -->
  <form method="POST" class="admin-form">
    <p>Click below to confirm your interest in <strong><?= htmlspecialchars($programme['ProgrammeName']); ?></strong>.</p>

    <!-- BUTTON ROW — .btn-row places buttons side by side (.btn-row from style5.css) -->
    <div class="btn-row">
      <!-- Submit button — .btn from style5.css (navy, rounded) -->
      <input type="submit" value="✓ Register My Interest" class="btn">

      <!-- Back button — .btn-secondary from style5.css (grey) -->
      <a href="programme.php?id=<?= $programme_id; ?>" class="btn-secondary">← Back</a>
    </div>

  </form>

</div><!-- end .container -->

<!-- FOOTER — .site-footer from style5.css -->
<footer class="site-footer">
  &copy; <?= date('Y'); ?> Student Course Hub
</footer>

</body>
</html>