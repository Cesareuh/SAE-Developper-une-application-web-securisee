<?php

namespace iutnc\nrv\evenement;
use iutnc\nrv\evenement\Spectacle;

class Soiree
{
    protected int $id;
    protected float $tarif,$lieu, $image;
    protected string $date, $thematique, $nom;
    protected $listeSpectacles;

    public function __construct($id, $nom, $date, $tarif, $thematique, $lieu, $image)
    {
        $id=(int)$id;
        $tarif=(float)$tarif;
        $lieu=(int)$lieu;
        $image=(int)$image;

        $this->id = $id;
        $this->nom = $nom;
        $this->date = $date;
        $this->lieu = $lieu;
        $this->thematique = $thematique;
        $this->tarif = $tarif;
        $this->image = $image;
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
