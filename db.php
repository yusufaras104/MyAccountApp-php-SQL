<?php
$host = 'localhost:2022';
$dbname = 'account_app';
$user = 'root';
$password = 'Londonschool@@321';

$dsn = "mysql:host=$host;dbname=$dbname";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
  die("Error connecting to database: " . $e->getMessage());
}