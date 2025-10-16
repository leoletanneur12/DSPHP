<?php
/**
 * Nom : Letanneur
 * Prenom : Leo
 * Fichier : exercice3.php
 * Date : 2025-10-16
 * Description : Test d'un mini ORM pour la classe Avion.
 */

require_once __DIR__ . '/Avion.class.php';
require_once __DIR__ . '/ManagerAvion.class.php';

// Etablit la connexion PDO. Adapter la DSN si besoin (MySQL, SQLite, etc.).
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/avions.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die('Erreur connexion BDD : ' . $exception->getMessage());
}

$manager = new ManagerAvion($pdo);
// La table "avions" doit déjà être créée dans la base (voir ManagerAvion::creerTable()).

// Jeu de donnees de demonstration.
$donneesAvions = [
    [
        'nom' => 'F4U Corsair',
        'paysOrigine' => 'Etats-Unis',
        'anneeService' => 1943,
        'constructeur' => 'Chance Vought Aircraft Division',
    ],
    [
        'nom' => 'Supermarine Spitfire',
        'paysOrigine' => 'Royaume-Uni',
        'anneeService' => 1938,
        'constructeur' => 'Supermarine Aviation Works',
    ],
    [
        'nom' => 'Mitsubishi A6M Zero',
        'paysOrigine' => 'Japon',
        'anneeService' => 1940,
        'constructeur' => 'Mitsubishi Heavy Industries',
    ],
];

foreach ($donneesAvions as $donnees) {
    $avion = new Avion();
    $avion->hydrate($donnees);
    $manager->inserer($avion);
}

// Recuperation et affichage des avions.
$avions = $manager->recupererTous();

echo '<h1>Listing des avions</h1>';

foreach ($avions as $avion) {
    echo 'ID : ' . $avion->getId() . '<br/>';
    echo 'Nom : ' . $avion->getNom() . '<br/>';
    echo 'Pays : ' . $avion->getPaysOrigine() . '<br/>';
    echo 'Mise en service : ' . $avion->getAnneeService() . '<br/>';
    echo 'Constructeur : ' . $avion->getConstructeur() . '<br/>';
    echo '------------------------------<br/>';
}
