1.1. Dictionnaire des données
1.1. Spectacle
        ID_Spectacle : Identifiant unique du spectacle (clé primaire).
        Titre : Titre du spectacle.
        Artiste : Nom de l artiste ou groupe du spectacle.
        Duree : Durée du spectacle (au format HH:MM).
        Style : Genre musical du spectacle (ex : Rock, Metal, Jazz).
        Video : Lien vers une vidéo de promotion ou d extrait du spectacle.
        Photo : Lien vers une image représentant le spectacle.
        Description : Description détaillée du spectacle.
        ID_Img : Référence à l image associée au spectacle (clé étrangère vers Image).
1.2. Image
        ID_Img : Identifiant unique pour chaque image (clé primaire).
        Nom_Img : Nom du fichier image.
        Taille_Img : Taille de l image (par exemple 500KB).
        Type_Img : Type MIME de l image (par exemple image/png).
        Blob_Img : Données binaires de l image (BLOB).
1.3. Lieu
        ID_Lieu : Identifiant unique du lieu (clé primaire).
        Nom_Lieu : Nom du lieu (par exemple "Le Zenith").
        Adresse : Adresse complète du lieu.
        Nb_Places_Assises : Nombre de places assises disponibles.
        Nb_Places_Debout : Nombre de places debout disponibles.
1.4. Soiree
        ID_Soiree : Identifiant unique de la soirée (clé primaire).
        Nom_Soiree : Nom de l événement.
        Date : Date de l événement.
        Tarif : Tarif d entrée pour l événement.
        Thematique : Thématique de la soirée (ex : soirée métal, soirée rock).
        ID_Lieu : Lieu où se déroule la soirée (clé étrangère vers Lieu).
        ID_Img : Image associée à la soirée (clé étrangère vers Image).
1.5. SoireeToSpectacle (Table de liaison)
        ID_Soiree : Référence vers la soirée (clé étrangère vers Soiree).
        ID_Spectacle : Référence vers le spectacle (clé étrangère vers Spectacle).
        Clé Primaire : Combinaison de ID_Soiree et ID_Spectacle.
1.6. Utilisateur
        ID_Utilisateur : Identifiant unique pour chaque utilisateur (clé primaire).
        Mail : Adresse email de l utilisateur.
        MotDePasse : Mot de passe haché de l utilisateur.
        Role : Rôle de l utilisateur dans le système (ex : visiteur, staff, admin).


2. Dépendances fonctionnelles :


ID_Spectacle → Titre, Artiste, Duree, Style, Video, Photo, Description, ID_Img
ID_Img → Nom_Img, Taille_Img, Type_Img, Blob_Img
ID_Lieu → Nom_Lieu, Adresse, Nb_Places_Assises, Nb_Places_Debout
ID_Soiree → Nom_Soiree, Date, Tarif, Thematique, ID_Lieu, ID_Img
(ID_Soiree, ID_Spectacle) → (Pas d autres attributs)
ID_Utilisateur → Mail, MotDePasse, Role

3. Clés minimales :

Spectacle : La clé primaire est ID_Spectacle.
Image : La clé primaire est ID_Img.
Lieu : La clé primaire est ID_Lieu.
Soiree : La clé primaire est ID_Soiree.
SoireeToSpectacle : La clé primaire est la combinaison de ID_Soiree et ID_Spectacle.
Utilisateur : La clé primaire est ID_Utilisateur.

4. Vérification de la 3FN  :

Spectacle : Respecte la 3FN. Tous les attributs dépendent uniquement de ID_Spectacle, et il n y a pas de dépendances transitives.
Image : Respecte la 3FN. Tous les attributs dépendent uniquement de ID_Img, et il n y a pas de dépendances transitives.
Lieu : Respecte la 3FN. Tous les attributs dépendent uniquement de ID_Lieu.
Soiree : Respecte la 3FN. Tous les attributs dépendent uniquement de ID_Soiree.
SoireeToSpectacle : Respecte la 3FN. Il s agit d une table de liaison sans attributs supplémentaires.
Utilisateur : Respecte la 3FN. Tous les attributs dépendent uniquement de ID_Utilisateur.

5. Décomposition en relations respectant la 3FN :
La structure de la base de donnée respecte la 3FN car :
Tous les attributs non-clés dépendent uniquement de la clé primaire.
Il n y a pas de dépendances transitives.


6. Script SQL pour Créer les Tables et Insérer des Données


DROP TABLE IF EXISTS soireetospectacle;
DROP TABLE IF EXISTS spectacle;
DROP TABLE IF EXISTS soiree;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS lieu;

-- Table Image
CREATE TABLE image (
                       id_img INT NOT NULL AUTO_INCREMENT,
                       nom_img VARCHAR(50) NOT NULL,
                       taille_img VARCHAR(25) NOT NULL,
                       type_img VARCHAR(25) NOT NULL,
                       blob_img BLOB,
                       id_img_bckgrnd INT,
                       PRIMARY KEY (id_img),
                       FOREIGN KEY (id_img_bckgrnd) REFERENCES image(id_img) ON DELETE SET NULL
);

