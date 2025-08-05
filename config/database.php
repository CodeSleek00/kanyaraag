<?php
$host = 'localhost';
$dbname = 'u298112699_kanyaraag';
$username = 'u298112699_kanya_raag';
$password = 'Divyasingh12';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?> 