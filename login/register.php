<?php include '../connect.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="../index/image/logo2.png" type="image/png">
    <link rel="stylesheet" href="register.css">
</head>

<body>

    <?php
    $message = "";

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $user_type = $_POST['user_type'];

        // Check if passwords match
        if ($password !== $confirm_password) {
            $message = "Passwords do not match. Please try again.";
        } else {
            // Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query based on user type
            if ($user_type === "artist") {
                $sql = "INSERT INTO Artist (artist_fname, artist_lname, artist_username, artist_password, admin_id) VALUES (:firstname, :lastname, :username, :password, 1)";
            } elseif ($user_type === "audience") {
                $sql = "INSERT INTO Audience (audience_fname, audience_lname, audience_username, audience_password) VALUES (:firstname, :lastname, :username, :password)";
            }
            // Check if the username already exists in Artist or Audience tables
            $checkUsernameSql = "SELECT artist_username AS username FROM Artist WHERE artist_username = :username 
UNION 
SELECT audience_username AS username FROM Audience WHERE audience_username = :username";
            $stmt = $pdo->prepare($checkUsernameSql);
            $stmt->execute(['username' => $username]);

            if ($stmt->rowCount() > 0) {
                $message = "Username is already taken. Please choose a different username.";
            } else {
                try {
                    $stmt = $pdo->prepare($sql);

                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashed_password); // Store hashed password

                    if ($stmt->execute()) {
                        header("Location: login.php");
                        exit();
                    } else {
                        $message = "Error: Could not execute the query.";
                    }
                } catch (PDOException $e) {
                    $message = "Error: " . $e->getMessage();
                }
            }
        }
    }
    ?>

    <div class="register-container">
        <h1>REGISTER</h1>

        <!-- Display error message if needed -->
        <?php if ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="firstname">Firstname</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Lastname</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Password Confirm</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label>Type</label>
            <div class="radio-group">
                <input type="radio" id="artist" name="user_type" value="artist" required>
                <label for="artist">Artist</label>
                <input type="radio" id="audience" name="user_type" value="audience" required>
                <label for="audience">Audience</label>
            </div>

            <button type="submit">Sign-up</button>
        </form>
        <p class="login-text">Already have an account? <a href="login.php">Log-in</a></p>
    </div>

</body>

</html>