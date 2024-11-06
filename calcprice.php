<?php
// Include database connection
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = 1;  // For now, hardcode user ID. Later, you can manage login sessions.
    $item_type = $_POST['itemType'];
    $quantity = $_POST['quantity'];

    // Price per kg (you can modify this as needed)
    $price_per_kg = [
        'plastic' => 10,
        'paper' => 5,
        'glass' => 8,
        'metal' => 15,
        'electronics' => 20
    ];

    $price = $price_per_kg[$item_type] * $quantity;
    $points_awarded = $quantity * 10;  // Example: 10 points per kg

    // Insert recycling record into database
    $sql = "INSERT INTO recycling_records (user_id, item_type, quantity, price, points_awarded)
            VALUES ('$user_id', '$item_type', '$quantity', '$price', '$points_awarded')";

    if ($conn->query($sql) === TRUE) {
        // Update total recycled and reward points in the users table
        $update_sql = "UPDATE users SET total_recycled = total_recycled + $quantity, reward_points = reward_points + $points_awarded WHERE id = $user_id";
        $conn->query($update_sql);

        echo "Recycling data added successfully!";
        echo "You earned $points_awarded points for recycling $quantity kg of $item_type!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
