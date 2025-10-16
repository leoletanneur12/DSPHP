<?php
/**
 * Nom : Letanneur
 * Prenom : Leo
 * Fichier : ManagerAvion.class.php
 * Date : 2025-10-16
 * Description : Gestion basique des avions en base via PDO.
 */

class ManagerAvion
{
    private $pdo;

    // Conserve l'instance PDO.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Cree la table si elle n'existe pas encore.
    public function creerTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS avions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT NOT NULL,
            pays_origine TEXT NOT NULL,
            annee_service INTEGER NOT NULL,
            constructeur TEXT NOT NULL
        )';

        $this->pdo->exec($sql);
    }

    // Supprime toutes les lignes pour faciliter les tests.
    public function supprimerTous()
    {
        $this->pdo->exec('DELETE FROM avions');
    }

    // Insere un avion dans la base et met a jour son id.
    public function inserer(Avion $avion)
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO avions (nom, pays_origine, annee_service, constructeur)
            VALUES (:nom, :pays, :annee, :constructeur)'
        );

        $requete->execute([
            ':nom' => $avion->getNom(),
            ':pays' => $avion->getPaysOrigine(),
            ':annee' => $avion->getAnneeService(),
            ':constructeur' => $avion->getConstructeur(),
        ]);

        $avion->setId((int) $this->pdo->lastInsertId());
    }

    // Retourne tous les avions stockes.
    public function recupererTous()
    {
        $resultat = $this->pdo->query('SELECT * FROM avions ORDER BY nom');
        $avions = [];

        while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
            $avions[] = new Avion([
                'id' => $ligne['id'],
                'nom' => $ligne['nom'],
                'paysOrigine' => $ligne['pays_origine'],
                'anneeService' => $ligne['annee_service'],
                'constructeur' => $ligne['constructeur'],
            ]);
        }

        return $avions;
    }
}
