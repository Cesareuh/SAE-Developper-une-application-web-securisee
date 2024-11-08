<?php
namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;
use iutnc\nrv\render\RenderSoiree;

class AffichageSoireeAction extends Action
{
    public function execute(): string
    {
        $idSoiree = $_GET['id_soiree'] ?? 0;

        $soiree = Repository::getInstance()->afficherSoiree($idSoiree);

        if ($soiree === null) {
            return "Soirée non trouvée.";
        }

        $renderer = new RenderSoiree($soiree);
        $html = $renderer->render(2);

        return $html;
    }
}

