<?php session_start();
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist | D'Arte</title>
    <link rel="icon" href="../index/image/logo2.png" type="image/png">
    <!-- <link rel="stylesheet" href="artistpage.css"> -->
    <link rel="stylesheet" href="artistpage.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <a href="../index/index.php" class="a-go-back-button">
        <i class="fas fa-arrow-left"></i>
    </a>
    <!-- Artists -->
    <section id="artist" class="artist-gallery">
        <div class="artist-background">
            <img src="../index/image/artistbg.png" alt="Artist Title and Background">
        </div>

        <div class="artist-grid">
            <?php
            try {
                // Fetch artists
                $sql = "SELECT artist_id, artist_fname, artist_lname, penname, artist_profile FROM Artist";
                $stmt = $pdo->query($sql);
                $artists = $stmt->fetchAll();

                foreach ($artists as $artist) {
                    $artist_id = $artist['artist_id'];

                    echo "
                <div class='artist-item'>
                    <div class='artist-info'>
                        <img class='artist-photo' src='{$artist['artist_profile']}' alt='{$artist['penname']}'>
                        <h3>{$artist['penname']}</h3>
                    </div>";

                    // Fetch artworks for this artist
                    $artwork_sql = "SELECT art_name, art_image FROM Artwork WHERE artist_id = :artist_id";
                    $artwork_stmt = $pdo->prepare($artwork_sql);
                    $artwork_stmt->execute(['artist_id' => $artist_id]);
                    $artworks = $artwork_stmt->fetchAll();

                    echo "<div class='artwork-collection'>";
                    foreach ($artworks as $artwork) {
                        echo "
                    <div class='artwork-item'>
                        <img src='{$artwork['art_image']}' alt='{$artwork['art_name']}'>
                        <p>{$artwork['art_name']}</p>
                    </div>";
                    }
                    echo "</div>
                </div>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
    </section>

</body>

</html>