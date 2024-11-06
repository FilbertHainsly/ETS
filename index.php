<?php 
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_verify($_POST['password'], PASSWORD_DEFAULT);
    $targetDir = "uploads/";

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $filename = basename($_FILES["profile_picture"]["name"]);
        $targetPath = $targetDir . uniqid() . "_" . $filename;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetPath)) {
            $profile_picture = $targetPath;
        }
    }

    $sql = "INSERT INTO players (username, email, password, profile_picture) VALUES ('$username', '$email', '$password', '$profile_picture')";
    if($conn->query($sql) === TRUE){
        header("Location: login.php");
        exit();
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="php_style.css">
</head>
<body>
  <div class="registration-container">
    <form action="index.php" method="post" enctype="multipart/form-data" class="registration-form">
      <h2>Registration Account</h2>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="profile_picture">Profile Picture</label>
      <input type="file" name="profile_picture" accept="image/*"><br>

      <button type="submit" class="register-button">Register</button>
      <p>Already have an account? <a href="login.php">login now!</a></p>
    </form>
  </div>
