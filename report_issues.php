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

$issueTypes = [];
$sql = "SELECT * FROM issue_types";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $issueTypes[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_type = $_POST['issue_type'];
    $new_issue_type = trim($_POST['new_issue_type']);
    $description = $_POST['description'];
    $location = $_POST['location'];
    $username = $_SESSION['username'];

    if (!empty($new_issue_type)) {
        $issue_type = $new_issue_type;
        $stmt = $conn->prepare("INSERT INTO issue_types (type) VALUES (?)");
        $stmt->bind_param("s", $new_issue_type);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("INSERT INTO issues (username, issue_type, description, location, city) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $issue_type, $description, $location, $city);

    if ($stmt->execute()) {
        $success_message = "Issue reported successfully.";
    } else {
        $error_message = "Error reporting issue: " . $stmt->error;
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
    <title>Report Issues</title>
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
                    <?php if ($loggedIn): ?>
                        <a href="report_issues.php">Report Issues</a> |
                        <a href="city_services.php">City Services</a> |
                        <a href="realtime_data.php">Real-Time Data</a> |
                        <a href="index.php?logout=true">Logout</a>
                    <?php else: ?>
                        <a href="login.php">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <div class="content">
        <div class="container">
            <div class="jumbotron text-center">
                <h2>Report an Issue</h2>
                <p>Help us improve the city by reporting issues.</p>
            </div>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <form action="report_issues.php" method="POST">
                <div class="form-group">
                    <label for="issue_type">Issue Type:</label>
                    <select class="form-control" id="issue_type" name="issue_type" required>
                        <?php foreach ($issueTypes as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['type']); ?>"><?php echo htmlspecialchars($type['type']); ?></option>
                        <?php endforeach; ?>
                        <option value="" disabled>-- OR --</option>
                        <option value="other">Other (Specify Below)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="new_issue_type">New Issue Type (if not listed above):</label>
                    <input type="text" class="form-control" id="new_issue_type" name="new_issue_type">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <button type="submit" class="btn btn-custom">Submit</button>
            </form>
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
