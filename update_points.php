<?php
session_start();
include('db_connection.php');

if (isset($_SESSION['user_id'])) {
    $pointsToAdd = intval($_POST['points']); // Get points to add from AJAX request
    $userId = $_SESSION['user_id'];

    $query = "UPDATE users SET reward_points = reward_points + ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $pointsToAdd, $userId);

    if ($stmt->execute()) {
        $_SESSION['points'] += $pointsToAdd; // Update session points
        echo "Points updated successfully!";
    } else {
        echo "Error updating points: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Error: User not logged in.";
}

mysqli_close($conn);
?>
