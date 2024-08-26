<?php
session_start();

if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

include "conn.php";

// Gère les requêtes POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    $response = array();

    if (isset($_POST['archive_id'])) {
        // Désarchivage ou Archivage
        $id = $_POST['archive_id'];
    
        // Vérifiez l'état actuel de l'archivage
        $checkArchiveQuery = "SELECT archive FROM etudiant WHERE id = ?";
        $stmt = $conn->prepare($checkArchiveQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($archive);
        $stmt->fetch();
        $stmt->close();
    
        if ($archive == 1) {
            // Si l'étudiant est déjà archivé, le désarchiver
            $sql = "UPDATE etudiant SET archive = 0 WHERE id = ?";
            $action = "désarchivé";
        } else {
            // Sinon, l'archiver
            $sql = "UPDATE etudiant SET archive = 1 WHERE id = ?";
            $action = "archivé";
        }
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = "L'étudiant a été $action avec succès.";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur lors de l'$action : " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
    echo json_encode($response);
    exit();
}

// Requête pour obtenir les étudiants archivés
$sql_archived_students = "SELECT * FROM etudiant WHERE archive = 1";
$result_archived_students = $conn->query($sql_archived_students);

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Étudiants Archivés</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
        <div id="content" class="content__data">
        <button class='action-btn archive-btn'><a href='./admin_dashboard.php'>RETOUR</a></button>
            <section class="middle-section">
                <div class="whitebox-table">
                    <div class="table__title__row">
                        <div class="title">Liste des Étudiants Archivés</div>
                    </div>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Date de naissance</th>
                                <th>Telephone</th>
                                <th>Niveau</th>
                                <th>Matricule</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_archived_students->num_rows > 0) {
                                while ($row = $result_archived_students->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['nom'] . "</td>";
                                    echo "<td>" . $row['prenom'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['date_naissance'] . "</td>";
                                    echo "<td>" . $row['telephone'] . "</td>";
                                    echo "<td>" . $row['niveau'] . "</td>";
                                    echo "<td>" . $row['matricule'] . "</td>";
                                    echo "<td>
                                        <button class='action-btn archive-btn' data-id='" . $row['id'] . "'>
                                            <i class='fa fa-folder-open'></i>
                                        </button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10'>Aucun étudiant archivé trouvé</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>  
        </div> 

    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('archive-btn')) {
                var id = e.target.getAttribute('data-id');
                if (confirm("Êtes-vous sûr de vouloir désarchiver cet étudiant ?")) {
                    fetch(window.location.href, {
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

