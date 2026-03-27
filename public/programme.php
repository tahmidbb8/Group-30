<?php
include('../db.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// ---------------- PROGRAMME QUERY ----------------
$query = "
    SELECT p.*, l.LevelName,
           s.Name AS LeaderName
    FROM programmes p
    JOIN levels l ON p.LevelID = l.LevelID
    LEFT JOIN staff s ON p.ProgrammeLeaderID = s.StaffID
    WHERE p.ProgrammeID = $id AND p.is_published = 1
";

$result = mysqli_query($conn, $query);
$programme = mysqli_fetch_assoc($result);

if (!$programme) {
    header("Location: index.php");
    exit;
}

// ---------------- MODULES QUERY ----------------
$mod_query = "
    SELECT m.ModuleID, m.ModuleName,
           m.Description AS ModuleDesc,
           pm.Year,
           s.Name AS LeaderName,
           (SELECT COUNT(*) FROM programmemodules pm2
            WHERE pm2.ModuleID = m.ModuleID) AS SharedCount
    FROM programmemodules pm
    JOIN modules m ON pm.ModuleID = m.ModuleID
    LEFT JOIN staff s ON m.ModuleLeaderID = s.StaffID
    WHERE pm.ProgrammeID = $id
    ORDER BY pm.Year ASC, m.ModuleName ASC
";

$mod_result = mysqli_query($conn, $mod_query);
$allModules = mysqli_fetch_all($mod_result, MYSQLI_ASSOC);

// Group modules by year
$modulesByYear = [];
foreach ($allModules as $m) {
    $year = $m['Year'] ?? 0;
    $modulesByYear[$year][] = $m;
}
ksort($modulesByYear);

$totalModules = count($allModules);

// ---------------- IMAGE FUNCTION ----------------
function getProgrammeImage($name) {
    $n = strtolower($name);

    if (strpos($n, 'artificial intelligence') !== false)
        return 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=1400&q=80';

    if (strpos($n, 'computer science') !== false)
        return 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1400&q=80';

    if (strpos($n, 'cyber') !== false)
        return 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1400&q=80';

    if (strpos($n, 'software') !== false)
        return 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1400&q=80';

    if (strpos($n, 'data') !== false)
        return 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1400&q=80';

    return 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80';
}

// ---------------- LABELS ----------------
$lvl = strtolower($programme['LevelName']);
$levelLabel = '';

if (strpos($lvl, 'bachelor') !== false) {
    $levelLabel = 'BSc';
} elseif (strpos($lvl, 'master') !== false) {
    $levelLabel = 'MSc';
}

// Leader initial
$leaderInitial = !empty($programme['LeaderName'])
    ? strtoupper(substr($programme['LeaderName'], 0, 1))
    : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($programme['ProgrammeName']) ?> — Student Course Hub</title>
  <link rel="stylesheet" href="../css/style2.css">
  
</head>

<body>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav class="navbar">
  <a href="index.php" class="brand">Student Course Hub</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="staff.php">Staff</a>
  </div>
  <a href="../user/login.php" class="btn-login">Student Login</a>
</nav>


<!-- ============================================================
     BREADCRUMB — shows current location: Home › Programme
     ============================================================ -->
<nav class="breadcrumb">
  <a href="index.php">Home</a>
  <span>›</span>
  <span><?= htmlspecialchars($programme['ProgrammeName']) ?></span>
</nav>


<!-- ============================================================
     HERO IMAGE — big banner with programme name overlaid
     ============================================================ -->
<div class="prog-hero">
  <!-- The actual image -->
  <img
    src="<?= getProgrammeImage($programme['ProgrammeName']) ?>"
    alt="<?= htmlspecialchars($programme['ProgrammeName']) ?>"
  >
  <!-- Dark overlay so text is readable -->
  <div class="prog-hero-overlay"></div>
  <!-- Text on top of the image -->
  <div class="prog-hero-content">
    <?php if ($levelLabel): ?>
      <span class="prog-level-tag">
        <?= $levelLabel ?> · <?= htmlspecialchars($programme['LevelName']) ?>
      </span>
    <?php endif; ?>
    <h1><?= htmlspecialchars($programme['ProgrammeName']) ?></h1>
  </div>
</div>


<!-- ============================================================
     STATS BAR — 4 quick facts shown as "Label: Value" items
     Label is shown ABOVE the value
      -->
<div class="stats-bar">

  <div class="stat-item">
    <span class="stat-lbl">Total Modules</span>
    <span class="stat-num"><?= $totalModules ?></span>
  </div>

  <div class="stat-item">
    <span class="stat-lbl">Years of Study</span>
    <span class="stat-num"><?= count($modulesByYear) ?></span>
  </div>

  <div class="stat-item">
    <span class="stat-lbl">Award</span>
    <span class="stat-num"><?= $levelLabel ?: htmlspecialchars($programme['LevelName']) ?></span>
  </div>

  <?php if (!empty($programme['LeaderName'])): ?>
  <div class="stat-item">
    <span class="stat-lbl">Programme Leader</span>
    <span class="stat-num" style="font-size:0.95rem">
      <?= htmlspecialchars($programme['LeaderName']) ?>
    </span>
  </div>
  <?php endif; ?>

</div>


<!-- ============================================================
     MAIN CONTENT — two column: left content + right sidebar
     ============================================================ -->
<main id="main-content">
  <div class="prog-layout">

    <!-- ====================================================
         LEFT COLUMN
         ==================================================== -->
    <div class="prog-main">

      <!-- About section -->
      <section class="content-section">
        <h2>About This Programme</h2>
        <p><?= nl2br(htmlspecialchars($programme['Description'])) ?></p>
      </section>

      <!-- Programme Leader (only if one exists) -->
      <?php if (!empty($programme['LeaderName'])): ?>
        <section class="content-section">
          <h2>Programme Leader</h2>
          <div class="leader-card">
            <!-- Circle avatar with first initial -->
            <div class="leader-avatar"><?= $leaderInitial ?></div>
            <div class="leader-info">
              <strong>
                <?= htmlspecialchars(trim($programme['LeaderName'])) ?>
              </strong>
              <?php if (!empty($programme['LeaderEmail'])): ?>
                <a href="mailto:<?= htmlspecialchars($programme['LeaderEmail']) ?>">
                  <?= htmlspecialchars($programme['LeaderEmail']) ?>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <!-- Modules section -->
      <section class="content-section">
        <h2>
          Modules
          <!-- Badge showing total e.g. "6 total" -->
          <span class="module-count-badge"><?= $totalModules ?> total</span>
        </h2>

        <?php if (empty($modulesByYear)): ?>
          <p><em>No modules are currently listed for this programme.</em></p>

        <?php else: ?>
          <!-- Loop through each year group -->
          <?php foreach ($modulesByYear as $year => $mods): ?>
            <div class="year-group">

              <!-- Year heading e.g. "Year 1 / 4 modules" -->
              <h3 class="year-label">
                <?= $year > 0 ? 'Year ' . $year : 'Core Modules' ?>
                <span class="year-count">
                  <?= count($mods) ?> module<?= count($mods) !== 1 ? 's' : '' ?>
                </span>
              </h3>

              <!-- 2-column grid of module cards -->
              <div class="modules-grid">
                <?php foreach ($mods as $m): ?>
                  <article class="module-card">

                    <!-- Module name -->
                    <h4><?= htmlspecialchars($m['ModuleName']) ?></h4>

                    <!-- Shared badge (only if used in more than 1 programme) -->
                    <?php if ($m['SharedCount'] > 1): ?>
                      <span class="shared-badge">
                        Shared across <?= $m['SharedCount'] ?> programmes
                      </span>
                    <?php endif; ?>

                    <!-- Module description -->
                    <p><?= htmlspecialchars($m['ModuleDesc']) ?></p>

                    <!-- Module leader (if assigned) -->
                    <?php if (!empty($m['LeaderName'])): ?>
                      <p class="module-leader">
                        👤 <strong>Module Leader:</strong>
                        <?= htmlspecialchars(trim($m['LeaderName'])) ?>
                      </p>
                    <?php endif; ?>

                  </article>
                <?php endforeach; ?>
              </div>

            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </section>

    </div><!-- end left column -->


    <!-- ====================================================
         RIGHT SIDEBAR
         ==================================================== -->
    <aside class="prog-sidebar">
      <div class="sidebar-card">

        <!-- Dark blue header -->
        <div class="sidebar-header">
          <h3>Programme Info</h3>
          <p>Quick overview</p>
        </div>

        <!-- Info rows: label on left, value on right -->
        <div class="sidebar-body">

          <div class="info-row">
            <span class="info-lbl">Award</span>
            <span class="info-val"><?= $levelLabel ?: htmlspecialchars($programme['LevelName']) ?></span>
          </div>

          <div class="info-row">
            <span class="info-lbl">Level</span>
            <span class="info-val"><?= htmlspecialchars($programme['LevelName']) ?></span>
          </div>

          <div class="info-row">
            <span class="info-lbl">Total Modules</span>
            <span class="info-val"><?= $totalModules ?></span>
          </div>

          <div class="info-row">
            <span class="info-lbl">Years</span>
            <span class="info-val"><?= count($modulesByYear) ?></span>
          </div>

          <?php if (!empty($programme['LeaderName'])): ?>
            <div class="info-row">
              <span class="info-lbl">Programme Lead</span>
              <span class="info-val"><?= htmlspecialchars($programme['LeaderName']) ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($programme['LeaderEmail'])): ?>
            <div class="info-row">
              <span class="info-lbl">Contact</span>
              <span class="info-val">
                <a href="mailto:<?= htmlspecialchars($programme['LeaderEmail']) ?>" style="color:#1a73e8">
                  Email Leader
                </a>
              </span>
            </div>
          <?php endif; ?>

        </div>

        <!-- Two clearly different buttons -->
        <div class="sidebar-footer">

          <!-- Blue filled button — main action -->
          <a href="../user/login.php" class="cta-register">
            ✉ Register Interest
          </a>

          <!-- Outlined button — secondary action, looks different -->
          <a href="index.php" class="cta-back">
            ← All Programmes
          </a>

        </div>

      </div>
    </aside>

  </div><!-- end prog-layout -->
</main>


<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer>
  <p>&copy; <?= date('Y') ?> Student Course Hub | All Rights Reserved</p>
</footer>

</body>

</html>

