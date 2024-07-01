<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection setup (replace with your actual database credentials)
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

    // Escape user inputs for security
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $city = $conn->real_escape_string($_POST['city']);
    $user_type = $conn->real_escape_string($_POST['user_type']);

    // Initialize variables for optional fields
    $services_offered = '';
    $work_email = '';

    // Check if user type is "provider" and set optional fields
    if ($user_type == 'provider') {
        $services_offered = $conn->real_escape_string($_POST['services_offered']);
        $work_email = $conn->real_escape_string($_POST['work_email']);
    }

    // Insert user into database
    $sql = "INSERT INTO users (username, password, city, user_type, services_offered, work_email) 
            VALUES ('$username', '$password', '$city', '$user_type', '$services_offered', '$work_email')";

    if ($conn->query($sql) === TRUE) {
        // Registration successful, redirect to home page
        $_SESSION['username'] = $username; // Store username in session for future use
        header("Location: index.php");
        exit();
    } else {
        // Registration failed, show error message (optional)
        $registration_error = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .register-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2 class="mb-4">Register</h2>
        <?php if (isset($registration_error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $registration_error; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="user_type">User Type:</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="user">Regular User</option>
                    <option value="provider">Provider</option>
                </select>
            </div>
            <div id="provider_fields" style="display: none;">
                <div class="form-group">
                    <label for="services_offered">Services Offered:</label>
                    <textarea class="form-control" id="services_offered" name="services_offered" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="work_email">Work Email:</label>
                    <input type="email" class="form-control" id="work_email" name="work_email">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <hr>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>

    <script>
        // Show/hide optional fields based on user type selection
        document.getElementById('user_type').addEventListener('change', function() {
            var providerFields = document.getElementById('provider_fields');
            if (this.value === 'provider') {
                providerFields.style.display = 'block';
            } else {
                providerFields.style.display = 'none';
            }
        });
    </script>
</body>
</html>
