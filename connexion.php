<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion d'Événements et Location</title>
    <link rel="stylesheet" href="connexion.css">
    <link rel="stylesheet" href="acceuil.css">
</head>
<body>
    <header>
       
        <nav>
            <ul>
                <li><a href="acceuil.html">Accueil</a></li>
                <li><a href="evenements.html">Événements</a></li>
                <li><a href="location.html">Location d'Équipements</a></li>
                <li><a href="dashboard.html">Espace Personnel</a></li>
                <li><a href="inscription.html">S'inscrire</a></li>
                <li><a href="connexion.html">Connexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="connexion">
            <h2>Se connecter</h2>
            <form id="form-connexion" method="post" action="process_connexion.php">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required><br><br>

                <button type="submit">Se connecter</button>
            </form>
            <p>Pas encore de compte ? <a href="inscription.html">Créer un compte</a></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Gestion d'Événements. Tous droits réservés.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>

<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!-- Formulaire de connexion HTML -->
