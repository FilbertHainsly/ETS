<?php
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM players WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['password']) {
            $_SESSION['username'] = $user['username'];
            setcookie("username", $user['username'], time() + (86400), "/");
            header("Location: main.html");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "No user found with this username.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta username="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="php_style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST" action="login.php">
            <div id="error-message"></div>
            <h2>Login</h2>
            <label>Username:</label>
            <input type="text" name="username" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