-- Table Lieu
CREATE TABLE lieu (
                      id_lieu INT NOT NULL AUTO_INCREMENT,
                      nom_lieu VARCHAR(100) NOT NULL,
                      adresse VARCHAR(255) NOT NULL,
                      nb_places_assises INT NOT NULL,
                      nb_places_debout INT NOT NULL,
                      PRIMARY KEY (id_lieu)
);

-- Table Spectacle
CREATE TABLE spectacle (
                           id_spectacle INT NOT NULL AUTO_INCREMENT,
                           titre VARCHAR(100) NOT NULL,
                           artiste VARCHAR(100),
                           duree TIME,
                           style VARCHAR(50),
                           video VARCHAR(500),
                           description VARCHAR(2000),
                           id_img INT,
                           PRIMARY KEY (id_spectacle),
                           FOREIGN KEY (id_img) REFERENCES image(id_img) ON DELETE SET NULL
);

-- Table Soiree
CREATE TABLE soiree (
                        id_soiree INT NOT NULL AUTO_INCREMENT,
                        nom_soiree VARCHAR(50),
                        date DATE NOT NULL,
                        tarif DECIMAL(10, 2),
                        thematique VARCHAR(100),
                        id_lieu INT,
                        id_img INT,
                        PRIMARY KEY (id_soiree),
                        FOREIGN KEY (id_lieu) REFERENCES lieu(id_lieu) ON DELETE SET NULL,
                        FOREIGN KEY (id_img) REFERENCES image(id_img) ON DELETE SET NULL
);

-- Table de liaison SoireeToSpectacle
CREATE TABLE soireetospectacle (
                                   id_soiree INT,
                                   id_spectacle INT,
                                   PRIMARY KEY (id_soiree, id_spectacle),
                                   FOREIGN KEY (id_soiree) REFERENCES soiree(id_soiree) ON DELETE CASCADE,
                                   FOREIGN KEY (id_spectacle) REFERENCES spectacle(id_spectacle) ON DELETE CASCADE
);

-- Table Utilisateur
CREATE TABLE utilisateur (
                             id_utilisateur INT NOT NULL AUTO_INCREMENT,
                             mail VARCHAR(100) NOT NULL UNIQUE,
                             motdepasse VARCHAR(255) NOT NULL,
                             role ENUM('visiteur', 'staff', 'admin', 'organisateur') NOT NULL,
                             PRIMARY KEY (id_utilisateur)
);

-- Insertion des utilisateurs
INSERT INTO utilisateur (mail, motdepasse, role) VALUES
    ('JohnDoe@gmail.com', 'hashed_password_1', 'visiteur'),
    ('Alice@exemple.com', 'hashed_password_2', 'admin'),
    ('Bob@exemple.com', 'hashed_password_3', 'staff');

-- Insertion des images
INSERT INTO image (nom_img, taille_img, type_img, blob_img, id_img_bckgrnd) VALUES
    ('rocknight.jpg', '2MB', 'image/jpeg', null, null),
    ('bluesvibes.jpg', '1.5MB', 'image/jpeg', null, null),
    ('soiree.jpg', '3MB', 'image/jpeg', null, null);

-- Insertion des lieux
INSERT INTO lieu (nom_lieu, adresse, nb_places_assises, nb_places_debout) VALUES
      ('Zénith de Nancy', 'Rue du Zénith, 54320 Maxéville', 18000, 7000),
      ('L Autre Canal', '45 Bd d Austrasie, 54000 Nancy', 0, 13000),
      (' Place Carnot', 'Pl. Carnot54000 Nancy', 0, 20000),
      (' Parc de la Pépinière', 'Parc de la pepinière, 54000 Nancy', 0, 25000);

-- Insertion des spectacles
INSERT INTO spectacle (titre, artiste, duree, style, video, description, id_img) VALUES
     ('Rock Night', 'ACDC', '02:00:00', 'Classic Rock', 'https://www.youtube.com/watch?v=gEPmA3USJdI', 'Concert de rock classique', 1),
     ('Blues Vibes', 'Blues Brothers', '01:30:00', 'Blues Rock', 'https://www.youtube.com/watch?v=RrhThz_1Z2I', 'Spectacle de blues rock', 2);

-- Insertion des soirées
INSERT INTO soiree (nom_soiree, date, tarif, thematique, id_lieu, id_img) VALUES
  ('Soirée Rock', '2024-10-01', 25.00, 'Rock', 1, 3),
  ('Soirée Blues', '2024-10-02', 30.00, 'Blues', 2, 3),
  ('Soirée Metal', '2024-10-03', 20.00, 'Metal', 3, 3);

-- Insertion des relations SoireeToSpectacle
INSERT INTO soireetospectacle (id_soiree, id_spectacle) VALUES
    (1, 1), -- Rock Night dans la première soirée
    (1, 2), -- Blues Vibes dans la première soirée
    (2, 1), -- Rock Night dans la deuxième soirée
    (3, 2); -- Blues Vibes dans la troisième soirée
