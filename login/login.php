<?php 
session_start(); 
$_SESSION['user_type'] = 'audience'; 

include '../connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="icon" href="../index/image/logo2.png" type="image/png">
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <?php

    // Initialize a message variable to display errors or success messages
    $message = "";

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $isValidUser = false; // Flag to indicate if the user is authenticated

        // Query to check username and password in Artist table
        $sqlArtist = "SELECT artist_password, artist_id FROM Artist WHERE artist_username = :username";
        $stmtArtist = $pdo->prepare($sqlArtist);
        $stmtArtist->execute(['username' => $username]);
        $artist = $stmtArtist->fetch(PDO::FETCH_ASSOC);

        // Query to check username and password in Audience table
        $sqlAudience = "SELECT audience_password, audience_id FROM Audience WHERE audience_username = :username";
        $stmtAudience = $pdo->prepare($sqlAudience);
        $stmtAudience->execute(['username' => $username]);
        $audience = $stmtAudience->fetch(PDO::FETCH_ASSOC);

        // Hardcoded check for Administrator
        if ($username === 'admin' && $password === '1234') {
            $isValidUser = true;
            $userType = 'admin';
        }

        // Check if user is in the Artist table and validate password
        if ($artist && !$isValidUser) { // Proceed if not already logged in as admin
            if (password_verify($password, $artist['artist_password'])) {
                $isValidUser = true;
                $userType = 'artist';
                $_SESSION['artist_id'] = $artist['artist_id']; // Set artist_id in session
            } else {
                $message = "Password did not match for Artist.";
            }
        }

       // Check if user is in the Audience table and validate password
       if ($audience && !$isValidUser) {
        if (password_verify($password, $audience['audience_password'])) {
            $isValidUser = true;
            $userType = 'audience';
            $_SESSION['audience_id'] = $audience['audience_id']; // Set audience_id in session
            $_SESSION['user_id'] = $_SESSION['audience_id']; // Assign audience_id to user_id after login
        } else {
            $message = "Password did not match for Audience.";
        }
    }
    
        session_regenerate_id(); // Prevent session fixation

        // Store user type in session and set user data
        if ($isValidUser) {
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $userType;


            // If the user is audience, set audience_id and user_id in the session
    if ($userType === 'audience') {
        $_SESSION['user_id'] = $_SESSION['audience_id'];
    }

    // Debugging print statement to verify session data
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";


            // Redirect based on user type after login
            if ($userType === 'admin') {
                header("Location: ../profile/admin/admin.php");
            } elseif ($userType === 'artist') {
                header("Location: ../index/index.php");
            } elseif ($userType === 'audience') {
                header("Location: ../index/index.php");
            }
            exit();
        } else {
            $message = "Invalid username or password. Please try again.";
        }
    }
    ?>

    <div class="login-container">
        <h1>LOG-IN</h1>

        <!-- Display error message if login fails -->
        <?php if ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Log In</button>
        </form>
        <p class="signup-text">New for D’Arte? <a href="register.php">Create an account.</a></p>
    </div>

</body>

</html>
