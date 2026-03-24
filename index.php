<?php
include "db.php";

/* ===== SEARCH LOGIC ===== */
$where = "WHERE programmes.is_published = 1";

if (!empty($_GET["search"])) {
    $search = $_GET["search"];
    $where .= " AND programmes.ProgrammeName LIKE '%$search%'";
}

/* ===== QUERY ===== */
$sql = "SELECT programmes.*, Levels.LevelName
        FROM programmes
        JOIN Levels ON programmes.LevelID = Levels.LevelID
        $where";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmes</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>All Programmes</h1>

    <!-- ===== SEARCH BAR ===== -->
    <form method="GET">
        <input type="text" name="search" placeholder="Search programmes..."
               value="<?php echo $_GET['search'] ?? ''; ?>">
        <button type="submit">Search</button>
    </form>

    <br>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<div style='border:1px solid #ccc; padding:15px; margin-bottom:15px;'>";

            echo "<h2>" . $row["ProgrammeName"] . "</h2>";
            echo "<p><strong>Level:</strong> " . $row["LevelName"] . "</p>";
            echo "<p>" . $row["Description"] . "</p>";

            echo "<a class='btn' href='programme_details.php?id=" . $row["ProgrammeID"] . "'>View Details</a>";

            echo "</div>";
        }

    } else {
        echo "<p>No programmes found.</p>";
    }
    ?>

</div>

</body>
</html>