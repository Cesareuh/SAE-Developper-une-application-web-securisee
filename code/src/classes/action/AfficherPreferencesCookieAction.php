<?php

namespace iutnc\nrv\action;

class AfficherPreferencesCookieAction extends Action
{
    public function execute(): string
    {

        $preferences = isset($_COOKIE['preferences']) ? json_decode($_COOKIE['preferences'], true) : [];
        if (empty($preferences)) {
            return "<p>Vous n'avez pas encore de spectacles préférés.</p>";
        }


        $html = "<h1>Vos Préférences</h1><ul>";
        foreach ($preferences as $idSpectacle) {
            $spectacle = Repository::getInstance()->getSpectacleById($idSpectacle);
            $html .= "<li>" . htmlspecialchars($spectacle->__get('titre')) . " - " . htmlspecialchars($spectacle->__get('artiste')) . "</li>";
        }
        $html .= "</ul>";

        return $html;
    }
}
