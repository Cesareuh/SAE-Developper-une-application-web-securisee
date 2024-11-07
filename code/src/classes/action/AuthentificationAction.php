<?php

namespace iutnc\nrv\action;

use PDO;

class AuthentificationAction extends Action
{
    public function execute(): string
    {
        // type post?
        if ($this->http_method === 'POST') {
            // Récupérer les info du formulaire
            $mail = $_POST['mail'] ?? '';
            $password = $_POST['password'] ?? '';

            // pour se co à la db
            $db = pdo::getInstance()->getConnection();


            $sql = "SELECT ID_Utilisateur, mail, MotDePasse, Role FROM Utilisateur WHERE mail = :mail";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':mail', $mail);
            $stmt->execute();

            // recup les infos du user
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // user existe ? bon mdp ?
            if ($user && password_verify($password, $user['MotDePasse'])) {
                // Si l'authentification réussit, démarrer une session
                session_start();
                $_SESSION['user_id'] = $user['ID_Utilisateur'];
                $_SESSION['user_email'] = $user['mail'];
                $_SESSION['user_role'] = $user['Role'];

                // retour à la page d'accueil
                header('Location: /accueil');
                exit;
            } else {
                $html = "<p style='color: red;'>Identifiants incorrects. Veuillez réessayer.</p>";
                return $html;
            }
        }

        // si non : se connecter
        $html = "
            <h1>Connexion</h1>
            <form method='POST' action='/authentification'>
                <label for='mail'>Email :</label>
                <input type='email' name='mail' required><br>
                <label for='password'>Mot de passe :</label>
                <input type='password' name='password' required><br>
                <button type='submit'>Se connecter</button>
            </form>
        ";

        return $html;
    }
}
