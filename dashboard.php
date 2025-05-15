
<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    $not_logged_in = true;
} else {
    // Récupérer les réservations d'équipements
    $stmt_equipements = $pdo->prepare("SELECT r.*, e.nom as equipement_nom 
                                      FROM reservations r
                                      JOIN equipements e ON r.equipement_id = e.id
                                      WHERE r.user_id = ? AND r.equipement_id IS NOT NULL");
    $stmt_equipements->execute([$_SESSION['user']['id']]);
    $reservations_equipements = $stmt_equipements->fetchAll();
    
    // Récupérer les inscriptions aux événements
    $stmt_evenements = $pdo->prepare("SELECT r.*, ev.titre as evenement_titre, ev.date as evenement_date, ev.lieu as evenement_lieu
                                     FROM reservations r
                                     JOIN evenements ev ON r.evenement_id = ev.id
                                     WHERE r.user_id = ? AND r.evenement_id IS NOT NULL");
    $stmt_evenements->execute([$_SESSION['user']['id']]);
    $reservations_evenements = $stmt_evenements->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SPORT RENT</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="background-image"></div>

    <header>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="evenements.php">Événements</a></li>
                <li><a href="location.php">Location d'Équipements</a></li>
                <li><a href="dashboard.php">Espace Personnel</a></li>
                <li><a href="inscription.php">S'inscrire</a></li>
                <li><a href="connexion.php" class="active">Connexion</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-container">
    <?php if (isset($not_logged_in) && $not_logged_in): ?>
        <!-- ... (code existant pour les non connectés) ... -->
    <?php else: ?>

        <?php if (isset($_GET['message_envoye'])): ?>
            <?php if ($_GET['message_envoye'] == 1): ?>
                <p style="color:green;">Votre message a été envoyé avec succès.</p>
            <?php else: ?>
                <p style="color:red;">Erreur lors de l'envoi du message.</p>
            <?php endif; ?>
        <?php endif; ?>

        <h1>Mon Espace Personnel</h1>

        <div class="dashboard-grid">
            <section class="reservations">
                <h2>Mes Réservations d'Équipements</h2>
                <div class="reservation-list">
                    <?php if ($reservations_equipements): ?>
                        <ul>
                            <?php foreach ($reservations_equipements as $reservation): ?>
                                <li>
                                    <p><strong>Équipement :</strong> <?= htmlspecialchars($reservation['equipement_nom']) ?></p>
                                    <p><strong>Date de début :</strong> <?= htmlspecialchars($reservation['date_debut']) ?></p>
                                    <p><strong>Date de fin :</strong> <?= htmlspecialchars($reservation['date_fin']) ?></p>
                                    <p><strong>Statut :</strong> <?= htmlspecialchars($reservation['statut']) ?></p>

                                    <form action="annuler_reservation.php" method="post" onsubmit="return confirm('Confirmer l\'annulation de cette réservation ?');">
                                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                        <button type="submit">Annuler</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune réservation d'équipement trouvée.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="events">
                <h2>Mes Événements Inscrits</h2>
                <div class="event-list">
                    <?php if ($reservations_evenements): ?>
                        <ul>
                            <?php foreach ($reservations_evenements as $inscription): ?>
                                <li>
                                    <p><strong>Événement :</strong> <?= htmlspecialchars($inscription['evenement_titre']) ?></p>
                                    <p><strong>Sport :</strong> <?= htmlspecialchars($inscription['sport']) ?></p>
                                    <p><strong>Date :</strong> <?= htmlspecialchars($inscription['evenement_date']) ?></p>
                                    <p><strong>Lieu :</strong> <?= htmlspecialchars($inscription['evenement_lieu']) ?></p>
                                    <p><strong>Statut :</strong> <?= htmlspecialchars($inscription['statut']) ?></p>

                                    <form action="annuler_evenement.php" method="post" onsubmit="return confirm('Confirmer l\'annulation de votre inscription à cet événement ?');">
                                        <input type="hidden" name="reservation_id" value="<?= $inscription['id'] ?>">
                                        <button type="submit">Annuler l'inscription</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Vous n'êtes inscrit à aucun événement.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="profile">
                <h2>Mon Profil</h2>
                <div class="profile-info">
                    <p><strong>Nom :</strong> <?= htmlspecialchars($_SESSION['user']['nom']) ?></p>
                    <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
                    <p><strong>Rôle :</strong> <?= htmlspecialchars($_SESSION['user']['role']) ?></p>
                </div>
                <button id="edit-profile" class="btn">Modifier le profil</button>
                <script>
                    document.getElementById('edit-profile').addEventListener('click', function() {
                        window.location.href = 'edit_profile.php';
                    });
                </script>
            </section>

            <!-- 🔽 NOUVELLE SECTION "Nous contacter" -->
            <section class="contact">
    <h2>Nous contacter</h2>
    <form method="post" action="envoyer_message.php" onsubmit="return validateMessage()">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
        <input type="hidden" name="nom" value="<?= htmlspecialchars($_SESSION['user']['nom']) ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">

        <label for="message">Votre message :</label><br>
        <textarea name="message" id="message" rows="5" cols="60" maxlength="300" required oninput="updateCharCount()"></textarea><br>
        <div id="charCount">300 caractères restants</div><br>

        <button type="submit">Envoyer</button> <!-- ← CE BOUTON DOIT ÊTRE À CET ENDROIT -->
    </form>

    <script>
        function updateCharCount() {
            const textarea = document.getElementById('message');
            const remaining = 300 - textarea.value.length;
            const counter = document.getElementById('charCount');
            counter.textContent = remaining + ' caractères restants';
            counter.style.color = remaining <= 50 ? 'red' : 'black';
        }

        function validateMessage() {
            const message = document.getElementById('message').value.trim();
            if (message.length === 0) {
                alert("Le message ne peut pas être vide.");
                return false;
            }
            if (message.length > 300) {
                alert("Le message ne peut pas dépasser 300 caractères.");
                return false;
            }
            return true;
        }
    </script>
</section>
        </div>
    <?php endif; ?>
    </main>
</body>
</html>
