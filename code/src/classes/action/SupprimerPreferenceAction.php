<?php
namespace iutnc\nrv\action;

class SupprimerPreferenceAction extends Action
{
    public function execute(): string
    {
        $id_spectacle = $_GET['id_spectacle'] ?? null;

        if ($id_spectacle) {
            $preferences = json_decode($_COOKIE['preferences'] ?? '[]', true);
            $preferences = array_diff($preferences, [$id_spectacle]); // Supprime le spectacle du tableau
            setcookie('preferences', json_encode($preferences), time() + 3600 * 24 * 30, '/');
        }

        // Redirige vers la page des préférences avec un message de confirmation
        header("Location: index.php?action=liste-preferences&status=removed");
        exit;
    }
}
