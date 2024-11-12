<?php

namespace iutnc\nrv\evenement;

class Lieu
{
    protected int $id, $nb_place_assises, $nb_places_debout;
    protected string $nom_lieu, $adresse;

    public function __construct($id, $nom_lieu, $adresse, $nb_place_assises, $nb_places_debout){
        $this->id = $id;
        $this->nb_place_assises = $nb_place_assises;
        $this->nb_places_debout = $nb_places_debout;
        $this->adresse = $adresse;
        $this->nom_lieu = $nom_lieu;
    }

    public function __get($name){
        return $this->$name;
    }

}