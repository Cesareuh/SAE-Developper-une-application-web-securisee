<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

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
            $repo->ajouterSpectacleToSoiree($_POST['soiree'], $_POST['spectacle']);
            $res="<p>Le spectacle {$_POST['spectacle']->__get('titre')} à était ajouté à la soirée {$_POST['soiree']->__get('nom')}</p>";
        }else{
            $res="<p>Type de requete incorecte</p>";
        }
        return $res;
    }
}
