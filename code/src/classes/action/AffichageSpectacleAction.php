<?php

namespace iutnc\nrv\action;

use iutnc\nrv\db\Database;

class AffichageSpectacleAction extends Action
{

    public function execute(): string
    {
        // Récupérer la connexion à la base de données
        $db = Database::getInstance()->getConnection();

        // Préparer la requête SQL pour récupérer les spectacles
        $sql = "SELECT ID_Spectacle, Titre, Artiste, Duree, Style, video, photo, description FROM Spectacle";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // Récupérer les résultats
        $spectacles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Construire le contenu HTML
        $html = "<h1>Liste des spectacles de la soirée</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $html .= "<li>";
            $html .= "<strong>Titre :</strong> " . htmlspecialchars($spectacle['Titre']) . "<br>";
            $html .= "<strong>Artiste :</strong> " . htmlspecialchars($spectacle['Artiste']) . "<br>";
            $html .= "<strong>Durée :</strong> " . htmlspecialchars($spectacle['Duree']) . "<br>";
            $html .= "<strong>Style :</strong> " . htmlspecialchars($spectacle['Style']) . "<br>";
            $html .= "<strong>Vidéo :</strong> " . htmlspecialchars($spectacle['video']) . "<br>";
            $html .= "<strong>Photo :</strong> " . htmlspecialchars($spectacle['photo']) . "<br>";
            $html .= "<strong>Description :</strong> " . htmlspecialchars($spectacle['description']) . "<br>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
