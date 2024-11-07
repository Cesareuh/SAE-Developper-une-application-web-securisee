<?php

namespace iutnc\nrv\action;

class AffichageSoireeAction extends Action
{

    public function execute(): string
    {
        // Récupérer la connexion à la base de données
        $db = Database::getInstance()->getConnection();

        // Préparer la requête SQL pour récupérer les soirées
        $sql = "SELECT ID_Soiree, Date, Nom_Lieu, tarif, thématique,image_soiree FROM Soiree";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // Récupérer les résultats
        $soirees = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Construire le contenu HTML
        $html = "<h1>Liste des Soirées du Festival</h1><ul>";
        foreach ($soirees as $soiree) {
            $html .= "<li>";
            $html .= "<strong>Date :</strong> " . htmlspecialchars($soiree['Date']) . "<br>";
            $html .= "<strong>Lieu :</strong> " . htmlspecialchars($soiree['Nom_Lieu']) . "<br>";
            $html .= "<strong>Tarif :</strong> " . htmlspecialchars($soiree['tarif']) . " €<br>";
            $html .= "<strong>Thématique :</strong> " . htmlspecialchars($soiree['thématique']) . "<br>";
            $html .= "<strong>image_soiree :</strong> " . htmlspecialchars($soiree['image_soiree']) . "<br>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }
}