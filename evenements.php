<?php
session_start();
require 'db_connect.php';

// Récupérer les événements de la base de données
$stmt = $pdo->query("SELECT * FROM evenements ORDER BY date");
$events = $stmt->fetchAll();

// Traitement de l'inscription à un événement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['s_inscrire'])) {
    if (!isset($_SESSION['user'])) {
        // Stocker l'ID de l'événement dans la session pour y revenir après connexion
        $_SESSION['redirect_to'] = 'evenements.php';
        $_SESSION['event_id'] = $_POST['event_id']; // Stocker l'ID de l'événement
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

// Vérifier l'état de connexion pour déboguer
$is_logged_in = isset($_SESSION['user']);
?>

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
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="images/Capture.jpg" alt="SPORT RENT Logo">
            </div>
            
            <nav>
                <ul>
                    <li><a href="accueil.html">Accueil</a></li>
                    <li><a href="evenements.php" class="active">Événements</a></li>
                    <li><a href="location.php">Location</a></li>
                    <li><a href="dashboard.php">Espace Perso</a></li>
                    <?php if($is_logged_in): ?>
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

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Message de débogage pour confirmer l'état de connexion -->
        <?php if($is_logged_in): ?>
            <div class="alert info">Connecté en tant que <?= htmlspecialchars($_SESSION['user']['nom']) ?></div>
        <?php endif; ?>

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
                        
                        <form method="post" action="evenements.php">
                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                            <button type="submit" name="s_inscrire" class="btn-event">
                                <?php if($is_logged_in): ?>
                                    S'inscrire
                                <?php else: ?>
                                    Se connecter pour s'inscrire
                                <?php endif; ?>
                            </button>
                        </form>
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

    
</body>
</html>