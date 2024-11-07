<?php

namespace iutnc\nrv\repository;

use iutnc\nrv\evenement\Soiree;
use iutnc\nrv\evenement\Spectacle;
use PDO;

class Repository
{
    private \PDO $pdo;
    private static ?Repository $instance = null;
    private static array $config = [ ];

    private function __construct(array $conf) {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new Repository(self::$config);
        }
        return self::$instance;
    }
    public static function setConfig(string $file) : void{
        $conf = parse_ini_file($file, true);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        self::$config = [ 'dsn'=> $conf['dsn'],'user'=> $conf['user'],'pass'=> $conf['pass'] ];
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
}