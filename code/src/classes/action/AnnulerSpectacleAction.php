<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\repository\Repository;

class AnnulerSpectacleAction extends Action
{
    public function execute(): string {
        header('Content-Type: application/json');
    
        // Vérifier si l'utilisateur est connecté et a le rôle 'staff'
        if (AuthnProvider::estConnecte() && AuthnProvider::getRole() === 'staff') {
            $id_spectacle = $_POST['id'] ?? null;
    
            if ($id_spectacle) {
                $repo = Repository::getInstance();
                $spectacle = $repo->afficherSpectacle((int)$id_spectacle);
    
                if ($spectacle !== null) {
                    $repo->annulerSpectacle((int)$id_spectacle);
                    echo json_encode(['success' => true]);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Spectacle non trouvé.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ID de spectacle manquant.']);
                exit;
            }
        }
    
        echo json_encode(['success' => false, 'message' => 'Action non autorisée.']);
        exit;
    }
    
}
