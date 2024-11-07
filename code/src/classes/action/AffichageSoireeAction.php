<?php

namespace iutnc\nrv\action;
use iutnc\nrv\repository\Repository;
class AffichageSoireeAction extends Action
{

    public function execute(): string
    {

        $db = Repository::getInstance()->getConnection();

        // Requête SQL avec jointure pour récupérer l'image
        $sql = "SELECT Soiree.ID_Soiree, Soiree.Date, Soiree.Nom_Lieu, Soiree.tarif, Soiree.thématique, Image.nom_img, Image.desc_img 
                FROM Soiree
                LEFT JOIN Image ON Soiree.ID_Img = Image.id_img";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // Récupérer les résultats
        $soirees = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Générer le HTML
        $html = "<h1>Liste des Soirées du Festival</h1><ul>";
        foreach ($soirees as $soiree) {
            $html .= "<li>";
            $html .= "<strong>Date :</strong> " . htmlspecialchars($soiree['Date']) . "<br>";
            $html .= "<strong>Lieu :</strong> " . htmlspecialchars($soiree['Nom_Lieu']) . "<br>";
            $html .= "<strong>Tarif :</strong> " . htmlspecialchars($soiree['tarif']) . " €<br>";
            $html .= "<strong>Thématique :</strong> " . htmlspecialchars($soiree['thématique']) . "<br>";
            if (!empty($soiree['nom_img'])) {
                $html .= "<strong>Image :</strong> " . htmlspecialchars($soiree['nom_img']) . "<br>";
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
