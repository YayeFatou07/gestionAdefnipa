<?php
 include "conn.php";

// Sanitize and validate the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Préparer la requête DELETE
    $stmt = $conn->prepare("DELETE FROM etudiant WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Succès de la suppression
        $message = "L'étudiant a été supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression: " . $conn->error;
    }

    $stmt->close();
} else {
    $message = "ID invalide.";
}

// Fermer la connexion
$conn->close();

// Redirection vers la page liste_etudiant.php avec un message
header("Location: liste_etudiant.php?message=" . urlencode($message));
exit();
