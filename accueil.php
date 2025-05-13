<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPORT RENT - Accueil</title>
    <link rel="stylesheet" href="accueil.css">
    <style>
        /* Style du bouton "Retour en haut" */
        #scrollToTopBtn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            display: none;
        }

        #scrollToTopBtn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>

    <header>
        <div class="header-content">
            <div class="logo">
                <img src="image/logo.jpg" alt="SPORT RENT Logo">
            </div>
            
            <nav>
                <ul>
                    <li><a href="accueil.php" class="active">Accueil</a></li>
                    <li><a href="evenements.php">Événements</a></li>
                    <li><a href="location.php">Location</a></li>
                    <li><a href="dashboard.php">Espace Perso</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="logout.php">Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="connexion.php">Connexion</a></li>
                        <li><a href="inscription.php">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="main-title">
            <h1>SPORT RENT</h1>
            <p class="subtitle">Location d'équipements & gestion d'événements sportifs</p>
        </section>

        <section class="content">
            <div class="card">
                <h2><i class="fas fa-calendar-alt"></i> Événements à venir</h2>
                <p>Découvrez nos prochains événements sportifs</p>
                <a href="evenements.php" class="btn">Voir tout</a>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-skiing"></i> Équipements disponibles</h2>
                <p>Louez du matériel de qualité</p>
                <a href="location.php" class="btn">Louer</a>
            </div>
        </section>

        
    </main>

    <footer>
        <p>&copy; 2025 SPORT RENT - Tous droits réservés</p>
    </footer>

    <!-- Bouton pour revenir en haut -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">Retour en haut</button>

    <script>
        // Fonction pour faire défiler la page vers le haut
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Afficher ou masquer le bouton en fonction du défilement de la page
        window.onscroll = function() {
            var button = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                button.style.display = "block";
            } else {
                button.style.display = "none";
            }
        };
    </script>
</body>
</html>
