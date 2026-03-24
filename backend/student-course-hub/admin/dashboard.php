<?php
require_once __DIR__ . '/../functions.php';
require_admin();

$pageTitle = 'Admin Dashboard';
$db = get_db();
$counts = [
    'programmes' => $db->query('SELECT COUNT(*) AS total FROM Programmes')->fetch_assoc()['total'],
    'modules' => $db->query('SELECT COUNT(*) AS total FROM Modules')->fetch_assoc()['total'],
    'interests' => $db->query('SELECT COUNT(*) AS total FROM InterestedStudents WHERE IsActive = 1')->fetch_assoc()['total'],
];

include __DIR__ . '/../header.php';
?>

<section class="hero">
    <h1>Admin Dashboard</h1>
    <p>Manage programmes, modules, and mailing lists.</p>
</section>

<section class="grid three-col">
    <article class="card"><h2><?= e((string) $counts['programmes']) ?></h2><p>Programmes</p></article>
    <article class="card"><h2><?= e((string) $counts['modules']) ?></h2><p>Modules</p></article>
    <article class="card"><h2><?= e((string) $counts['interests']) ?></h2><p>Active interest registrations</p></article>
</section>

<section class="grid two-col">
    <article class="card">
        <h2>Manage data</h2>
        <p><a class="button-link" href="<?= BASE_URL ?>/admin/programmes.php">Manage Programmes</a></p>
        <p><a class="button-link" href="<?= BASE_URL ?>/admin/modules.php">Manage Modules</a></p>
    </article>
    <article class="card">
        <h2>Mailing list</h2>
        <p><a class="button-link" href="<?= BASE_URL ?>/admin/interested_students.php">View interested students</a></p>
        <p><a class="button-link" href="<?= BASE_URL ?>/admin/export_mailing_list.php">Export CSV</a></p>
    </article>
</section>

<?php include __DIR__ . '/../footer.php'; ?>
