<?php
session_start();

$loggedIn = isset($_SESSION['username']);
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

if (!$loggedIn || $userType !== 'provider') {
    header("Location: login.php");
    exit();
}

// Database connection setup
$server = "localhost";
$username = "root";
$password = ""; // Replace with your actual MySQL password if set
$database = "smart_city_management"; // Correct database name

// Create connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_type = $_POST['service_type'];
    $details = $_POST['details'];
    $status = 'Pending';

    $stmt = $conn->prepare("INSERT INTO services (user_id, service_type, details, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $service_type, $details, $status);

    if ($stmt->execute()) {
        $success_message = "Service added successfully.";
    } else {
        $error_message = "Error adding service: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Add Service</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="add_service.php" method="POST">
            <div class="form-group">
                <label for="service_type">Service Type:</label>
                <input type="text" class="form-control" id="service_type" name="service_type" required>
            </div>
            <div class="form-group">
                <label for="details">Details:</label>
                <textarea class="form-control" id="details" name="details" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Service</button>
        </form>
    </div>
</body>
</html>
