
<?php
session_start();
include 'database.php';

if (!$pdo) {
    die("connection échouée avec signup.php.");
}

if (isset($_POST['signup'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom']; 
    $email = $_POST['signup_mail'];
    $mot_de_passe = $_POST['signup_password'];

    $insertQuery = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES ('$nom', '$prenom', '$email', '$mot_de_passe')";



       
if ($pdo->query($insertQuery)){
    $_SESSION['email']=$email;
        header("Location: homepage.php");
        exit(); 
    } else {
        echo "Erreur lors de l'insertion des données.";
        
        }
  
}

?>