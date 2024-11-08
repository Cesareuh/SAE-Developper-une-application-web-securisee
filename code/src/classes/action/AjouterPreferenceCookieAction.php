<?php

namespace iutnc\nrv\action;

class AjouterPreferenceCookieAction extends Action
{
    public function execute(): string
    {
        $idSpectacle = $_POST['id_spectacle'] ?? null;
        if (!$idSpectacle) {
            return "<p>Aucun spectacle sélectionné.</p>";
        }
        $preferences = isset($_COOKIE['preferences']) ? json_decode($_COOKIE['preferences'], true) : [];

        if (!in_array($idSpectacle, $preferences)) {
            $preferences[] = $idSpectacle; // juste l'id du spectacle pour ne pas prendre trop de place
        }

        if (count($preferences) > 10) {
            array_shift($preferences); //si le nb de preferences depasse 10 on supprime la plus ancienne
        }

        setcookie('preferences', json_encode($preferences), time() + 3600 * 24 * 30, "/", "", true, true); // 30 jours

        return "<p>Le spectacle a été ajouté à vos préférences.</p>";
    }
}
