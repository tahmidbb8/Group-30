<?php
// Start the session so we know who is logged in
session_start();

// Connect to the database
include('../config/db.php');

// If the student is not logged in, send them to the login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Get the logged-in student's details from the session
$user = $_SESSION['user'];

// Handle when the student clicks "Register Interest" or "Remove Interest"
// We use POST (not GET) so the action is not visible in the URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['programme_id'])) {
    $programme_id = (int)$_POST['programme_id'];

    if ($_POST['action'] === 'add') {
        // Add the student's interest to the database
        // ON DUPLICATE KEY UPDATE means: if they already registered, just refresh the date
        $stmt = $pdo->prepare("
            INSERT INTO interestedstudents (ProgrammeID, StudentName, Email, RegisteredAt)
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE RegisteredAt = NOW()
        ");
        $stmt->execute([$programme_id, $user['name'], $user['email']]);

    } elseif ($_POST['action'] === 'remove') {
        // Remove the student's interest from the database
        $stmt = $pdo->prepare("DELETE FROM interestedstudents WHERE ProgrammeID = ? AND Email = ?");
        $stmt->execute([$programme_id, $user['email']]);
    }

    // Reload the page so the button updates (add becomes remove, or vice versa)
    header("Location: dashboard.php");
    exit;
}

// Get all levels for the filter dropdown e.g. Bachelor's, Master's
$levels = $pdo->query("SELECT * FROM levels ORDER BY LevelName")->fetchAll(PDO::FETCH_ASSOC);

// Get the list of programme IDs this student has registered interest in
$interestStmt = $pdo->prepare("SELECT ProgrammeID FROM interestedstudents WHERE Email = ?");
$interestStmt->execute([$user['email']]);
$userInterests = $interestStmt->fetchAll(PDO::FETCH_COLUMN); // returns a flat array of IDs

// Read the search and level filter from the URL e.g. ?search=cyber&level=2
$search        = isset($_GET['search']) ? trim($_GET['search']) : '';
$selectedLevel = isset($_GET['level']) ? (int)$_GET['level'] : 0;

// Build the SQL query dynamically based on what filters are active
$params = [];
$where  = "WHERE p.IsPublished = 1"; // always only show published programmes

if ($selectedLevel > 0) {
    $where   .= " AND p.LevelID = ?";
    $params[] = $selectedLevel;
}

if ($search !== '') {
    // Search in both name and description columns
    $where   .= " AND (p.ProgrammeName LIKE ? OR p.Description LIKE ?)";
    $like     = '%' . $search . '%'; // % means "anything before or after"
    $params[] = $like;
    $params[] = $like;
}

