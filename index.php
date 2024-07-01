<?php
session_start();

$loggedIn = isset($_SESSION['username']);
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

if (!$loggedIn) {
    header("Location: index.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart City Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header, .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 1.5rem;
        }
        .header a, .footer a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover, .footer a:hover {
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
        .btn-custom {
            background-color: #343a40;
            color: #ffffff;
            border-radius: 0.5rem;
        }
        .btn-custom:hover {
            background-color: #495057;
            color: #ffffff;
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
                    <?php if ($loggedIn && $userType === 'user'): ?>
                        <a href="report_issues.php">Report Issues</a> |
                    <?php elseif ($loggedIn && $userType === 'provider'): ?>
                        <a href="city_services.php">Manage Services</a> |
                    <?php endif; ?>
                    <a href="realtime_data.php">Real-Time Data</a> |
                    <a href="index.php?logout=true">Logout</a>
                </nav>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="container">
            <div class="jumbotron text-center">
                <h2>Welcome to the Smart City Management System</h2>
                <?php if ($loggedIn): ?>
                    <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You are logged in as a <?php echo htmlspecialchars($userType); ?>.</p>
                <?php else: ?>
                    <p>Please <a href="login.php" class="btn btn-custom">Login</a> to access the system.</p>
                <?php endif; ?>
            </div>
            <div class="row">
                <?php if ($userType === 'user'): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Report Issues</h5>
                                <p class="card-text">Report and track issues in your city to help us improve the infrastructure and services.</p>
                                <a href="report_issues.php" class="btn btn-custom btn-block">Report Issues</a>
                            </div>
                        </div>
                    </div>
                <?php elseif ($userType === 'provider'): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Manage Services</h5>
                                <p class="card-text">Manage the services you offer in the smart city and collect user feedback.</p>
                                <a href="city_services.php" class="btn btn-custom btn-block">Manage Services</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Real-Time Data</h5>
                            <p class="card-text">View real-time data about city operations and infrastructure.</p>
                            <a href="realtime_data.php" class="btn btn-custom btn-block">Real-Time Data</a>
                        </div>
                    </div>
                </div>
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
    <script>
        $(document).ready(function(){
            $("a").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function(){
                        window.location.hash = hash;
                    });
                }
            });
        });
    </script>
</body>
</html>
