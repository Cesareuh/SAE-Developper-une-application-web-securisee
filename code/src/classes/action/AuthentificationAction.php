<?php

namespace iutnc\nrv\action;

class AuthentificationAction extends Action
{

    public function execute():string{
		$res = $this->form();
		if($this->http_method === "POST"){
			$email = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
			$mdp = $_POST['mdp'];
			$cmdp = $_POST['cmdp'];

			if($mdp != $cmdp){
				$res = $res . "<div>Veuillez confirmer votre mot de passe</div>";
			}else{
				try{
					AuthnProvider::register($email, $mdp);
					$res = "<div>Utilisateur enregistré</div>";
				}catch (\Exception $e){
					$res = $res . "<div>".$e->getMessage()."</div>";
				}
			}

		}
		return $res;

	}

	private function form():string{
			return <<<END
				<form id="form" method="post" action="?action=register">
					<label> Email : 
						<input type="text" name="email" placeholder="exemple@mail.com"/>
					</label>
					<br>
					<label>
						Mot de passe : 
						<input type="password" name="mdp" placeholder="Mot de passe"/>
					</label>
					<br>
					<label>
						Confirmer mot de passe : 
						<input type="password" name="cmdp" placeholder="Mot de passe"/>
					</label>
					<br>
					<input type="submit" name="submit" value="S'enregistrer"/>
END;
	}
}

