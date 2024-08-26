<?php
session_start();

if ($_SESSION['role'] != 2) {
    header("Location: index.php");
    exit();
}

include "conn.php";

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
                        <a href="./user_dashboard.php">
                            <i class="iconview ico__dashboard"></i>
                            <span>Accueil</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="./etudiant.php">
                            <i class="iconview ico__clients"></i>
                            <span>Étudiant</span>
                        </a>
                    </li>
                    <li>
                        <a href="./deconnexion.php">
                            <i class="iconview ico__clients"></i>
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

                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>

    <script src="./js/script.js"></script>
</body>

</html>
