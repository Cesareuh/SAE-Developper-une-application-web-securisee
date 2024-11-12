<?php
namespace iutnc\nrv\auth;

use iutnc\nrv\repository\Repository;

class Authz{
	static function checkRole(int $expected_role):bool{
		return AuthnProvider::getSignedInUser()["role"] === $expected_role;
	}
}