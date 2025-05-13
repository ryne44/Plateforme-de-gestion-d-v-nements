<?php
session_start();
require 'db_connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$message = '';
$success = false;

// Récupérer les informations actuelles de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation de base
    if (empty($nom) || empty($email)) {
        $message = "Le nom et l'email sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'email invalide.";
    } else {
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $message = "Cet email est déjà utilisé par un autre compte.";
        } else {
            // Si l'utilisateur souhaite modifier son mot de passe
            if (!empty($current_password)) {
                // Vérifier que l'ancien mot de passe est correct
                if (!password_verify($current_password, $user['mot_de_passe'])) {
                    $message = "Le mot de passe actuel est incorrect.";
                } elseif (empty($new_password) || empty($confirm_password)) {
                    $message = "Veuillez remplir tous les champs de mot de passe.";
                } elseif ($new_password !== $confirm_password) {
                    $message = "Les nouveaux mots de passe ne correspondent pas.";
                } else {
                    // Tout est valide, mettre à jour avec le nouveau mot de passe
                    $passwordHash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
                    if ($stmt->execute([$nom, $email, $passwordHash, $user_id])) {
                        $_SESSION['user']['nom'] = $nom;
                        $_SESSION['user']['email'] = $email;
                        $message = "Profil et mot de passe mis à jour avec succès !";
                        $success = true;
                    } else {
                        $message = "Erreur lors de la mise à jour du profil.";
                    }
                }
            } else {
                // Mise à jour sans changer le mot de passe
                $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
                if ($stmt->execute([$nom, $email, $user_id])) {
                    $_SESSION['user']['nom'] = $nom;
                    $_SESSION['user']['email'] = $email;
                    $message = "Profil mis à jour avec succès !";
                    $success = true;
                } else {
                    $message = "Erreur lors de la mise à jour du profil.";
                }
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
    <title>Modifier le profil - SPORT RENT</title>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="inscription.css">
    <style>
        .edit-profile-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .password-section {
            margin-top: 2rem;
            border-top: 1px solid #eee;
            padding-top: 1.5rem;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">
                <img src="image/logo.jpg" alt="SPORT RENT Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="accueil.php">Accueil</a></li>
                    <li><a href="evenements.php">Événements</a></li>
                    <li><a href="location.php">Location</a></li>
                    <li><a href="dashboard.php" class="active">Espace Perso</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="edit-profile-container">
            <h1>Modifier mon profil</h1>

            <?php if (!empty($message)): ?>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="edit_profile.php">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="password-section">
                    <h3>Changer de mot de passe</h3>
                    <p>Laissez vide si vous ne souhaitez pas changer de mot de passe</p>

                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>

                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>

                <div class="buttons">
                    <a href="dashboard.php" class="btn" style="background-color: #6c757d;">Annuler</a>
                    <button type="submit" class="btn">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 SPORT RENT - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>