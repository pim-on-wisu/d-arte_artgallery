<?php
session_start();
include '../../connect.php';

// Check if the user is logged in and if they are an artist
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'artist') {
    header("Location: ../../index/index.php");
    exit();
}

// Retrieve artist data from the database
$username = $_SESSION['username'];
$penname = "";
$firstname = "";
$lastname = "";
$address = "";
$birthplace = "";
$artist_id = "";
$profilePicture = "https://www.transparentpng.com/download/user/gray-user-profile-icon-png-fP8Q1P.png"; 
// Fetch current artist data
$sql = "SELECT artist_id, penname, artist_fname, artist_lname, artist_address, birth_place, artist_profile FROM Artist WHERE artist_username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $artist_id = $user['artist_id'];
    $penname = $user['penname'];
    $firstname = $user['artist_fname'];
    $lastname = $user['artist_lname'];
    $address = $user['artist_address'];
    $birthplace = $user['birth_place'];
    $profilePicture = $user['artist_profile'] ? $user['artist_profile'] : $profilePicture;
}

// Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save_change'])) {
        $penname = $_POST['penname'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $birthplace = $_POST['birthplace'];
        $profileUrl = $_POST['profile_url'];

        $sql = "UPDATE Artist SET penname = :penname, artist_fname = :firstname, artist_lname = :lastname, artist_address = :address, birth_place = :birthplace, artist_profile = :profileUrl WHERE artist_username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'penname' => $penname,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'address' => $address,
            'birthplace' => $birthplace,
            'profileUrl' => $profileUrl,
            'username' => $username
        ]);

        // Redirect after saving
        header("Location: ../../index/index.php");
        exit();
    }

    if (isset($_POST['upload_artwork'])) {
        $artworkUrl = $_POST['artwork_url'];
        $artworkName = $_POST['artwork_name'];
        $type = $_POST['type'];
        $year = $_POST['year'];
        $description = $_POST['description'];

        $sql = "INSERT INTO Artwork (art_image, art_name, art_type, art_date, art_description, artist_id, admin_id) 
        VALUES (:artworkUrl, :artworkName, :type, :year, :description, :artist_id, :admin_id)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            'artworkUrl' => $artworkUrl,
            'artworkName' => $artworkName,
            'type' => $type,
            'year' => $year,
            'description' => $description,
            'artist_id' => $artist_id,
            'admin_id' => 1
        ]);

        echo "<script>
                var uploadMessage = '" . ($result ? "Upload successful" : "Failed to upload") . "';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Profile</title>
    <link rel="icon" href="../../index/image/logo2.png" type="image/png">
    <link rel="stylesheet" href="artist_info.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    

</head>

<body>

    <div class="container">
        <a href="../../index/index.php" class="ar-go-back-button">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Logout button at the top right -->
        <div class="logout-container">
            <form action="../../login/logout.php" method="POST">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

        <h1>Edit Information</h1>
        <!-- User Information Section -->
        <div class="user-info-section">
            <div class="profile-section">
                <div class="profile-image" style="background-image: url('<?php echo $profilePicture; ?>');"></div>
            </div>

            <form action="artist_info.php" method="POST">
                <div class="form-group">
                    <label for="profile_url">Profile Image URL</label>
                    <input type="url" id="profile_url" name="profile_url" value="<?php echo htmlspecialchars($profilePicture ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="penname">Penname</label>
                    <input type="text" id="penname" name="penname" value="<?php echo htmlspecialchars($penname ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="birthplace">Birth Place</label>
                    <input type="text" id="birthplace" name="birthplace" value="<?php echo htmlspecialchars($birthplace ?? ''); ?>">
                </div>

                <button type="submit" name="save_change" class="save-button">Save Changes</button>
            </form>
        </div>

        <!-- Artwork Upload Section -->
        <h2>Artwork Uploads</h2>
        <form action="artist_info.php" method="POST">
            <div class="form-group">
                <label for="artwork_url">Artwork URL</label>
                <input type="url" id="artwork_url" name="artwork_url" placeholder="Enter the URL of your artwork" required>
            </div>

            <div class="form-group">
                <label for="artwork_name">Name</label>
                <input type="text" id="artwork_name" name="artwork_name" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" id="type" name="type" required>
            </div>

            <div class="form-group">
                <label for="year">Year</label>
                <input type="text" id="year" name="year" required>
            </div>

            <div class="form-group">
                <label for="description">Descriptions</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <button type="submit" name="upload_artwork" class="upload-button">Upload</button>
        </form>
    </div>

    <!-- Popup structure -->
    <div id="popup" class="popup-overlay">
        <div class="popup-content">
            <span class="popup-close" onclick="closePopup()">×</span>
            <p id="popup-message"></p>
        </div>
    </div>

    <!-- JavaScript for Popup -->
    <script>
        function showPopup(message) {
            document.getElementById('popup-message').textContent = message;
            document.getElementById('popup').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof uploadMessage !== 'undefined') {
                showPopup(uploadMessage);
            }
        });
    </script>

</body>

</html>