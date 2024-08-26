<?php
session_start();

if ($_SESSION['role'] != 2) {
    header("Location: index.php");
    exit();
}
//echo "Bienvenue, " . $_SESSION['nom'] . " " . $_SESSION['prenom'] . "! Vous êtes connecté en tant qu'utilisateur.";
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
                    <li >
                        <a href="./user_dashboard.php">
                            <i class="iconview ico__dashboard"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    
                    <li class="active">
                        <a href="./etudiant.php">
                            <i class="iconview ico__clients"></i>
                            <span>Étudiant</span>
                        </a>
                    </li>
                    <li>
                        <a href="./deconnexion.php">
                            <i class="iconview ico__invoices"></i>
                            <span>Deconnexion</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content" class="content__data">
            <!-- Middle Section -->
            <section class="middle-section">
            <div class="middle__title__row">
                    <div class="title__left__col">
                    <img src="./images/images.png" alt="Logo" style="width: 300px; height: auto;">
                    <fieldset>
                        <h1 class='title'><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom'] . " 👋"; ?></h1>
                    </fieldset>    
                    </div>
                </div>
                <div class="middle__content__row">
                    <div class="middle__leftside__column">

                        <!-- Modal to Add User -->
                       <div class="container">
                            <button id="openModalBtn" class="open-modal-btn">Ajouter un Etudiants</button>

                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Ajouter un Etudiants</h2>
                                    <form id="userForm" action="D_etudiant.php" method="post">
                                        <div class="form-group">
                                            <label for="name">Nom :</label>
                                            <input type="text" id="name" name="name" required>
                                            <label for="prenom">Prénom :</label>
                                            <input type="text" id="prenom" name="prenom" required>
                                            <label for="email">Email :</label>
                                            <input type="email" id="email" name="email" required>
                                            <label for="tel">Téléphone :</label>
                                            <input type="number" id="tel" name="tel" required>
                                            <label for="date">Date de naissance :</label>
                                            <input type="date" id="date" name="date" required max="2005-12-31">
                                            <label for="niveau">Niveau :</label>
                                            <select id="niveau" name="niveau">
                                                <option value="L1">L1</option>
                                                <option value="L2">L2</option>
                                                <option value="L3">L3</option>
                                                <option value="M1">M1</option>
                                                <option value="M2">M2</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="submit-btn">Ajouter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
 <!-- Success Modal -->
                       <div id="successModal" class="modal" style="display:none;">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <p id="modalMessage"></p>
                            </div>
                        </div>

                       <!-- User Table -->
                        <!-- User Table -->
                        <div class="whitebox-table">
                            <div class="table__title__row">
                                <div class="title">Liste des Étudiants</div>
                            </div>
                            <?php
                                 include "conn.php";

                                // Exécuter une requête SQL pour récupérer les données non archivées
                                $sql = "SELECT id, nom, prenom, date_naissance, email, telephone, niveau, matricule 
                                        FROM etudiant 
                                        WHERE archive = 0";
                                $result = $conn->query($sql); // query permet de récupérer une requête

                                echo "<table class='user-table'>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Date de naissance</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Niveau</th>
                                        <th>Matricule</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                                
                                // Récupérer les données 
                                if ($result->num_rows > 0) {
                                    // Afficher les données pour chaque ligne
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['nom'] . "</td>";
                                        echo "<td>" . $row['prenom'] . "</td>";
                                        echo "<td>" . $row['date_naissance'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['telephone'] . "</td>";
                                        echo "<td>" . $row['niveau'] . "</td>";         
                                        echo "<td>" . $row['matricule'] . "</td>";
                                        echo "<td>
                                                <button class='action-btn delete-btn' onclick=\"confirmAction('supprimer', " . $row['id'] . ")\"><a href='suppr.php?id=" . $row['id'] . "'>Supprimer</a></button>
                                                <button class='action-btn edit-btn' onclick=\"editUser(" . $row['id'] . ")\">Éditer</button>
                                                <button class='action-btn archive-btn' onclick='confirmAction(\"archiver\", " . $row['id'] . ")'>Archiver</button>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>Pas de résultats</td></tr>";
                                }
                                echo "</tbody></table>";

                                // Fermer la connexion
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
