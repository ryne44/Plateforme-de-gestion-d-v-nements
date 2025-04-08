<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location - SPORT RENT</title>
    <link rel="stylesheet" href="location.css">
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-content">
            <img src="images/Capture.jpg" alt="SPORT RENT" class="logo">
            <nav>
                <ul>
                    <li><a href="accueil.html">Accueil</a></li>
                    <li><a href="evenements.php">Événements</a></li>
                    <li><a href="location.php" class="active">Location</a></li>
                    <li><a href="dashboard.php">Espace Perso</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="page-header">
            <h1>Location d'Équipements</h1>
            <p>Trouvez le matériel parfait pour votre pratique sportive</p>
        </section>

        <section class="location-container">
            <div class="filters">
                <select id="category-filter">
                    <option value="all">Toutes catégories</option>
                    <option value="football">Football</option>
                    <option value="tennis">Tennis</option>
                    <option value="randonnee">Randonnée</option>
                </select>
                <input type="text" id="search-equipment" placeholder="Rechercher un équipement...">
            </div>

            <div class="equipment-grid" id="equipment-container">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 SPORT RENT - Tous droits réservés</p>
    </footer>

    <script src="location.js"></script>
</body>
<?php
session_start();
require 'db_connect.php'; // Connexion à la base de données

// Si l'utilisateur essaie de réserver sans être connecté, il sera redirigé vers la page de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        header('Location: connexion.php');
        exit;
    }

    // Si l'utilisateur est connecté, on continue avec la réservation
    $equipement_id = $_POST['equipement_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Validation des dates pour éviter les erreurs dans la base de données
    if (strtotime($date_debut) && strtotime($date_fin)) {
        // Préparer et exécuter la requête pour insérer la réservation
        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, equipement_id, date_debut, date_fin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user']['id'], $equipement_id, $date_debut, $date_fin]);

        // Redirection vers le tableau de bord après la réservation
        header('Location: dashboard.php?success=1');
        exit;
    } else {
        // Gestion d'erreur si les dates ne sont pas valides
        $error_message = "Les dates de réservation sont invalides. Veuillez vérifier les informations.";
    }
}
?>
<?php if (!isset($_SESSION['user'])): ?>
    <p class="warning-message">Vous devez être connecté pour réserver un équipement. <a href="connexion.php">Se connecter</a></p>
<?php endif; ?>
