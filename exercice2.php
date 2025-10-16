<?php
/**
 * Nom : Letanneur
 * Prenom : Leo
 * Fichier : exercice2.php
 * Date : 2025-10-16
 * Description : Gestion simplifiee d'un avion avec contraintes de vitesse et altitude.
 */

// Classe de base representant un vehicule generique.
abstract class Vehicule
{
    protected $demarrer = false;

    protected $vitesse = 0;

    protected $vitesseMax;

    abstract public function decelerer($vitesse);

    abstract public function accelerer($vitesse);

    // Demarre le vehicule.
    public function demarrer()
    {
        $this->demarrer = true;
    }

    // Eteint le vehicule.
    public function eteindre()
    {
        $this->demarrer = false;
        $this->vitesse = 0;
    }

    // Retourne vrai si le vehicule est demarre.
    public function isDemarre()
    {
        return $this->demarrer;
    }

    // Retourne la vitesse actuelle.
    public function getVitesse()
    {
        return $this->vitesse;
    }

    // Met a jour la vitesse actuelle.
    protected function setVitesse($vitesse)
    {
        $this->vitesse = max(0, (int) $vitesse);
    }

    // Retourne la vitesse maximale.
    public function getVitesseMax()
    {
        return $this->vitesseMax;
    }

    // Met a jour la vitesse maximale.
    protected function setVitesseMax($vitesseMax)
    {
        $this->vitesseMax = max(0, (int) $vitesseMax);
    }

    // Retourne une representation textuelle du vehicule.
    public function __toString()
    {
        $chaine = "Ceci est un vehicule <br/>";
        $chaine .= "---------------------- <br/>";

        return $chaine;
    }
}

// Classe representant un avion avec gestion de l'altitude et du train d'atterrissage.
class Avion extends Vehicule
{
    private $altitude = 0;

    private $trainAtterrissageSorti = true;

    // Initialise l'avion avec sa vitesse maximale.
    public function __construct($vitesseMax)
    {
        $this->setVitesseMax($vitesseMax);
    }

    // Retourne l'altitude actuelle.
    public function getAltitude()
    {
        return $this->altitude;
    }

    // Met a jour l'altitude actuelle.
    protected function setAltitude($altitude)
    {
        $this->altitude = max(0, (int) $altitude);
    }

    // Retourne vrai si le train est sorti.
    public function isTrainAtterrissageSorti()
    {
        return $this->trainAtterrissageSorti;
    }

    // Met a jour l'etat du train d'atterrissage.
    protected function setTrainAtterrissageSorti($etat)
    {
        $this->trainAtterrissageSorti = (bool) $etat;
    }

    // Accelere l'avion en respectant la vitesse maximale.
    public function accelerer($vitesse)
    {
        $vitesse = (int) $vitesse;

        if (! $this->isDemarre()) {
            throw new RuntimeException('Impossible d accelerer : le moteur est eteint.');
        }

        if ($vitesse <= 0) {
            throw new InvalidArgumentException('La vitesse a ajouter doit etre positive.');
        }

        $nouvelleVitesse = min($this->getVitesseMax(), $this->getVitesse() + $vitesse);
        $this->setVitesse($nouvelleVitesse);

        return $this->getVitesse();
    }

    // Decelere l'avion sans passer en dessous de 0.
    public function decelerer($vitesse)
    {
        $vitesse = (int) $vitesse;

        if (! $this->isDemarre()) {
            throw new RuntimeException('Impossible de decelerer : le moteur est eteint.');
        }

        if ($vitesse <= 0) {
            throw new InvalidArgumentException('La vitesse a retirer doit etre positive.');
        }

        $this->setVitesse($this->getVitesse() - $vitesse);

        return $this->getVitesse();
    }

    // Permet a l'avion de decoller lorsque la vitesse est suffisante.
    public function decoller()
    {
        if (! $this->isDemarre()) {
            throw new RuntimeException('Impossible de decoller moteur eteint.');
        }

        if ($this->getVitesse() < 120) {
            throw new RuntimeException('La vitesse doit atteindre 120 km/h pour decoller.');
        }

        if ($this->getAltitude() > 0) {
            throw new RuntimeException('L avion est deja en vol.');
        }

        $this->setAltitude(100);
    }