// Run the query and get all matching programmes
$stmt = $pdo->prepare("
    SELECT p.*, l.LevelName
    FROM programmes p
    JOIN levels l ON p.LevelID = l.LevelID
    $where
    ORDER BY l.LevelID ASC, p.ProgrammeName ASC
");
$stmt->execute($params);
$programmes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Split programmes into groups so we can show them under separate headings
$bachelors = $masters = $other = [];
foreach ($programmes as $p) {
    $lvl = strtolower($p['LevelName']);
    if (strpos($lvl, 'bachelor') !== false)   $bachelors[] = $p;
    elseif (strpos($lvl, 'master') !== false) $masters[]   = $p;
    else                                       $other[]     = $p;
}

// Filter down to just the programmes the student is interested in
$myInterested = array_filter($programmes, fn($p) => in_array($p['ProgrammeID'], $userInterests));

// Pick a relevant image based on keywords in the programme name
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
    if (strpos($n, 'machine learning') !== false)
        return 'https://images.unsplash.com/photo-1507146153580-69a1fe6d8aa1?auto=format&fit=crop&w=800&q=80';
    // Default image if no keyword matched
    return 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Dashboard — Student Course Hub</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- Skip link for keyboard/screen reader users -->
<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Top navigation bar -->
<header class="main-header" role="banner">
  <div class="header-container">
    <a href="dashboard.php" class="site-title">Student Course Hub</a>
    <nav aria-label="User navigation">
      <a href="../public/index.php" class="nav-link">Browse Programmes</a>
      <a href="../public/staff.php" class="nav-link">Staff</a>
      <!-- Logout destroys the session and redirects to homepage -->
      <a href="logout.php" class="nav-link btn-logout">Logout</a>
    </nav>
  </div>
</header>

<!-- Welcome banner — greets the student by name -->
<div class="dashboard-header" role="region" aria-label="Welcome banner">
  <h1>Welcome back, <?= htmlspecialchars($user['name']) ?> 👋</h1>
  <p>You have expressed interest in <strong><?= count($userInterests) ?></strong> programme<?= count($userInterests) !== 1 ? 's' : '' ?>. Explore more below.</p>
</div>

<!-- Search bar and level filter -->
<div class="dash-controls" role="search" aria-label="Search and filter programmes">

  <!-- Search form — submits via GET so the search term appears in the URL -->
  <form method="GET" action="" class="dash-search-form">
    <label for="dash-search" class="sr-only">Search programmes</label>
    <input
      type="text"
      id="dash-search"
      name="search"
      value="<?= htmlspecialchars($search) ?>"
      placeholder="Search programmes…"
      aria-label="Search programmes"
    >
    <!-- Keep the level filter active when searching -->
    <?php if ($selectedLevel > 0): ?>
      <input type="hidden" name="level" value="<?= $selectedLevel ?>">
    <?php endif; ?>
    <button type="submit">Search</button>
    <!-- Show a Clear link only if a filter is currently active -->
    <?php if ($search !== '' || $selectedLevel > 0): ?>
      <a href="dashboard.php" class="clear-link">✕ Clear</a>
    <?php endif; ?>
  </form>

  <!-- Level dropdown — auto-submits when student changes selection -->
  <form method="GET" action="" class="dash-filter-form">
    <?php if ($search !== ''): ?>
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <?php endif; ?>
    <label for="dash-level">Level:</label>
    <select id="dash-level" name="level" onchange="this.form.submit()" aria-label="Filter by level">
      <option value="0" <?= $selectedLevel === 0 ? 'selected' : '' ?>>All</option>
      <?php foreach ($levels as $lv): ?>
        <option value="<?= $lv['LevelID'] ?>" <?= ($selectedLevel == $lv['LevelID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($lv['LevelName']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </form>

</div>

<!-- Tab buttons — clicking one shows/hides the matching tab panel below -->
<div class="tab-nav" role="tablist" aria-label="Programme categories">
  <!-- "active" class highlights the currently selected tab -->
  <button class="tab-btn active" role="tab" aria-selected="true"  aria-controls="tab-all"       id="btn-all"       onclick="switchTab('all',this)">
    All <span class="count"><?= count($programmes) ?></span>
  </button>
  <button class="tab-btn"        role="tab" aria-selected="false" aria-controls="tab-bachelor"  id="btn-bachelor"  onclick="switchTab('bachelor',this)">
    Bachelor's <span class="count"><?= count($bachelors) ?></span>
  </button>
  <button class="tab-btn"        role="tab" aria-selected="false" aria-controls="tab-masters"   id="btn-masters"   onclick="switchTab('masters',this)">
    Master's <span class="count"><?= count($masters) ?></span>
  </button>
  <button class="tab-btn"        role="tab" aria-selected="false" aria-controls="tab-interests" id="btn-interests" onclick="switchTab('interests',this)">
    My Interests <span class="count"><?= count($userInterests) ?></span>
  </button>
</div>

<!-- Main content area — contains all four tab panels -->
<main id="main-content">

  <!-- ALL TAB — shows every programme grouped by level -->
  <div id="tab-all" class="tab-panel active" role="tabpanel" aria-labelledby="btn-all">
    <?php if (empty($programmes)): ?>
      <div class="container"><p>No programmes found. <a href="dashboard.php">Clear search</a></p></div>
    <?php else: ?>

      <?php if (!empty($bachelors)): ?>
        <!-- Banner heading for the Bachelor's section -->
        <div class="level-banner bachelor" role="region" aria-label="Bachelor's programmes">
          <span class="level-tag">Undergraduate</span>
          <h2>Bachelor's Degrees</h2>
          <p><?= count($bachelors) ?> programmes available</p>
          <span class="level-num" aria-hidden="true"><?= count($bachelors) ?></span>
        </div>
        <div class="programmes-grid">
          <?php foreach ($bachelors as $p): ?>
            <!-- Check if this student has already registered interest in this programme -->
            <?php $interested = in_array($p['ProgrammeID'], $userInterests); ?>
            <article class="programme-card" aria-label="<?= htmlspecialchars($p['ProgrammeName']) ?>">
              <div class="card-img-wrap">
                <img src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                     alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                     loading="lazy">
                <span class="prog-level-badge">BSc</span>
              </div>
              <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                <!-- Show a short preview of the description -->
                <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 130)) ?>…</p>
                <div class="card-actions">
                  <a href="../public/programme.php?id=<?= $p['ProgrammeID'] ?>"
                     class="btn btn-primary"
                     aria-label="View details for <?= htmlspecialchars($p['ProgrammeName']) ?>">Details</a>
                  <!-- This small form adds or removes interest when the button is clicked -->
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="programme_id" value="<?= $p['ProgrammeID'] ?>">
                    <!-- The action switches between "add" and "remove" depending on current state -->
                    <input type="hidden" name="action" value="<?= $interested ? 'remove' : 'add' ?>">
                    <button type="submit"
                            class="btn <?= $interested ? 'btn-danger' : 'btn-success' ?>"
                            aria-label="<?= $interested ? 'Remove interest in' : 'Register interest in' ?> <?= htmlspecialchars($p['ProgrammeName']) ?>">
                      <?= $interested ? 'Remove Interest' : 'Register Interest' ?>
                    </button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($masters)): ?>
        <!-- Banner heading for the Master's section -->
        <div class="level-banner masters" role="region" aria-label="Master's programmes">
          <span class="level-tag">Postgraduate</span>
          <h2>Master's Degrees</h2>
          <p><?= count($masters) ?> programmes available</p>
          <span class="level-num" aria-hidden="true"><?= count($masters) ?></span>
        </div>
        <div class="programmes-grid">
          <?php foreach ($masters as $p): ?>
            <?php $interested = in_array($p['ProgrammeID'], $userInterests); ?>
            <article class="programme-card" aria-label="<?= htmlspecialchars($p['ProgrammeName']) ?>">
              <div class="card-img-wrap">
                <img src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                     alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                     loading="lazy">
                <span class="prog-level-badge badge-masters">MSc</span>
              </div>
              <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 130)) ?>…</p>
                <div class="card-actions">
                  <a href="../public/programme.php?id=<?= $p['ProgrammeID'] ?>"
                     class="btn btn-primary"
                     aria-label="View details for <?= htmlspecialchars($p['ProgrammeName']) ?>">Details</a>
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="programme_id" value="<?= $p['ProgrammeID'] ?>">
                    <input type="hidden" name="action" value="<?= $interested ? 'remove' : 'add' ?>">
                    <button type="submit"
                            class="btn <?= $interested ? 'btn-danger' : 'btn-success' ?>"
                            aria-label="<?= $interested ? 'Remove interest in' : 'Register interest in' ?> <?= htmlspecialchars($p['ProgrammeName']) ?>">
                      <?= $interested ? 'Remove Interest' : 'Register Interest' ?>
                    </button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    <?php endif; ?>
  </div>

  <!-- BACHELOR'S TAB — only Bachelor's programmes -->
  <div id="tab-bachelor" class="tab-panel" role="tabpanel" aria-labelledby="btn-bachelor">
    <?php if (empty($bachelors)): ?>
      <div class="container"><p>No bachelor's programmes found.</p></div>
    <?php else: ?>
      <div class="programmes-grid" style="max-width:1200px;margin:24px auto;padding:0 24px">
        <?php foreach ($bachelors as $p): ?>
          <?php $interested = in_array($p['ProgrammeID'], $userInterests); ?>
          <article class="programme-card" aria-label="<?= htmlspecialchars($p['ProgrammeName']) ?>">
            <div class="card-img-wrap">
              <img src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                   alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                   loading="lazy">
              <span class="prog-level-badge">BSc</span>
            </div>
            <div class="card-body">
              <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
              <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 130)) ?>…</p>
              <div class="card-actions">
                <a href="../public/programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">Details</a>
                <form method="POST" style="display:inline">
                  <input type="hidden" name="programme_id" value="<?= $p['ProgrammeID'] ?>">
                  <input type="hidden" name="action" value="<?= $interested ? 'remove' : 'add' ?>">
                  <button type="submit" class="btn <?= $interested ? 'btn-danger' : 'btn-success' ?>">
                    <?= $interested ? 'Remove Interest' : 'Register Interest' ?>
                  </button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- MASTER'S TAB — only Master's programmes -->
  <div id="tab-masters" class="tab-panel" role="tabpanel" aria-labelledby="btn-masters">
    <?php if (empty($masters)): ?>
      <div class="container"><p>No master's programmes found.</p></div>
    <?php else: ?>
      <div class="programmes-grid" style="max-width:1200px;margin:24px auto;padding:0 24px">
        <?php foreach ($masters as $p): ?>
          <?php $interested = in_array($p['ProgrammeID'], $userInterests); ?>
          <article class="programme-card" aria-label="<?= htmlspecialchars($p['ProgrammeName']) ?>">
            <div class="card-img-wrap">
              <img src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                   alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                   loading="lazy">
              <span class="prog-level-badge badge-masters">MSc</span>
            </div>
            <div class="card-body">
              <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
              <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 130)) ?>…</p>
              <div class="card-actions">
                <a href="../public/programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">Details</a>
                <form method="POST" style="display:inline">
                  <input type="hidden" name="programme_id" value="<?= $p['ProgrammeID'] ?>">
                  <input type="hidden" name="action" value="<?= $interested ? 'remove' : 'add' ?>">
                  <button type="submit" class="btn <?= $interested ? 'btn-danger' : 'btn-success' ?>">
                    <?= $interested ? 'Remove Interest' : 'Register Interest' ?>
                  </button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- MY INTERESTS TAB — only programmes the student has registered interest in -->
  <div id="tab-interests" class="tab-panel" role="tabpanel" aria-labelledby="btn-interests">
    <div style="max-width:1200px;margin:24px auto;padding:0 24px">
      <?php if (empty($userInterests)): ?>
        <!-- Shown when they haven't registered interest in anything yet -->
        <div class="empty-interests" role="status">
          <p>You haven't registered interest in any programme yet.</p>
          <p>Browse the tabs above and click <strong>Register Interest</strong> to add programmes here.</p>
        </div>
      <?php else: ?>
        <div class="programmes-grid">
          <?php foreach ($programmes as $p):
            // Skip this programme if the student hasn't registered interest in it
            if (!in_array($p['ProgrammeID'], $userInterests)) continue;
            $lvl = strtolower($p['LevelName']);
            // Decide the badge label and style class
            $badge      = strpos($lvl, 'bachelor') !== false ? 'BSc' : (strpos($lvl, 'master') !== false ? 'MSc' : htmlspecialchars($p['LevelName']));
            $badgeClass = strpos($lvl, 'master') !== false ? 'badge-masters' : '';
          ?>
            <article class="programme-card" aria-label="<?= htmlspecialchars($p['ProgrammeName']) ?>">
              <div class="card-img-wrap">
                <img src="<?= getProgrammeImage($p['ProgrammeName']) ?>"
                     alt="<?= htmlspecialchars($p['ProgrammeName']) ?>"
                     loading="lazy">
                <span class="prog-level-badge <?= $badgeClass ?>"><?= $badge ?></span>
              </div>
              <div class="card-body">
                <h3 class="card-title"><?= htmlspecialchars($p['ProgrammeName']) ?></h3>
                <p><?= htmlspecialchars(mb_substr($p['Description'], 0, 130)) ?>…</p>
                <div class="card-actions">
                  <a href="../public/programme.php?id=<?= $p['ProgrammeID'] ?>" class="btn btn-primary">Details</a>
                  <!-- In this tab, the button always removes (since they're already interested) -->
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="programme_id" value="<?= $p['ProgrammeID'] ?>">
                    <input type="hidden" name="action" value="remove">
                    <button type="submit" class="btn btn-danger"
                            aria-label="Remove interest in <?= htmlspecialchars($p['ProgrammeName']) ?>">
                      Remove Interest
                    </button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</main>

<!-- Footer -->
<footer role="contentinfo">
  &copy; <?= date('Y') ?> Student Course Hub | All Rights Reserved
</footer>

<!-- Tab switching JavaScript
     This shows the clicked tab panel and hides all others -->
<script>
function switchTab(name, btn) {
  // Hide all tab panels
  document.querySelectorAll('.tab-panel').forEach(p => {
    p.classList.remove('active');
    p.setAttribute('aria-hidden', 'true');
  });
  // Remove "active" from all tab buttons
  document.querySelectorAll('.tab-btn').forEach(b => {
    b.classList.remove('active');
    b.setAttribute('aria-selected', 'false');
  });
  // Show the selected tab panel
  document.getElementById('tab-' + name).classList.add('active');
  document.getElementById('tab-' + name).setAttribute('aria-hidden', 'false');
  // Highlight the clicked button
  btn.classList.add('active');
  btn.setAttribute('aria-selected', 'true');
}
</script>

</body>
</html>