<?php
session_start();

// Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}
include "conn.php";

// Sélectionner les etudiants et les ordonner
$sql = "SELECT id, nom, prenom, date_naissance, email, niveau, modul1, modul2,modul3,modul4,statut FROM etudiant  
ORDER BY 
            CASE 
                WHEN statut = 'admis' THEN 1
                WHEN statut = 'recalé' THEN 2
                WHEN statut = 'encours' THEN 3
                ELSE 4
            END";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Gestion Dashboard</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="dashboard__wrapper">
        <!-- Sidebar -->
        <div id="myNav" class="left__sidebar overlay">
            <div class="sidebar__header">
                <a class="logo" href="Dashboard.html">
                    <img src="./images/Logo_Marketing_Agency_Digital.-removebg-preview.png" alt="logo" />
                </a>
            </div>
            <div class="slidebar__middle">
                <ul class="slidebar__list">
                    <li><a href="./admin_dashboard.php"><i class="iconview ico__dashboard"></i><span>Accueil</span></a></li>
                    <li><a href="./admin.php"><i class="iconview ico__invoices"></i><span>Administration</span></a></li>
                    <li class="active"><a href="./liste_etudiant.php"><i class="iconview ico__clients"></i><span>Étudiant</span></a></li>
                    <li><a href="./deconnexion.php"><i class="iconview ico__invoices"></i><span>Déconnexion</span></a></li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content" class="content__data">
            <!-- Middle Section -->
            <section class="middle-section">
                <div class="middle__title__row">
                    <div class="title__left__col">
                        <?php echo "<h1 class='title'> " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . " 👋 </h1>"; ?>
                    </div>
                </div>
                <div class="middle__content__row">
                    <div class="middle__leftside__column">

                        <!-- User Table -->
                        <div class="whitebox-table">
                            <div class="table__title__row">
                                <div class="title">Liste des Etudiants</div>
                            </div>
                            <?php
                            echo "<table class='user-table'>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Niveau</th>
                                        <th>Module1</th>
                                        <th>Module2</th>
                                        <th>Module3</th>
                                        <th>Module4</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $statusClass = '';
                                        if ($row['statut'] == 'admis') {
                                            $statusClass = 'admis';
                                        } elseif ($row['statut'] == 'recalé') {
                                            $statusClass = 'recalé';
                                        } elseif ($row['statut'] == 'encours') {
                                            $statusClass = 'encours';
                                        } 
                                    echo "<tr class='$statusClass' > ";
                                    echo "<td>" . $row['nom'] . "</td>";
                                    echo "<td>" . $row['prenom'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['niveau'] . "</td>";
                                    echo "<td>" . $row['modul1'] . "</td>";
                                    echo "<td>" . $row['modul2'] . "</td>";
                                    echo "<td>" . $row['modul3'] . "</td>";
                                    echo "<td>" . $row['modul4'] . "</td>";
                                    echo "<td>" . $row['statut'] . "</td>";
                                    echo "<td>
                                           
                                            <button class='action-btn archive-btn'><a href='ajoutNotes.php?etudiant_id=" . $row['id'] . "'>Ajout notes</a></button>
                                            <button class='action-btn archive-btn'><a href='pages/bulletin.php?etudiant_id=" . $row['id'] . "'>Bulletin</a></button>

                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>Pas de résultats</td></tr>";
                            }
                            echo "</tbody></table>";

                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="./js/script.js"></script>
</body>
</html>
