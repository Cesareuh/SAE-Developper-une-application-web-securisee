<?php

namespace iutnc\nrv\action;

class AfficherListeSpectacleAction extends Action
{

    public function execute(): string {

        $db = Database::getInstance()->getConnection();


        $sql = "SELECT ID_Spectacle, Titre, Artiste, Duree, Style FROM Spectacle";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $spectacles = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        $html = "<h1>Liste des spectacles de la soirée</h1><ul>";
        foreach ($spectacles as $spectacle) {
            $html .= "<li>";
            $html .= "<strong>Titre :</strong> " . htmlspecialchars($spectacle['Titre']) . "<br>";
            $html .= "<strong>Artiste :</strong> " . htmlspecialchars($spectacle['Artiste']) . "<br>";
            $html .= "<strong>Durée :</strong> " . htmlspecialchars($spectacle['Duree']) . "<br>";
            $html .= "<strong>Style :</strong> " . htmlspecialchars($spectacle['Style']) . "<br>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}