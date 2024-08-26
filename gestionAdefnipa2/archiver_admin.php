<?php
 include "conn.php";
// Sanitize and validate the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Préparer la déclaration UPDATE pour archiver
    $stmt = $conn->prepare("UPDATE admins SET archived = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Exécuter la requête
    if ($stmt->execute()) {
        $message = "L'administrateur a été archivé avec succès.";
    } else {
        $message = "Erreur lors de l'archivage: " . $conn->error;
    }

    $stmt->close();
} else {
    $message = "ID invalide.";
}

// Fermer la connexion
$conn->close();

// Redirection vers admin.php avec un message
header("Location: admin.php?message=" . urlencode($message));
exit();
?>
