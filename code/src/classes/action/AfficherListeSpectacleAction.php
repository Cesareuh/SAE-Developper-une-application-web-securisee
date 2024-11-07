<?php

namespace iutnc\nrv\action;

use iutnc\nrv\db\Database;

class AfficherListeSpectacleAction extends Action
{

    public function execute(): string {

        $db = Database::getInstance()->getConnection();

        // Requête SQL pour récupérer les informations des spectacles
        $sql = "SELECT ID_Spectacle, Titre, Artiste, Duree, Style FROM Spectacle";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $spectacles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Générer le HTML pour afficher la liste des spectacles
        $html = "<h1>Liste des Spectacles de la Soirée</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $html .= "<li>";
            $html .= "<p><strong>Titre :</strong> " . htmlspecialchars($spectacle['Titre']) . "</p>";
            $html .= "<p><strong>Artiste :</strong> " . htmlspecialchars($spectacle['Artiste']) . "</p>";
            $html .= "<p><strong>Durée :</strong> " . htmlspecialchars($spectacle['Duree']) . "</p>";
            $html .= "<p><strong>Style :</strong> " . htmlspecialchars($spectacle['Style']) . "</p>";

            // Option pour un lien vers plus de détails
            $html .= "<a href='?action=afficher_spectacle&id=" . urlencode($spectacle['ID_Spectacle']) . "'>Voir les détails</a>";

            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
