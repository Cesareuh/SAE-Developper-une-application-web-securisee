<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AffichageSpectacleAction extends Action
{

    public function execute(): string
    {

        $db = Repository::getInstance()->getConnection();

        // Requête SQL avec jointure pour récupérer les informations de l'image associée à chaque spectacle
        $sql = "SELECT Spectacle.ID_Spectacle, Spectacle.Titre, Spectacle.Artiste, Spectacle.Duree, Spectacle.Style, Spectacle.video, Spectacle.description, 
                       Image.nom_img, Image.desc_img
                FROM Spectacle
                LEFT JOIN Image ON Spectacle.ID_Img = Image.id_img";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $spectacles = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Générer le HTML pour afficher les informations du spectacle avec les détails de l'image
        $html = "<h1>Présentation du Spectacle</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $html .= "<li>";
            $html .= "<strong>Titre :</strong> " . htmlspecialchars($spectacle['Titre']) . "<br>";
            $html .= "<strong>Artiste :</strong> " . htmlspecialchars($spectacle['Artiste']) . "<br>";
            $html .= "<strong>Durée :</strong> " . htmlspecialchars($spectacle['Duree']) . "<br>";
            $html .= "<strong>Style :</strong> " . htmlspecialchars($spectacle['Style']) . "<br>";
            $html .= "<strong>Vidéo :</strong> " . htmlspecialchars($spectacle['video']) . "<br>";

            // Afficher les informations de l'image si elles sont disponibles
            if (!empty($spectacle['nom_img'])) {
                $html .= "<strong>Image :</strong> " . htmlspecialchars($spectacle['nom_img']) . "<br>";
                $html .= "<strong>Description de l'image :</strong> " . htmlspecialchars($spectacle['desc_img']) . "<br>";
            } else {
                $html .= "<strong>Image :</strong> Aucune image disponible<br>";
            }

            $html .= "<strong>Description :</strong> " . htmlspecialchars($spectacle['description']) . "<br>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}
