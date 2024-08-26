<?php
session_start();

include "conn.php";

// Gère les requêtes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $response = array();

    if (isset($_POST['etudiant_id'])) {
        $etudiant_id = intval($_POST['etudiant_id']);
        $module1 = $_POST['note1'];
        $module2 = $_POST['note2'];
        $module3 = $_POST['note3'];
        $module4 = $_POST['note4'];

        // Calculer la moyenne
        $average = ($module1 + $module2 + $module3 + $module4) / 4;

        // Déterminer le statut basé sur la moyenne
        $status = ($average >= 10) ? 'admis' : 'recalé';

        $sql = "UPDATE etudiant SET modul1 = ?, modul2 = ?, modul3 = ?, modul4 = ?, statut = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiisi", $module1, $module2, $module3, $module4, $status, $etudiant_id);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "Les notes ont été ajoutées avec succès.";
            // Redirection vers la page notes.php
            header('Location: notes.php'); 
            exit();
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur lors de l'ajout des notes : " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
    echo json_encode($response);
    exit();
}
?>
