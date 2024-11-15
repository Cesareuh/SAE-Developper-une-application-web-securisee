<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Spectacle;
use iutnc\nrv\repository\Repository;
use iutnc\nrv\auth\AuthnProvider;

class RenderSpectacle extends Renderer
{
    private Spectacle $spec;

    public function __construct(Spectacle $s){
        $this->spec = $s;
    }

    public function render(int $selector): string {
        $repo = Repository::getInstance();
        $res = "";
    
        // Vérifier si l'utilisateur est connecté et récupérer ses informations
        try {
            $user = AuthnProvider::getSignedInUser();
            $isStaff = $user['role'] === 'staff';
            $isAdmin = $user['role'] === 'admin'; // Vérifie si l'utilisateur a le rôle 'staff'
        } catch (\Exception $e) {
            $isStaff = false; // Si l'utilisateur n'est pas connecté, on suppose qu'il n'est pas staff
            $isAdmin = false;
        }

    
        // Vérifier si le spectacle est annulé
        $statut = $this->spec->statut;
        $isCancelled = ($statut === 'annulé');
    
        switch ($selector) {
            case 1:
                // Affichage simple
                $res .= "<div class='simple'>
                    <a href='index.php?action=afficher-spectacle&id_spectacle=".$this->spec->id."'>
                    <div class='haut'>
                        <h1 class='artiste'>".$this->spec->artiste."</h1>";
    
                // Affichage de l'image de fond
                if ($this->spec->id_img_bg !== null) {
                    $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img_bg))."' class='bg' alt='Photo de l'artiste' />";
                } else {
                    $res .= "<img src='../../../images_de_base/fond.jpg' class='bg' alt='Photo de l'artiste' />";
                }
    
                $res .= "</div>
                    <div class='bas'>";
    
                // Affichage du statut annulé pour tout le monde
                if ($isCancelled) {
                    $res .= "<p class='annule'>Spectacle Annulé</p>";
                }
    
                $res .= "<h2 class='titre'>".$this->spec->titre."</h2>
                    <div class='infos'>
                        <h3 class='style'>".$this->spec->style."</h3>
                        <h3 class='duree'>".$this->spec->duree." min</h3>
                    </div>";
    
                if ($isStaff || $isAdmin && !$isCancelled) { // Vérifie si l'utilisateur est staff et que le spectacle n'est pas annulé
                    $res .= "
                        <form action='index.php?action=afficher-liste-spectacle' method='post'>
                            <input type='hidden' name='id' value='{$this->spec->id}' />
                            <button type='submit' name='annuler' class='btn-annuler'>Annuler le Spectacle</button>
                        </form>
                    ";
                }
                

    
                // Ajouter aux préférences
                $res .= "<a href='index.php?action=ajouter-preference&id_spectacle=".$this->spec->id."' class='btn-preference'><button>Ajouter aux préférences</button></a>
                </div></a>
                </div>";
                break;
    
            case 2:
                // Affichage plus complexe
                $res .= "<div class='complexe'><div class='haut'>";
    
                // Image de fond
                if ($this->spec->id_img_bg !== null) {
                    $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img_bg))."' class='bg' alt='Photo de l'artiste' />";
                } else {
                    $res .= "<img src='../../../images_de_base/fond.jpg' class='bg' alt='Photo de l'artiste' />";
                }
    
                // Image du profil
                if ($repo->getImageById($this->spec->id_img)) {
                    $res .= "<img src='data:image/png;base64,".base64_encode($repo->getImageById($this->spec->id_img))."' class='photo_profil' alt='Photo de l'artiste' />";
                } else {
                    $res .= "<img src='../../../images_de_base/pp.jpg' class='photo_profil' alt='Photo de l'artiste' />";
                }
    
                // Affichage du statut annulé
                if ($isCancelled) {
                    $res .= "<p class='annule'>Spectacle Annulé</p>";
                }
    
                $res .= "<h1 class='artiste'>".$this->spec->artiste."</h1>
                </div>
                <div class='bas'>
                    <h2 class='titre'>".$this->spec->titre."</h2>
                    <div class='infos'>
                        <h3 class='style'>".$this->spec->style."</h3>
                        <h3 class='duree'>".$this->spec->duree." min</h3>
                    </div>
                    <div class='presentation'>
                        <p class='desc'>".$this->spec->description."</p>
                        <hr>";
    
                // Affichage de la vidéo
				$res .= "<iframe width='560' height='315' src='".$this->spec->video."' 
					title='YouTube video player' frameborder='0' 
					allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' 
					referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>";
    
                $res .= "</div>
                    <h1>Soirées incluant ce spectacle</h1>
                    <ul>";
    
                // Affichage des soirées liées à ce spectacle
                foreach ($repo->getSoireeBySpectacleId($this->spec->id) as $soiree) {
                    $res .= "<li>" . (new RenderSoiree($soiree))->render(1) . "</li>";
                }
    
                $res .= "</ul>";
    
                if ($isStaff || $isAdmin && !$isCancelled) { // Vérifie si l'utilisateur est staff et que le spectacle n'est pas annulé
                    $res .= "
                        <form action='index.php?action=annuler-spectacle' method='post'>
                            <input type='hidden' name='id' value='".$this->spec->id."' />
                            <button type='submit' class='btn-annuler'>Annuler le Spectacle</button>
                        </form>
                    ";
                }            

                // Ajouter aux préférences
                $res .= "<a href='index.php?action=ajouter-preference&id_spectacle=".$this->spec->id."' class='btn-preference'>Ajouter aux préférences</a>
                </div></div>";
                break;
        }
    
        return $res;
    }
    
}
