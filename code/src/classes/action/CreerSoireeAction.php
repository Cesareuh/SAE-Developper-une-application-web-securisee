<?php

namespace iutnc\nrv\action;

class CreerSoireeAction extends Action
{
    public function execute(): string{
        if($this->http_method === 'GET'){
			return <<<END
<h1>Créer une soirée :</h1>
		<form id="form" method="post" action="?action=add-soiree">
		<label>Nom soirée:</label>
		<input type="text" name="nom_soiree" placeholder="nom de la soirée"/>
		<Label>Date:</Label>
		<input type="date" name="date_soiree"/>
		<Label></Label>
		<button type="submit" form="form" value="Valider">Valider</button>
		</form>
END;
		}
        else{
            $repo = Repository::getInstance();
            $_SESSION['soiree'] = new Soiree(filter_var($_POST['nom_soiree'], FILTER_SANITIZE_SPECIAL_CHARS));

            $_SESSION['soiree'] = $repo->saveEmptySoiree($_SESSION['soiree'], AuthnProvider::getSignedInUser()['role']);

            // $render = (new AudioListRenderer($_SESSION['playlist']))->render();
            return "<div>Soirée créée</div>";
        }
    }
}
