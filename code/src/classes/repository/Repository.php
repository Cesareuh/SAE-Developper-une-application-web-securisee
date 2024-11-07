<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {

        $repository = Repository::getInstance();
        $spectacles = $repository->trouveTousSpectacles();

        $html = "<h1>Liste des Spectacles</h1><ul>";
        foreach ($spectacles as $spectacle) {

            $html .= "<li>";
            $html .= "<p><strong>Titre :</strong> " . htmlspecialchars($spectacle->getTitre()) . "</p>";
            $html .= "<p><strong>Artiste :</strong> " . htmlspecialchars($spectacle->getArtiste()) . "</p>";
            $html .= "<p><strong>Durée :</strong> " . htmlspecialchars($spectacle->getDuree()) . " minutes</p>";
            $html .= "<p><strong>Style :</strong> " . htmlspecialchars($spectacle->getStyle()) . "</p>";


            $html .= "<a href='?action=afficher_spectacle&id=" . urlencode($spectacle->getId()) . "'>Voir les détails</a>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
