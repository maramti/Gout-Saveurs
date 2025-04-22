<?php
session_start();

$results = [];
$query = '';

if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);

    include 'includes/database.php';

    $sql = "SELECT * FROM restaurant 
            WHERE nom LIKE :q OR categorie LIKE :q 
            OR description LIKE :q OR localisation LIKE :q";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['q' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
    <link href="assets/projet.css" rel="stylesheet">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GOÛT & AVIS</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
</head>
<body>
<?php if (isset($_SESSION['user_id'])): ?>
      <div class="welcome-message">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_nom']); ?> !</h1>
      </div>
    <?php endif; ?>

  <!-- HOME / ABOUT SECTION -->
  <div class="background" id="mainBackground">
  <nav class="navbar">
        <ul>
          <li><a href="javascript:void(0);" class="active" onclick="showContent('home')">Acceuil</a></li>
          <li><a href="javascript:void(0);" onclick="showContent('about')">A propos de nous</a></li>
          <li>
              <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-icon">
                  <i class="fas fa-user"></i>
                  <div class="dropdown">
                  
                    <a href="forms/logout.php" class="item_user">
                      <i class="fas fa-right-from-bracket"></i> déconnecter
                    </a>
                    <a href="user_interface.php" class="item_user">
                      <i class="fas fa-heart"></i> tableau de bord
                    </a>
                    <a href="settings.html" class="item_user">
                      <i class="fas fa-key"></i>Paramètres
                    </a>
                  </div>
                </div>
              <?php else: ?>
                
                <a href="../forms/login.php" > Se connecter</a>
              <?php endif; ?>
          </li>
        </ul>
      </nav>


    <div class="content" id="home">
      <h1 class="title">GOÛT & AVIS</h1>
      <div class="search-box">
        <form method="GET" action="main.php">
          <input
            type="text"
            placeholder="EX : PLAT, SPÉCIALITÉ.."
            name="query"
            id="searchInput"
            required
          />
          <button type="submit" style="display: none;"></button>
        </form>
        <i class="fas fa-magnifying-glass"></i>
      </div>
      <p class="slogan">
        PLATEFORME DE NOTATION ET DE GESTION DES<br />RESTAURANTS ET CAFÉS
      </p>
    </div>

    <div class="content" id="about" style="display: none">
      <h1 class="title">À propos de GOÛT & AVIS</h1>
      <p>Bienvenue sur GOÛT & AVIS, la plateforme idéale pour partager vos avis et expériences culinaires.</p>
      <p>Notre mission est de créer une communauté où chaque utilisateur peut facilement trouver des restaurants et cafés qui correspondent à ses préférences personnelles.</p>
    </div>
  </div>

  <!-- RESULTS SECTION -->
  <div class="results-page" id="results" style="display: none">
    <div class="results-nav">
      <a href="javascript:void(0);" onclick="returnToSection('home')">Acceuil</a>
      <a href="javascript:void(0);" onclick="returnToSection('about')">A propos de nous</a>
      <a href="forms/login.php">Se connecter</a>
    </div>

    <div class="top-search-box">
      <form method="GET" action="main.php">
        <input type="text" id="resultInput" name="query" placeholder="EX : PLAT, SPÉCIALITÉ.." />
        <button type="submit" style="display: none;"></button>
      </form>
      <i class="fas fa-magnifying-glass"></i>
    </div>

    <div class="results-text">
      <?php if (!empty($query)): ?>
        <h2>Résultats pour : <?= htmlspecialchars($query) ?></h2>
        <?php if (count($results) > 0): ?>
          <ul class="container">
            <?php foreach ($results as $row): ?>
              <a href="restaurant.php?id=<?= $row['id_restaurant'] ?>">
              <li class="item"> 
                <strong><?= $row['nom'] ?></strong><br>
                <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" width="200"><br>
                <strong>Catégorie :</strong> <?= $row['categorie'] ?><br>
                <strong>Localisation :</strong> <?= $row['localisation'] ?><br>
                Description : <?= $row['description'] ?>
                
              </li>
            </a>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p>Aucun résultat trouvé.</p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.has("query")) {
    const background = document.getElementById("mainBackground");
    background.classList.add("slide-up");

    setTimeout(() => {
        background.style.display = "none";
        background.classList.remove("slide-up");
        document.getElementById("results").style.display = "block";
        document.getElementById("resultInput").value = urlParams.get("query");
    }, 600); // attendre que le slide-up s'affiche
}

    });

    function showContent(section) {
      document.querySelectorAll(".content").forEach((el) => {
        el.style.display = "none";
      });
      document.getElementById(section).style.display = "block";
      const links = document.querySelectorAll(".navbar a");
      links.forEach((link) => link.classList.remove("active"));
      event.target.classList.add("active");
    }

    function returnToSection(sectionId) {
      const resultsPage = document.getElementById("results");
      resultsPage.classList.add("slide-down");

      setTimeout(() => {
        resultsPage.style.display = "none";
        resultsPage.classList.remove("slide-down");

        const mainBackground = document.getElementById("mainBackground");
        mainBackground.style.display = "block";

        document.querySelectorAll(".content").forEach((el) => {
          el.style.display = "none";
        });

        document.getElementById(sectionId).style.display = "block";

        const links = mainBackground.querySelectorAll(".navbar a");
        links.forEach((link) => {
          link.classList.remove("active");
          if (link.textContent.toLowerCase() === sectionId) {
            link.classList.add("active");
          }
        });
      }, 600);
    }

    function showRecommendations() {
      const background = document.getElementById("mainBackground");
      background.classList.add("slide-up");

      setTimeout(() => {
        background.style.display = "none";
        background.classList.remove("slide-up");
        const results = document.getElementById("results");
        results.style.display = "block";
        document.querySelector(".results-text").style.display = "none";
      }, 600);
    }
  </script>
</body>
</html>
