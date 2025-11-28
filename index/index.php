<?php
session_start(); 
include '../connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Arte</title>
    <link rel="icon" href="../index/image/logo2.png" type="image/png">

    <!-- Link CSS files -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="spotlight.css">
    <link rel="stylesheet" href="exhibitions.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <link rel="stylesheet" href="artwork.css">
    <link rel="stylesheet" href="artist.css">
    <link rel="stylesheet" href="aboutus.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#monthly-spotlight">Monthly Spotlight</a></li>
                <li><a href="#exhibitions">Exhibitions</a></li>
                <li><a href="#artwork">Artwork</a></li>
                <li><a href="#artist">Artist</a></li>
                <li><a href="#about">About Us</a></li>

                <!-- Show Profile button if user is logged in, otherwise show nothing -->
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Display profile link based on user type -->
                    <?php if ($_SESSION['user_type'] === 'artist'): ?>
                        <li><a href="../profile/artist/artist_info.php">Profile</a></li>
                    <?php elseif ($_SESSION['user_type'] === 'audience'): ?>
                        <li><a href="../profile/audience/audience_info.php">Profile</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- home -->
    <section class="hero">
        <div class="logo">D'Arte</div>
        <!-- Search Bar always in the hero section -->
        <div class="search-wrapper hero-search">
            <div class="search-container">
                <form method="GET" action="search.php" style="display: flex; width: 100%; height: 100%;">
                    <input type="text" id="searchInput" name="query" placeholder="Search for artists, artworks, etc." onkeyup="fetchSuggestions()">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                <div id="suggestionBox" class="suggestion-box"></div>
            </div>
        </div>


        <div class="hero-content">
            <h1 id="gallery-title">Art Gallery</h1>
            <p id="gallery-subtitle">For those who see the world through the lens of art.</p>
        </div>

        <!-- Show Login button if user is not logged in -->
        <?php if (!isset($_SESSION['username'])): ?>
            <a href="../login/login.php" class="login-button">Log-In</a>
        <?php endif; ?>
    </section>

    <!-- Monthly Spotlight Section -->
    <section id="monthly-spotlight" class="spotlight-section">
        <img src="image/1.png" alt="Rank 1" class="spotlight-top-rank">
        <img src="image/2.png" alt="Rank 1" class="spotlight-top-rank2">
        <img src="image/3.png" alt="Rank 1" class="spotlight-top-rank3">
        <div class="spotlight-image-container">
            <img src="image/ms.png" alt="Monthly Spotlight Image">
        </div>
        <div class="spotlight-grid">
            <?php
            try {
                $sql = "SELECT Artwork.art_id, Artwork.artist_id, Artwork.art_name, Artist.artist_fname, Artist.artist_lname, Artist.penname, Artwork.art_date, Artwork.art_type, Artwork.art_image, Artwork.like_count 
                FROM Artwork
                JOIN Artist ON Artwork.artist_id = Artist.artist_id
                ORDER BY like_count DESC LIMIT 7";
                $stmt = $pdo->query($sql);
                $spotlights = $stmt->fetchAll();

                // Display the top 3 artworks (rank 1-3)
                for ($index = 0; $index < 3; $index++) {
                    $spotlight = $spotlights[$index];
                    echo "
            <div class='spotlight-item spotlight-item-big'>
                <img src='{$spotlight['art_image']}' alt='{$spotlight['art_name']}'>                <div class='spotlight-info'>
                    <h3>{$spotlight['art_name']}</h3>
                    <p>Artist: {$spotlight['penname']}</p>
                    <p>Year: {$spotlight['art_date']}</p>
                    <p>Medium: {$spotlight['art_type']}</p>
                </div>
            </div>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="spotlight-grid-rank-4-7">
            <?php
            // Display the next 4 artworks (rank 4-7)
            for ($index = 3; $index < 7; $index++) {
                $spotlight = $spotlights[$index];
                echo "
        <div class='spotlight-item'>
            <img src='{$spotlight['art_image']}' alt='{$spotlight['art_name']}'>
            <div class='spotlight-info'>
                <h3>{$spotlight['art_name']}</h3>
                <p>Artist: {$spotlight['penname']}</p>
                <p>Year: {$spotlight['art_date']}</p>
                <p>Medium: {$spotlight['art_type']}</p>
            </div>
        </div>";
            }
            ?>
        </div>
        <div class="spotlight-image-container-bgms img">
            <img src="image/bgms.png" alt="Monthly Spotlight Image">
        </div>
    </section>

    <!-- Exhibitions Section -->
    <section id="exhibitions" class="exhibitions-section">
        <h1>Exhibitions</h1>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php
                try {
                    // Fetch exhibitions from the database
                    $sql = "SELECT gallery_name, e_start_date, address, city, exhibition_image FROM Exhibition";
                    $stmt = $pdo->query($sql);
                    $exhibitions = $stmt->fetchAll();

                    foreach ($exhibitions as $exhibition) {
                        echo "
                        <div class='swiper-slide exhibition-item'>
                            <div class='exhibition-image' style='background-image: url({$exhibition['exhibition_image']});'>
                            </div>
                            <div class='exhibition-info'>
                                <h2>{$exhibition['gallery_name']}</h2>
                                <p><span class='icon'>📅</span> {$exhibition['e_start_date']}</p>
                                <p><span class='icon'>📍</span> {$exhibition['address']}, {$exhibition['city']}</p>
                            </div>
                        </div>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Artwork Section -->
    <section id="artwork" class="artwork-gallery">

        <!-- Background Image -->
        <div class="artwork-image">
            <img src="image/artworkbg.png" alt="Artwork Tittle and BG">
        </div>
        <div class="artwork-grid">
            <!-- Artwork Item 1 -->
            <div class="artwork-item" data-id="1">
                <img src="https://images8.alphacoders.com/130/thumb-350-1300671.webp">
                <h3>Heaven Official's Blessing</h3>
                <p>Artist: Chang Yang</p>
                <p>Year: 2022</p>
                <p>Medium: Digital Painting</p>
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-1" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-1" class="heart"></label>
                </div>
            </div>


            <!-- Artwork Item 2 -->
            <div class="artwork-item" data-id="2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ea/Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg/700px-Van_Gogh_-_Starry_Night_-_Google_Art_Project.jpg" alt="Starry Night">
                <h3>Starry Night</h3>
                <p>Artist: Vincent Van Gogh</p>
                <p>Year: 1889</p>
                <p>Type: Oil on canvas</p>
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-2" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-2" class="heart"></label>
                </div>
            </div>
            <!-- Artwork Item 3 -->
            <div class="artwork-item" data-id="3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/%22Un_Bar_aux_Folies-Berg%C3%A8re%22_by_%C3%89douard_Manet_%281882%29.jpg/700px-%22Un_Bar_aux_Folies-Berg%C3%A8re%22_by_%C3%89douard_Manet_%281882%29.jpg" alt="A Bar at the Folies Bergère">
                <h3>A Bar at the Folies Bergère</h3>
                <p>Artist: Édouard Manet</p>
                <p>Year: 1882</p>
                <p>Type: Oil on canvas</p>
                <!-- <span class="like-button">❤</span> -->
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-3" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-3" class="heart"></label>
                </div>
            </div>
            <!-- Artwork Item 4 -->
            <div class="artwork-item" data-id="4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Caspar_David_Friedrich_-_Wanderer_above_the_Sea_of_Fog.jpeg/600px-Caspar_David_Friedrich_-_Wanderer_above_the_Sea_of_Fog.jpeg" alt="Wanderer above the Sea Fog">
                <h3>Wanderer above the Sea Fog</h3>
                <p>Artist: Caspar David Friedrich</p>
                <p>Year: 1818</p>
                <p>Type: Oil on canvas</p>
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-4" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-4" class="heart"></label>
                </div>
            </div>
            <!-- Artwork Item 5 -->
            <div class="artwork-item" data-id="5">
                <img src="https://schabrieres.wordpress.com/wp-content/uploads/2012/03/picasso_the_blind-_man_s_meal_1903.jpg" alt="Le Repas de l'aveugle">
                <h3>Le Repas de l'aveugle</h3>
                <p>Artist: Pablo Picasso</p>
                <p>Year: 1903</p>
                <p>Type: Oil on canvas</p>
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-5" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-5" class="heart"></label>
                </div>
            </div>
            <!-- Artwork Item 6 -->
            <div class="artwork-item" data-id="6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/The_Swing_%28P430%29.jpg/600px-The_Swing_%28P430%29.jpg" alt="The Swing">
                <h3>The Swing</h3>
                <p>Artist: Jean-Honoré Fragonard</p>
                <p>Year: 1767</p>
                <p>Type: Oil on canvas</p>
                <div class="#artwork like-interactions">
                    <input type="checkbox" id="heart-checkbox-6" class="heart-checkbox" onclick="handleLikeClick(event)">
                    <label for="heart-checkbox-6" class="heart"></label>
                </div>
            </div>
        </div>

        <a href="../artworkfullpage/artwork.php" class="artwork-see-more-button">See More</a>
    </section>

    <!-- Artist Section -->
    <section id="artist" class="artist-gallery">
        <div class="artist-background">
            <img src="image/artistbg.png" alt="Artist Title and Background">
        </div>

        <div class="artist-grid">
            <?php
            try {
                $sql = "SELECT artist_id, artist_fname, artist_lname, penname, artist_profile FROM Artist LIMIT 4";
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
                    echo "</div></div>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>
        <a href="../artistfullpage/artist.php" class="artist-see-more-button">See More</a>
    </section>

    <!-- About Us Section -->
    <section id="about">
        <div class="aboutus-image-container">
            <img src="image/aboutus.png" alt="About Us Image">
        </div>
    </section>


    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('#exhibitions .swiper-container', {
                slidesPerView: 3,
                spaceBetween: 20,
                loop: true,
                pagination: {
                    el: '#exhibitions .swiper-pagination',
                    clickable: true,
                },
                freeMode: true,
                grabCursor: true,
            });
        });
    </script>


    <!-- Search Suggestions JavaScript -->
    <script>
        function fetchSuggestions() {
            let query = document.getElementById("searchInput").value;

            if (query.length === 0) {
                document.getElementById("suggestionBox").style.display = "none";
                return;
            }

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "search_suggestions.php?q=" + query, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let response = xhr.responseText;
                    let suggestionBox = document.getElementById("suggestionBox");

                    if (response.trim() !== "") {
                        suggestionBox.innerHTML = response;
                        suggestionBox.style.display = "block";
                    } else {
                        suggestionBox.style.display = "none";
                    }
                }
            };
            xhr.send();
        }

        function selectSuggestion(value) {
            document.getElementById("searchInput").value = value;
            document.getElementById("suggestionBox").style.display = "none";
        }
    </script>


</body>

</html>