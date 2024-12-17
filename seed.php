<?php

require 'vendor/autoload.php';

use Faker\Factory;

$host = 'localhost';
$db = 'ux';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

$faker = Factory::create();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tableCheckQuery = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0,
    image_url VARCHAR(2083) NOT NULL
)";
if (!$conn->query($tableCheckQuery)) {
    die("Error creating table: " . $conn->error);
}

for ($i = 0; $i < 1000; $i++) {
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $name = $faker->word;
        $description = $faker->sentence;
        $price = $faker->randomFloat(2, 5, 100);
        $image_url = $faker->imageUrl(300, 300, null, true, $name);
        $stmt->bind_param("ssds", $name, $description, $price, $image_url);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

$conn->close();

echo "Database seeded successfully!";