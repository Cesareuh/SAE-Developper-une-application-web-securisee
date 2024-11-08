<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSpectacle;

class AffichageSpectacleAction extends Action
{
    public function execute(): string
    {
        $idSpectacle = $_GET['id_spectacle'] ?? 0;

        $spectacle = Repository::getInstance()->afficherSpectacle($idSpectacle);

        if ($spectacle === null) {
            return "Spectacle non trouvé.";
        }
        $renderSpectacle = new RenderSpectacle($spectacle);

        return $renderSpectacle->render(2);
    }
}
