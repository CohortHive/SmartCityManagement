<?php
session_start();

$loggedIn = isset($_SESSION['username']);
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';
$city = $loggedIn ? $_SESSION['city'] : '';

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}

if ($userType != 'provider') {
    header("Location: index.php");
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

$sql = "SELECT * FROM issues WHERE city = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $city);
$stmt->execute();
$result = $stmt->get_result();

$issues = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $issues[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_id']) && isset($_POST['status'])) {
    $issue_id = $_POST['issue_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE issues SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $issue_id);
    $stmt->execute();
    header("Location: view_issues.php");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Issues</title>
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
                        <a href="view_issues.php">View Issues</a> |
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
                <h2>View Reported Issues</h2>
                <p>Manage reported issues in the city.</p>
            </div>
            <?php if (!empty($issues)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Issue Type</th>
                                <th>Description</th>
                                <th>Location</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($issues as $issue): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($issue['id']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['username']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['issue_type']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['description']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['location']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['city']); ?></td>
                                    <td><?php echo htmlspecialchars($issue['status']); ?></td>
                                    <td>
                                        <form action="view_issues.php" method="POST">
                                            <input type="hidden" name="issue_id" value="<?php echo $issue['id']; ?>">
                                            <select name="status" class="form-control">
                                                <option value="Pending" <?php if ($issue['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                                <option value="In Progress" <?php if ($issue['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                                <option value="Resolved" <?php if ($issue['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                                            </select>
                                            <button type="submit" class="btn btn-custom btn-sm mt-1">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    No issues reported.
                </div>
            <?php endif; ?>
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
