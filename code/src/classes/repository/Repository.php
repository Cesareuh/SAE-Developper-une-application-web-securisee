<?php

namespace iutnc\nrv\action;

use iutnc\nrv\repository\Repository;

class AfficherListeSpectacleAction extends Action
{
    public function execute(): string {

        $repository = Repository::getInstance();
        $spectacles = $repository->trouveTousSpectacles();

        $html = "<h1>Liste des Spectacles</h1><ul>";
        foreach ($spectacles as $spectacle) {

            $html .= "<li>";
            $html .= "<p><strong>Titre :</strong> " . htmlspecialchars($spectacle->getTitre()) . "</p>";
            $html .= "<p><strong>Artiste :</strong> " . htmlspecialchars($spectacle->getArtiste()) . "</p>";
            $html .= "<p><strong>Durée :</strong> " . htmlspecialchars($spectacle->getDuree()) . " minutes</p>";
            $html .= "<p><strong>Style :</strong> " . htmlspecialchars($spectacle->getStyle()) . "</p>";


            $html .= "<a href='?action=afficher_spectacle&id=" . urlencode($spectacle->getId()) . "'>Voir les détails</a>";
            $html .= "</li><br>";
        }
        $html .= "</ul>";

        return $html;
    }


    public function trouveTousSpectacles(): array{
        $query=$this->pdo->prepare("SELECT * FROM spectacle");
        $query->execute();
        $list=[];
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $id=$row['id_spectacle'];
            $titre=$row['titre'];
            $artiste=$row['artiste'];
            $duree=$row['duree'];
            $style=$row['style'];
            $video=$row['video'];
            $photo=$row['id_img'];
            $description=$row['description'];
            $spectacle=new Spectacle($id, $titre, $artiste, $photo, (int)$duree, $style, $video, $description);
            array_push($list, $spectacle);
        }
        return $list;
    }

    public function trouveToutesSoirees():array{
        $query=$this->pdo->prepare("SELECT * FROM `soiree`");
        $query->execute();
        $list=[];
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $id=$row['id_soiree'];
            $nom=$row['nom_soiree'];
            $date=$row['date'];
            $lieu=$row['nom_lieu'];
            $tarif=$row['tarif'];
            $thematique=$row['thematique'];
            $image=$row['id_img'];
            $soiree=new Soiree($id, $nom, $date, $lieu, $thematique, $tarif, $image);
            array_push($list, $soiree);
        }
        return $list;
    }

    public function ajouterSpectacle(Spectacle $spectacle)
    {
        $stmt = $this->pdo->prepare("insert into spectacle values (?,?,?,?,?,?,?,?)");
        $id=0;
        $titre=$spectacle->__get('titre');
        $artiste=$spectacle->__get('artiste');
        $duree=$spectacle->__get('duree');
        $style=$spectacle->__get('style');
        $video=$spectacle->__get('video');
        $photo=$spectacle->__get('id_img');
        $description=$spectacle->__get('description');
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $titre);
        $stmt->bindParam(3, $artiste);
        $stmt->bindParam(4, $duree);
        $stmt->bindParam(5, $style);
        $stmt->bindParam(6, $video);
        $stmt->bindParam(7, $photo);
        $stmt->bindParam(8, $description);
        $stmt->execute();
    }

    public function ajouterSpectacleToSoiree(Spectacle $spectacle, Soiree $soiree){
        $idSpectacle=$spectacle->__get('id');
        $idSoiree=$soiree->__get('id');
        $stmt=$this->pdo->prepare('insert into soireetospectacle values (?,?)');
        $stmt->bindParam(1, $idSoiree);
        $stmt->bindParam(2, $idSpectacle);
        $stmt->execute();
    }

    public function ajouterImage(String $img, String $nom, String $type, int $taille):int{
        $img_blob=file_get_contents($img);
        $id=0;
        $stmt=$this->pdo->prepare('insert into image values (?,?,?,?,?)');
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $nom);
        $stmt->bindParam(3, $taille);
        $stmt->bindParam(4, $type);
        $stmt->bindParam(5, $img_blob);
        $stmt->execute();
        return $id;
    }

    public function afficherSpectacle(int $idSpectacle): ?Spectacle {
        // Récupérer les informations du spectacle
        $query = $this->pdo->prepare("SELECT * FROM `spectacle` WHERE `id_spectacle` = :id");
        $query->bindParam(':id', $idSpectacle, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Créer un objet Spectacle
            return new Spectacle(
                $row['id_spectacle'],
                $row['titre'],
                $row['artiste'],
                $row['duree'],
                $row['style'],
                $row['video'],
                $row['photo'],
                $row['description']
            );
        }

        return null; // Si le spectacle n'est pas trouvé
    }

    public function afficherSoiree(int $idSoiree): ?Soiree {
        // Récupérer les informations de la soirée
        $query = $this->pdo->prepare("SELECT * FROM `soiree` WHERE `id_soiree` = :id");
        $query->bindParam(':id', $idSoiree, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Créer un objet Soiree
            return new Soiree(
                $row['id_soiree'],
                $row['nom_soiree'],
                $row['date'],
                $row['nom_lieu'],
                $row['thematique'],
                $row['tarif'],
                $row['image_soiree']
            );
        }

        return null; // Si la soirée n'est pas trouvée
    }




}