1. Dictionnaire des Données
    Spectacle:
    ID_Spectacle : Identifiant unique pour chaque spectacle.
    Titre : Le titre du spectacle.
    Artiste : L artiste ou le groupe qui réalise le spectacle.
    Duree : Durée du spectacle (format HH:MM:SS).
    Style : Le genre musical du spectacle (ex. Classic Rock, Metal, Blues Rock, etc.).
    video : Lien vidéo pour le spectacle.
    photo : Photo du groupe/artiste.
    description : description detaillée du spectacle

    Soiree
    ID_Soiree : Identifiant unique pour chaque soirée.
    Nom_Soirée : nom de la soirée
    Date : La date spécifique de la soirée.
    Nom_Lieu : Le nom du lieu où la soirée se déroule.
    tarif : Le tarif d entrée pour la soirée.
    thématique : La thématique de la soirée (par exemple, soirée métal, soirée rock, etc.).
    image_soiree : image qui represente la soiree

    SoireeToSpectacle
    ID_Soiree : Lien vers une soirée spécifique.
    ID_Spectacle : Lien vers un spectacle spécifique.

    Utilisateur
    ID_Utilisateur : Identifiant unique pour chaque utilisateur.
    mail : L email de l utilisateur.
    MotDePasse : Le mot de passe haché de l utilisateur.
    Role : Le rôle de l utilisateur (visiteur, staff, admin, organisateur).

2. Dépendances Fonctionnelles

    ID_Spectacle → Titre, Artiste, Duree, Style, video, photo, description

    ID_Soiree → Date, Nom_Soirée,Nom_Lieu, tarif, thématique, image_soiree

    ID_Soiree, ID_Spectacle → Aucun autre attribut (clés de liaison)

    ID_Utilisateur → mail, MotDePasse, Role

3. Clés Minimales

    Spectacle : ID_Spectacle est la clé primaire.
    Soiree : ID_Soiree est la clé primaire.
    SoireeToSpectacle : (ID_Soiree, ID_Spectacle) est la clé primaire.
    Utilisateur : ID_Utilisateur est la clé primaire.

4. Vérification de la 3FN

    Spectacle : Respecte la 3FN, car les attributs dépendent uniquement de l identifiant primaire ID_Spectacle.
    Soiree : Respecte la 3FN, car les attributs dépendent uniquement de l identifiant primaire ID_Soiree.
    SoireeToSpectacle : Respecte la 3FN, car il s agit d une table de liaison sans redondance d informations.
    Utilisateur : Respecte la 3FN, car les attributs dépendent uniquement de l identifiant primaire ID_Utilisateur.

5. Décomposition en Relations Respectant la 3FN

    La structure actuelle des tables respecte déjà la 3FN. Il n est pas nécessaire de décomposer davantage.

6. Script SQL pour Créer les Tables et Insérer des Données


-- Table Soiree
CREATE TABLE Soiree (
    ID_Soiree INT PRIMARY KEY,
    Nom_soiree VARCHAR(50)
    Date DATE NOT NULL,
    Nom_Lieu VARCHAR(100) NOT NULL,
    tarif DECIMAL(10, 2),
    thématique VARCHAR(100)
);

-- Table Spectacle
CREATE TABLE Spectacle (
    ID_Spectacle INT PRIMARY KEY,
    Titre VARCHAR(100) NOT NULL,
    Artiste VARCHAR(100),
    Duree TIME,
    Style VARCHAR(50),
    video VARCHAR(500),
    photo VARCHAR(50),
    description VARCHAR(2000)
);

-- Table de liaison SoireeToSpectacle
CREATE TABLE SoireeToSpectacle (
    ID_Soiree INT,
    ID_Spectacle INT,
    PRIMARY KEY (ID_Soiree, ID_Spectacle),
    FOREIGN KEY (ID_Soiree) REFERENCES Soiree(ID_Soiree),
    FOREIGN KEY (ID_Spectacle) REFERENCES Spectacle(ID_Spectacle)
);

-- Table Utilisateur
CREATE TABLE Utilisateur (
    ID_Utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100) NOT NULL,
    MotDePasse VARCHAR(255) NOT NULL,
    Role ENUM('visiteur', 'staff', 'admin', 'organisateur') NOT NULL
);

-- Insertion des utilisateurs
INSERT INTO Utilisateur (mail, MotDePasse, Role)
VALUES
    ('JohnDoe@gmail.com', 'hashed_password_1', 'visiteur'),
    ('Alice@exemple.com', 'hashed_password_2', 'admin'),
    ('Bob@exemple.com', 'hashed_password_3', 'staff');

-- Insertion des spectacles
INSERT INTO Spectacle (ID_Spectacle, Titre, Artiste, Duree, Style, video, photo, desription) VALUES
    (1, 'Rock Night', 'ACDC', '02:00:00', 'Classic Rock', 'https://www.youtube.com/watch?v=gEPmA3USJdI', 'acdc.png',null),
    (2, 'Blues Vibes', 'Blues Brothers', '01:30:00', 'Blues Rock', 'https://www.youtube.com/watch?v=RrhThz_1Z2I', 'blues.png';null);

-- Insertion des soirées
INSERT INTO Soiree (ID_Soiree,nom_soiree, Date, Nom_Lieu, tarif, thématique,  image_soiree) VALUES
      (1,'Soirée 1', '2024-10-01', 'Nancy Arena', 25.00, 'Rock','imagesoiree1.png'),
      (2, 'Soirée 2','2024-10-02', 'Le Zenith', 30.00, 'Blues','imagesoiree1.png'),
      (3, 'Soirée 3','2024-10-03', 'La Lune', 20.00, 'Metal','imagesoiree1.png');

-- Insertion des relations SoireeToSpectacle
INSERT INTO SoireeToSpectacle (ID_Soiree, ID_Spectacle) VALUES
    (1, 1), -- Rock Night dans la première soirée
    (1, 2), -- Blues Vibes dans la première soirée
    (2, 1), -- Rock Night dans la deuxième soirée
    (3, 2); -- Blues Vibes dans la troisième soirée