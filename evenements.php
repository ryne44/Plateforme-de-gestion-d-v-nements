<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements Sportifs - SPORT RENT</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="evenements.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>





<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s_inscrire'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['redirect_to'] = 'evenements.php';
        header('Location: connexion.php');
        exit;
    }
    
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user']['id'];
    
    // Vérifier si l'utilisateur n'est pas déjà inscrit
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ? AND evenement_id = ?");
    $stmt->execute([$user_id, $event_id]);
    
    if ($stmt->rowCount() === 0) {
        // Insérer la réservation (inscription à l'événement)
        $insert = $pdo->prepare("INSERT INTO reservations (user_id, evenement_id, date_debut, date_fin, statut) 
                               VALUES (?, ?, NOW(), NOW(), 'confirme')");
        $insert->execute([$user_id, $event_id]);
        
        $_SESSION['success'] = "Inscription à l'événement réussie !";
    } else {
        $_SESSION['error'] = "Vous êtes déjà inscrit à cet événement.";
    }
    
    header('Location: evenements.php');
    exit;
}
?>



    <!-- Même structure d'en-tête que l'accueil -->
    <header>
        <div class="header-content">
            <img src="images/Capture.jpg" alt="SPORT RENT Logo">
            </div>
            
            <nav>
            <ul>
                <li><a href="accueil.html">Accueil</a></li>
                <li><a href="evenements.php" class="active">Événements</a></li>
                <li><a href="location.php">Location</a></li>
                <li><a href="dashboard.php">Espace Perso</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
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
        <section class="page-header">
            <h1>Événements Sportifs</h1>
            <p>Découvrez et participez aux prochains événements près de chez vous</p>
        </section>

        <section class="events-container">
            <div class="filters">
                <select id="sport-filter">
                    <option value="all">Tous les sports</option>
                    <option value="football">Football</option>
                    <option value="basket">Basketball</option>
                    <option value="tennis">Tennis</option>
                    <option value="running">Course à pied</option>
                    <option value="surf">Surf</option>
                </select>
                <input type="text" id="search-input" placeholder="Rechercher un événement...">
            </div>

            <div class="events-grid" id="events-grid">
                <?php foreach ($events as $event): ?>
                <div class="event-card">
                    <div class="event-image" style="background-image: url('images/<?= htmlspecialchars($event['image']) ?>')"></div>
                    <div class="event-info">
                        <span class="event-sport"><?= htmlspecialchars($event['sport']) ?></span>
                        <h3><?= htmlspecialchars($event['titre']) ?></h3>
                        <div class="event-meta">
                            <span><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['date'])) ?></span>
                            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['lieu']) ?></span>
                        </div>
                        <div class="event-meta">
                            <span><i class="fas fa-tag"></i> <?= htmlspecialchars($event['prix']) ?>€</span>
                        </div>
                        <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
                        <a href="dashboard.php?action=register_event&event_id=<?= $event['id'] ?>" class="btn-event">S'inscrire</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 SPORT RENT - Tous droits réservés</p>
        </div>
    </footer>

    <script src="events.js"></script>
</body>
</html>