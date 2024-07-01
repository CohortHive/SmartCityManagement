<?php
session_start();

$loggedIn = isset($_SESSION['username']);
$city = $loggedIn ? $_SESSION['city'] : '';

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}

$server = "localhost";
$username = "root";
$password = ""; // Replace with your actual MySQL password if set
$database = "smart_city_management"; // Correct database name

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$services = [];
$sql = "SELECT * FROM services";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Services</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header,
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 1.5rem;
        }

        .header a,
        .footer a {
            color: #ffffff;
            text-decoration: none;
        }

        .header a:hover,
        .footer a:hover {
            text-decoration: underline;
        }

        .header .navbar-brand {
            font-size: 1.75rem;
        }

        .header nav a {
            margin-right: 1rem;
        }

        .footer {
            text-align: center;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .content {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .jumbotron {
            background-color: #343a40;
            color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
        }

        .card {
            margin: 1rem 0;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="index.php"><i class="fas fa-city"></i> Smart City</a>
                <nav>
                    <a href="index.php">Home</a> |
                    <?php if ($loggedIn) : ?>
                        <a href="report_issues.php">Report Issues</a> |
                        <a href="city_services.php">City Services</a> |
                        <a href="realtime_data.php">Real-Time Data</a> |
                        <a href="index.php?logout=true">Logout</a>
                    <?php else : ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="container">
            <div class="jumbotron text-center">
                <h2>City Services</h2>
                <p>Explore the various services provided by the city.</p>
            </div>
            <div class="row">
                <div class="content">
                    <div class="container mt-4">
                        <h1>City Services</h1>
                        <p>List of available city services:</p>
                        <ul>
                            <li>Electricity</li>
                            <li>Water Supply</li>
                            <li>Waste Management</li>
                            <li>Healthcare</li>
                            <li>Public Transport</li>
                            <li>Public Safety</li>
                            <!-- Add more default services as needed -->
                        </ul>
                        <h2>Add New City Service</h2>
                        <form action="submit_service.php" method="POST">
                            <div class="form-group">
                                <label for="serviceName">Service Name:</label>
                                <input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="Enter new service name" required>
                            </div>
                            <div class="form-group">
                                <label for="serviceDescription">Description:</label>
                                <textarea class="form-control" id="serviceDescription" name="serviceDescription" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Service</button>
                            <br>
                        </form>
                    </div>
                    <br>
                </div>
                
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Smart City Management System. All rights reserved.</p>
                <p>
                    Follow us on:
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </p>
            </div>
        </footer>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>