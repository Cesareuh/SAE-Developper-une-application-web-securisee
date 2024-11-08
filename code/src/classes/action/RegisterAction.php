<?php
namespace iutnc\nrv\action;

use Exception;
use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\exception\AuthException;

class RegisterAction extends Action {
    public function execute() : string {
        $res = $this->form();
        if ($this->http_method === 'POST') {
            try {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $mdp = filter_var($_POST['mdp'], FILTER_SANITIZE_STRING);
                $mdp_confirm = filter_var($_POST['mdp_confirm'], FILTER_SANITIZE_STRING);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("L'email n'est pas valide.");
                }
                AuthnProvider::register($email, $mdp, $mdp_confirm);
                session_start();
                $_SESSION['utilisateur'] = $email;
                header('Location: /accueil');
                exit;
            } catch (AuthException $e) {
                $res .= "<div style='color: red;'>Erreur lors de l'inscription : " . $e->getMessage() . "</div>";
            } catch (Exception $e) {
                $res .= "<div style='color: red;'>Une erreur est survenue : " . $e->getMessage() . "</div>";
            }
        }

        return $res;
    }

    private function form(): string {
        return "
            <h1>Inscription</h1>
            <form action='?action=register' method='POST'>
                <label for='email'>Email :</label>
                <input type='email' name='email' required><br>
                
                <label for='mdp'>Mot de passe :</label>
                <input type='password' name='mdp' required><br>
                
                <label for='mdp_confirm'>Confirmer le mot de passe :</label>
                <input type='password' name='mdp_confirm' required><br>
                
                <button type='submit'>S'inscrire</button>
            </form>
        ";
    }
}
