<?php
// Vérifier si l'ID de l'étudiant est passé dans l'URL
if (isset($_GET['etudiant_id'])) {
    $etudiant_id = $_GET['etudiant_id'];

    include "../conn.php";
    // Récupérer les informations de l'étudiant
    $sql = "SELECT * FROM etudiant WHERE id = $etudiant_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $etudiant = $result->fetch_assoc();
    } else {
        echo "Étudiant introuvable.";
        exit();
    }
    
    // Fermer la connexion
    $conn->close();
} else {
    echo "ID de l'étudiant non spécifié.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no" />
    <title>Gestion Dashboard</title>
    
    <link rel="stylesheet" href="../css/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
<body>
    <div class="container">
    <div class="logo">
            <img src="../images/images.png" alt="Logo" style="width: 150px; height: auto;">
    </div>
       <u><h1>BULLETIN DES NOTES DE </u>:<?php echo $etudiant['prenom'] . ' ' . $etudiant['nom']; ?></h1>
       <u><h3>DATE DE NAISSANCE: </u><?php echo $etudiant['date_naissance']; ?></h3>
       <u><h3>MATRICULE: </u><?php echo $etudiant['matricule']; ?></h3>
       <u><h3>EMAIL:</u> <?php echo $etudiant['email']; ?></h3>
       <u><h3>TELEPHONE:</u> <?php echo $etudiant['telephone']; ?></h3>
       <u><h3>NIVEAU: </u><?php echo $etudiant['niveau']; ?></h3>
      
       <?php// echo $etudiant['prenom'] . ' ' . $etudiant['nom']; ?>
       <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>NOTE 1</th>
                    <th>NOTE 2</th>
                    <th>NOTE 3</th>
                    <th>NOTE 4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>MODULE</td>
                    <td><?php echo htmlspecialchars($etudiant['modul1']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['modul2']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['modul3']); ?></td>
                    <td><?php echo htmlspecialchars($etudiant['modul4']); ?></td>
                </tr>
                <!-- Ajouter d'autres matières ici si nécessaire -->
            </tbody>
            
        </table><br>
        <h3>MOYENNE : <?php echo $average = ($etudiant['modul1'] + $etudiant['modul2'] + $etudiant['modul3'] + $etudiant['modul4']) / 4; ?></h3>
        <h3>STATUS DE L'ETUDIANT : <?php echo $etudiant['statut']; ?></h3>
        <div class="logo">
            <img src="../images/signer.png" alt="Logo" style="width: 150px; height: auto; ">
        </div>
    </div>
    <button class='action-btn archive-btn'><a href='../notes.php'>RETOUR</a></button>
</body>
</html>
