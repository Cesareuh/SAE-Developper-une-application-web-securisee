<?php
namespace iutnc\nrv\action;

use Exception;
use iutnc\nrv\auth\AuthnProvider;

class SignInAction extends Action{
	public function execute() : string {
		$res = $this->form();
		if ($this->http_method === 'POST'){
			try{
				$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
				AuthnProvider::signin($email, $_POST['mdp']);

				$_SESSION['utilisateur'] = $email;
				$res = "<div>L'utilisateur ".$email." est connecté</div>";
			}catch (Exception $e){
				$res = $res . "<div>Identifiants incorrects : ".$e->getMessage()."</div>";
			}
		}

		return $res;
	}

	private function form():string{
		return "<div>Se connecter !</div>
			<form action='?action=signin' method=POST>
			<br>
			Email : <input type='text' name='email'/>
			<br>
			Mdp : <input type='password' name='mdp'>
			<br>
			<input type='submit' name='Se connecter' value='Se connecter'/>
			</form>";
	}
}
