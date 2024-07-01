<?php
session_start();

$loggedIn = isset($_SESSION['username']);
$city = $loggedIn && isset($_SESSION['city']) ? $_SESSION['city'] : '';

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Data</title>
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

        /* Scroll bar style for news updates */
        #news-updates {
            max-height: 300px;
            /* Set a maximum height for the scroll */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }
    </style>

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
                <h2>Real-Time Data</h2>
                <p>View real-time information such as weather updates and latest news.</p>
            </div>
            <div class="row">
                <!-- Weather Data Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Weather Information</h5>
                            <div id="weather-data">
                                <!-- Weather data will be loaded dynamically here -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Real-Time News Updates Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Real-Time News Updates</h5>
                            <ul id="news-updates" class="list-group">
                                <!-- News updates will be loaded dynamically here -->
                            </ul>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to fetch weather data using API
            function fetchWeatherData(city) {
                var apiKey = "9c863cc17741c132a322bcaed8bfb52c";
                var weatherApiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

                $.get(weatherApiUrl, function(data) {
                    // Update weather data in the HTML
                    var weatherHtml = `
                        <p>Temperature: ${data.main.temp} &deg;C</p>
                        <p>Weather: ${data.weather[0].description}</p>
                        <p>Humidity: ${data.main.humidity}%</p>
                    `;
                    $("#weather-data").html(weatherHtml);
                });
            }

            // Function to fetch news articles using News API
            function fetchNewsUpdates() {
                var newsApiKey = "a87a1feb824d4c9a987d5f4647c8049c"; // Replace with your News API key
                var newsApiUrl = `https://newsapi.org/v2/top-headlines?country=us&apiKey=${newsApiKey}`;

                $.get(newsApiUrl, function(data) {
                    // Update news updates in the HTML
                    var newsHtml = "";
                    data.articles.forEach(function(article) {
                        newsHtml += `
                            <li class="list-group-item">
                                <h6>${article.title}</h6>
                                <p>${article.description}</p>
                                <a href="${article.url}" target="_blank" class="btn btn-sm btn-primary">Read More</a>
                            </li>
                        `;
                    });
                    $("#news-updates").html(newsHtml);
                });
            }

            // Call fetchWeatherData function with default city (replace with user's city if available)
            fetchWeatherData("<?php echo $city; ?>");

            // Call fetchNewsUpdates function to fetch and display news articles
            fetchNewsUpdates();
        });
    </script>
</body>

</html>