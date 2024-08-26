<?php
session_start();

include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Vérifier si le mot de passe est correct
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['role'] = $user['role'];

            // Rediriger l'utilisateur en fonction de son rôle
            if ($user['role'] == 1) {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            // Authentification échouée, afficher un message d'erreur
        $_SESSION['error'] = 'Mot de passe incorrect';
        header("Location:index.php ");
        exit();
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
             // Authentification échouée, afficher un message d'erreur
             $_SESSION['error'] = 'Aucun utilisateur trouvé avec cet email';
             header("Location:index.php ");
             exit();
    }

    $stmt->close();
    $conn->close();
}
?>
