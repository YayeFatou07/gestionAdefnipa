<?php
session_start();
// Redirige vers la page de connexion si l'utilisateur n'est pas autorisé
if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

 include "conn.php";

// Gère les requêtes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $response = array();

    if (isset($_POST['delete_id'])) {
        // Suppression
        $id = $_POST['delete_id'];
        $sql = "DELETE FROM etudiant WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "L'étudiant a été supprimé avec succès.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur lors de la suppression : " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['edit_id'])) {
        // Édition
        $id = $_POST['edit_id'];
        $nom = $_POST['edit_name'];
        $prenom = $_POST['edit_prenom'];
        $email = $_POST['edit_email'];
        $telephone = $_POST['edit_tel'];
        $date_naissance = $_POST['edit_date'];
        $niveau = $_POST['edit_niveau'];

        $sql = "UPDATE etudiant SET nom = ?, prenom = ?, email = ?, telephone = ?, date_naissance = ?, niveau = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nom, $prenom, $email, $telephone, $date_naissance, $niveau, $id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "L'étudiant a été mis à jour avec succès.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur lors de la mise à jour : " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['archive_id'])) {
        // Archivage
        $id = $_POST['archive_id'];
        $sql = "UPDATE etudiant SET archive = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "L'étudiant a été archivé avec succès.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur lors de l'archivage : " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['name'])) {
        // Ajout
        $nom = $conn->real_escape_string($_POST['name']);
        $prenom = $conn->real_escape_string($_POST['prenom']);
        $email = $conn->real_escape_string($_POST['email']);
        $telephone = $conn->real_escape_string($_POST['tel']);
        $date_naissance = $conn->real_escape_string($_POST['date']);
        $niveau = $conn->real_escape_string($_POST['niveau']);

        // Fonction pour générer le matricule
        function genererMatricule($nom, $prenom) {
            $prefix = strtoupper(substr($nom, 0, 2) . substr($prenom, 0, 2));
            $uniqueNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            return $prefix . $uniqueNumber;
        }

        $checkEmailQuery = "SELECT id FROM etudiant WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = "Erreur : L'email est déjà utilisé.";
        } else {
            $matricule = genererMatricule($nom, $prenom);

            $sql = "INSERT INTO etudiant (nom, prenom, email, telephone, date_naissance, niveau, matricule) 
                    VALUES ('$nom', '$prenom', '$email', '$telephone', '$date_naissance', '$niveau', '$matricule')";

            if ($conn->query($sql) === TRUE) {
                $response['status'] = 'success';
                $response['message'] = "L'étudiant a été ajouté avec succès.";
                $response['matricule'] = $matricule;
            } else {
                $response['status'] = 'error';
                $response['message'] = "Erreur : " . $conn->error;
            }
        }
    }

    $conn->close();
    echo json_encode($response);
    exit();
}

