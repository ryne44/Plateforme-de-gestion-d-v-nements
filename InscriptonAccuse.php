<?php
session_start(); // Pour utiliser les variables de session si nécessaire

// Vérification si la redirection a eu lieu avec un succès
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    $messageClass = "success-message";
} else {
    $message = "Il y a eu un problème lors de l'inscription. Veuillez réessayer.";
    $messageClass = "error-message";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - SPORT RENT</title>
    <!-- Lien vers les fichiers CSS -->
    <link rel="stylesheet" href="accueil.css"> <!-- Style de la page d'accueil -->
    <link rel="stylesheet" href="inscription.css"> <!-- Style spécifique à l'inscription -->
</head>
<body>
    <header>
        <img src="images/Capture.jpg" alt="SPORT RENT" class="logo">
        <nav>
            <a href="accueil.php">Accueil</a>
            <a href="inscription.php">Inscription</a>
        </nav>   
    </header>

    <main class="auth-container">
        <div class="auth-card">
            <h1>Accusé de réception</h1>

            <!-- Affichage du message de succès ou d'erreur -->
            <p class="<?= $messageClass; ?>"><?= htmlspecialchars($message); ?></p>

            <p>Vous pouvez maintenant <a href="connexion.php">vous connecter</a> avec votre compte.</p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 SPORT RENT. Tous droits réservés.</p>
    </footer>
</body>
</html>
