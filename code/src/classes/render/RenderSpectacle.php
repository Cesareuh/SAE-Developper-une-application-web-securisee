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
			$res = $res ."
		<div class=bg></div>
		<div class=complexe>
			<div class=haut>
				<img src=".$this->spec->photo_artiste." class=photo_profil />
				<h1 class=artiste>".$this->spec->artiste."</h1>
			</div>
			<div class=bas>
				<h2 class=titre>".$this->spec->titre."</h2>
				<div class=infos>
					<h3 class=style>".$this->spec->style."</h3>
					<h3 class=duree>" . $this->spec->duree . " min</h3>
				</div>
				<div class=presentation>
					<p class=desc>".$this->spec->description."</p>
					<hr>
					<iframe width=560 height=315 src=".$this->spec->video." title=YouTube video player frameborder=0 allow=accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share referrerpolicy=strict-origin-when-cross-origin allowfullscreen></iframe>
				</div>
			</div>
		</div>";
			break;
		}
		return $res;
	}
}
