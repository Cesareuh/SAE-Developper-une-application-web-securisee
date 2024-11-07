<?php

namespace iutnc\nrv\action;

class CreerSpectacleAction extends Action
{

    public function execute(): string
    {
        if($this->http_method === 'GET'){
			return <<<END
			<h3>Ajouter un spectacle :</h3>
			<form id="form" method="post" action="?action=add-spectacle">
				<label> Titre : 
				<input type="text" name="titre" placeholder="titre"/>
				</label>
				<label> Style :
				<input type="text" name="style" placeholder="style"/>
				</label>
				<label> Duree :
				<input type="text" name="duree" placeholder="duree"/>
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
				</form>
				<button type="submit" form="form"> Valider </button>
			END;
        }
        else{
            $repo = Repository::getInstance();
			$infos = [
				$_POST['titre'],
				$_POST['style'],
				$_POST['duree'],
				$_POST['artiste'],
				$_POST['photo'],
				$_POST['video'],
			];

            foreach($infos as $key => $val){
				$infos[$key] = filter_var($infos[$key], FILTER_SANITIZE_SPECIAL_CHARS);
			}

			$at = new Spectacle($infos[0], $infos[1], $infos[2], $infos[3], $infos[4], $infos[5], $infos[6]);
			$at = $repo->saveAjouterSoiree($at);

			return "<div>Spectacle ajouté</div>";
        }
    }
}