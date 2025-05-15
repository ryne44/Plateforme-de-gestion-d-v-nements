<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db_connect.php'; // définit $pdo

// 1. Nombre total d'utilisateurs
$reqUsers = $pdo->query("SELECT COUNT(*) AS total_users FROM utilisateurs");
$totalUsers = $reqUsers->fetch(PDO::FETCH_ASSOC)['total_users'];

// 2. Nombre de réservations par équipement (classées du plus grand au plus petit)
$reqReservations = $pdo->query("
    SELECT e.nom AS equipement, COUNT(r.id) AS total_reservations
    FROM reservations r
    JOIN equipements e ON r.equipement_id = e.id
    GROUP BY e.nom
    ORDER BY total_reservations DESC
");
$reservationsData = $reqReservations->fetchAll(PDO::FETCH_ASSOC);

// 3. Nombre d'utilisateurs connectés ce mois-ci (via table connexions)
$currentMonth = date('m');
$currentYear = date('Y');

$reqConnected = $pdo->prepare("
    SELECT COUNT(DISTINCT user_id) AS utilisateurs_connectes
    FROM connexions
    WHERE MONTH(date_connexion) = :mois AND YEAR(date_connexion) = :annee
");
$reqConnected->execute([
    'mois' => $currentMonth,
    'annee' => $currentYear
]);
$utilisateursConnectes = $reqConnected->fetch(PDO::FETCH_ASSOC)['utilisateurs_connectes'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques - SportRent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #444;
            margin-top: 40px;
        }
        table {
            width: 60%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>Statistiques de la plateforme SportRent</h1>

    <h2>Nombre total d'utilisateurs</h2>
    <p><strong><?= $totalUsers ?></strong> utilisateurs inscrits.</p>

    <h2>Réservations par équipement</h2>
    <table>
        <thead>
            <tr>
                <th>Équipement</th>
                <th>Nombre de réservations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservationsData as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['equipement']) ?></td>
                    <td><?= $row['total_reservations'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Utilisateurs connectés ce mois-ci</h2>
    <p><strong><?= $utilisateursConnectes ?></strong> utilisateurs se sont connectés en <?= date('F Y') ?>.</p>
</body>
</html>