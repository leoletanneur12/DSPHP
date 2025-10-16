<?php
/**
 * Nom : Letanneur
 * Prénom : Leo
 * Fichier : exercice1.php
 * Date : 2025-10-16
 * Description : Gestion des règles de vitesse et frein de parking.
 */

abstract class Vehicule 
{
    protected $demarrer = FALSE;
    protected $vitesse = 0;
    protected $vitesseMax;

    // On oblige les classes filles à définir les méthodes abstracts
    abstract function demarrer();
    abstract function eteindre();
    abstract function decelerer($vitesse);
    abstract function accelerer($vitesse);

    // Méthode magique toString
    public function __toString()
    {
        $chaine = "Ceci est un véhicule <br/>";
        $chaine .= "---------------------- <br/>";
        return $chaine;
    }
}

class Voiture extends Vehicule 
{
   
    // à compléter
}

$veh1 = new Voiture(110);
$veh1->demarrer();
$veh1->accelerer(40);
echo $veh1;
$veh1->accelerer(40);
echo $veh1;
$veh1->accelerer(12);
$veh1->accelerer(40);
echo $veh1;
$veh1->accelerer(40);
$veh1->decelerer(120);
echo $veh1;

$veh2 = new Voiture(180);
echo $veh2;

echo "############################ <br/>";
echo "Nombre de voiture instanciée : " . Voiture::getNombreVoiture() . "<br/>";
