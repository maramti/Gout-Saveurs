<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GOÛT & AVIS</title>
    <link rel="stylesheet" href="style.css" />
    <style>

    </style>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <script
      src="https://kit.fontawesome.com/a076d05399.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <!-- HOME / ABOUT SECTION -->
    <div class="background" id="mainBackground">
      <nav class="navbar">
        <ul>
          <li>
            <a
              href="javascript:void(0);"
              class="active"
              onclick="showContent('home')"
              >Home</a
            >
          </li>
          <li>
            <a href="javascript:void(0);" onclick="showContent('about')"
              >About</a
            >
          </li>
          <li>
      <a href="javascript:void(0);" onclick="showRecommendations()">Nos Recommandations</a>
</li>

          

          <li><a href="login.html">Sign in</a></li>
        </ul>
      </nav>

      <div class="content" id="home">
        <h1 class="title">GOÛT & AVIS</h1>
        <div class="search-box">
          <span class="label"></span>
          <input
            type="text"
            placeholder="EX : PLAT, SPÉCIALITÉ.."
            id="searchInput"
          />
          <i class="fas fa-microphone"></i>
        </div>
        <p class="slogan">
          PLATEFORME DE NOTATION ET DE GESTION DES<br />RESTAURANTS ET CAFÉS
        </p>
      </div>

      <div class="content" id="about" style="display: none">
        <h1 class="title">À propos de GOÛT & AVIS</h1>
        <p>
          Bienvenue sur GOÛT & AVIS, la plateforme idéale pour partager vos avis
          et expériences culinaires.
        </p>
        <p>
          Notre mission est de créer une communauté où chaque utilisateur peut
          facilement trouver des restaurants et cafés qui correspondent à ses
          préférences personnelles.
        </p>
      </div>
    </div>

    <!-- RESULTS SECTION -->
    <div class="results-page" id="results" style="display: none">
      <div class="results-nav">
        <a href="javascript:void(0);" onclick="returnToSection('home')">Home</a>
        <a href="javascript:void(0);" onclick="returnToSection('about')"
        
          >About</a
        >
        <a href="login.html">Sign in</a>
      </div>

      <div class="top-search-box">
        <span class="label"></span>
        <input
          type="text"
          id="resultInput"
          placeholder="EX : PLAT, SPÉCIALITÉ.."
        />
        <i class="fas fa-microphone"></i>
      </div>
      <div class="results-text">Voici les résultats pour votre recherche.</div>
    </div>
    <div id="restaurant-page" style="display:none;">
        <h1>Restaurant Page</h1>
        <p>Cette page est vide pour l'instant. Vous pouvez y ajouter des détails du restaurant.</p>

        <div class="recommendations-text" style="display: none;">
  Voici nos recommandations pour vous.
</div>

    </div>



    <script>
      function showContent(section) {
        document.querySelectorAll(".content").forEach((el) => {
          el.style.display = "none";
        });
        document.getElementById(section).style.display = "block";
        const links = document.querySelectorAll(".navbar a");
        links.forEach((link) => link.classList.remove("active"));
        event.target.classList.add("active");
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
    document.querySelector(".recommendations-text").style.display = "block";
  }, 600);
}


      const searchInput = document.getElementById("searchInput");
searchInput.addEventListener("keydown", function (e) {
  if (e.key === "Enter" && this.value.trim() !== "") {
    const keyword = this.value.trim();

    // Animate transition
    const background = document.getElementById("mainBackground");
    background.classList.add("slide-up");

    setTimeout(() => {
      background.style.display = "none";
      background.classList.remove("slide-up");
      document.getElementById("results").style.display = "block";
      document.getElementById("resultInput").value = keyword;

      // 🔍 Fetch search results
      fetch("search.php?q=" + encodeURIComponent(keyword))
        .then(response => {
          if (!response.ok) throw new Error("Erreur lors de la recherche.");
          return response.json();
        })
        .then(data => {
          const resultsContainer = document.createElement("div");
          resultsContainer.classList.add("results-list");

          if (data.length === 0) {
            resultsContainer.innerHTML = "<p>Aucun restaurant trouvé.</p>";
          } else {
            data.forEach(r => {
  resultsContainer.innerHTML += `
    <div class="restaurant" onclick="showRestaurantPage(${r.id_restaurant})">
      <h2>${r.nom}</h2>
      <p><strong>Localisation :</strong> ${r.localisation}</p>
      <p>${r.description}</p>
      <p><strong>Catégorie :</strong> ${r.categorie}</p>
      ${r.image ? `<img src="${r.image.split(',')[0]}" alt="Image de ${r.nom}" style="max-width:200px; border-radius:8px;">` : ''}
    </div>`;
});



          }

          // Inject results
          const resultsPage = document.getElementById("results");
          // Clear previous results if any
          const oldResults = resultsPage.querySelector(".results-list");
          if (oldResults) oldResults.remove();
          resultsPage.appendChild(resultsContainer);
        })
        .catch(error => {
          alert(error.message);
        });

    }, 600);
  }
});

 


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
      function showRestaurantPage(id) {
  window.location.href = "newpagerest.php?id=" + id;
}


    </script>
  </body>
</html>
