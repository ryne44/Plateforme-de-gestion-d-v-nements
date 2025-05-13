<?php
require 'db_connect.php'; // Assure-toi que ce fichier contient bien $pdo
session_start(); // Pour stocker les messages

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = ""; // Stocker le message d'erreur ou de succès

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs sont remplis
    if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $message = "Tous les champs sont obligatoires !";
    } else {
        // Récupérer et nettoyer les données
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Vérifier si l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Format d'email invalide !";
        } 
        // Vérifier si les mots de passe correspondent
        elseif ($password !== $confirm_password) {
            $message = "Les mots de passe ne correspondent pas !";
        } 
        else {
            // Hacher le mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Vérifier si l'email existe déjà
                $checkStmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
                $checkStmt->execute([$email]);

                if ($checkStmt->fetch()) {
                    $message = "Cet email est déjà utilisé !";
                } else {
                    // Insérer l'utilisateur dans la base
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");

                    if ($stmt->execute([$nom, $email, $passwordHash])) {
                        // Rediriger vers InscriptonAccuse.php avec un message de succès
                        header('Location: InscriptonAccuse.php?success=1');
                        exit;
                    } else {
                        $message = "Erreur lors de l'inscription.";
                    }
                }
            } catch (PDOException $e) {
                $message = "Erreur d'inscription : " . $e->getMessage(); // Affiche l'erreur SQL en cas de problème
            }
        }
    }
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
        <img src="image/logo.jpg" alt="SPORT RENT" class="logo">
        <nav>
            <a href="accueil.php">Accueil</a>
            <a href="inscription.php" class="active">Inscription</a>
        </nav>   
    </header>

    <main class="auth-container">
        <div class="auth-card">
            <h1>Inscription</h1>

            <!-- Affichage des messages d'erreur -->
            <?php if (!empty($message)): ?>
                <p class="error-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <form method="post" action="inscription.php">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" name="nom" id="nom" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">S'inscrire</button>
            </form>
            
            <p class="auth-link">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
        </div>
    </main>

  
</body>
</html>