// Sélectionner uniquement les étudiants non archivés
$sql = "SELECT id, nom, prenom, date_naissance, email, telephone, niveau, matricule FROM etudiant WHERE archive = 0";
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
        <!-- bare de navigation -->
        <div id="myNav" class="left__sidebar overlay">
            <div class="sidebar__header">
                    <img src="./images/Logo_Marketing_Agency_Digital.-removebg-preview.png" alt="logo" />
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

        <!-- Contenue de la page -->
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

                        <!-- Modale pour ajouter un Etudiant-->
                        <div class="container">
                            <button id="openModalBtn" class="open-modal-btn">Ajouter un Etudiant</button>
                            <div id="myModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Ajouter un Etudiant</h2>
                                    <form id="userForm">
                                        <div class="form-group">
                                            <label for="name">Nom :</label>
                                            <input type="text" id="name" name="name" required>
                                            <label for="prenom">Prénom :</label>
                                            <input type="text" id="prenom" name="prenom" required>
                                            <label for="email">Email :</label>
                                            <input type="email" id="email" name="email" required>
                                            <label for="tel">Téléphone :</label>
                                            <input type="number" id="tel" name="tel" min='760000000' max="789999999" required >
                                            <label for="date">Date de naissance :</label>
                                            <input type="date" id="date" name="date" required max="2005-12-31">
                                            <label for="niveau">Niveau :</label>
                                            <select id="niveau" name="niveau" required>
                                                <option value="L1">L1</option>
                                                <option value="L2">L2</option>
                                                <option value="L3">L3</option>
                                                <option value="M1">M1</option>
                                                <option value="M2">M2</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="submit-btn">Ajouter</button>
                                    </form>
                                    <div id="responseMessage"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal pour editer un Etudiant -->
                        <div class="container">
                            <div id="editModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <h2>Éditer un Etudiant</h2>
                                    <form id="editForm">
                                        <input type="hidden" id="edit_id" name="edit_id">
                                        <div class="form-group">
                                            <label for="edit_name">Nom :</label>
                                            <input type="text" id="edit_name" name="edit_name" required>
                                            <label for="edit_prenom">Prénom :</label>
                                            <input type="text" id="edit_prenom" name="edit_prenom" required>
                                            <label for="edit_email">Email :</label>
                                            <input type="email" id="edit_email" name="edit_email" required>
                                            <label for="edit_tel">Téléphone :</label>
                                            <input type="number" id="edit_tel" name="edit_tel" required min='760000000' max="789999999">
                                            <label for="edit_date">Date de naissance :</label>
                                            <input type="date" id="edit_date" name="edit_date" required max="2005-12-31">
                                            <label for="edit_niveau">Niveau :</label>
                                            <select id="edit_niveau" name="edit_niveau" required>
                                                <option value="L1">L1</option>
                                                <option value="L2">L2</option>
                                                <option value="L3">L3</option>
                                                <option value="M1">M1</option>
                                                <option value="M2">M2</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="submit-btn">Mettre à jour</button>
                                    </form>
                                    <div id="editResponseMessage"></div>
                                </div>
                            </div>
                        </div>



                        <!-- Affichage de la liste des etudiants -->
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
                                        <th>Date de Naissance</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Niveau</th>
                                        <th>Matricule</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>";

                            if ($result->num_rows > 0) {
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
                                           <button class='action-btn delete-btn' data-id='" . $row['id'] . "'>
                                                <i class='fa-solid fa-trash-can'></i>
                                            </button>

                                            <button class='action-btn edit-btn' data-id='" . $row['id'] . "' data-nom='" . $row['nom'] . "' data-prenom='" . $row['prenom'] . "' data-email='" . $row['email'] . "' data-tel='" . $row['telephone'] . "' data-date='" . $row['date_naissance'] . "' data-niveau='" . $row['niveau'] . "'>
                                                <i class='fa-solid fa-pen-to-square'></i>
                                            </button>

                                            <button class='action-btn archive-btn' data-id='" . $row['id'] . "'>
                                                <i class='fa-regular fa-file-zipper'></i>
                                            </button>
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
    <script>
        // Modal Handling for Add
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("openModalBtn");
        var span = document.getElementsByClassName("close")[0];
        var form = document.getElementById("userForm");
        var responseMessage = document.getElementById("responseMessage");

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Form Submission Handling for Add
        form.onsubmit = function(event) {
            event.preventDefault();

            var formData = new FormData(form);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    responseMessage.innerHTML = "<p class='success-message'>" + data.message + "</p>";
                    form.reset();
                } else {
                    responseMessage.innerHTML = "<p class='error-message'>" + data.message + "</p>";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Modal Handling for Edit
        var editModal = document.getElementById("editModal");
        var editForm = document.getElementById("editForm");
        var editResponseMessage = document.getElementById("editResponseMessage");

        // Handle Edit Button Click
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('edit-btn')) {
                var id = e.target.getAttribute('data-id');
                var nom = e.target.getAttribute('data-nom');
                var prenom = e.target.getAttribute('data-prenom');
                var email = e.target.getAttribute('data-email');
                var tel = e.target.getAttribute('data-tel');
                var date = e.target.getAttribute('data-date');
                var niveau = e.target.getAttribute('data-niveau');

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_name').value = nom;
                document.getElementById('edit_prenom').value = prenom;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_tel').value = tel;
                document.getElementById('edit_date').value = date;
                document.getElementById('edit_niveau').value = niveau;

                editModal.style.display = "block";
            }
        });

        // Handle Close for Edit Modal
        var editSpan = document.getElementsByClassName("close")[1];
        editSpan.onclick = function() {
            editModal.style.display = "none";
        }

        // Form Submission Handling for Edit
        editForm.onsubmit = function(event) {
            event.preventDefault();

            var formData = new FormData(editForm);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    editResponseMessage.innerHTML = "<p class='success-message'>" + data.message + "</p>";
                    editForm.reset();
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    editResponseMessage.innerHTML = "<p class='error-message'>" + data.message + "</p>";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Button Handlers for Actions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                var id = e.target.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant ?")) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'delete_id=' + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            } else if (e.target.classList.contains('archive-btn')) {
                var id = e.target.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir archiver cet étudiant ?")) {
                    fetch('', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'archive_id=' + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        if (data.status === 'success') {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            }
        });
    </script>
</body>
</html>
