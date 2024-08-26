<?php

function genererMatricule($nom, $prenom) {
    $prefix = strtoupper(substr($nom, 0, 2) . substr($prenom, 0, 2)); // Ex: ABXY
    $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // 0000 - 9999
    return $prefix . $uniqueNumber;
}

include "conn.php";

$response = array(); // Crée un tableau pour la réponse

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $conn->real_escape_string($_POST['name']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $telephone = $conn->real_escape_string($_POST['tel']);
    $date_naissance = $conn->real_escape_string($_POST['date']);
    $niveau = $conn->real_escape_string($_POST['niveau']);

    $checkEmailQuery = "SELECT id FROM etudiant WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = "Erreur : L'email est déjà utilisé.";
    } else {
        $matricule = genererMatricule($nom, $prenom);

        $sql = "INSERT INTO etudiant (nom, prenom, email, telephone, date_naissance, niveau, matricule) 
                VALUES ('$nom', '$prenom', '$email', '$telephone', '$date_naissance', '$niveau', '$matricule')";

        if ($conn->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = "L'étudiant a été ajouté avec succès.";
            $response['matricule'] = $matricule; // Ajoute le matricule à la réponse
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur : " . $conn->error;
        }
    }

    $conn->close();

    // Retourne la réponse en JSON
    echo json_encode($response);
}
