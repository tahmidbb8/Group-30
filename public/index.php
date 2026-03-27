<?php
include('../db.php');

// Read filters
$selectedLevel = isset($_GET['level']) ? (int)$_GET['level'] : 0;
$search        = isset($_GET['search']) ? trim($_GET['search']) : '';

// Load levels
$levelsResult = mysqli_query($conn, "SELECT * FROM levels ORDER BY LevelName");
$levels = mysqli_fetch_all($levelsResult, MYSQLI_ASSOC);

// Build query
$where = "WHERE p.is_published = 1";

// Level filter
if ($selectedLevel > 0) {
    $where .= " AND p.LevelID = " . intval($selectedLevel);
}

// Search filter
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($conn, $search);
    $where .= " AND (p.ProgrammeName LIKE '%$searchEscaped%' OR p.Description LIKE '%$searchEscaped%')";
}

// Final query
$query = "
    SELECT p.*, l.LevelName
    FROM programmes p
    JOIN levels l ON p.LevelID = l.LevelID
    $where
    ORDER BY l.LevelID ASC, p.ProgrammeName ASC
";

$result = mysqli_query($conn, $query);
$programmes = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Stats
$totalProg  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM programmes WHERE is_published = 1"))[0];
$totalStaff = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM staff"))[0];

// Separate programmes
$bachelors = [];
$masters   = [];
$other     = [];

foreach ($programmes as $p) {
    $lvl = strtolower($p['LevelName']);

    if (strpos($lvl, 'bachelor') !== false) {
        $bachelors[] = $p;
    } elseif (strpos($lvl, 'master') !== false) {
        $masters[] = $p;
    } else {
        $other[] = $p;
    }
}

// Image function (unchanged)
function getProgrammeImage($name) {
    $n = strtolower($name);

    if (strpos($n, 'artificial intelligence') !== false)
        return 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=800&q=80';

    if (strpos($n, 'computer science') !== false)
        return 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80';

    if (strpos($n, 'cyber') !== false)
        return 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80';

    if (strpos($n, 'software') !== false)
        return 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80';

    if (strpos($n, 'data') !== false)
        return 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80';

    return 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Course Hub — Programmes</title>
  <link rel="stylesheet" href="../css/style4.css">
</head>
<body>

<!-- Top navigation -->
<nav class="navbar">
  <div class="nav-left">
    <!-- Site logo -->
    <a href="index.php" class="brand">Student Course Hub</a>
  </div>

  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="staff.php">Staff</a>
  </div>

  <div class="nav-right">
    <a href="../user/login.php" class="btn-login">Student Login</a>
    <a href="../staff/login.php" class="btn-login btn-login-staff">Staff Login</a>
    <a href="../admin/login.php" class="btn-login btn-login-admin">Admin Login</a>
  </div>
</nav>


<!-- Hero section -->
<section class="hero-banner">
  <div class="hero-content">
    <h1>Find Your <em>Perfect</em><br>Programme</h1>
    <p>Explore undergraduate and postgraduate degrees designed to launch your career.</p>

    <!-- Search form -->
    <form method="GET" action="" class="hero-search">
      <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search) ?>"
        placeholder="Search e.g. Cyber Security, AI…"
      >

      <!-- Keep level selected -->
      <?php if ($selectedLevel > 0): ?>
        <input type="hidden" name="level" value="<?= $selectedLevel ?>">
      <?php endif; ?>

      <button type="submit">Search</button>
    </form>

    <!-- Quick numbers -->
    <div class="hero-stats">
      <div class="hero-stat">
        <span class="num"><?= $totalProg ?></span>
        <span class="lbl">Programmes</span>
      </div>
      <div class="hero-stat">
        <span class="num"><?= $totalStaff ?></span>
        <span class="lbl">Staff Members</span>
      </div>
      <div class="hero-stat">
        <span class="num">2</span>
        <span class="lbl">Study Levels</span>
      </div>
    </div>
  </div>
</section>


