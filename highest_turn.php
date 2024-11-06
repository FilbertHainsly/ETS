<?php
include 'connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $turns = isset($_GET['turns']) ? intval($_GET['turns']) : 0;
    $username = $_SESSION['username'];

    $sql = "SELECT highest_turn FROM players WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $currentHighestTurn = $user['highest_turn'];

        if ($turns < $currentHighestTurn || $currentHighestTurn == 0) {
            $sqlUpdate = "UPDATE players SET highest_turn = $turns WHERE username='$username'";
            if ($conn->query($sqlUpdate) === TRUE) {
                echo json_encode(['message' => 'Highest turn updated successfully.']);
            } else {
                echo json_encode(['message' => 'Error updating highest turn: ' . $conn->error]);
            }
        } else {
            echo json_encode(['message' => 'Current turns are not lower than the highest turn.']);
        }
    } else {
        echo json_encode(['message' => 'User not found.']);
    }

    $conn->close();
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
?>
