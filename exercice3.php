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

// Parametres de connexion a adapter selon votre environnement.
$dsn = 'mysql:host=localhost;dbname=dphp;charset=utf8mb4';
$utilisateur = 'root';
$motDePasse = '';

try {
    $pdo = new PDO($dsn, $utilisateur, $motDePasse);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    die('Erreur connexion BDD : ' . $exception->getMessage());
}

$manager = new ManagerAvion($pdo);
// La table "avions" doit deja exister dans la base.

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

// Exemple de script SQL attendu pour la table :
// CREATE TABLE avions (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     nom VARCHAR(100) NOT NULL,
//     pays_origine VARCHAR(100) NOT NULL,
//     annee_service INT NOT NULL,
//     constructeur VARCHAR(150) NOT NULL
// );
