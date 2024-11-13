<?php

namespace iutnc\nrv\evenement;
use iutnc\nrv\evenement\Spectacle;

class Soiree
{
    protected int $id, $id_lieu, $id_img;
    protected float $tarif;
    protected string $date, $thematique, $nom;
    protected $listeSpectacles;

    public function __construct($id, $nom, $date, $tarif, $thematique, $id_lieu, $id_img)
    {
        $id=(int)$id;
        $tarif=(float)$tarif;
        $id_lieu=(int)$id_lieu;
        $id_img=(int)$id_img;

        $this->id = $id;
        $this->nom = $nom;
        $this->date = $date;
        $this->id_lieu = $id_lieu;
        $this->thematique = $thematique;
        $this->tarif = $tarif;
        $this->id_img = $id_img;
        $this->listeSpectacles = array();
    }

    public function addSpectacle(Spectacle $spectacle): void
    {
        $this->listeSpectacles[] = $spectacle;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function __get($attr): mixed
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
    }
}
