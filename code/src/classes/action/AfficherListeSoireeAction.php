<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSoiree;

class AfficherListeSoireeAction extends Action
{
    public function execute(): string
    {

        $soirees = Repository::getInstance()->trouveToutesSoirees();
        $html = "<h1>Liste des Soirées du Festival</h1><ul>";

        foreach ($soirees as $soiree) {
            $renderSoiree = new RenderSoiree($soiree);

            $html .= "<li>" . $renderSoiree->render(1) . "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
