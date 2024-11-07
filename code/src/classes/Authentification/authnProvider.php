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

	static function register($email, $mdp): void{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$repo = Repository::getInstance();
			$stmt = $repo->pdo->prepare("select * from Utilisateur where mail = ?");
			$stmt->bindParam(1, $email);
			$stmt->execute();
			if($stmt->fetch(\PDO::FETCH_ASSOC) === false){
				if(strlen($mdp) >= 10){
					$passwd = password_hash($mdp, PASSWORD_BCRYPT, ["cost" => 12]);
					$stmt = $repo->pdo->prepare("insert into Utilisateur values (0,?,?,1)");
					$stmt->bindParam(1,$email);
					$stmt->bindParam(2,$passwd);
					$stmt->execute();
				}else{
					throw new \Exception("Mot de passe trop court");
				}
			}else{
				throw new \Exception("Utilisateur déjà inscrit");
			}
		}else{
			throw new \Exception("Email non valide");
		}
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
