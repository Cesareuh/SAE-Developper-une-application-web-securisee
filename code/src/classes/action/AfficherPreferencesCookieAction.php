<?php

namespace iutnc\nrv\action;

class AfficherPreferencesCookieAction extends Action
{
    public function execute(): string
    {
        // Récupérer les préférences depuis le cookie ou initialiser un tableau vide
        $preferences = isset($_COOKIE['preferences']) ? json_decode($_COOKIE['preferences'], true) : [];

        // Si aucune préférence n'est enregistrée, retourner un message d'absence de favoris
        if (empty($preferences)) {
            return "<p>Vous n'avez pas encore de spectacles préférés.</p>";
        }

        // Générer la liste des spectacles préférés
        $html = "<h1>Vos Préférences</h1><ul>";

        foreach ($preferences as $idSpectacle) {
            // Utiliser le repository pour récupérer le spectacle à partir de son ID
            $spectacle = \iutnc\nrv\repository\Repository::getInstance()->getSpectacleById($idSpectacle);

            // Vérifier si le spectacle est valide
            if ($spectacle) {
                // Ajouter le titre et l'artiste du spectacle à la liste HTML
                $html .= "<li>" . htmlspecialchars($spectacle->titre) . " - " . htmlspecialchars($spectacle->artiste) . "</li>";
            } else {
                // Gérer les erreurs si le spectacle n'est pas trouvé
                $html .= "<li>Spectacle non trouvé (ID: $idSpectacle)</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }
}
