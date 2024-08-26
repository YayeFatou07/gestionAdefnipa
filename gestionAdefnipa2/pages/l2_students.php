<?php
include "../conn.php";

// Requête pour obtenir les étudiants en L1
$sql = "SELECT * FROM etudiant WHERE niveau = 'L2' ORDER BY (modul1 + modul2 + modul3 + modul4) / 4 DESC ";
$result = $conn->query($sql);

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Étudiants L1</title>
    <link href="../css/style.css" type="text/css" rel="stylesheet">
</head>
<body>              
          
        <!-- Sidebar -->
        <!-- Include your sidebar code here -->

        <!-- Page Content -->
        <div id="content" class="content__data">
        <button class='action-btn archive-btn'><a href='../admin_dashboard.php'>RETOUR</a></button>
            <section class="middle-section">
                <div class="whitebox-table">
                    <div class="table__title__row">
                        <div class="title">Liste des Étudiants en Licence 2</div>
                    </div>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de Naissance</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Niveau</th>
                                <th>Matricule</th>
                                <th>Moyenne</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['date_naissance']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['telephone']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['niveau']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['matricule']) . "</td>";
                                    echo "<td>" . htmlspecialchars(($row['modul1']+$row['modul2']+$row['modul3']+$row['modul4']) /4) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['statut']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>Aucun étudiant trouvé</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
