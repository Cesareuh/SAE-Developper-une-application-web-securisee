<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AffichageSpectacleAction extends Action
{
    public function execute(): string
    {
        // Récupérer l'ID du spectacle depuis l'URL (ou utiliser un ID par défaut)
        $idSpectacle = $_GET['id_spectacle'] ?? 0;


        $spectacle = Repository::getInstance()->afficherSpectacle($idSpectacle);

        if ($spectacle === null) {
            return "Spectacle non trouvé.";
        }


        $html = "<h1>Spectacle : " . htmlspecialchars($spectacle->getTitre()) . "</h1>";
        $html .= "<strong>Artiste :</strong> " . htmlspecialchars($spectacle->getArtiste()) . "<br>";
        $html .= "<strong>Durée :</strong> " . htmlspecialchars($spectacle->getDuree()) . " minutes<br>";
        $html .= "<strong>Style :</strong> " . htmlspecialchars($spectacle->getStyle()) . "<br>";
        $html .= "<strong>Description :</strong> " . htmlspecialchars($spectacle->getDescription()) . "<br>";

        // Affichage du lien vers la vidéo du spectacle
        if (!empty($spectacle->getVideo())) {
            $html .= "<strong>Vidéo :</strong> <a href='" . htmlspecialchars($spectacle->getVideo()) . "' target='_blank'>Voir la vidéo</a><br>";
        }

        if (!empty($spectacle->getPhoto())) {
            $html .= "<img src='" . htmlspecialchars($spectacle->getPhoto()) . "' alt='Image du spectacle'><br>";
        } else {
            $html .= "<p>Aucune image disponible pour ce spectacle.</p>";
        }

        return $html;
    }
}
