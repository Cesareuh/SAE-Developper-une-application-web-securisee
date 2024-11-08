<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSpectacle;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {

        $spectacles = Repository::getInstance()->trouveTousSpectacles();

        $html = "<h1>Liste des Spectacles</h1><ul>";

        foreach ($spectacles as $spectacle) {
            $renderSpectacle = new RenderSpectacle($spectacle);

            $html .= "<li>" . $renderSpectacle->render(1) . "</li><br>";
        }

        $html .= "</ul>";

        return $html;
    }
}
