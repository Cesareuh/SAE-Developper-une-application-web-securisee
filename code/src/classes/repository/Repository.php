<?php

namespace iutnc\nrv\repository;

use Couchbase\User;
use iutnc\nrv\evenement\Soiree;
use iutnc\nrv\evenement\Spectacle;
use PDO;
use PDOException;

class Repository {
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
            $statut=$row['statut'];
            $spectacle=new Spectacle($id, $titre, $artiste, $duree, $style, $video, $description, $photo, $statut);
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
            $lieu=$row['id_lieu'];
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
        $stmt->bindParam(7, $description);
        $stmt->bindParam(8, $photo);
        $stmt->execute();
    }

    public function ajouterSpectacleToSoiree(Soiree $soiree, Spectacle $spectacle){
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
            $lieu = $row['id_lieu'];
            $tarif = $row['tarif'];
            $thematique = $row['thematique'];
            $image = $row['id_img'];

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
                $row['description'],
                $row['id_img'],
                $row['statut']
            );
        }

        return null;
    }

    public function getSpectacleById(int $idSpectacle)
    {
        $query = $this->pdo->prepare("SELECT * FROM spectacles WHERE id = ?");
        $query->execute([$idSpectacle]);
        return $query->fetchObject();
    }

	public function getSoireeBySpectacleId(int $idSpectacle){
		$stmt = $this->pdo->prepare("select * from soireetospectacle where id_spectacle = :id");
		$stmt->bindParam(":id", $idSpectacle);
		$stmt->execute();

		$listeSoiree = array();
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			$id = $row['id_soiree'];
			$nom = $row['nom_soiree'];
			$date = $row['date'];
			$tarif = $row['tarif'];
			$thematique = $row['thematique'];
			$lieu = $row['id_lieu'];
			$image = $row['id_img'];

			array_push($listeSoiree, new Soiree($id, $nom, $date, $lieu, $thematique, $tarif, $image));
		}
	}

    public function getImageById(int $id_img):mixed{
        $query = $this->pdo->prepare("select * from image where id_img like :id");
        $query->bindParam(":id", $id_img);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)["blob_img"];

    }

    // Vérifie si un utilisateur existe déjà par son email
    public function trouverUtilisateurParMail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Utilisateur WHERE mail = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Crée un nouvel utilisateur dans la base de données
    public function creerUtilisateur(string $email, string $hashedPassword): void
    {
        // On fixe le rôle à 'visiteur' par défaut
        $stmt = $this->pdo->prepare("INSERT INTO Utilisateur (mail, MotDePasse, Role) VALUES (?, ?, 'visiteur')");
        $stmt->execute([$email, $hashedPassword]);
    }

    public function ajouterImage(String $img, String $nom, String $type, int $taille, mixed $id_bg):int{
        $img_blob=file_get_contents($img);
        $id=0;
        $stmt=$this->pdo->prepare('insert into image values (?,?,?,?,?,?)');
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $nom);
        $stmt->bindParam(3, $taille);
        $stmt->bindParam(4, $type);
        $stmt->bindParam(5, $img_blob);
        $stmt->bindParam(6, $id_bg);
        $stmt->execute();
        $id=$this->pdo->lastInsertId();
        return $id;
    }

    public function trouveOptionsPourFiltre(string $filtre): array {
        $query = "SELECT DISTINCT $filtre FROM spectacle 
            JOIN soireetospectacle ON spectacle.id_spectacle = soireetospectacle.id_spectacle
            JOIN soiree ON soireetospectacle.id_soiree = soiree.id_soiree
            JOIN lieu ON soiree.id_lieu = lieu.id_lieu";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function trouveSpectaclesFiltres(string $filtre, string $valeur): array {
        // Construire la requête dynamique pour le filtre
        $query = "SELECT *
            FROM spectacle 
            JOIN soireetospectacle ON spectacle.id_spectacle = soireetospectacle.id_spectacle
            JOIN soiree ON soireetospectacle.id_soiree = soiree.id_soiree
            JOIN lieu ON soiree.id_lieu = lieu.id_lieu
            WHERE $filtre = :valeur";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['valeur' => $valeur]);

        // Récupérer tous les spectacles
        $spectacles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Créer une liste vide de spectacles
        $list = [];

        // Parcours des résultats de la requête et création des objets Spectacle
        foreach ($spectacles as $row) {
            $id = $row['id_spectacle'];
            $titre = $row['titre'];
            $artiste = $row['artiste'];
            $duree = (int) $row['duree'];
            $style = $row['style'];
            $video = $row['video'];
            $photo = $row['id_img'];
            $description = $row['description'];
            $statut=$row['statut'];
            $spectacle=new Spectacle($id, $titre, $artiste, $duree, $style, $video, $description, $photo, $statut);
            array_push($list, $spectacle);
        }

        return $list;
    }

    public function trouveTousLieux():array{
        $query= $this->pdo->prepare("SELECT * FROM lieu");
        $query->execute();
        $list=[];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id=$row['id_lieu'];
            $nom=$row['nom_lieu'];
            $adresse=$row['adresse'];
            $nb_places_assises=$row['nb_places_assises'];
            $nb_places_debout=$row['nb_places_debout'];
            $s=new Soiree($id, $nom, $adresse, $nb_places_assises,$nb_places_debout, $nb_places_assises,$nb_places_debout);
            array_push($list, $s);
        }
        return $list;
    }

    public function ajouterSoiree(Soiree $soiree){
        $query=$this->pdo->prepare("insert into soiree values (?,?,?,?,?,?,?)");
        $id=0;
        $nom=$soiree->nom;
        $date=$soiree->date;
        $tarif=$soiree->tarif;
        $thematique=$soiree->thematique;
        $lieu=$soiree->lieu;
        $image=$soiree->image;

        $query->bindParam(1,$id);
        $query->bindParam(2, $nom);
        $query->bindParam(3, $date);
        $query->bindParam(4, $tarif);
        $query->bindParam(5, $thematique);
        $query->bindParam(6, $lieu);
        $query->bindParam(7, $image);
        $query->execute();
    }


    public function annulerSpectacle(int $idSpectacle): void
    {
        $query = $this->pdo->prepare("UPDATE spectacle SET statut = 'annulé' WHERE id_spectacle = :id");
        $query->bindParam(':id', $idSpectacle, PDO::PARAM_INT);
        $query->execute();
    }

    public function getImageBgId(int $id_img): mixed {
        $query = $this->pdo->prepare("SELECT id_img_bckgrnd FROM image WHERE id_img = :id");
        $query->bindParam(":id", $id_img);
        $query->execute();


        $result = $query->fetch(\PDO::FETCH_ASSOC);
        return $result ? $result['id_img_bckgrnd'] : null;
    }

    public function getPassword(string $email): ?string{
        $query = $this->pdo->prepare("select motdepasse from utilisateur where mail = ?");
        $query->bindParam(1, $email);
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC)["motdepasse"] ?: null;
    }

    public function afficherTousUtilisateurs():?array{
        $query = $this->pdo->prepare("SELECT * FROM utilisateur");
        $query->execute();
        $list=[];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $mail=$row['mail'];
            $role=$row['role'];
            $user['mail']=$mail;
            $user['role']=$role;
            array_push($list, $user);
        }
        return $list;
    }

    public function setRole(string $mail, string $role): void
    {
        $query = $this->pdo->prepare("UPDATE utilisateur SET role = ? WHERE mail = ?");
        $query->bindParam(1, $role, PDO::PARAM_STR);
        $query->bindParam(2, $mail, PDO::PARAM_STR);
        $query->execute();
    }



}
