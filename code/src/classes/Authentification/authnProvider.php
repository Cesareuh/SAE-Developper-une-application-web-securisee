<?php
namespace iutnc\nrv\auth;



class AuthnProvider{
	static function signin($email, $mdp): void{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$repo = Repository::getInstance();
			$hash = $repo->getPassword($email);

			if(!password_verify($mdp, $hash)) throw new AuthnException();
		}else{
			throw new \Exception("Email non valide");
		}
	}

	public static function register($email, $password, $confirm_password) {
        $email = self::filterSanitizeInput($email);
        $password = self::filterSanitizeInput($password);
        $confirm_password = self::filterSanitizeInput($confirm_password);
        if (empty($email) || empty($password) || empty($confirm_password)) {
            throw new AuthException("Tous les champs doivent être remplis.");
        }
        if ($password !== $confirm_password) {
            throw new AuthException("Les mots de passe ne correspondent pas.");
        }
        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            throw new AuthException("L'email est déjà utilisé.");
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = self::$pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'users', ?)");
        return $stmt->execute([$email, $hashedPassword]);
    }

	static function getSignedInUser(): ?array{
		if(isset($_SESSION["user"])){
			$repo = Repository::getInstance();
			$stmt = $repo->pdo->prepare("select * from Utilisateur where mail = ?");
			$stmt->bindParam(1, $_SESSION["user"]);
			$stmt->execute();
			$user = $stmt->fetch(\PDO::FETCH_ASSOC);
			return $user;
		}else{
			throw new \Exception("Aucun utilisateur connecté");
		}
		return null;
	}
}
