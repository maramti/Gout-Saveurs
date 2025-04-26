<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_pfa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $image = $_POST['image'];

    // Validation des données
    if (!empty($name) && !empty($location) && !empty($category) && !empty($image)) {
        $stmt = $conn->prepare("INSERT INTO restaurant (nom, localisation, categorie, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $location, $category, $image);

        if ($stmt->execute()) {
            echo "Nouveau restaurant ajouté avec succès.";
            header('Location: dashboard.html');
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

$conn->close();
?>