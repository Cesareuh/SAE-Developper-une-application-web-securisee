<?php

namespace iutnc\nrv\evenement;
use iutnc\nrv\evenement\Spectacle;

class Soiree{
	protected int $id;
	protected float $tarif;
	protected string $date, $lieu, $thematique;
	protected $listeSpectacles;

	public function __construct($id, $date, $lieu, $thematique, $tarif){
		$this->id = $id;
		$this->date = $date;
		$this->lieu = $lieu;
		$this->listeSpectacles = array();
	}

	public function addSpectacle(Spectacle$spectacle):void{
		$this->listeSpectacles[] = $spectacle;
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
