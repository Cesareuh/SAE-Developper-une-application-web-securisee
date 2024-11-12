<?php

namespace iutnc\nrv\evenement;


class Spectacle
{
    protected int $id, $duree, $id_img;

    protected string $titre, $artiste, $style, $description, $video, $statut;
    protected int $id_img_bg;


    public function __construct($id, $titre, $artiste, $duree, $style, $video, $description ,$id_img,$statut)
    {
		$id=(int)$id;
		$duree =(int)$duree;
		$id_img=(int)$id_img;

        $this->id = $id;
        $this->titre = $titre;
        $this->artiste = $artiste;
        $this->id_img = $id_img;

		$repo = \iutnc\nrv\repository\Repository::getInstance();
		$this->id_img_bg = $repo->getImageBgId($this->id_img);

        $this->duree = $duree;
        $this->style = $style;
        $this->description = $description;
        $this->video = $video;
        $this->statut = $statut;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function __get($attr): mixed
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        } else {
            return null;
        }
    }
}
