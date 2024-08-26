<?php
session_start();

if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

include "conn.php";

// Requête pour obtenir les étudiants non archivés
$sql_non_archived_students = "SELECT * FROM etudiant WHERE archive = 0";
$result_non_archived_students = $conn->query($sql_non_archived_students);

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Étudiants Non Archivés</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">

</head>
<body>
    <div id="content" class="content__data">
        <button class='action-btn archive-btn'><a href='./admin_dashboard.php'>RETOUR</a></button>
        <section class="middle-section">
            <div class="whitebox-table">
                <div class="table__title__row">
                    <div class="title">Liste des Étudiants Non Archivés</div>
                </div>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Niveau</th>
                            <th>Matricule</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result_non_archived_students->num_rows > 0) {
                            while ($row = $result_non_archived_students->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['nom'] . "</td>";
                                echo "<td>" . $row['prenom'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['matricule'] . "</td>";
                                echo "<td>" . $row['niveau'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>Aucun étudiant non archivé trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>
</html>
