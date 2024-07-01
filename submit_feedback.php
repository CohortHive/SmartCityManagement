<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_id']) && isset($_POST['feedback'])) {
    $service_id = $_POST['service_id'];
    $feedback = $_POST['feedback'];
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous';

    // Database connection setup (replace with your actual database credentials)
    $server = "localhost";
    $username_db = "root";
    $password = ""; // Replace with your actual MySQL password if set
    $database = "smart_city_management"; // Correct database name

    // Create connection
    $conn = new mysqli($server, $username_db, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert feedback into the database (you can create a feedback table if needed)
    $stmt = $conn->prepare("INSERT INTO feedback (service_id, feedback, username) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $service_id, $feedback, $username);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the services page
    header("Location: city_services.php");
    exit();
} else {
    echo "Invalid request.";
}
?>
