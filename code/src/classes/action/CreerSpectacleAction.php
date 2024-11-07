<?php

namespace iutnc\nrv\action;

use DateTime;
use iutnc\nrv\evenement\Spectacle;
use iutnc\nrv\repository\Repository;

class CreerSpectacleAction extends Action
{

    public function execute(): string
    {
        if($this->http_method === 'GET'){
			return <<<END
			<h3>Ajouter un spectacle :</h3>
			    <form id="form" method="post" action="?action=creer-spectacle" enctype="multipart/form-data">
				<label> Titre : 
				<input type="text" name="titre" placeholder="titre"/>
				</label>
				<label> Style :
				<input type="text" name="style" placeholder="style"/>
				</label>
				<label> Duree :
				<input type="number" name="duree" placeholder="duree"/>
				</label>
				<label> Artiste :
				<input type="text" name="artiste" placeholder="nom de l'artiste"/>
				</label>
				<label> Photo :
				<input type="file" name="photo" placeholder="image du spectacle" accept=".png, .jpeg, .jpg"/>
				</label>
				<label> Video :
				<input type="text" name="video" placeholder="video de présentation"/>
				</label>
				<button type="submit" form="form"> Valider </button>
				</form>
			END;
        }
        else {
            if ($this->http_method === 'POST') {
                $repo = Repository::getInstance();
                $infos = [
                    'titre' => filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS),
                    'style' => filter_var($_POST['style'], FILTER_SANITIZE_SPECIAL_CHARS),
                    'artiste' => filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS),
                    'video' => filter_var($_POST['video'], FILTER_SANITIZE_SPECIAL_CHARS)
                ];
                $d=(int)$_POST['duree'];
                $id_img=$repo->ajouterImage($_FILES['photo']['tmp_name'], $_FILES['photo']['name'], $_FILES['photo']['type'], $_FILES['photo']['size']);
                $_FILES(['photo'],['size']);

                $at = new Spectacle(0, $infos['titre'], $infos['artiste'], $id_img, $d, $infos['style'], "zzz", $infos['video']);
                $repo->ajouterSpectacle($at);

                return "<div>Spectacle ajouté</div>";
            } else {
                return "Mauvais type requete";
            }
        }
    }
}