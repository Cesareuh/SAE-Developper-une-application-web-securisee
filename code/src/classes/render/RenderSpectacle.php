<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Spectacle;

class RenderSpectacle extends Renderer{
	private Spectacle $spec;

	public function __construct(Spectacle $s){
		$this->spec = $s;
	}
	
	public function render(int $selector):string{
		$res = "";
		switch($selector){
		case 1:
			$res = $res ."<div>
				<img ".$this->spec->photo_artiste." alt=Photo de ".$this->spec->artiste."/>
				<h1 id=artiste>".$this->spec->artiste."</h1>
				<h2 id=titre>".$this->spec->titre."</h2>
				<p id=style>".$this->spec->style."</p>
				<p id=duree>".$this->spec->duree."</p>
				<p id=desc>".$this->spec->description."</p>
				 <iframe width=\"420\" height=\"315\"
				src=\"https://www.youtube.com/embed/tgbNymZ7vqY\"></iframe>
				</div>";
			break;
		case 2:
			break;
		}
		return $res;
	}
}
