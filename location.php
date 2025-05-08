<?php
session_start();
require 'db_connect.php'; // Connexion à la base de données

// Récupérer les équipements depuis la base de données
$stmt = $pdo->query("SELECT * FROM equipements WHERE disponible = TRUE");
$equipements = $stmt->fetchAll();

// Traiter la réservation d'équipement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['louer'])) {
    if (!isset($_SESSION['user'])) {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        $_SESSION['redirect_to'] = 'location.php';
        header('Location: connexion.php');
        exit;
    }

    // Si l'utilisateur est connecté, on continue avec la réservation
    $equipement_id = $_POST['equipement_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Validation des dates pour éviter les erreurs dans la base de données
    if (strtotime($date_debut) && strtotime($date_fin) && strtotime($date_debut) < strtotime($date_fin)) {
        try {
            // Préparer et exécuter la requête pour insérer la réservation
            $stmt = $pdo->prepare("INSERT INTO reservations (user_id, equipement_id, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, 'confirme')");
            $result = $stmt->execute([$_SESSION['user']['id'], $equipement_id, $date_debut, $date_fin]);
            
            if ($result) {
                // Débogage: Enregistrer le dernier ID inséré
                $lastId = $pdo->lastInsertId();
                $_SESSION['success'] = "Votre réservation d'équipement a été confirmée! (ID: $lastId)";
                
                // Redirection vers le tableau de bord après la réservation
                header('Location: dashboard.php');
                exit;
            } else {
                $error_message = "Erreur lors de l'enregistrement de la réservation.";
            }
        } catch (PDOException $e) {
            // Capturer toute erreur SQL pour déboguer
            $error_message = "Erreur de base de données: " . $e->getMessage();
        }
    } else {
        // Gestion d'erreur si les dates ne sont pas valides
        $error_message = "Les dates de réservation sont invalides. La date de fin doit être ultérieure à la date de début.";
    }
}
?>

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
            <div class="logo">
                <img src="image2/logo.jpg" alt="SPORT RENT Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="accueil.php">Accueil</a></li>
                    <li><a href="evenements.php">Événements</a></li>
                    <li><a href="location.php" class="active">Location</a></li>
                    <li><a href="dashboard.php">Espace Perso</a></li>
                    <?php if(isset($_SESSION['user'])): ?>
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
            <h1>Location d'Équipements</h1>
            <p>Trouvez le matériel parfait pour votre pratique sportive</p>
        </section>

        <?php if(isset($error_message)): ?>
            <div class="alert error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['user'])): ?>
            <div class="alert info">Connecté en tant que <?= htmlspecialchars($_SESSION['user']['nom']) ?></div>
        <?php else: ?>
            <div class="alert info">Connectez-vous pour pouvoir louer des équipements</div>
        <?php endif; ?>

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
                <!-- Kit Football Complet -->
                <div class="equipment-card" data-type="football">
                    <img src="image2/football.jpg" alt="Kit Football Complet" class="equipment-image">
                    <div class="equipment-info">
                        <span class="equipment-type">Football</span>
                        <h3>Kit Football Complet</h3>
                        <div class="equipment-meta">
                            <span><i class="fas fa-tag"></i> 25.00€/jour</span>
                            <span><i class="fas fa-box-open"></i> En stock</span>
                        </div>
                        <p class="equipment-description">Ballon + chaussures + plots + filet</p>
                        
                        <form method="post" action="location.php" class="reservation-form">
                            <input type="hidden" name="equipement_id" value="1">
                            <div class="date-fields">
                                <div>
                                    <label for="date_debut_1">Du:</label>
                                    <input type="date" id="date_debut_1" name="date_debut" required 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                                <div>
                                    <label for="date_fin_1">Au:</label>
                                    <input type="date" id="date_fin_1" name="date_fin" required 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                            <button type="submit" name="louer" class="btn-rent">Louer</button>
                        </form>
                    </div>
                </div>

                <!-- Raquette Tennis Pro -->
                <div class="equipment-card" data-type="tennis">
                    <img src="image2/tennis.jpg" alt="Raquette Tennis Pro" class="equipment-image">
                    <div class="equipment-info">
                        <span class="equipment-type">Tennis</span>
                        <h3>Raquette Tennis Pro</h3>
                        <div class="equipment-meta">
                            <span><i class="fas fa-tag"></i> 15.00€/jour</span>
                            <span><i class="fas fa-box-open"></i> En stock</span>
                        </div>
                        <p class="equipment-description">Raquette Wilson Pro Staff + tube de balles</p>
                        
                        <form method="post" action="location.php" class="reservation-form">
                            <input type="hidden" name="equipement_id" value="2">
                            <div class="date-fields">
                                <div>
                                    <label for="date_debut_2">Du:</label>
                                    <input type="date" id="date_debut_2" name="date_debut" required 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                                <div>
                                    <label for="date_fin_2">Au:</label>
                                    <input type="date" id="date_fin_2" name="date_fin" required 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                            <button type="submit" name="louer" class="btn-rent">Louer</button>
                        </form>
                    </div>
                </div>

                <!-- Sac à Dos Randonnée -->
                <div class="equipment-card" data-type="randonnee">
                    <img src="image2/randonnee.jpg" alt="Sac à Dos Randonnée" class="equipment-image">
                    <div class="equipment-info">
                        <span class="equipment-type">Randonnée</span>
                        <h3>Sac à Dos Randonnée</h3>
                        <div class="equipment-meta">
                            <span><i class="fas fa-tag"></i> 10.00€/jour</span>
                            <span><i class="fas fa-box-open"></i> En stock</span>
                        </div>
                        <p class="equipment-description">Sac 60L étanche avec porte-gourde</p>
                        
                        <form method="post" action="location.php" class="reservation-form">
                            <input type="hidden" name="equipement_id" value="3">
                            <div class="date-fields">
                                <div>
                                    <label for="date_debut_3">Du:</label>
                                    <input type="date" id="date_debut_3" name="date_debut" required 
                                           min="<?= date('Y-m-d') ?>">
                                </div>
                                <div>
                                    <label for="date_fin_3">Au:</label>
                                    <input type="date" id="date_fin_3" name="date_fin" required 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                            <button type="submit" name="louer" class="btn-rent">Louer</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 SPORT RENT - Tous droits réservés</p>
    </footer>

    <script src="location.js"></script>
</body>
</html>
