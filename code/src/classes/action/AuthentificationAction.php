<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\repository\Repository;

class AuthentificationAction extends Action
{
    public function execute(): string
    {

        if ($this->http_method === 'POST') {
            // Récupérer les informations du formulaire
            $mail = $_POST['mail'] ?? '';
            $password = $_POST['password'] ?? '';

            try {
                // Appelle la méthode signin pour valider l'authentification
                AuthnProvider::signin($mail, $password);

                //  démarre une session si l'auth marche
                $_SESSION['utilisateur'] = $mail;

                // Rediriger l'utilisateur vers la page d'accueil ou une page protégée
                return "<p style='color: green;'>Vous êtes connecté.</p>";
            } catch (\Exception $e) {
                // Si l'auth echoue: erreur
                $html = "<p style='color: red;'>Identifiants incorrects. Veuillez réessayer.</p>";
                return $html;
            }
        }else if($this->http_method === 'GET') {


            // Si la méthode HTTP n'est pas POST, hop connexion
            $html = "
            <h1>Connexion</h1>
            <form method='POST' action='?action=authentification'>
                <label for='mail'>Email :</label>
                <input type='email' name='mail' required><br>
                <label for='password'>Mot de passe :</label>
                <input type='password' name='password' required><br>
                <button type='submit'>Se connecter</button>
            </form>
        ";

            return $html;
        }else return "Mauvais type requête";
    }
}
