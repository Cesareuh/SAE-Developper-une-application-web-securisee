<?php

namespace iutnc\nrv\action;

class CreerSoireeAction extends Action
{
    public function execute(): string{
        if($this->http_method === 'GET'){
			return <<<END
        <form id="form" method="post" action="?action=add-soiree">
        <label>Nom playlist:
        <input type="text" name="nom_soiree" placeholder="nom de la soirée"/>
        </label>
        </form>
        <button type="submit" form="form" value="Valider">Valider</button>
        END;
        }
        else{
            $repo = Repository::getInstance();
            $_SESSION['soiree'] = new Soiree(filter_var($_POST['nom_soiree'], FILTER_SANITIZE_SPECIAL_CHARS));

            $_SESSION['soiree'] = $repo->saveEmptyPlaylist($_SESSION['soiree'], AuthnProvider::getSignedInUser()['id']);
            
            // $render = (new AudioListRenderer($_SESSION['playlist']))->render();
            return "<div>Soirée créée</div>";
        }
    }
}