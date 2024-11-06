<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch user details
    $query = "SELECT * FROM users WHERE name='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    // Check if a matching user exists
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id']; // Store user ID for point updates
        $_SESSION['username'] = $user['name'];
        $_SESSION['points'] = $user['reward_points'];
        
        header("Location: index.php"); // Redirect to home page
    } else {
        echo "<script>alert('Invalid username or password. Please try again.'); window.location.href = 'login.html';</script>";
    }
}

mysqli_close($conn);
?>
