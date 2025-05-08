<?php
session_start();
require 'db_connect.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier que le formulaire a été soumis correctement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $user_id = $_SESSION['user']['id'];
    $nom = $_SESSION['user']['nom'];
    $email = $_SESSION['user']['email'];
    $message = trim($_POST['message']);
    $date_message = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (user_id, nom, email, message, date_message) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $nom, $email, $message, $date_message]);

        // Redirection avec message de succès
        header("Location: dashboard.php?message_envoye=1");
        exit;
    } catch (PDOException $e) {
        // En cas d'erreur : log et redirection avec message d'échec
        error_log("Erreur lors de l'envoi du message : " . $e->getMessage());
        header("Location: dashboard.php?message_envoye=0");
        exit;
    }
} else {
    // Formulaire invalide ou message vide
    header("Location: dashboard.php?message_envoye=0");
    exit;
}
