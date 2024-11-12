<?php

namespace iutnc\nrv\action;

use iutnc\nrv\auth\AuthnProvider;
use iutnc\nrv\repository\Repository;

class InscriptionAction extends Action
{
    public function execute(): string
    {
        // Vérifier si la méthode HTTP est POST pour traiter l'inscription
        if ($this->http_method === 'POST') {
            // Récupérer les informations du formulaire
            $mail = $_POST['mail'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'visiteur'; // Rôle par défaut 'visiteur'

            // Vérification basique des champs
            if (empty($mail) || empty($password)) {
                return "<p style='color: red;'>Tous les champs sont obligatoires.</p>";
            }

            // Validation de l'email
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                return "<p style='color: red;'>Veuillez entrer une adresse email valide.</p>";
            }

            try {
                // Vérifier si l'utilisateur existe déjà
                $userExists = Repository::trouverUtilisateurParMail($mail);
                if ($userExists) {
                    return "<p style='color: red;'>Cet email est déjà utilisé.</p>";
                }

                // Hachage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Enregistrer l'utilisateur dans la base de données
                Repository::creerUtilisateur($mail, $hashedPassword, $role);

                // Démarrer une session et connecter l'utilisateur automatiquement après l'inscription
                session_start();
                $_SESSION['utilisateur'] = $mail;

                // Rediriger l'utilisateur vers la page d'accueil
                header('Location: /accueil');
                exit;
            } catch (\Exception $e) {
                // Gérer les erreurs
                return "<p style='color: red;'>Erreur lors de l'inscription : " . $e->getMessage() . "</p>";
            }
        }

        // Si la méthode HTTP n'est pas POST, afficher le formulaire d'inscription
        $html = "
            <h1>Inscription</h1>
            <form method='POST' action='?action=inscription-action '>
                <label for='mail'>Email :</label>
                <input type='email' name='mail' required><br>
                <label for='password'>Mot de passe :</label>
                <input type='password' name='password' required><br>
                <button type='submit'>S'inscrire</button>
            </form>
        ";

        return $html;
    }
}
