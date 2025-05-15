<?php
require 'db_connect.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $message = "Tous les champs sont obligatoires !";
    } else {
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Format d'email invalide !";
        } 
        elseif ($password !== $confirm_password) {
            $message = "Les mots de passe ne correspondent pas !";
        }
        // Vérification de la complexité du mot de passe
        elseif (strlen($password) < 12) {
            $message = "Le mot de passe doit contenir au moins 12 caractères !";
        }
        elseif (!preg_match('/[A-Z]/', $password)) {
            $message = "Le mot de passe doit contenir au moins une majuscule !";
        }
        elseif (!preg_match('/[a-z]/', $password)) {
            $message = "Le mot de passe doit contenir au moins une minuscule !";
        }
        elseif (!preg_match('/[0-9]/', $password)) {
            $message = "Le mot de passe doit contenir au moins un chiffre !";
        }
        // vérifications ici (caractères spéciaux, etc.)
        else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            try {
                $checkStmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
                $checkStmt->execute([$email]);

                if ($checkStmt->fetch()) {
                    $message = "Cet email est déjà utilisé !";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");

                    if ($stmt->execute([$nom, $email, $passwordHash])) {
                        header('Location: InscriptonAccuse.php?success=1');
                        exit;
                    } else {
                        $message = "Erreur lors de l'inscription.";
                    }
                }
            } catch (PDOException $e) {
                $message = "Erreur d'inscription : " . $e->getMessage();
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
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="inscription.css">
  
    <script>
        function checkPasswordRequirements() {
            const password = document.getElementById('password').value;
            const requirements = {
                length: password.length >= 12,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };
            
            // Mise à jour de l'affichage des exigences
            document.getElementById('req-length').className = requirements.length ? 'requirement valid' : 'requirement invalid';
            document.getElementById('req-uppercase').className = requirements.uppercase ? 'requirement valid' : 'requirement invalid';
            document.getElementById('req-lowercase').className = requirements.lowercase ? 'requirement valid' : 'requirement invalid';
            document.getElementById('req-number').className = requirements.number ? 'requirement valid' : 'requirement invalid';
        }
    </script>
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
                    <input type="password" name="password" id="password" required oninput="checkPasswordRequirements()">
                    
                    <div class="password-requirements">
                        <h4>Exigences du mot de passe :</h4>
                        <p id="req-length" class="requirement invalid">✓ 12 caractères minimum</p>
                        <p id="req-uppercase" class="requirement invalid">✓ Au moins une majuscule</p>
                        <p id="req-lowercase" class="requirement invalid">✓ Au moins une minuscule</p>
                        <p id="req-number" class="requirement invalid">✓ Au moins un chiffre</p>
                    </div>
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