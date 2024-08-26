<?php
// Vérifier si l'ID de l'étudiant est passé dans l'URL
if (isset($_GET['etudiant_id'])) {
    $etudiant_id = $_GET['etudiant_id'];

    include "conn.php";

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
    <link href="./css/style2.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
<body>
  
       <div class="container">
       <h1>Ajout de Notes pour <?php echo $etudiant['prenom'] . ' ' . $etudiant['nom']; ?></h1>
            <form id="userForm" action="saveNotes.php" method="post">
                <div class="form-group">
                    <input type="hidden" name="etudiant_id" value="<?php echo $etudiant_id; ?>">
                    <label for="note1">Note 1 :</label>
                    <input type="number" name="note1" id="name" min="0" max="20"  required>
                    <label for="note2">Note 2 :</label>
                    <input type="number" name="note2" min="0" max="20" required>
                    <label for="note3">Note 3 :</label>
                    <input type="number" name="note3" min="0" max="20" required>
                    <label for="note4">Note 4 :</label>
                    <input type="number" name="note4" min="0" max="20" required>
                     
                </div>   
                <button class="action-btn submit-btn "   type=" submit">Ajouter </button>    
               
         
            </form>
            
        </div>
        <button class='action-btn submit-btn'><a href='./notes.php'>RETOUR</a></button>
</body>
</html>
