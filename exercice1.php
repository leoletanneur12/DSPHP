<?php
/**
 * Nom : À compléter
 * Prénom : À compléter
 * Fichier : exercice1.php
 * Date : 2025-10-16
 * Description : Gestion des règles de vitesse et frein de parking.
 */

/** Classe représentant un véhicule générique. */
abstract class Vehicule
{
    protected $demarrer = false;

    protected $vitesse = 0;

    protected $vitesseMax;

    abstract public function demarrer();

    abstract public function eteindre();

    abstract public function decelerer($vitesse);

    abstract public function accelerer($vitesse);

    /** Retourne une représentation textuelle du véhicule. */
    public function __toString()
    {
        $chaine = $this->formatLine('Ceci est un véhicule');
        $chaine .= $this->formatLine('----------------------');

        return $chaine;
    }

    /** Retourne l'état du contact. */
    public function isDemarre()
    {
        return $this->demarrer;
    }

    /** Met à jour l'état du contact. */
    protected function setDemarrer($etat)
    {
        $this->demarrer = (bool) $etat;
    }

    /** Retourne la vitesse actuelle. */
    public function getVitesse()
    {
        return $this->vitesse;
    }

    /** Met à jour la vitesse actuelle. */
    protected function setVitesse($vitesse)
    {
        $this->vitesse = max(0, (int) $vitesse);
    }

    /** Retourne la vitesse maximale. */
    public function getVitesseMax()
    {
        return $this->vitesseMax;
    }

    /** Met à jour la vitesse maximale. */
    protected function setVitesseMax($vitesseMax)
    {
        $this->vitesseMax = max(0, (int) $vitesseMax);
    }

    /** Retourne la chaîne formatée en fonction du contexte (CLI ou navigateur). */
    protected function formatLine($texte)
    {
        return $texte . $this->getLineBreak();
    }

    /** Retourne le séparateur adapté (saut de ligne ou balise HTML). */
    protected function getLineBreak()
    {
        return PHP_SAPI === 'cli' ? PHP_EOL : '<br/>';
    }
}

/** Classe concrète représentant une voiture. */
class Voiture extends Vehicule
{
    private static $nombreVoiture = 0;

    private $freinParking = true;

    /** Initialise la voiture avec sa vitesse maximale. */
    public function __construct($vitesseMax)
    {
        $this->setVitesseMax($vitesseMax);
        self::$nombreVoiture++;
    }

    /** Démarre le véhicule et désactive le frein de parking. */
    public function demarrer()
    {
        if ($this->isDemarre()) {
            return false;
        }

        $this->setDemarrer(true);
        $this->desactiverFreinParking();

        return true;
    }

    /** Éteint le véhicule et réactive le frein de parking. */
    public function eteindre()
    {
        if (! $this->isDemarre()) {
            return false;
        }

        $this->setDemarrer(false);
        $this->setVitesse(0);
        $this->activerFreinParking();

        return true;
    }

    /** Ralentit le véhicule sans descendre sous 0 km/h. */
    public function decelerer($vitesse)
    {
        $vitesse = (int) $vitesse;

        if ($vitesse <= 0 || ! $this->isDemarre()) {
            return false;
        }

        $this->setVitesse($this->getVitesse() - $vitesse);

        return true;
    }

    /** Accélère le véhicule en respectant la vitesse maximale et le frein de parking. */
    public function accelerer($vitesse)
    {
        $vitesse = (int) $vitesse;

        if ($vitesse <= 0 || ! $this->isDemarre() || $this->isFreinParkingActive()) {
            return false;
        }

        $nouvelleVitesse = min($this->getVitesseMax(), $this->getVitesse() + $vitesse);
        $this->setVitesse($nouvelleVitesse);

        return true;
    }

    /** Retourne le nombre de voitures instanciées. */
    public static function getNombreVoiture()
    {
        return self::$nombreVoiture;
    }

    /** Affiche l'état courant de la voiture. */
    public function __toString()
    {
        $chaine = parent::__toString();
        $chaine .= $this->formatLine('Type : Voiture');
        $chaine .= $this->formatLine('Démarrée : ' . ($this->isDemarre() ? 'Oui' : 'Non'));
        $chaine .= $this->formatLine('Frein de parking : ' . ($this->isFreinParkingActive() ? 'Activé' : 'Désactivé'));
        $chaine .= $this->formatLine('Vitesse actuelle : ' . $this->getVitesse() . ' km/h');
        $chaine .= $this->formatLine('Vitesse maximale : ' . $this->getVitesseMax() . ' km/h');

        return $chaine;
    }

    /** Indique si le frein de parking est actif. */
    public function isFreinParkingActive()
    {
        return $this->freinParking;
    }

    /** Active le frein de parking lorsque la voiture est immobile. */
    public function activerFreinParking()
    {
        if ($this->getVitesse() > 0) {
            return false;
        }

        if ($this->isFreinParkingActive()) {
            return true;
        }

        $this->setFreinParking(true);

        return true;
    }

    /** Désactive le frein de parking pour permettre le déplacement. */
    public function desactiverFreinParking()
    {
        if (! $this->isFreinParkingActive()) {
            return true;
        }

        $this->setFreinParking(false);

        return true;
    }

    /** Met à jour l'état du frein de parking. */
    protected function setFreinParking($etat)
    {
        $this->freinParking = (bool) $etat;
    }
}

$separator = PHP_SAPI === 'cli' ? PHP_EOL : '<br/>';

$veh1 = new Voiture(110);
$veh1->demarrer();
$veh1->accelerer(40);
echo $veh1 . $separator;
$veh1->accelerer(40);
echo $veh1 . $separator;
$veh1->accelerer(12);
$veh1->accelerer(40);
echo $veh1 . $separator;
$veh1->accelerer(40);
$veh1->decelerer(120);
echo $veh1 . $separator;

$veh2 = new Voiture(180);
echo $veh2 . $separator;

echo '############################' . $separator;
echo 'Nombre de voiture instanciée : ' . Voiture::getNombreVoiture() . $separator;
