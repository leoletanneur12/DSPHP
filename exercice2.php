<?php
/**
 * Nom : Letanneur
 * Prénom : Leo
 * Fichier : exercice2.php
 * Date : 2025-10-16
 * Description : Gestion des véhicules et ajout d'un avion.
 */

// Classe représentant un véhicule générique.
abstract class Vehicule
{
    protected $demarrer = false;

    protected $vitesse = 0;

    protected $vitesseMax = 0;

    abstract public function decelerer($vitesse);

    abstract public function accelerer($vitesse);

    // Démarre le véhicule.
    public function demarrer()
    {
        $this->setDemarrer(true);
    }

    // Éteint le véhicule.
    public function eteindre()
    {
        $this->setDemarrer(false);
        $this->setVitesse(0);
    }

    // Indique si le véhicule est démarré.
    public function estDemarre()
    {
        return $this->demarrer;
    }

    // Retourne la vitesse actuelle.
    public function getVitesse()
    {
        return $this->vitesse;
    }

    // Retourne la vitesse maximale.
    public function getVitesseMax()
    {
        return $this->vitesseMax;
    }

    // Met à jour la vitesse actuelle.
    protected function setVitesse($vitesse)
    {
        $this->vitesse = max(0, (int) $vitesse);
    }

    // Met à jour la vitesse maximale.
    protected function setVitesseMax($vitesseMax)
    {
        $this->vitesseMax = max(0, (int) $vitesseMax);
    }

    // Met à jour l'état du contact.
    protected function setDemarrer($etat)
    {
        $this->demarrer = (bool) $etat;
    }

    // Retourne une représentation textuelle du véhicule.
    public function __toString()
    {
        $chaine = $this->formatLine('Ceci est un véhicule');
        $chaine .= $this->formatLine('----------------------');

        return $chaine;
    }

    // Prépare une ligne en fonction du contexte (CLI ou navigateur).
    protected function formatLine($texte)
    {
        return $texte . $this->getLineBreak();
    }

    // Retourne le séparateur adapté (saut de ligne ou balise HTML).
    protected function getLineBreak()
    {
        return PHP_SAPI === 'cli' ? PHP_EOL : '<br/>';
    }
}

// Classe représentant un avion.
class Avion extends Vehicule
{
    private $altitude = 0;

    private $altitudeMax = 0;

    private $trainSorti = true;

    // Initialise un avion avec ses limites de vitesse et d'altitude.
    public function __construct($vitesseMax, $altitudeMax)
    {
        $this->setVitesseMax($vitesseMax);
        $this->setAltitudeMax($altitudeMax);
    }

    // Accélère l'avion dans les limites autorisées.
    public function accelerer($vitesse)
    {
        if (! $this->estDemarre() || $this->getAltitude() === 0) {
            return false;
        }

        if ($this->getTrainSorti()) {
            return false;
        }

        $vitesse = (int) $vitesse;

        if ($vitesse <= 0) {
            return false;
        }

        $nouvelleVitesse = min($this->getVitesseMax(), $this->getVitesse() + $vitesse);
        $this->setVitesse($nouvelleVitesse);

        return true;
    }

    // Décélère l'avion sans passer sous 0 km/h.
    public function decelerer($vitesse)
    {
        if (! $this->estDemarre()) {
            return false;
        }

        $vitesse = (int) $vitesse;

        if ($vitesse <= 0) {
            return false;
        }

        $this->setVitesse($this->getVitesse() - $vitesse);

        return true;
    }

    // Décolle si la machine est démarrée et encore au sol.
    public function decoller()
    {
        if (! $this->estDemarre() || $this->getAltitude() > 0) {
            return false;
        }

        $this->setAltitude(100);
        $this->rentrerTrainAtterrissage();

        return true;
    }

    // Atterrit immédiatement.
    public function atterrir()
    {
        if ($this->getAltitude() === 0) {
            return false;
        }

        $this->setAltitude(0);
        $this->setVitesse(0);
        $this->sortirTrainAtterrissage();

        return true;
    }

    // Augmente l'altitude sans dépasser le plafond.
    public function prendreAltitude($valeur)
    {
        if ($this->getAltitude() === 0) {
            return false;
        }

        $valeur = (int) $valeur;

        if ($valeur <= 0) {
            return false;
        }

        $nouvelleAltitude = min($this->getAltitudeMax(), $this->getAltitude() + $valeur);
        $this->setAltitude($nouvelleAltitude);

        return true;
    }

    // Diminue l'altitude sans passer sous 0 m.
    public function perdreAltitude($valeur)
    {
        if ($this->getAltitude() === 0) {
            return false;
        }

        $valeur = (int) $valeur;

        if ($valeur <= 0) {
            return false;
        }

        $this->setAltitude($this->getAltitude() - $valeur);

        if ($this->getAltitude() === 0) {
            $this->sortirTrainAtterrissage();
        }

        return true;
    }

    // Sort le train d'atterrissage.
    public function sortirTrainAtterrissage()
    {
        $this->setTrainSorti(true);

        return true;
    }

    // Rentre le train d'atterrissage.
    public function rentrerTrainAtterrissage()
    {
        $this->setTrainSorti(false);

        return true;
    }

    // Retourne l'altitude actuelle.
    public function getAltitude()
    {
        return $this->altitude;
    }

    // Retourne l'altitude maximale.
    public function getAltitudeMax()
    {
        return $this->altitudeMax;
    }

    // Indique si le train est sorti.
    public function getTrainSorti()
    {
        return $this->trainSorti;
    }

    // Prépare une description textuelle de l'avion.
    public function __toString()
    {
        $ligne = parent::__toString();
        $ligne .= $this->formatLine('Type : Avion');
        $ligne .= $this->formatLine('Démarré : ' . ($this->estDemarre() ? 'Oui' : 'Non'));
        $ligne .= $this->formatLine('Vitesse : ' . $this->getVitesse() . ' km/h');
        $ligne .= $this->formatLine('Vitesse max : ' . $this->getVitesseMax() . ' km/h');
        $ligne .= $this->formatLine('Altitude : ' . $this->getAltitude() . ' m');
        $ligne .= $this->formatLine('Plafond : ' . $this->getAltitudeMax() . ' m');
        $ligne .= $this->formatLine('Train sorti : ' . ($this->getTrainSorti() ? 'Oui' : 'Non'));

        return $ligne;
    }

    // Met à jour l'altitude courante.
    protected function setAltitude($altitude)
    {
        $this->altitude = max(0, min((int) $altitude, $this->getAltitudeMax()));
    }

    // Met à jour le plafond de l'avion.
    protected function setAltitudeMax($altitudeMax)
    {
        $altitudeMax = max(0, (int) $altitudeMax);
        $this->altitudeMax = min(40000, $altitudeMax);
    }

    // Met à jour l'état du train d'atterrissage.
    protected function setTrainSorti($etat)
    {
        $this->trainSorti = (bool) $etat;
    }

    // Encadre la vitesse maximale des avions.
    protected function setVitesseMax($vitesseMax)
    {
        parent::setVitesseMax(min(2000, (int) $vitesseMax));
    }
}

// Exemple d'utilisation simple.
$separator = PHP_SAPI === 'cli' ? PHP_EOL : '<br/>';

$avion = new Avion(950, 12000);
$avion->demarrer();
$avion->decoller();
$avion->rentrerTrainAtterrissage();
$avion->accelerer(300);
$avion->prendreAltitude(2000);
echo $avion . $separator;

$avion->perdreAltitude(1500);
$avion->sortirTrainAtterrissage();
$avion->atterrir();
echo $avion . $separator;
