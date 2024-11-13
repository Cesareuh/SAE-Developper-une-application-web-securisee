<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\repository\Repository;

class DeconnexionAction extends Action
{
    public function execute(): string
    {
        unset($_SESSION['utilisateur']);
        return "Vous êtes déconnecté";
    }
}

