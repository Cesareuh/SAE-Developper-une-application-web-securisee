<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Soiree;

class RenderSoiree extends Renderer{
	private Soiree $soiree;

	public function __construct(Soiree $s){
		$this->soiree = $s;
	}
	
	public function render(int $selector):string{
		$res = "";
		switch($selector){
		case 1:
			$res = $res ."<div class=simple>
				<img class=photo_soiree src=\"".$this->soiree->photo."\" alt=\"Photo de la soirée\"/>
				<h1 class=nom_soiree>".$this->soiree->nom."</h1>
				<h2 class=theme_soiree>".$this->soiree->thematique."</h2>
				<p class=date>&#128197".$this->soiree->date."</p>
				<p class=lieu>&#128205".$this->soiree->lieu."</p>
				</div>";
			break;
		case 2:
			$res = $res ."<div class=complexe>
				<h1 class=nom_soiree>".$this->soiree->nom."</h1>
				<h2 class=theme_soiree>".$this->soiree->thematique."</h2>
				<h3 class=tarif_soiree>Tarif : ".$this->soiree->tarif."€</h3>
				<p class=date>&#128197".$this->soiree->date."</p>
				<p class=lieu>&#128205".$this->soiree->lieu."</p>
				
				<div class=liste>";
				foreach($this->soiree->listeSpectacles as $spec){
					$res = $res . (new RenderSpectacle($spec))->render(1);
				}
				$res = $res. "</div></div>";
			break;
		}
		return $res;
	}
}
