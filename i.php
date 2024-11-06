<?php
// Assuming $customer_id, $amount_recycled, and $points_awarded are obtained from a form
$sql = "INSERT INTO recycle_records (customer_id, amount_recycled, recycling_date, points_awarded)
        VALUES ('$customer_id', '$amount_recycled', NOW(), '$points_awarded')";

if ($conn->query($sql) === TRUE) {
    // Update total_recycled and reward_points in customers table
    $update_sql = "UPDATE customers SET total_recycled = total_recycled + $amount_recycled, reward_points = reward_points + $points_awarded WHERE customer_id = $customer_id";
    $conn->query($update_sql);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>
