<?php

namespace iutnc\nrv\dispatch;
use iutnc\nrv\action;

class Dispatcher{
    private string $action;

    public function __construct(){
        $this->action = '';
        if(isset($_GET['action'])) $this->action=$_GET['action'];
    }

    public function run(){
        switch ($this->action){
            case 'afficher-soiree':
                $a=(new action\AffichageSoireeAction())->execute();
                break;
            case 'afficher-spectacle':
                $a=(new action\AffichageSpectacleAction())->execute();
                break;
            case 'afficher-liste-spectacle':
                $a=(new action\AfficherListeSpectacleAction())->execute();
                break;
            case 'ajouter-spectacle':
                $a=(new action\AjouterSpectacleAction())->execute();
                break;
            case 'authentification':
                $a=(new action\AuthentificationAction())->execute();
                break;
            case 'creer-soiree':
                $a=(new action\CreerSoireeAction())->execute();
                break;
            case 'creer-spectacle':
                $a=(new action\CreerSpectacleAction())->execute();
                break;
            default :
                $a='Index';
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
                <link rel="stylesheet" type="text/css" href="style.css">
            </head>
            <body>
                <div id="index">
                    <a href="main.php?action=ajouter-spectacle"><button type="button">Ajouter Spectacle à Soirée</button></a>
                    <a href="main.php?action=creer-soiree"><button type="button">Créer une Soirée</button></a>
                    <a href="main.php?action=creer-spectacle"><button type="button">Créer un Spectacle</button></a>
                </div>
                $html
            </body>
        END;
    }
}
