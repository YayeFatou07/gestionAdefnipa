<?php
include "conn.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
    
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE admins SET nom = ?, prenom = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nom, $prenom, $email, $id);
    
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Administrateur mis à jour avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $conn->error]);
            }
    
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
        }
        exit();
}

?>

