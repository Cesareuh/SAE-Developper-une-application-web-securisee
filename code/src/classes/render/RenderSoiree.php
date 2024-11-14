<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Soiree;

class RenderSoiree extends Renderer{
	private Soiree $soiree;

	public function __construct(Soiree $s){
		$this->soiree = $s;
	}

	public function render(int $selector):string{
		$repo = \iutnc\nrv\repository\Repository::getInstance();
		$res = "";
		switch($selector){
		case 1:
                $res .= "<div class=simple>
				<div class=haut >
					<h1 class=artiste>".$this->soiree->nom."</h1>";
    //             if($this->soiree->id_img !== null){
    //                 $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->soiree->id_img))."' class=bg alt='Photo de la soirée' />";
				// }else{
                    $res .= "<img src='../../../images_de_base/fond.jpg' class=bg alt='Photo de la soirée' />";
				// }
                $res.= "
				</div>
				<div class=bas >
					<div class=infos_soiree >
						<h3 class=date>Date : ".$this->soiree->date."</h3>
						<h3 class=lieu>Lieu : ".$repo->trouveLieuParId($this->soiree->id_lieu)->nom_lieu."</h3>
						<h3 class=thematique>Thème : ".$this->soiree->thematique."</h3>
						<h3 class=tarif>Tarif : ".$this->soiree->tarif."€</h3>
					</div>
				</div>
				</div>";
			break;
		case 2:

            $res = $res ."<div class=complexe>
                <h1 class=nom_soiree>".$this->soiree->nom."</h1>
                <h2 class=theme_soiree>".$this->soiree->thematique."</h2>
                <h3 class=tarif_soiree>Tarif : ".$this->soiree->tarif."€</h3>
                <p class=date>&#128197".$this->soiree->date."</p>
                <p class=lieu>&#128205".$this->soiree->lieu."</p>";

            if (!empty($this->soiree->photo)) {
                $res .= "<img class='photo_soiree' src='" . $this->soiree->photo . "' alt='Image de la soirée' />";
            } else {
                $res .= "<p>Aucune image disponible pour cette soirée.</p>";
            }

            $res .= "<div class=liste>";
            foreach($this->soiree->listeSpectacles as $spec){
                $res .= (new RenderSpectacle($spec))->render(1);
            }
            $res .= "</div></div>";
            break;
        }
        return $res;
	}
}