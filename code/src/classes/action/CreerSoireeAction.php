<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\evenement\Soiree;
use iutnc\nrv\repository\Repository;

class CreerSoireeAction extends Action
{
    public function execute(): string{
        $repo=Repository::getInstance();
        if($this->http_method === 'GET'){
            $lLieux = $repo->trouveTousLieux();
			$res= <<<END
            <h1>Créer une soirée :</h1>
            <form id="form" method="post" action="?action=creer-soiree" enctype="multipart/form-data">
                <label>Nom soirée :</label>
                <input type="text" name="nom_soiree" placeholder="nom de la soirée"/>
                <Label>Date:</Label>
                <input type="date" name="date_soiree"/>
                <Label>Tarif :</Label>
                <input type="number" step="0.01" name="tarif_soiree"/>
                <Label>Thématique :</Label>
                <input type="text" name="thematique_soiree" placeholder="théme"/>
                <label>Lieu :</label>
                <select name="lieu_soiree">
                    <option value='' selected>Choisir un lieu</option>
            END;
            foreach($lLieux as $lieu){
                $res.= <<<END
                    <option value="$lieu->id">$lieu->nom</option>
                END;
            }


            $res.=<<<END
                </select>
                <Label>Image :</Label>
                <input type="file" name="image" accept=".png, .jpeg, .jpg"/>
                <button type="submit" form="form">Valider</button>
            </form>
            END;
            return $res;
		}
        else if ($this->http_method === 'POST'){
            $repo = Repository::getInstance();
            $id_img= $repo->ajouterImage($_FILES['image']['tmp_name'], $_FILES['image']['name'], $_FILES['image']['type'], $_FILES['image']['size'], null);
            $info = [
                'nom_soiree' => filter_var($_POST['nom_soiree'], FILTER_SANITIZE_SPECIAL_CHARS),
                'date' => $_POST['date_soiree'],
                'tarif_soiree' => $_POST['tarif_soiree'],
                'thematique_soiree' => filter_var($_POST['thematique_soiree'], FILTER_SANITIZE_SPECIAL_CHARS),
                'lieu_soiree' => $_POST['lieu_soiree'],
                'image' => $id_img
            ];
            $soiree = new Soiree(0, $info['nom_soiree'], $info['date'], $info['tarif_soiree'], $info['thematique_soiree'], $info['lieu_soiree'], $info['image']);
            $repo->ajouterSoiree($soiree);
            // $render = (new AudioListRenderer($_SESSION['playlist']))->render();
            return "<div>Soirée créée</div>";
        }else{
            return "mauvais type requête";
        }
    }
}
