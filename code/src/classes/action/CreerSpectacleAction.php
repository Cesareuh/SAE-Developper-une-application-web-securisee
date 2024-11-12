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
				<label> Titre :</label>
				<input type="text" name="titre" placeholder="titre"/>
				<label> Style :</label>
				<input type="text" name="style" placeholder="style"/>
				<label> Duree :</label>
				<input type="number" name="duree" placeholder="duree"/>
				<label> Artiste :</label>
				<input type="text" name="artiste" placeholder="nom de l'artiste"/>
				<label> Photo :</label>
				<input type="file" name="photo" placeholder="image du spectacle" accept=".png, .jpeg, .jpg"/>
				<label> Background :</label>
				<input type="file" name="background" placeholder="fond de la page" accept=".png, .jpeg, .jpg"/>
				<label> Video :</label>
				<input type="text" name="video" placeholder="video de présentation"/>
				<label> Description :</label>
				<textarea name="description" placeholder="Description du spectacle" rows=5 cols=30 ></textarea>
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
                    'video' => filter_var($_POST['video'], FILTER_SANITIZE_SPECIAL_CHARS),
                    'description' => filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS)
                ];
                $d=(int)$_POST['duree'];
				// Insérer photo de fond
                $id_bg=$repo->ajouterImage($_FILES['background']['tmp_name'], $_FILES['background']['name'], $_FILES['background']['type'], $_FILES['background']['size'], null);
				// Insérer photo de profil
                $id_img=$repo->ajouterImage($_FILES['photo']['tmp_name'], $_FILES['photo']['name'], $_FILES['photo']['type'], $_FILES['photo']['size'], $id_bg);


                $at = new Spectacle(0, $infos['titre'], $infos['artiste'], $d, $infos['style'], $infos['video'], $infos['description'], $id_img);
                $repo->ajouterSpectacle($at);

                return "<div>Spectacle ajouté</div>";
            } else {
                return "Mauvais type requete";
            }
        }
    }
}
