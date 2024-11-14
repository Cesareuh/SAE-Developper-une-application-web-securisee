<?php

namespace iutnc\nrv\action;

class AjouterPreferenceCookieAction extends Action
{
    public function execute(): string
    {
        $id_spectacle = $_GET['id_spectacle'] ?? null;
        $status = '';

        if ($id_spectacle) {
            // Récupérer les préférences existantes
            $preferences = json_decode($_COOKIE['preferences'] ?? '[]', true);

            // Si l'ID du spectacle n'est pas déjà dans les préférences, l'ajouter
            if (!in_array($id_spectacle, $preferences)) {
                $preferences[] = $id_spectacle; // Ajoute l'ID du spectacle aux préférences
                setcookie('preferences', json_encode($preferences), time() + 3600 * 24 * 30, "/"); // Sauvegarde les préférences dans le cookie
                $status = 'added'; // Message de succès pour l'ajout
            } else {
                $status = 'already'; // Message si déjà dans les préférences
            }
        } else {
            $status = 'missing'; // Message si l'ID du spectacle est manquant
        }

        // Redirige vers la page des spectacles avec le statut
        header("Location: index.php?action=afficher-liste-spectacle&status=$status");
        exit;
    }
}
