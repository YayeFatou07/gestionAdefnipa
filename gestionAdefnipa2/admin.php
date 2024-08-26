<?php
session_start();
// Vérifier si l'utilisateur est un administrateur
if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

// Afficher les messages s'ils existent
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>alert('$message');</script>";
}

include "conn.php";


// Initialisation de la variable de recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construire la requête SQL
$sql = "SELECT id, nom, prenom, email FROM admins WHERE 1";
if (!empty($search)) {
    $search = $conn->real_escape_string($search); // Sécuriser la recherche
    $sql .= " AND (nom LIKE '%$search%' OR prenom LIKE '%$search%' OR email LIKE '%$search%')";
}

$result = $conn->query($sql);


// Fermer la connexion
$conn->close();
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
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="dashboard__wrapper">
        <!-- Sidebar -->
        <div id="myNav" class="left__sidebar overlay">
            <div class="sidebar__header">
                    <img src="./images/Logo_Marketing_Agency_Digital.-removebg-preview.png" alt="logo" />
            </div>
            <div class="slidebar__middle">
                <ul class="slidebar__list">
                    <li>
                        <a href="./admin_dashboard.php">
                            <i class="iconview ico__dashboard"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="./admin.php">
                            <i class="iconview ico__invoices"></i>
                            <span>Administration</span>
                        </a>
                    </li>
                    <li>
                        <a href="./liste_etudiant.php">
                            <i class="iconview ico__clients"></i>
                            <span>Étudiant</span>
                        </a>
                    </li>
                    <li>
                        <a href="./deconnexion.php">
                            <i class="iconview ico__invoices"></i>
                            <span>Déconnexion</span>
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
                    <img src="./images/2.png" alt="Logo" style="width: 300px; height: auto;">
                    <fieldset>
                        <h1 class='title'><?php echo $_SESSION['prenom'] . " " . $_SESSION['nom'] . " 👋"; ?></h1>
                    </fieldset>    
                    </div>
                </div>
                <div class="search-wrapper">
                    <form method="GET" action="admin.php" class="search-form">
                       <input type="text" name="search" placeholder="Rechercher par nom, prénom ou email..." class="search-input" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                       <button type="submit" class="search-btn">🔍</button>
                    </form>
                </div>
                
                <div class="middle__content__row">
                    <div class="middle__leftside__column">

                        <!-- Modale pour l'ajout d'un administrateur -->
                        <div class="container">
                            <button id="openModalBtn" class="open-modal-btn">Ajouter un Administrateur</button>
                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Ajouter un Administrateur</h2>
                                    <form id="userForm" method="POST" action="login.php">
                                        <div class="form-group">
                                            <label for="nom">Nom :</label>
                                            <input type="text" id="nom" name="nom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="prenom">Prénom :</label>
                                            <input type="text" id="prenom" name="prenom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email :</label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mot_de_passe">Mot de passe :</label>
                                            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Rôle :</label>
                                            <select id="role" name="role" required>
                                                <option value="2">Utilisateur</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="submit-btn">Ajouter</button>
                                    </form>
                                </div>
                            </div>
                        </div>



                        <div class="container">
                            <div id="editModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Éditer un Administrateur</h2>
                                    <form id="editForm" method="POST" action="editer_admin.php">
                                        <input type="hidden" id="edit_id" name="edit_id">
                                        <div class="form-group">
                                            <label for="edit_name">Nom :</label>
                                            <input type="text" id="edit_name" name="nom" required>
                                            <label for="edit_prenom">Prénom :</label>
                                            <input type="text" id="edit_prenom" name="prenom" required>
                                            <label for="edit_email">Email :</label>
                                            <input type="email" id="edit_email" name="email" required>
                                            
                                        </div>
                                        <button type="submit" class="submit-btn">Mettre à jour</button>
                                    </form>
                                    <div id="editResponseMessage"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des administrateurs-->
                <div class="whitebox-table">
                    <div class="table__title__row">
                        <div class="title">Liste des Administrateurs</div>
                    </div>
                    <table class='user-table'>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                        <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td>
                                            <button class='action-btn delete-btn' onclick="confirmAdminAction('supprimer', <?php echo $row['id']; ?>)"><i class="fa-solid fa-trash-can"></i></button>
                                            <button class='action-btn archive-btn' onclick="confirmAdminAction('archiver', <?php echo $row['id']; ?>)"><i class="fa-regular fa-file-zipper"></i></button>
                                            <button class='action-btn edit-btn'
                                                data-id="<?php echo $row['id']; ?>"
                                                data-nom="<?php echo htmlspecialchars($row['nom']); ?>"
                                                data-prenom="<?php echo htmlspecialchars($row['prenom']); ?>"
                                                data-email="<?php echo htmlspecialchars($row['email']); ?>">
                                                <i class='fa-solid fa-pen-to-square'></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan='5'>Pas de résultats</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <div id="mysuccessModal" class="modal">
        <div class="modal-content">
             <span class="close">&times;</span>
             <p id="successModalMessage"></p>
        </div>
    </div>

</body>
</html>
