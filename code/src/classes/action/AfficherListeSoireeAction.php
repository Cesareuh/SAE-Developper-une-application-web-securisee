<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AfficherListeSoireeAction extends Action
{
    public function execute(): string
    {

        $soirees = Repository::getInstance()->trouveToutesSoirees();


        $html = "<h1>Liste des Soirées du Festival</h1><ul>";
        foreach ($soirees as $soiree) {
            $html .= "<li>";
            $html .= "<strong>Date :</strong> " . htmlspecialchars($soiree['date']) . "<br>";
            $html .= "<strong>Lieu :</strong> " . htmlspecialchars($soiree['nom_lieu']) . "<br>";
            $html .= "<strong>Tarif :</strong> " . htmlspecialchars($soiree['tarif']) . " €<br>";
            $html .= "<strong>Thématique :</strong> " . htmlspecialchars($soiree['thematique']) . "<br>";
            if (!empty($soiree['nom_img'])) {
                $html .= "<strong>Image :</strong> <img src='" . htmlspecialchars($soiree['nom_img']) . "' alt='Image de la soirée'><br>";
                $html .= "<strong>Description de l'image :</strong> " . htmlspecialchars($soiree['desc_img']) . "<br>";
            } else {
                $html .= "<strong>Image :</strong> Aucune image disponible<br>";
            }
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
