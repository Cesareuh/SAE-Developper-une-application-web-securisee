<?php

namespace iutnc\nrv\action;

use iutnc\nrv\render\RenderSpectacle;

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

        // Si un spectacle doit être supprimé
        if (isset($_GET['remove_id'])) {
            $idToRemove = $_GET['remove_id'];
            // Retirer l'ID de spectacle des préférences
            $preferences = array_filter($preferences, function($id) use ($idToRemove) {
                return $id != $idToRemove;
            });
            // Réindexer le tableau après suppression
            $preferences = array_values($preferences);
            // Sauvegarder de nouveau les préférences dans le cookie
            setcookie('preferences', json_encode($preferences), time() + 3600 * 24 * 30, '/');
            // Rediriger après suppression pour éviter que l'URL ne soit répétée après un rechargement
            header('Location: index.php?action=afficher-preferences');
            exit;
        }

        // Générer la liste des spectacles préférés
        $html = "<h1>Vos Préférences</h1><ul>";

        foreach ($preferences as $idSpectacle) {
            // Utiliser le repository pour récupérer le spectacle à partir de son ID
            $spectacle = \iutnc\nrv\repository\Repository::getInstance()->afficherSpectacle($idSpectacle);

            // Vérifier si le spectacle est valide
            if ($spectacle) {
                // Ajouter le titre, l'artiste du spectacle et un bouton de suppression
				$html .= "<li>".(new RenderSpectacle($spectacle))->render(1). " <a href='index.php?action=afficher-preferences&remove_id=" . $idSpectacle . "' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce spectacle de vos préférences ?\");'><button>Supprimer</button></a>"."</li>";
            } else {
                // Gérer les erreurs si le spectacle n'est pas trouvé
                $html .= "<li>Spectacle non trouvé (ID: $idSpectacle)</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }
}
