<?php
header('Content-Type: application/json');

// Database connection
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'admin_management';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Retrieve data from POST
$id = isset($_POST['index']) ? intval($_POST['index']) : 0;
$nom = isset($_POST['nom']) ? $conn->real_escape_string($_POST['nom']) : '';
$prenom = isset($_POST['prenom']) ? $conn->real_escape_string($_POST['prenom']) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$niveau = isset($_POST['niveau']) ? $conn->real_escape_string($_POST['niveau']) : '';

if (empty($id) || empty($nom) || empty($prenom) || empty($email) || empty($niveau)) {
    echo json_encode(['success' => false, 'message' => 'Tous les champs doivent être remplis.']);
    exit();
}

// Prepare SQL query
$sql = "UPDATE etudiant SET nom=?, prenom=?, email=?, niveau=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssi', $nom, $prenom, $email, $niveau, $id);

// Execute query
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Étudiant mis à jour avec succès.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'étudiant.']);
}

$stmt->close();
$conn->close();