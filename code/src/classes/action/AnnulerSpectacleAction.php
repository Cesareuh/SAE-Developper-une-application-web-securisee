<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;

class AnnulerSpectacleAction extends Action
{
    public function execute(): string
    {
        // Vérifier si l'utilisateur est connecté et a le rôle 'staff'
        if (AuthnProvider::estConnecte() && AuthnProvider::getRole() === 'staff') {

            $idSpectacle = $_GET['id'] ?? null;

            if ($idSpectacle) {
                // Annuler le spectacle
                $repo = Repository::getInstance();
                $repo->annulerSpectacle((int)$idSpectacle);

                return "<p style='color: green;'>Le spectacle a été annulé avec succès.</p>";
            }

        } else {
            return "<p style='color: red;'>Vous n'avez pas les droits pour annuler un spectacle.</p>";
        }
        return "<p style='color: red;'>Action impossible.</p>";
    }
}
