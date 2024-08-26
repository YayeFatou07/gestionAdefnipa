<?php
session_start();
if ($_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

include "conn.php";

// Requête pour compter le nombre total d'administrateurs
$sql_admins = "SELECT COUNT(*) AS total_admins FROM admins";
$result_admins = $conn->query($sql_admins);
$total_admins = $result_admins ? $result_admins->fetch_assoc()['total_admins'] : 0;

// Requête pour compter le nombre total d'étudiants
$sql_students = "SELECT COUNT(*) AS total_students FROM etudiant";
$result_students = $conn->query($sql_students);
$total_students = $result_students ? $result_students->fetch_assoc()['total_students'] : 0;

// Requête pour compter le nombre d'étudiants archivés
$sql_archived = "SELECT COUNT(*) AS total_archived FROM etudiant WHERE archive = 1";
$result_archived = $conn->query($sql_archived);
$total_archived = $result_archived ? $result_archived->fetch_assoc()['total_archived'] : 0;

// Requête pour compter le nombre d'étudiants non archivés
$sql_non_archived = "SELECT COUNT(*) AS total_non_archived FROM etudiant WHERE archive = 0";
$result_non_archived = $conn->query($sql_non_archived);
$total_non_archived = $result_non_archived ? $result_non_archived->fetch_assoc()['total_non_archived'] : 0;

// Requêtes pour compter les étudiants par niveau
$sql_l1 = "SELECT COUNT(*) AS total_l1 FROM etudiant WHERE niveau = 'L1'";
$sql_l2 = "SELECT COUNT(*) AS total_l2 FROM etudiant WHERE niveau = 'L2'";
$sql_l3 = "SELECT COUNT(*) AS total_l3 FROM etudiant WHERE niveau = 'L3'";
$sql_m1 = "SELECT COUNT(*) AS total_m1 FROM etudiant WHERE niveau = 'M1'";
$sql_m2 = "SELECT COUNT(*) AS total_m2 FROM etudiant WHERE niveau = 'M2'";

$result_l1 = $conn->query($sql_l1);
$total_l1 = $result_l1 ? $result_l1->fetch_assoc()['total_l1'] : 0;

$result_l2 = $conn->query($sql_l2);
$total_l2 = $result_l2 ? $result_l2->fetch_assoc()['total_l2'] : 0;

$result_l3 = $conn->query($sql_l3);
$total_l3 = $result_l3 ? $result_l3->fetch_assoc()['total_l3'] : 0;

$result_m1 = $conn->query($sql_m1);
$total_m1 = $result_m1 ? $result_m1->fetch_assoc()['total_m1'] : 0;

$result_m2 = $conn->query($sql_m2);
$total_m2 = $result_m2 ? $result_m2->fetch_assoc()['total_m2'] : 0;

// Fermer la connexion
$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Gestion Dashboard</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<!-- *********************************sidebar**************************************-->

<div class="dashboard__wrapper">
    <!-- Sidebar -->
    <div id="myNav" class="left__sidebar overlay">
        <div class="sidebar__header">
            <a class="logo" href="./dashboard.html">
                <img src="./images/Logo_Marketing_Agency_Digital.-removebg-preview.png" alt="logo" />
            </a>
        </div>
        <div class="slidebar__middle">
            <ul class="slidebar__list">
                <li class="active">
                    <a href="./admin_dashboard.php">
                        <i class="iconview ico__dashboard"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li>
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
                        <span>Deconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

<!-- *********************************sidebar**************************************-->


    <!-- Page Content -->
    <div id="content" class="content__data">
        <!-- Middle Section -->
        <section class="middle-section">
            <div class="middle__title__row">
                <div class="title__left__col">
                    <?php
                    echo "<h1 class='title'>Hello " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . " 👋 Vous êtes connecté en tant qu'administrateur.</h1>";
                    ?>
                </div>
            </div>
            <div class="middle__content__row">
                <div class="middle__leftside__column">
                    <div class="mainlist__block__row">
                        <!-- Admin Card -->
                        <div class="listbox__column invoiced__box">
                            <a href="./admin.php" title="Admin">
                                <div class="card">
                                    <div class="listicon ico__invoiced">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">Admin</div>
                                        <div class="listcount"><?php echo $total_admins; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Student Card -->
                        <div class="listbox__column overdue__box">
                            <a href="./liste_etudiant.php" title="Étudiant">
                                <div class="card">
                                    <div class="listicon ico__overdue">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">Étudiant</div>
                                        <div class="listcount"><?php echo $total_students; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Archived Students Card -->
                        <div class="listbox__column draft__box">
                            <a href="archived_students.php" title="E. Archivé">
                                <div class="card">
                                    <div class="listicon ico__draft">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">E. Archivé</div>
                                        <div class="listcount"><?php echo $total_archived; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Non-Archived Students Card -->
                        <div class="listbox__column unpaid__box">
                            <a href="non_archived_students.php" title="E. Non_Archivé">
                                <div class="card">
                                    <div class="listicon ico__unpaid">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">E. Non_Archivé</div>
                                        <div class="listcount"><?php echo $total_non_archived; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- L1 Students Card -->
                        <div class="listbox__column l1__box">
                            <a href="pages/l1_students.php" title="L1 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__l1">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">L1 Étudiants</div>
                                        <div class="listcount"><?php echo $total_l1; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- L2 Students Card -->
                        <div class="listbox__column l2__box">
                            <a href="pages/l2_students.php" title="L2 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__l2">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">L2 Étudiants</div>
                                        <div class="listcount"><?php echo $total_l2; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- L3 Students Card -->
                        <div class="listbox__column l3__box">
                            <a href="pages/l3_students.php" title="L3 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__l3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">L3 Étudiants</div>
                                        <div class="listcount"><?php echo $total_l3; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- M1 Students Card -->
                        <div class="listbox__column m1__box">
                            <a href="pages/m1_students.php" title="M1 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__m1">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">M1 Étudiants</div>
                                        <div class="listcount"><?php echo $total_m1; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- M2 Students Card -->
                        <div class="listbox__column m2__box">
                            <a href="pages/m2_students.php" title="M2 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__m2">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">M2 Étudiants</div>
                                        <div class="listcount"><?php echo $total_m2; ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="listbox__column m2__box">
                            <a href="notes.php" title="M2 Étudiants">
                                <div class="card">
                                    <div class="listicon ico__m2">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <div class="list__detail">
                                        <div class="listname">NOTES</div>
                                        <div class="listcount"></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="./js/script.js"></script>
</body>
</html>
