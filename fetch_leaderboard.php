<?php
include 'db_connection.php';

// Define the query
$sql = "SELECT name, reward_points FROM users ORDER BY reward_points DESC LIMIT 3";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result === false) {
    // If the query failed, display the error message and stop execution
    die("Error executing query: " . mysqli_error($conn));
}

// Check if there are any rows in the result
if (mysqli_num_rows($result) > 0) {
    echo '<ol>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li>' . $row['name'] . ' - ' . $row['reward_points'] . ' points</li>';
    }
    echo '</ol>';
} else {
    echo 'No users to display';
}

// Close the database connection
mysqli_close($conn);
?>

