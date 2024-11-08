1.
1. Spectacle
    ID_Spectacle : Identifiant unique pour chaque spectacle (clé primaire).
    Titre : Titre du spectacle.
    Artiste : Lartiste ou le groupe qui réalise le spectacle.
    Duree : Durée du spectacle, au format HH:MM:SS.
    Style : Genre musical du spectacle (ex. Classic Rock, Metal, Blues Rock).
    video : Lien vers une vidéo du spectacle.
    photo : Lien vers la photo du groupe ou artiste.
    description : Description détaillée du spectacle.

2. Image
    id_img : Identifiant unique pour chaque image (clé primaire).
    nom_img : Nom de l image.
    taille_img : Taille de l image (ex. 500KB).
    type_img : Type de l image (ex. image/png).
    blob_img : Données de l image au format binaire (BLOB).

3. Soiree
    ID_Soiree : Identifiant unique pour chaque soirée (clé primaire).
    Nom_Soiree : Nom de la soirée.
    Date : Date de la soirée.
    Nom_Lieu : Nom du lieu où se déroule la soirée.
    tarif : Tarif d entrée pour la soirée.
    thématique : Thématique de la soirée (ex. soirée métal, soirée rock).
    image_soiree : Image représentant la soirée, en référence à une image de l entité Image.

4. SoireeToSpectacle
    ID_Soiree : Référence vers une soirée spécifique (clé étrangère vers Soiree).
    ID_Spectacle : Référence vers un spectacle spécifique (clé étrangère vers Spectacle).
    Clé primaire : Combinaison de ID_Soiree et ID_Spectacle.

5. Utilisateur
    ID_Utilisateur : Identifiant unique pour chaque utilisateur (clé primaire).
    mail : Adresse email de l utilisateur.
    MotDePasse : Mot de passe haché de l utilisateur.
    Role : Rôle de l utilisateur dans le système (ex. visiteur, staff, admin, organisateur).

2.Spectacle

    ID_Spectacle → Titre, Artiste, Duree, Style, video, photo, description

    Id_img → nom_img, taille_img, type_img, blob_img

    ID_Soiree → Date, Nom_Soiree, Nom_Lieu, tarif, thématique, image_soiree

    (ID_Soiree, ID_Spectacle) → Aucun autre attribut.

    ID_Utilisateur → mail, MotDePasse, Role


3. Clés Minimales
    Spectacle : ID_Spectacle est la clé primaire.
    Image : id_img est la clé primaire.
    Soiree : ID_Soiree est la clé primaire.
    SoireeToSpectacle : (ID_Soiree, ID_Spectacle) est la clé primaire (clé composite).
    Utilisateur : ID_Utilisateur est la clé primaire.

4. Vérification de la 3FN

    Spectacle : Respecte la 3FN, car les attributs (Titre, Artiste, Duree, Style, video, photo, description) dépendent
                   uniquement de l identifiant primaire ID_Spectacle.
    Image : Respecte la 3FN, car les attributs (nom_img, taille_img, type_img, blob_img) dépendent uniquement
                   de l identifiant primaire id_img.
    Soiree : Respecte la 3FN, car les attributs (Date, Nom_Soiree, Nom_Lieu, tarif, thématique, image_soiree) dépendent
                   uniquement de l identifiant primaire ID_Soiree.
    SoireeToSpectacle : Respecte la 3FN, car il s agit d une table de liaison définie par une clé composite (ID_Soiree,
                   ID_Spectacle) et ne contient pas de redondance d informations.
    Utilisateur : Respecte la 3FN, car les attributs (mail, MotDePasse, Role) dépendent uniquement de l identifiant
                   primaire ID_Utilisateur.

5. Décomposition en Relations Respectant la 3FN
    La structure actuelle des tables respecte déjà la 3FN, car chaque table est organisée de manière à ce que tous les
    attributs dépendent uniquement de la clé primaire.

6. Script SQL pour Créer les Tables et Insérer des Données


DROP TABLE IF EXISTS soireetospectacle;
DROP TABLE IF EXISTS spectacle;
DROP TABLE IF EXISTS soiree;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS image;

-- Table Image
CREATE TABLE image (
                       id_img INT NOT NULL AUTO_INCREMENT,
                       nom_img VARCHAR(50) NOT NULL,
                       taille_img VARCHAR(25) NOT NULL,
                       type_img VARCHAR(25) NOT NULL,
                       blob_img BLOB ,
                       PRIMARY KEY (id_img)
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
                        nom_lieu VARCHAR(100) NOT NULL,
                        tarif DECIMAL(10, 2),
                        thematique VARCHAR(100),
                        id_img INT,
                        PRIMARY KEY (id_soiree),
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
INSERT INTO image (nom_img, taille_img, type_img, blob_img) VALUES
            ('rocknight.jpg', '2MB', 'image/jpeg', null),
            ('bluesvibes.jpg', '1.5MB', 'image/jpeg', null),
            ('soiree.jpg', '3MB', 'image/jpeg', null);

-- Insertion des spectacles
INSERT INTO spectacle (titre, artiste, duree, style, video, description, id_img) VALUES
             ('Rock Night', 'ACDC', '02:00:00', 'Classic Rock', 'https://www.youtube.com/watch?v=gEPmA3USJdI', 'Concert de rock classique', 1),
             ('Blues Vibes', 'Blues Brothers', '01:30:00', 'Blues Rock', 'https://www.youtube.com/watch?v=RrhThz_1Z2I', 'Spectacle de blues rock', 2);

-- Insertion des soirées
INSERT INTO soiree (nom_soiree, date, nom_lieu, tarif, thematique, id_img) VALUES
               ('Soirée Rock', '2024-10-01', 'Nancy Arena', 25.00, 'Rock', 3),
               ('Soirée Blues', '2024-10-02', 'Le Zenith', 30.00, 'Blues', 3),
               ('Soirée Metal', '2024-10-03', 'La Lune', 20.00, 'Metal', 3);

-- Insertion des relations SoireeToSpectacle
INSERT INTO soireetospectacle (id_soiree, id_spectacle) VALUES
                (1, 1), -- Rock Night dans la soirée 1
                (1, 2), -- Blues Vibes dans la soirée 1
                (2, 1), -- Rock Night dans la soirée 2
                (3, 2); -- Blues Vibes dans la soirée 3

