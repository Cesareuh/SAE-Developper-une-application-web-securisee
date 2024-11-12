<?php

namespace iutnc\nrv\render;

use iutnc\nrv\evenement\Spectacle;
use iutnc\nrv\repository\Repository;

class RenderSpectacle extends Renderer
{
    private Spectacle $spec;

    public function __construct(Spectacle $s)
    {
        $this->spec = $s;
    }

    public function render(int $selector): string
    {
        $repo = Repository::getInstance();
        $res = "";

        // Vérifier si le spectacle est annulé
        $statut = $this->spec->getStatut();
        $isCancelled = ($statut === 'annulé');

        switch ($selector) {
            case 1:
                $res .= "<div class='simple'>
                            <a href='index.php?action=afficher-spectacle&id_spectacle=" . $this->spec->id . "'>
                                <div class='haut'>";

                // Afficher l'image de fond si elle existe
                if ($this->spec->id_img_bg !== null) {
                    $res .= "<img src='data:image/png;base64," . base64_encode($repo->getImageById($this->spec->id_img_bg)) . "' class='bg' alt='Photo de l'artiste' />";
                }

                $res .= "<h1 class='artiste'>" . $this->spec->artiste . "</h1>
                        </div>
                        <div class='bas'>
                            <h2 class='titre'>" . $this->spec->titre . "</h2>
                            <div class='infos'>
                                <h3 class='style'>" . $this->spec->style . "</h3>
                                <h3 class='duree'>" . $this->spec->duree . " min</h3>
                            </div>
                        </div>
                    </a>
                </div>";
                break;

            case 2:
                $res .= "<div class='complexe'>
                            <div class='haut'>";

                // Afficher un message si le spectacle est annulé
                if ($isCancelled) {
                    $res .= "<h2 class='annule' style='color:red;'>Spectacle Annulé</h2>";
                }

                // Afficher l'image de fond si elle existe
                if ($this->spec->id_img_bg !== null) {
                    $res .= "<img src='data:image/png;base64," . base64_encode($repo->getImageById($this->spec->id_img_bg)) . "' class='bg' alt='Photo de l'artiste' />";
                }

                // Afficher l'image du spectacle
                $res .= "<img src='data:image/png;base64," . base64_encode($repo->getImageById($this->spec->id_img)) . "' class='photo_profil' alt='Photo de l'artiste' />
                         <h1 class='artiste'>" . $this->spec->artiste . "</h1>
                         </div>
                         <div class='bas'>
                             <h2 class='titre'>" . $this->spec->titre . "</h2>
                             <div class='infos'>
                                 <h3 class='style'>" . $this->spec->style . "</h3>
                                 <h3 class='duree'>" . $this->spec->duree . " min</h3>
                             </div>
                             <div class='presentation'>
                                 <p class='desc'>" . $this->spec->description . "</p>
                                 <hr>";

                // Ajouter le lien vers la vidéo si elle existe
                if (!empty($this->spec->video)) {
                    $res .= "<iframe width='560' height='315' src='" . $this->spec->video . "' 
                                title='YouTube video player' frameborder='0' 
                                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' 
                                referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>";
                }

                $res .= "</div>
                        </div>
                    </div>";
                break;
        }

        return $res;
    }
}
