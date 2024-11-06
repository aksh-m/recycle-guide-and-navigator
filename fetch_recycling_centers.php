<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = mysqli_real_escape_string($conn, $_POST['city']);

    // Query to fetch recycling centers based on city
    $query = "SELECT name, address, city, pincode, num FROM recycling_centers WHERE city = '$city'";
    $result = mysqli_query($conn, $query);

    $centers = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $centers[] = $row;
        }
    }

    // Return as JSON
    echo json_encode($centers);
}
mysqli_close($conn);
?>
