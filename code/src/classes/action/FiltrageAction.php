<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class FiltrageAction extends Action
{
    public function execute(): string {

        if ($this->http_method === 'GET') {
            $res = <<<END
            <h1>Liste des Spectacles Filtrés</h1>
            <form method='POST' action='?action=filtre'>
            <input type='hidden' name='action' value='filtrer_spectacles'>
            <label for='date'>Date :</label> <input type='date' name='date' id='date'><br>
            <label for='lieu'>Lieu :</label> <input type='text' name='lieu' id='lieu'><br>
            <label for='style'>Style :</label> <input type='text' name='style' id='style'  ><br>
            <button type='submit'>Filtrer</button>
            </form>
            END;
        }else if($this->http_method === 'POST') {

            // Récupération donnée de filtrage
            $date = $_GET['date'] ?? null;
            $lieu = $_GET['lieu'] ?? null;
            $style = $_GET['style'] ?? null;

            // Récupération des spectacles après filtre
            $spectacles = Repository::getInstance()->trouverSpectaclesFiltre($date, $lieu, $style);
            foreach ($spectacles as $spectacle) {
                $res = <<<END
                    <ul>
                        <li>
                        <p><strong>Titre :</strong>{$spectacle->__get('titre')}</p>
                        <p><strong>Artiste :</strong>{$spectacle->__get('artiste')}</p>
                        <p><strong>Durée :</strong>{$spectacle->__get('duree')} minutes</p>
                        <p><strong>Style :</strong>{$spectacle->__get('style')}</p>
                        <a href='?action=afficher_spectacle&id=urlencode({$spectacle->__get('id')})'>Voir les détails</a>
                        </li><br>
                    </ul>
                    END;
            }
        }

        return $res;
    }
}