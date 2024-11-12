<?php
namespace iutnc\nrv\auth;

use iutnc\nrv\exception\AuthException;
use iutnc\nrv\repository\Repository;

class AuthnProvider{
	static function signin($email, $mdp): void{
        $repo = Repository::getInstance();
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if($repo->trouverUtilisateurParMail($email)!=null){
			$hash = $repo->getPassword($email);

			if(!password_verify($mdp, $hash)) throw new AuthException();
		}else{
			throw new \Exception("Email non valide");
		}
	}

	public static function register($email, $password, $confirm_password) {
        $repo = Repository::getInstance();
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $passwordSan = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_passwordSan = filter_var($confirm_password, FILTER_SANITIZE_SPECIAL_CHARS);
        if (empty($email) || empty($password) || empty($confirm_password)) {
            throw new AuthException("Tous les champs doivent être remplis.");
        }
        if($email !== filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new AuthException("Email non valide");
        }
        if($password != $passwordSan || $confirm_passwordSan != $passwordSan){
            throw new AuthException("Caracteres incorectes dans le mot de passe");
        }
        if ($password !== $confirm_password) {
            throw new AuthException("Les mots de passe ne correspondent pas.");
        }

        if ($repo->trouverUtilisateurParMail($email)===null) {
            throw new AuthException("L'email est déjà utilisé.");
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $repo->creerUtilisateur($email, $hashedPassword);
    }

	static function getSignedInUser(): ?array{
		if(isset($_SESSION["user"])){
			$repo = Repository::getInstance();
            return $repo->trouverUtilisateurParMail($_SESSION["user"]);
		}else{
			throw new \Exception("Aucun utilisateur connecté");
		}
	}
}
