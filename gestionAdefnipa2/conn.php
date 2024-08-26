<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'Admin_management';

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die('Échec de la connexion : ' . $conn->connect_error);
}

?>
