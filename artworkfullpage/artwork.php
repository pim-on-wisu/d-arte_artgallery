<?php
session_start();
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artwork | D'Arte</title>
    <link rel="icon" href="../index/image/logo2.png" type="image/png">
    <link rel="stylesheet" href="artworkpage.css">
    <script src="like.js" defer></script> <!-- Link the JavaScript file here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <a href="../index/index.php" class="go-back-button">
        <i class="fas fa-arrow-left"></i>
    </a>

    <section id="artwork" class="artwork-gallery">

        <!-- Background Image -->
        <div class="artwork-image">
            <img src="../index/image/artworkbg.png" alt="Artwork Title and BG">
        </div>

        <!-- Artwork Grid -->
        <div class="artwork-grid">
            <?php
            try {
                // Adjust the SQL query to join with the Artist table and concatenate artist_fname and artist_lname
                $sql = "SELECT Artwork.art_id, Artwork.art_name, Artwork.art_image, Artwork.art_date, Artwork.art_description, 
                        Artwork.art_type, CONCAT(Artist.artist_fname, ' ', Artist.artist_lname) AS artist_name 
                        FROM Artwork 
                        INNER JOIN Artist ON Artwork.artist_id = Artist.artist_id";

                $stmt = $pdo->query($sql);
                $artworks = $stmt->fetchAll();

                // Determine if the user is logged in
                $loggedIn = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'audience';
                $audience_id = $loggedIn ? $_SESSION['audience_id'] : null;

                foreach ($artworks as $artwork) {
                    $art_id = $artwork['art_id'];

                    // Get like count for each artwork
                    $sqlCountLikes = "SELECT COUNT(*) AS like_count FROM Likes WHERE art_id = :art_id";
                    $stmtCountLikes = $pdo->prepare($sqlCountLikes);
                    $stmtCountLikes->execute(['art_id' => $art_id]);
                    $likeCount = $stmtCountLikes->fetch(PDO::FETCH_ASSOC)['like_count'];

                    // Check if the current audience has liked this artwork
                    $isLiked = '';
                    if ($loggedIn) {
                        $sqlCheckLiked = "SELECT * FROM Likes WHERE audience_id = :audience_id AND art_id = :art_id";
                        $stmtCheckLiked = $pdo->prepare($sqlCheckLiked);
                        $stmtCheckLiked->execute(['audience_id' => $audience_id, 'art_id' => $art_id]);
                        if ($stmtCheckLiked->rowCount() > 0) {
                            $isLiked = 'checked';
                        }
                    }

                    echo "
                    <div class='artwork-item' data-id='{$art_id}'>
                        <img src='{$artwork['art_image']}' alt='{$artwork['art_name']}'>
                        <h3>{$artwork['art_name']}</h3>
                        <p><strong>Artist:</strong> {$artwork['artist_name']}</p>
                        <p><strong>Year:</strong> {$artwork['art_date']}</p>
                        <p><strong>Type:</strong> {$artwork['art_type']}</p>
                        <div class='post-interactions'>";

                    // Only show the like button if the user is logged in
                    if ($loggedIn) {
                        echo "
                            <input type='checkbox' id='heart-checkbox-{$art_id}' class='heart-checkbox' data-art-id='{$art_id}' {$isLiked}>
                            <label for='heart-checkbox-{$art_id}' class='heart'></label>";
                    } else {
                        echo "
                            <span class='heart not-logged-in'></span>";
                    }

                    echo "</div></div>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </section>

    <!-- Alert users to log in first -->
    <script>
        function handleLikeClick(event) {
            event.preventDefault();
            alert('Please log in first to like this artwork.');
        }
    </script>
</body>

</html>

<!-- <span class='like-count'>{$likeCount} Likes</span>"; -->