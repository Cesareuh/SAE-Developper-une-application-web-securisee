<?php

namespace iutnc\nrv\evenement;

class Spectacle{
	protected int $id, $duree;
	protected string $titre, $artiste, $photo_artiste, $style, $description, $video;

	public function __construct($id, $titre, $artiste, $photo_artiste, $duree, $style, $description, $video){
		$this->id = $id;
		$this->titre = $titre;
		$this->artiste = $artiste;
		$this->photo_artiste = $photo_artiste;
		$this->duree = $duree;
		$this->style = $style;
		$this->description = $description;
		$this->video = $video;
	}

	public function setId($id):void{
		$this->id = $id;
	}

	public function __get($attr):mixed{
		if(property_exists($this, $attr)){
			return $this->$attr;
		}
	}

}
