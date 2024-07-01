<?php
// Handle form submission for adding new city services

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $serviceName = trim($_POST['serviceName']);
    $serviceDescription = trim($_POST['serviceDescription']);

    // Validate and process data (you can add more validation as needed)

    // For demonstration, you can simply print the submitted data
    echo "<h2>Service Added Successfully!</h2>";
    echo "<p><strong>Service Name:</strong> " . htmlspecialchars($serviceName) . "</p>";
    echo "<p><strong>Description:</strong> " . htmlspecialchars($serviceDescription) . "</p>";

    // TODO: Save the service name and description to a database or send it via email
} else {
    // Handle invalid requests here
    echo "<h2>Error: Invalid Request</h2>";
}
?>
