<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/../arbres.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}