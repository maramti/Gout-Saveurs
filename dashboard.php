<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=db_pfa;charset=utf8", "root", "");

// Fonction pour ajouter un restaurant
function ajouterRestaurant($nom, $localisation, $categorie, $image) {
    global $pdo;
    $sql = "INSERT INTO restaurant (nom, localisation, categorie, image) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nom, $localisation, $categorie, $image]);
}

// Vérification et traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $localisation = $_POST['localisation'] ?? '';
    $categorie = $_POST['categorie'] ?? '';
    $image = $_POST['image'] ?? '';

    // Validation des données
    if ($nom && $localisation && $categorie && $image) {
        ajouterRestaurant($nom, $localisation, $categorie, $image);
        echo "Restaurant ajouté avec succès !";
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Restaurant</title>
</head>
<body>
    <h1>Ajouter un Restaurant</h1>
    <form action="" method="post">
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="localisation">Localisation:</label>
        <input type="text" id="localisation" name="localisation" required><br>

        <label for="categorie">Catégorie:</label>
        <input type="text" id="categorie" name="categorie" required><br>

        <label for="image">Image URL:</label>
        <input type="text" id="image" name="image" required><br>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>