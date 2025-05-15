<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SPORT RENT</title>
    <link rel="stylesheet" href="accueil.css"> <!-- Style spécifique pour la page de connexion -->
    <link rel="stylesheet" href="connexion.css"> <!-- Style général de l'accueil -->
</head>
<body>
    <div class="background-image"></div> <!-- Fond d'écran -->

    <header>
        <nav>
            <ul>
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="evenements.php">Événements</a></li>
                <li><a href="location.php">Location d'Équipements</a></li>
                <li><a href="dashboard.php">Espace Personnel</a></li>
                <li><a href="inscription.php">S'inscrire</a></li>
                <li><a href="connexion.php" class="active">Connexion</a></li>
                <li><a href="statistiques.php">Statistiques - SportRent</a></li>

            </ul>
        </nav>
    </header>

    <main>
        <section id="connexion" class="auth-container">
            <div class="auth-card">
                <h1>Se connecter</h1>

                <!-- Affichage des messages d'erreur -->
                <?php if (!empty($message)): ?>
                    <p class="error-message"><?= htmlspecialchars($message) ?></p>
                <?php endif; ?>

                <!-- Formulaire de connexion -->
                <form id="form-connexion" method="post" action="connexion.php">
                    <div class="form-group">
                        <label for="email">Email :</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe :</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                    </div>

                    <button type="submit">Se connecter</button>
                </form>

                <div class="auth-link">
                    <p>Pas encore de compte ? <a href="inscription.php">Créer un compte</a></p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Gestion d'Événements. Tous droits réservés.</p>
    </footer>

    <script src="script.js"></script> <!-- Si tu as un fichier JS pour des interactions supplémentaires -->
</body>
</html>

<?php
session_start();
require 'db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['mot_de_passe'];
    
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'nom' => $user['nom'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            
            // Redirection en fonction du rôle
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php'); // Créez cette page pour l'admin
            } else {
                header('Location: dashboard.php'); // Page normale pour les utilisateurs
            }
            exit;
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Aucun compte trouvé avec cet email.";
    }
}
?>