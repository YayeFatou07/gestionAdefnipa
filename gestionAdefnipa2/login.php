<?php

header('Content-Type: application/json'); // Indiquer que la réponse est en JSON

include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO admins (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe, $role);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Administrateur ajouté avec succès.']);
       
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $stmt->error]);
    }

    $stmt->close();
   
}

$conn->close();
