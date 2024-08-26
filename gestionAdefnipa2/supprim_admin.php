<?php
 include "conn.php";

// Sanitize and validate the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        // Succès de la suppression
        $message = "L'administrateur a été supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression: " . $conn->error;
    }

    $stmt->close();
} else {
    $message = "ID invalide.";
}

// Fermer la connexion
$conn->close();

// Redirection vers la page admin.php avec un message
header("Location: admin.php?message=" . urlencode($message));
exit();
