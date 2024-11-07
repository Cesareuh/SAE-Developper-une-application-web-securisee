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
			$res = $res ."<div class=simple>
				<h1 class=artiste>".$this->spec->artiste."</h1>
				<h2 class=titre>".$this->spec->titre."</h2>
				<p class=style>".$this->spec->style."</p>
				<p class=duree>".$this->spec->duree."</p>
				</div>";
			break;
		case 2:
			$res = $res ."<div class=complexe>
				<img src=\"".$this->spec->photo_artiste."\" alt=\"Photo de ".$this->spec->artiste."\"/>
				<h1 class=artiste>".$this->spec->artiste."</h1>
				<h2 class=titre>".$this->spec->titre."</h2>
				<p class=style>".$this->spec->style."</p>
				<p class=duree>".$this->spec->duree."</p>
				<p class=desc>".$this->spec->description."</p>
				 <iframe width=\"420\" height=\"315\"
				src=\"".$this->spec->video."\"></iframe>
				</div>";
			break;
		}
		return $res;
	}
}
