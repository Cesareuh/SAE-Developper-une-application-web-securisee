<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class GestionRoleAction extends Action
{

    public function execute(): string
    {
        $repo = Repository::getInstance();
        $lUser = $repo->afficherTousUtilisateurs();
        if($this->http_method == "GET"){
            $res=<<<END
                <form method="POST" action="?action=gestion-role">
                    <label>Utilisateur :</label>
                    <select name="utilisateur">
                        <option value="">Choisissez un utilisateur</option>
            END;
            foreach($lUser as $u){
                if($u['role']!='admin') {
                    $res .= "<option value=" . $u['mail'] . ">" . $u['mail'] . "</option>";
                }
            }
            $res .= <<<END
                    </select>
                    <label>Role :</label>
                    <select name="role">
                        <option value="">Choisissez un role</option>
                        <option value="admin">Admin</option>
                        <option value="visiteur">Visiteur</option>
                        <option value="staff">Staff</option>
                        <option value="organisateur">Organisateur</option>
                    </select>
                    <input type="submit" value="Valider">
            END;

            return $res;

        }else if($this->http_method == "POST"){
            $mail=$_POST["utilisateur"];
            $role=$_POST["role"];
            $repo->setRole($mail,$role);
            return $mail . " est désormais ". $role;
        }else{
            return "Mauvais type requete";
        }
    }

}