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
    public function trouveToutesSoireesAvecImages(): array {
        $query = $this->pdo->prepare(
            "SELECT soiree.id_soiree, soiree.date, soiree.nom_lieu, soiree.tarif, soiree.thematique, 
                   image.nom_img, image.desc_img
                   FROM soiree
                   LEFT JOIN image ON soiree.id_img = image.id_img"
        );
        $query->execute();

        $soirees = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $soirees[] = $row;
        }
        return $soirees;
    }


    public function trouveTousSpectacles(): array{
        $query=$this->pdo->prepare("SELECT * FROM spectacle");
        $query->execute();
        $list=[];
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $id=$row['id_spectacle'];
            $titre=$row['titre'];
            $artiste=$row['artiste'];
            $duree =(int)$row['duree'];
            $style=$row['style'];
            $video=$row['video'];
            $photo=$row['id_img'];
            $description=$row['description'];
            $spectacle=new Spectacle($id, $titre, $artiste, $duree, $style, $video, $description, $photo);
            array_push($list, $spectacle);
        }
        return $list;
    }

    public function trouveToutesSoirees():array{
        $query=$this->pdo->prepare("SELECT * FROM soiree");
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
        $photo=$spectacle->__get('photo-artiste');
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

    public function afficherSoiree(int $idSoiree): ?Soiree {
        $query = $this->pdo->prepare("SELECT * FROM soiree WHERE id_soiree = :id");
        $query->bindParam(':id', $idSoiree, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $id = $row['id_soiree'];
            $nom = $row['nom_soiree'];
            $date = $row['date'];
            $lieu = $row['nom_lieu'];
            $tarif = $row['tarif'];
            $thematique = $row['thematique'];
            $image = $row['image_soiree'];

            return new Soiree($id, $nom, $date, $lieu, $thematique, $tarif, $image);
        }
        return null;
    }

    public function afficherSpectacle(int $idSpectacle): ?Spectacle {

        $query = $this->pdo->prepare("SELECT * FROM spectacle WHERE id_spectacle = :id");
        $query->bindParam(':id', $idSpectacle, PDO::PARAM_INT);
        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {

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

        return null;
    }

    public function trouverSpectaclesFiltre(?string $date, ?string $lieu, ?string $style): array {
        $query = "
            SELECT spectacle.id_spectacle, titre, artiste, duree, style, nom_lieu, date, spectacle.id_img, description, video
            FROM spectacle 
            JOIN soireetospectacle ON spectacle.id_spectacle = soireetospectacle.id_spectacle
            JOIN soiree ON soireetospectacle.id_soiree = soiree.id_soiree
            WHERE 1=1
        ";
        $params = [];


        if ($date) {
            $query .= " AND date_spectacle = :date";
            $params[':date'] = $date;
        }

        if ($lieu) {
            $query .= " AND lieu = :lieu";
            $params[':lieu'] = $lieu;
        }

        if ($style) {
            $query .= " AND style = :style";
            $params[':style'] = $style;
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);


        // Récupération des résultats
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = new Spectacle(
                $row['id_spectacle'],
                $row['titre'],
                $row['artiste'],
                (int)$row['duree'],
                $row['style'],
                $row['video'],
                $row['description'],
                $row['id_img']);
        }

        return $result;
    }

}