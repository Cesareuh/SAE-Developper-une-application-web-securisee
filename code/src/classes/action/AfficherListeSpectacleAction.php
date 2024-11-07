<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {

        $db = Repository::getInstance()->getConnection();


        $sql = "SELECT id_spectacle, titre, artiste, duree, style FROM spectacle";
        $stmt = $db->prepare($sql);
        $stmt->execute();


        $spectacles = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        $html = "<h1>Liste des Spectacles</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $html .= "<li>";
            $html .= "<p><strong>Titre :</strong> " . htmlspecialchars($spectacle['titre']) . "</p>";
            $html .= "<p><strong>Artiste :</strong> " . htmlspecialchars($spectacle['artiste']) . "</p>";
            $html .= "<p><strong>Durée :</strong> " . htmlspecialchars($spectacle['duree']) . " minutes</p>";
            $html .= "<p><strong>Style :</strong> " . htmlspecialchars($spectacle['style']) . "</p>";


            $html .= "<a href='?action=afficher_spectacle&id=" . urlencode($spectacle['id_spectacle']) . "'>Voir les détails</a>";

            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
