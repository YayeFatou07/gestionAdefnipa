<?php
 include "conn.php";

// Sanitize and validate the ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM etudiant WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "L'étudiant a été supprimé avec succès";
    } else {
        echo "Erreur lors de la suppression: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "ID invalide.";
}

$conn->close();

// Redirect to the main page
header("Location: etudiant.php");
exit();
?>