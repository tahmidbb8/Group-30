<?php
include('../db.php');

// Fetch staff using MySQLi
$query = "SELECT * FROM staff ORDER BY Name ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching staff data: " . mysqli_error($conn));
}

$staffList = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Ensure missing fields don’t break HTML
foreach ($staffList as &$s) {
    $s['Photo'] = $s['Photo'] ?? '';
    $s['Title'] = $s['Title'] ?? '';
    $s['Email'] = $s['Email'] ?? '';
    $s['Bio']   = $s['Bio'] ?? '';
}
unset($s);

// Unsplash fallback portraits
$unsplashPortraits = [
    'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1607746882042-944635dfe10e?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?auto=format&fit=crop&w=400&q=80',
    'https://images.unsplash.com/photo-1554151228-14d9def656e4?auto=format&fit=crop&w=400&q=80',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Staff — Student Course Hub</title>
  <link rel="stylesheet" href="../css/style4.css">
</head>
<body>

<!-- ===== Skip link ===== -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- ===== Navbar ===== -->
<nav class="navbar" aria-label="Main navigation">
  <div class="nav-left">
    <a href="index.php" class="brand">Student Course Hub</a>
  </div>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="staff.php" aria-current="page">Staff</a>
  </div>
  <div class="nav-right">
    <a href="../user/login.php" class="btn-login">Student Login</a>
    <a href="../admin/login.php" class="btn-login btn-login-admin">Admin</a>
  </div>
</nav>

<!-- ===== Staff hero ===== -->
<section class="staff-hero" aria-label="Staff page header">
  <div class="staff-hero-content">
    <span class="badge">Our Academic Team</span>
    <h1>Meet Our Staff</h1>
    <p><?= count($staffList) ?> dedicated academics across all programmes.</p>
  </div>
</section>

<!-- ===== Main content ===== -->
<main id="main-content">
  <div class="container">

    <?php if (!empty($staffList)): ?>
      <ul class="staff-grid" role="list" aria-label="Staff members">
        <?php foreach ($staffList as $i => $s): ?>
          <?php
            // Use actual photo if it exists in images/, else cycle through Unsplash
            $hasPhoto = !empty($s['Photo']);
            $photoSrc = $hasPhoto
              ? 'images/' . htmlspecialchars($s['Photo'])
              : $unsplashPortraits[$i % count($unsplashPortraits)];
            $displayName = htmlspecialchars($s['Name']);
          ?>
          <li class="staff-card" role="listitem">
            <!-- Photo area -->
            <div class="staff-photo">
              <img src="<?= $photoSrc ?>"
                   alt="Portrait of <?= $displayName ?>"
                   loading="lazy"
                   onerror="this.src='<?= $unsplashPortraits[$i % count($unsplashPortraits)] ?>'">
              <div class="staff-photo-overlay" aria-hidden="true"></div>
              <div class="staff-photo-name">
                <h2><?= $displayName ?></h2>
                <?php if (!empty($s['Title'])): ?>
                  <span class="title-tag"><?= htmlspecialchars($s['Title']) ?></span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Body -->
            <div class="staff-card-body">
              <?php if (!empty($s['Email'])): ?>
                <a href="mailto:<?= htmlspecialchars($s['Email']) ?>"
                   class="staff-email"
                   aria-label="Email <?= $displayName ?>">
                  <span aria-hidden="true">✉</span>
                  <?= htmlspecialchars($s['Email']) ?>
                </a>
              <?php endif; ?>

              <p class="staff-bio">
                <?php if (!empty($s['Bio'])): ?>
                  <?= nl2br(htmlspecialchars(mb_substr($s['Bio'], 0, 220))) ?>…
                <?php else: ?>
                  <em>No biography available.</em>
                <?php endif; ?>
              </p>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>

    <?php else: ?>
      <p>No staff members are currently listed.</p>
    <?php endif; ?>

  </div>
</main>

<!-- ===== Footer ===== -->
<footer role="contentinfo">
  <p>&copy; <?= date('Y') ?> Student Course Hub | All Rights Reserved</p>
</footer>

</body>
</html>