<!-- Filter bar -->
<div class="filter-bar">
  <form method="GET" action="" class="filter-form">

    <!-- Keep search text -->
    <?php if ($search !== ''): ?>
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <?php endif; ?>

    <label for="level-select">Filter by level:</label>

    <!-- Submit automatically when changed -->
    <select name="level" id="level-select" onchange="this.form.submit()">
      <option value="0" <?= $selectedLevel === 0 ? 'selected' : '' ?>>All Levels</option>

      <?php foreach ($levels as $lv): ?>
        <option
          value="<?= $lv['LevelID'] ?>"
          <?= ($selectedLevel == $lv['LevelID']) ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($lv['LevelName']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <!-- Clear active filters -->
    <?php if ($search !== '' || $selectedLevel > 0): ?>
      <a href="index.php" class="clear-link">✕ Clear</a>
    <?php endif; ?>

    <!-- Result count -->
    <span class="result-count">
      <?= count($programmes) ?> programme<?= count($programmes) !== 1 ? 's' : '' ?> found
    </span>
  </form>
</div>





<!-- Main programme section -->
<main id="main-content">
  <div class="container">

    <?php if (empty($programmes)): ?>
      <!-- Show message if nothing matched -->
      <div class="no-results">
        <p>No programmes found<?= $search ? ' for "<strong>' . htmlspecialchars($search) . '</strong>"' : '' ?>.</p>
        <a href="index.php" class="btn btn-primary">View all programmes</a>
      </div>

    <?php else: ?>

      <!-- Bachelor's programmes -->
      <?php if (!empty($bachelors)): ?>
        <section class="level-section">

          <!-- Section heading -->
          <div class="level-banner bachelor">
            <span class="level-tag">Undergraduate</span>
            <h2>Bachelor's Degrees</h2>
            <p>3-year full-time programmes leading to a BSc award.</p>
            <span class="level-num"><?= count($bachelors) ?></span>
          </div>

          <!-- Programme cards -->
          <div class="programmes-grid">
            <?php foreach ($bachelors as $p): ?>
              <!-- Single programme card -->
              <article class="programme-card">

                <!-- Programme image -->
                <div class="card-img-wrap">
                  <img
                    src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                    alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                    loading="lazy"
                  >
                  <span class="prog-level-badge">BSc</span>
                </div>

                <!-- Programme details -->
                <div class="card-body">
                  <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>

                  <!-- Short description -->
                  <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 120)) ?>…</p>

                  <a href="programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">
                    View Details →
                  </a>
                </div>

              </article>
            <?php endforeach; ?>
          </div>

        </section>
      <?php endif; ?>


      <!-- Master's programmes -->
      <?php if (!empty($masters)): ?>
        <section class="level-section">

          <!-- Section heading -->
          <div class="level-banner masters">
            <span class="level-tag">Postgraduate</span>
            <h2>Master's Degrees</h2>
            <p>1-year advanced programmes leading to an MSc award.</p>
            <span class="level-num"><?= count($masters) ?></span>
          </div>

          <!-- Programme cards -->
          <div class="programmes-grid">
            <?php foreach ($masters as $p): ?>
              <article class="programme-card">

                <!-- Programme image -->
                <div class="card-img-wrap">
                  <img
                    src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                    alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                    loading="lazy"
                  >
                  <span class="prog-level-badge badge-masters">MSc</span>
                </div>

                <!-- Programme details -->
                <div class="card-body">
                  <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                  <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 120)) ?>…</p>
                  <a href="programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">
                    View Details →
                  </a>
                </div>

              </article>
            <?php endforeach; ?>
          </div>

        </section>
      <?php endif; ?>


      <!-- Other programme types -->
      <?php if (!empty($other)): ?>
        <section class="level-section">

          <h2 class="section-title-plain">Other Programmes</h2>

          <div class="programmes-grid">
            <?php foreach ($other as $p): ?>
              <article class="programme-card">

                <div class="card-img-wrap">
                  <img
                    src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                    alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                    loading="lazy"
                  >
                  <!-- Show actual level name -->
                  <span class="prog-level-badge badge-other">
                    <?= htmlspecialchars($p['LevelName']) ?>
                  </span>
                </div>

<!-- Programme details -->
<div class="card-body">
                  <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                  <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 120)) ?>…</p>
                  <a href="programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">
                    View Details →
                  </a>
                </div>

              </article>
            <?php endforeach; ?>
          </div>

        </section>
      <?php endif; ?>

    <?php endif; // End results check ?>

  </div><!-- Main container -->
</main>


<!-- Footer -->
<footer>
  <!-- Current year updates automatically -->
  <p>&copy; <?= date('Y') ?> Student Course Hub | All Rights Reserved</p>
</footer>

</body>
</html>

