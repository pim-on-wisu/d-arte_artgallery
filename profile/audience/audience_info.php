<?php
session_start();
include '../../connect.php';

// Check if the user is logged in and is of type "audience"
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'audience') {
    header("Location: ../../index/index.php");
    exit();
}

// Retrieve audience data from the database
$username = $_SESSION['username'];
$firstname = "";
$lastname = "";
$email = "";
$phone = "";
$profilePicture = "userpic.png"; // Default profile picture

// Fetch current user data from the database
$sql = "SELECT audience_fname, audience_lname, audience_username, audience_email, audience_phone_no FROM Audience WHERE audience_username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $firstname = $user['audience_fname'];
    $lastname = $user['audience_lname'];
    $username = $user['audience_username'];
    $email = $user['audience_email'];
    $phone = $user['audience_phone_no'];
}

// Update user data upon form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update user data in the database
    $sql = "UPDATE Audience SET audience_fname = :firstname, audience_lname = :lastname, audience_username = :username, audience_email = :email, audience_phone_no = :phone WHERE audience_username = :originalUsername";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'originalUsername' => $_SESSION['username']
    ]);

    // Update session username if changed
    $_SESSION['username'] = $username;

    // Redirect to the home page after saving changes
    header("Location: ../../index/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audience Profile</title>
    <link rel="icon" href="../../index/image/logo2.png" type="image/png">

    <link rel="stylesheet" href="audience_info.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="edit-container">
        <a href="../../index/index.php" class="au-go-back-button">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Logout button at the top right -->
        <div class="au-logout-container">
            <form action="../../login/logout.php" method="POST">
                <button type="submit" class="au-logout-button">Logout</button>
            </form>
        </div>

        <h1>Edit Information</h1>

        <!-- Profile Picture Section Above Form Fields -->
        <div class="user-info-section">
            <div class="profile-section">
                <div class="profile-image" style="background-image: url('<?php echo $profilePicture; ?>');"></div>
            </div>

            <form action="audience_info.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
                </div>

                <button type="submit" class="save-button">Save Changes</button>
            </form>
        </div>
    </div>
</body>

</html>