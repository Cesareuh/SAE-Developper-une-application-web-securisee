<?php

namespace iutnc\nrv\dispatch;
use iutnc\nrv\action;
use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\auth\Authz;

class Dispatcher{
    private string $action;

    public function __construct(){
        $this->action = '';
        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        }
    }

    public function run(){
        switch ($this->action){
            case 'afficher-soiree':
                $a = (new action\AffichageSoireeAction())->execute();
                break;
            case 'afficher-spectacle':
                $a = (new action\AffichageSpectacleAction())->execute();
                break;
            case 'afficher-liste-spectacle':
                $a = (new action\AfficherListeSpectacleAction())->execute();
                break;
            case 'ajouter-spectacle':
                $a = (new action\AjouterSpectacleAction())->execute();
                break;
            case 'authentification':
                $a = (new action\AuthentificationAction())->execute();
                break;
            case 'creer-soiree':
                $a = (new action\CreerSoireeAction())->execute();
                break;
            case 'creer-spectacle':
                $a = (new action\CreerSpectacleAction())->execute();
                break;
            case 'afficher-liste-soiree':
                $a = (new action\AfficherListeSoireeAction())->execute();
                break;
            case 'filtre':
                $a = (new action\FiltrageAction())->execute();
                break;
            case 'inscription':
                $a = (new action\InscriptionAction())->execute();
                break;
            case 'deconnexion' :
                $a = (new action\DeconnexionAction())->execute();
                break;
            default:
                $a = 'Index';
                break;
        }
        $this->renderPage($a);
    }

    private function renderPage(string $html)
    {
        echo <<<END
            <!doctype html>
            <head>
                <title>NRV</title>
            </head>
            <link rel="stylesheet" type="text/css" href="style.css?v=1">
            <body>
                <div id="index">
                    <a href="index.php?action=afficher-liste-spectacle"><button type="button">afficher liste spectacle</button></a>
        END;
        try {
            $role = AuthnProvider::getSignedInUser()['role'];
        } catch (\Exception $e) {
            $role="visiteur";
        }
        if($role!="visiteur") {
            echo <<<END
                    <a href="index.php?action=ajouter-spectacle"><button type="button">Ajouter Spectacle à Soirée</button></a>
                    <a href="index.php?action=creer-soiree"><button type="button">Créer une Soirée</button></a>
                    <a href="index.php?action=creer-spectacle"><button type="button">Créer un Spectacle</button></a>
        END;
        }
        echo <<<END
                    <a href="index.php?action=authentification"><button type="button">Authentification</button></a>
                    <a href="index.php?action=inscription"><button type="button">S'inscrire</button></a>
        END;
        echo <<<END
                    <a href="index.php?action=deconnexion"><button type="button">Deconnexion</button></a>
                </div>
                $html
            </body>
        END;
    }
}
