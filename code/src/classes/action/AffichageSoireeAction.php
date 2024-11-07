<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AffichageSoireeAction extends Action
{
    public function execute(): string
    {
        $idSoiree = $_GET['id_soiree'] ?? 0;

        
        $soiree = Repository::getInstance()->afficherSoiree($idSoiree);

        if ($soiree === null) {
            return "Soirée non trouvée.";
        }


        $html = "<h1>Soirée : " . htmlspecialchars($soiree->getNom()) . "</h1>";
        $html .= "<strong>Date :</strong> " . htmlspecialchars($soiree->getDate()) . "<br>";
        $html .= "<strong>Lieu :</strong> " . htmlspecialchars($soiree->getLieu()) . "<br>";
        $html .= "<strong>Tarif :</strong> " . htmlspecialchars($soiree->getTarif()) . " €<br>";
        $html .= "<strong>Thématique :</strong> " . htmlspecialchars($soiree->getThematique()) . "<br>";

        if (!empty($soiree->getImage())) {
            $html .= "<img src='" . htmlspecialchars($soiree->getImage()) . "' alt='Image de la soirée'><br>";
        } else {
            $html .= "<p>Aucune image disponible pour cette soirée.</p>";
        }

        return $html;
    }
}
