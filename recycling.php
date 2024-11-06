<?php
// Include database connection
include 'db_connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = 1;  // Hardcoded user ID for now; replace with session management later
    $item_type = $_POST['itemType'];
    $quantity = $_POST['quantity'];

    // Price per kg
    $price_per_kg = [
        'plastic' => 20,
        'paper' => 10,
        'glass' => 15,
        'metal' => 25,
        'electronics' => 30
    ];

    // Calculate price and points awarded
    if (array_key_exists($item_type, $price_per_kg)) {
        $price = $price_per_kg[$item_type] * $quantity;
        $points_awarded = $quantity * 10;  // Example: 10 points per kg

        // Insert recycling record into the recycling_data table
        $sql = "INSERT INTO recycling_data (user_id, item_type, quantity, points_earned)
                VALUES ('$user_id', '$item_type', '$quantity', '$points_awarded')";

        if ($conn->query($sql) === TRUE) {
            // Update total recycled and reward points in the users table
            $update_sql = "UPDATE users SET total_recycled = total_recycled + $quantity, reward_points = reward_points + $points_awarded WHERE id = $user_id";
            $conn->query($update_sql);

            echo "Recycling data added successfully!";
            echo "You earned $points_awarded points for recycling $quantity kg of $item_type!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Invalid item type selected.";
    }
}

$conn->close();
?>
