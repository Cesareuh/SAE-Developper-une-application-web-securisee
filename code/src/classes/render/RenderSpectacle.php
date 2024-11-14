<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Spectacle;
use iutnc\nrv\repository\Repository;

class RenderSpectacle extends Renderer
{
    private Spectacle $spec;

    public function __construct(Spectacle $s){
        $this->spec = $s;
    }

    public function render(int $selector):string{
        $repo = Repository::getInstance();
        $res = "";

        $statut = $this->spec->statut;
        $isCancelled = ($statut === 'annulé');

        switch($selector){
            case 1:
                $res .= "<div class=simple>
				<a href='index.php?action=afficher-spectacle&id_spectacle=".$this->spec->id."' >
				<div class=haut >
					<h1 class=artiste>".$this->spec->artiste."</h1>";
                if($this->spec->id_img_bg !== null){
                    $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img_bg))."' class=bg alt='Photo de l'artiste' />";
				}else{
                    $res .= "<img src='../../../images_de_base/fond.jpg' class=bg alt='Photo de l'artiste' />";
				}
                $res .= "</div>
				<div class=bas >";
                if ($isCancelled) {
                    $res .= "<p class='annule' >Spectacle Annulé</p>";
                }
				$res .= "<h2 class=titre>".$this->spec->titre."</h2>
					<div class=infos >
						<h3 class=style>".$this->spec->style."</h3>
						<h3 class=duree>".$this->spec->duree." min</h3>
					</div>
                    <a href='index.php?action=ajouter-preference&id_spectacle=".$this->spec->id."' class='btn-preference'>Ajouter aux préférences</a>
				</div></a>
				</div>";
                break;

            case 2:
                $res .= "<div class=complexe><div class=haut>";
                if($this->spec->id_img_bg !== null){
                    $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img_bg))."' class=bg alt='Photo de l'artiste' />";
				}else{
                    $res .= "<img src='../../../images_de_base/fond.jpg' class=bg alt='Photo de l'artiste' />";
                }
				if($repo->getImageById($this->spec->id_img)){
					$res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img))."' class=photo_profil alt='Photo de l'artiste' />";
				}else{
                    $res .= "<img src='../../../images_de_base/pp.jpg' class=photo_profil alt='Photo de l'artiste' />";
				}
                if ($isCancelled) {
                    $res .= "<p class='annule' >Spectacle Annulé</p>";
                }
				$res .= "<h1 class=artiste>".$this->spec->artiste."</h1>
                </div>
                <div class=bas>
                    <h2 class=titre>".$this->spec->titre."</h2>
                    <div class=infos>
                        <h3 class=style>".$this->spec->style."</h3>
                        <h3 class=duree>" . $this->spec->duree . " min</h3>
                    </div>
                    <div class=presentation>
                        <p class=desc>".$this->spec->description."</p>
                        <hr>";
                if (!empty($this->spec->video)) {
                    $res .= "<iframe width='560' height='315' src='" . $this->spec->video . "' 
                                title='YouTube video player' frameborder='0' 
                                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' 
                                referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>";
                }
				$res .= "</div>
					<h1>Soirées incluant ce spectacle</h1>
					<ul>";
				foreach($repo->getSoireeBySpectacleId($this->spec->id) as $soiree){
					$res .= "<li>" . (new RenderSoiree($soiree))->render(1) . "</li>";
				}
				$res .= "</ul>
                    <a href='index.php?action=ajouter-preference&id_spectacle=".$this->spec->id."' class='btn-preference'>Ajouter aux préférences</a>
                </div></div>";
                break;
        }

        return $res;
    }
}