    // Permet de monter en altitude en controlant le train d'atterrissage.
    public function monter($gainAltitude)
    {
        $gainAltitude = (int) $gainAltitude;

        if ($gainAltitude <= 0) {
            throw new InvalidArgumentException('Le gain d altitude doit etre positif.');
        }

        if (! $this->isDemarre()) {
            throw new RuntimeException('Impossible de monter moteur eteint.');
        }

        if ($this->getAltitude() >= 300 && $this->isTrainAtterrissageSorti()) {
            throw new RuntimeException('Impossible de monter : le train doit etre rentre au dessus de 300 metres.');
        }

        $this->setAltitude($this->getAltitude() + $gainAltitude);
    }

    // Permet de descendre en altitude sans passer sous 0.
    public function descendre($perteAltitude)
    {
        $perteAltitude = (int) $perteAltitude;

        if ($perteAltitude <= 0) {
            throw new InvalidArgumentException('La perte d altitude doit etre positive.');
        }

        $altitudeCible = $this->getAltitude() - $perteAltitude;
        $this->setAltitude($altitudeCible);
    }

    // Rentre le train d'atterrissage.
    public function rentrerTrain()
    {
        $this->setTrainAtterrissageSorti(false);
    }

    // Sort le train d'atterrissage.
    public function sortirTrain()
    {
        $this->setTrainAtterrissageSorti(true);
    }

    // Tente un atterrissage en verifiant vitesse et altitude.
    public function atterrir()
    {
        if (! $this->isTrainAtterrissageSorti()) {
            throw new RuntimeException('Impossible d atterrir : train rentre.');
        }

        $vitesseActuelle = (int) $this->getVitesse();

        if ($vitesseActuelle < 80 || $vitesseActuelle > 110) {
            throw new RuntimeException('L atterrissage requiert une vitesse entre 80 et 110 km/h.');
        }

        $altitudeActuelle = (int) $this->getAltitude();

        if ($altitudeActuelle < 50 || $altitudeActuelle > 150) {
            throw new RuntimeException('L altitude doit etre comprise entre 50 et 150 metres pour atterrir.');
        }

        $this->setAltitude(0);
        $this->setVitesse(0);
    }

    // Retourne une representation detaillee de l'avion.
    public function __toString()
    {
        $chaine = parent::__toString();
        $chaine .= "Type : Avion <br/>";
        $chaine .= "Moteur : " . ($this->isDemarre() ? 'On' : 'Off') . " <br/>";
        $chaine .= "Vitesse : " . $this->getVitesse() . " km/h <br/>";
        $chaine .= "Altitude : " . $this->getAltitude() . " m <br/>";
        $chaine .= "Train : " . ($this->isTrainAtterrissageSorti() ? 'Sorti' : 'Rentre') . " <br/>";

        return $chaine;
    }
}

// Affiche l'etat courant de l'avion avec un libelle.
function afficherEtat($etape, Avion $avion)
{
    echo "<strong>" . $etape . "</strong><br/>";
    echo $avion;
}

// Exemple d'utilisation pour valider les fonctionnalites principales.
try {
    $avion = new Avion(900);

    $avion->demarrer();
    $avion->accelerer(130);
    $avion->decoller();
    afficherEtat('Apres decollage', $avion);

    $avion->rentrerTrain();
    $avion->monter(350);
    afficherEtat('Train rentre au dessus de 300 m', $avion);

    try {
        $avion->sortirTrain();
        $avion->monter(10);
    } catch (Exception $avertissement) {
        echo 'Controle train : ' . $avertissement->getMessage() . "<br/>";
        $avion->rentrerTrain();
    }

    $avion->descendre(300);
    $avion->sortirTrain();
    $avion->descendre(40);
    $avion->decelerer(20);
    afficherEtat('Approche finale', $avion);

    $avion->atterrir();
    $avion->eteindre();
    afficherEtat('Au sol', $avion);
} catch (Exception $exception) {
    echo 'Erreur inattendue : ' . $exception->getMessage() . "<br/>";
}
