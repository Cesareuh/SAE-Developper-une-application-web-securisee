<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use PDOException;

class AjouterSpectacleAction extends Action
{

    public function execute(): string
    {
        $repo=Repository::getInstance();
        if($this->http_method == "GET"){
            $lSpectacle = $repo->trouveTousSpectacles();
            $lSoiree = $repo->trouveToutesSoirees();
            $res=<<<END
            <h1>Ajouter un spectacle à une soirée :</h1>
            <form method="POST" action="?action=ajouter-spectacle">
            <label>Soiree</label>
            <select name="soiree" /> 
            <option value='' selected>choisissez une soiree</option>
            END;
            foreach ($lSoiree as $soiree) {
                $res.=<<<END
                        <option value="{$soiree->__get('id')}">{$soiree->__get('nom')}</option>
                END;
            }
            $res.=<<<END
                    </select>
                    <Label>Spectacle</Label>
                    <select name="spectacle">
                        <option value='' selected>choisissez un spectacle</option>                
            END;
            foreach ($lSpectacle as $spectacle) {
                $res.=<<<END
                        <option value="{$spectacle->__get('id')}">{$spectacle->__get('titre')}</option>
                END;
            }
            $res .= <<<END
                    </select>
                    <button type="submit">Valider</button>
                </form>
                END;
        }else if($this->http_method == "POST"){
            $spect = $repo->afficherSpectacle($_POST["spectacle"]);
            $soir = $repo->afficherSoiree($_POST["soiree"]);
            try {
                $repo->ajouterSpectacleToSoiree($soir, $spect);
            }catch (PDOException $e){
                return "lien déjà existant";
            }
            $res="<p>Le spectacle {$spect->__get('titre')} à était ajouté à la soirée {$soir->__get('nom')}</p>";
        }else{
            $res="<p>Type de requete incorecte</p>";
        }
        return $res;
    }
}
