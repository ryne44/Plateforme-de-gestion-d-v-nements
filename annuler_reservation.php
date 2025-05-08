<?php
session_start();
require 'db_connect.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie que l'ID de la réservation est fourni
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_SESSION['user']['id'];

    // Vérifie que la réservation appartient bien à l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        // Mise à jour du statut à "annulée"
        $update_stmt = $pdo->prepare("UPDATE reservations SET statut = 'annulée' WHERE id = ?");
        $update_stmt->execute([$reservation_id]);

        $_SESSION['message'] = "La réservation a bien été annulée.";
    } else {
        $_SESSION['message'] = "Réservation introuvable ou non autorisée.";
    }
} else {
    $_SESSION['message'] = "Requête invalide.";
}

// Redirige vers le dashboard
header("Location: dashboard.php");
exit();
?>
