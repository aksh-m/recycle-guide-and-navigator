<?php 
session_start();
include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Hash the password
    

    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE name='$username' OR email='$email'";
    $check_result = mysqli_query($conn, $check_query);

    // Check if query execution was successful
    if ($check_result) {
        if (mysqli_num_rows($check_result) > 0) {
            // If username or email already exists
            echo "<script>alert('Username or Email already exists. Please try again.'); window.location.href = 'register.html';</script>";
        } else {
            // Insert the new user into the database
            $insert_query = "INSERT INTO users (id, email, name, password) VALUES ('$name', '$email', '$username', '$password')";
            if (mysqli_query($conn, $insert_query)) {
                echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'login.html';</script>";
            } else {
                // Handle query error
                echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
            }
        }
    } else {
        // Handle check query error
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
