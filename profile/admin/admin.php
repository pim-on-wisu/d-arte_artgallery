<?php
include '../../connect.php'; 

// exhibtion
// Handle adding a new exhibition
if (isset($_POST['add_exhibition'])) {
    $gallery_name = $_POST['gallery_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $e_start_date = $_POST['e_start_date'];
    $e_end_date = $_POST['e_end_date'];
    $exhibition_image = $_POST['exhibition_image'];
    $admin_id = 1;

    // Call the stored procedure to add the exhibition
    $stmt = $pdo->prepare("CALL add_exhibition(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$gallery_name, $address, $city, $state, $postal_code, $country, $e_start_date, $e_end_date, $admin_id, $exhibition_image]);
    $stmt->closeCursor(); // Close the cursor

}

// Handle deleting an exhibition
if (isset($_GET['delete_exhibition_id'])) {
    $delete_id = $_GET['delete_exhibition_id'];

    // Call the stored procedure to delete the exhibition
    $stmt = $pdo->prepare("CALL delete_exhibition(?)");
    $stmt->execute([$delete_id]);
    $stmt->closeCursor(); // Close the cursor
}

// Handle editing an exhibition
$edit_exhibition = null;
if (isset($_GET['edit_exhibition_id'])) {
    $edit_id = $_GET['edit_exhibition_id'];

    // Call the stored procedure to read the exhibition details
    $stmt = $pdo->prepare("CALL read_exhibition(?)");
    $stmt->execute([$edit_id]);
    $edit_exhibition = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor(); // Close the cursor

    // Check if the exhibition exists
    if (!$edit_exhibition) {
        echo "<p style='color:red;'>Exhibition not found.</p>";
    }
}

// Handle updating an exhibition
if (isset($_POST['update_exhibition'])) {
    $exhibition_id = $_POST['exhibition_id'];
    $gallery_name = $_POST['gallery_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];
    $e_start_date = $_POST['e_start_date'];
    $e_end_date = $_POST['e_end_date'];

    // Call the stored procedure to update the exhibition
    $stmt = $pdo->prepare("CALL update_exhibition(?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$exhibition_id, $gallery_name, $address, $city, $state, $postal_code, $country, $e_start_date, $e_end_date]);
    $stmt->closeCursor(); // Close the cursor

    header("Location: admin.php#exhibitions-management");
    exit;
}

// Handle deleting an artist
if (isset($_GET['delete_artist_id'])) {
    $delete_id = $_GET['delete_artist_id'];

    // First delete all artworks related to this artist
    $stmt = $pdo->prepare("DELETE FROM Artwork WHERE artist_id = ?");
    $stmt->execute([$delete_id]);
    $stmt->closeCursor(); // Close the cursor

    // Then delete the artist
    $stmt = $pdo->prepare("DELETE FROM Artist WHERE artist_id = ?");
    $stmt->execute([$delete_id]);
    $stmt->closeCursor(); // Close the cursor
}

// Fetch artists for display
$stmt = $pdo->query("SELECT * FROM Artist");
$artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor(); // Close the cursor

// Handle deleting an artwork
if (isset($_GET['delete_artwork_id'])) {
    $delete_id = $_GET['delete_artwork_id'];
    $stmt = $pdo->prepare("DELETE FROM Artwork WHERE art_id = ?");
    $stmt->execute([$delete_id]);
    $stmt->closeCursor(); // Close the cursor
}

// Fetch artworks for display
$stmt = $pdo->query("
    SELECT Artwork.art_id, Artwork.art_name, CONCAT(Artist.artist_fname, ' ', Artist.artist_lname) AS artist_name 
    FROM Artwork 
    LEFT JOIN Artist ON Artwork.artist_id = Artist.artist_id
    ORDER BY Artwork.art_id ASC
");
$artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor(); // Close the cursor
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | D'Arte</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="logo">
            <h2>D'Arte Admin</h2>
        </div>
        <nav class="nav-menu">
            <a href="#exhibitions-management" onclick="showSection('exhibitions-management')">Exhibitions Management</a>
            <a href="#artist-management" onclick="showSection('artist-management')">Artist Management</a>
            <a href="#artwork-management" onclick="showSection('artwork-management')">Artwork Management</a>
            <div class="au-logout-container">
                <form action="../../login/logout.php" method="POST">
                    <button type="submit" class="btn">Logout</button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
        <header class="main-header">
            <h2>Welcome, Admin</h2>
        </header>

        <!-- Exhibitions Management Section -->
        <section id="exhibitions-management" class="content-section">
            <h2>Exhibitions Management</h2>

            <!-- Add New Exhibition Form -->
            <form action="admin.php" method="post" class="form-grid">
                <input type="text" name="gallery_name" placeholder="Gallery Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="state" placeholder="State" required>
                <input type="text" name="postal_code" placeholder="Postal Code" required>
                <input type="text" name="country" placeholder="Country" required>

                <div class="date-group">
                    <label for="e_start_date">Start Date:</label>
                    <input type="date" id="e_start_date" name="e_start_date" required>
                </div>
                <div class="date-group">
                    <label for="e_end_date">End Date:</label>
                    <input type="date" id="e_end_date" name="e_end_date" required>
                </div>

                <!-- Input field for the Exhibition Image URL -->
                <label for="exhibition_image">Exhibition Image URL:</label>
                <input type="text" id="exhibition_image" name="exhibition_image" placeholder="Image URL" required>

                <button type="submit" name="add_exhibition">Add Exhibition</button>
            </form>

            <!-- Edit Exhibition Section -->
            <?php if ($edit_exhibition): ?>
                <div class="edit-exhibition">
                    <h3>Edit Exhibition</h3>
                    <form action="admin.php" method="post" class="form-grid">
                        <input type="hidden" name="exhibition_id" value="<?= $edit_exhibition['exhibition_id'] ?>">

                        <input type="text" name="gallery_name" placeholder="Gallery Name" value="<?= htmlspecialchars($edit_exhibition['gallery_name']) ?>" required>
                        <input type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($edit_exhibition['address']) ?>" required>
                        <input type="text" name="city" placeholder="City" value="<?= htmlspecialchars($edit_exhibition['city']) ?>" required>
                        <input type="text" name="state" placeholder="State" value="<?= htmlspecialchars($edit_exhibition['state']) ?>" required>
                        <input type="text" name="postal_code" placeholder="Postal Code" value="<?= htmlspecialchars($edit_exhibition['postal_code']) ?>" required>
                        <input type="text" name="country" placeholder="Country" value="<?= htmlspecialchars($edit_exhibition['country']) ?>" required>

                        <div class="date-group">
                            <label for="e_start_date">Start Date:</label>
                            <input type="date" id="e_start_date" name="e_start_date" value="<?= $edit_exhibition['e_start_date'] ?>" required>
                        </div>
                        <div class="date-group">
                            <label for="e_end_date">End Date:</label>
                            <input type="date" id="e_end_date" name="e_end_date" value="<?= $edit_exhibition['e_end_date'] ?>" required>
                        </div>

                        <button type="submit" name="update_exhibition">Update Exhibition</button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Display All Exhibitions -->
            <div class="exhibitions-list">
                <h3>All Exhibitions</h3>
                <ul>
                    <?php
                    $result = $pdo->query("SELECT * FROM Exhibition");
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<li>";
                        echo "<span><strong>{$row['gallery_name']}</strong> | {$row['city']} ({$row['e_start_date']} to {$row['e_end_date']})</span>";
                        echo "<div class='actions'>";
                        echo "<a href='admin.php?delete_exhibition_id={$row['exhibition_id']}' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this exhibition?')\">Delete</a>";
                        echo "<a href='admin.php?edit_exhibition_id={$row['exhibition_id']}' class='edit-btn'>Edit</a>";
                        echo "</div>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>
        </section>

        <!-- Artist Management Section -->
        <section id="artist-management" class="content-section">
            <h2>Artist Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Pen Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artists as $artist): ?>
                        <tr>
                            <td><?= htmlspecialchars($artist['artist_id'] ?? '') ?></td>
                            <td><?= htmlspecialchars($artist['artist_fname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($artist['artist_lname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($artist['penname'] ?? '') ?></td>
                            <td>
                                <a href="admin.php?delete_artist_id=<?= $artist['artist_id'] ?>" onclick="return confirm('Are you sure you want to delete this artist?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Artwork Management Section -->
        <section id="artwork-management" class="content-section">
            <h2>Artwork Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Art Name</th>
                        <th>Artist</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artworks as $artwork): ?>
                        <tr>
                            <td><?= htmlspecialchars($artwork['art_id']) ?></td>
                            <td><?= htmlspecialchars($artwork['art_name']) ?></td>
                            <td><?= htmlspecialchars($artwork['artist_name']) ?></td>
                            <td>
                                <a href="admin.php?delete_artwork_id=<?= $artwork['art_id'] ?>" onclick="return confirm('Are you sure you want to delete this artwork?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script>
        function showSection(id) {
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => section.style.display = 'none');
            document.getElementById(id).style.display = 'block';
        }
        showSection('exhibitions-management'); 
    </script>
</body>

</html>
