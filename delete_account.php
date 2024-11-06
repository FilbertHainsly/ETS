<?php
include 'connection.php';
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
    $query = "SELECT profile_picture FROM players WHERE username='$username'";
    $result = $conn->query($query);
    $picturePath = null;

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $picturePath = $user['profile_picture'];
    }

    $sql = "DELETE FROM players WHERE username='$username'";
    if ($conn->query($sql) === TRUE) {
        if ($picturePath && file_exists($picturePath)) {
            unlink($picturePath);
        }

        session_unset();
        session_destroy();
        echo "<script>alert('Your account has been deleted.'); window.location.href='index.php';</script>";
    } else {
        echo "Error deleting account: " . $conn->error;
    }

    $conn->close();
}
?>
