<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_SESSION['user']['id'];

    // Vérifier que la réservation appartient bien à l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ? AND evenement_id IS NOT NULL");
    $stmt->execute([$reservation_id, $user_id]);
    $reservation = $stmt->fetch();

    if ($reservation) {
        // Supprimer la réservation de l'événement
        $delete = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $delete->execute([$reservation_id]);
        $_SESSION['success'] = "Inscription annulée avec succès.";
    } else {
        $_SESSION['error'] = "Erreur : inscription non trouvée.";
    }
}

header("Location: dashboard.php");
exit;
