<?php
$host = 'localhost';  // Serveur MySQL (local)
$dbname = 'sportrent'; // Remplace par le vrai nom de ta base de données
$username = 'root';
$password = 'root'; // Mets ton mot de passe ici (ou laisse vide si tu n'en as pas)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>