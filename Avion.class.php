<?php
/**
 * Nom : Letanneur
 * Prenom : Leo
 * Fichier : Avion.class.php
 * Date : 2025-10-16
 * Description : Representation d'un avion pour l'ORM basique.
 */

class Avion
{
    private $id;

    private $nom = '';

    private $paysOrigine = '';

    private $anneeService = 0;

    private $constructeur = '';

    // Initialise l'avion via un tableau optionnel.
    public function __construct(array $donnees = [])
    {
        if (! empty($donnees)) {
            $this->hydrate($donnees);
        }
    }

    // Renseigne les proprietes depuis un tableau cle => valeur.
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $cle => $valeur) {
            $methode = 'set' . ucfirst($cle);

            if (method_exists($this, $methode)) {
                $this->$methode($valeur);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id !== null ? (int) $id : null;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = trim((string) $nom);
    }

    public function getPaysOrigine()
    {
        return $this->paysOrigine;
    }

    public function setPaysOrigine($paysOrigine)
    {
        $this->paysOrigine = trim((string) $paysOrigine);
    }

    public function getAnneeService()
    {
        return $this->anneeService;
    }

    public function setAnneeService($anneeService)
    {
        $annee = (int) $anneeService;

        if ($annee < 0) {
            $annee = 0;
        }

        $this->anneeService = $annee;
    }

    public function getConstructeur()
    {
        return $this->constructeur;
    }

    public function setConstructeur($constructeur)
    {
        $this->constructeur = trim((string) $constructeur);
    }

    // Raccourci pour recuperer les donnees sous forme de tableau.
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'nom' => $this->getNom(),
            'paysOrigine' => $this->getPaysOrigine(),
            'anneeService' => $this->getAnneeService(),
            'constructeur' => $this->getConstructeur(),
        ];
    }
}
