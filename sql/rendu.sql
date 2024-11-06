1. Dictionnaire des Données

Spectacle:
ID_Spectacle : Identifiant unique pour chaque spectacle.
Titre : Le titre du spectacle.
Artiste : Lartiste ou le groupe qui réalise le spectacle.
Duree : Durée du spectacle (format HH:MM:SS).
Style : Le genre musical du spectacle (ex. Classic Rock, Metal, Blues Rock, etc.).
video : video du groupe/artiste
photo : photo du groupe/artiste

Soiree
ID_Soiree : Identifiant unique pour chaque soirée.
Date : La date spécifique de la soirée.
Nom_Lieu : Le nom du lieu où la soirée se déroule.

SoireeToSpectacle
ID_Soiree : Lien vers une soirée spécifique.
ID_Spectacle : Lien vers un spectacle spécifique.

Utilisateur
ID_Utilisateur : Identifiant unique pour chaque utilisateur.
mail : mail de l utilisateur.
MotDePasse : Le mot de passe haché de l utilisateur.
Role : Le rôle de l utilisateur (visiteur, staff, admin).

2. Dépendances Fonctionnelles


ID_Spectacle → Titre, Artiste, Duree, Style, photo, video

ID_Soiree → Date, Nom_Lieu,

ID_Soiree, ID_Spectacle → Aucun autre attribut (clés de liaison)

ID_Utilisateur → mail, MotDePasse, Role

3. Clés Minimales

Les clés minimales pour chaque table sont :

Spectacle : ID_Spectacle est la clé primaire.
Soiree : ID_Soiree est la clé primaire.
SoireeToSpectacle : (ID_Soiree, ID_Spectacle) est la clé primaire.
Utilisateur : ID_Utilisateur est la clé primaire.

4. Vérification de la 3FN

Spectacle : La table respecte la 3FN, car les attributs dépendent uniquement de l identifiant primaire (ID_Spectacle).
Soiree : La table respecte la 3FN, car les attributs dépendent uniquement de l identifiant primaire (ID_Soiree).
SoireeToSpectacle : Cette table est en 3FN car les clés de liaison ne contiennent pas de données redondantes.
Utilisateur : La table respecte également la 3FN, car les attributs dépendent uniquement de l identifiant primaire (ID_Utilisateur).

5. Décomposition en Relations Respectant la 3FN
La structure actuelle des tables respecte déjà la 3FN. Il n est pas nécessaire de décomposer davantage.

6.

CREATE TABLE Soiree (
    ID_Soiree INT PRIMARY KEY,
    Date DATE NOT NULL,
    ID_Lieu INT,
    FOREIGN KEY (ID_Lieu) REFERENCES Lieu(ID_Lieu)
);

CREATE TABLE Spectacle (
    ID_Spectacle INT PRIMARY KEY,
    Titre VARCHAR(100) NOT NULL,
    Artiste VARCHAR(100),
    Duree TIME,
    Style VARCHAR(50),
    video VARCHAR(500),
    photo VARCHAR(50)
);

CREATE TABLE SoireeToSpectacle (
    ID_Soiree INT,
    ID_Spectacle INT,
    PRIMARY KEY (ID_Soiree, ID_Spectacle),
    FOREIGN KEY (ID_Soiree) REFERENCES Soiree(ID_Soiree),
    FOREIGN KEY (ID_Spectacle) REFERENCES Spectacle(ID_Spectacle)
);

INSERT INTO Lieu (ID_Lieu, Nom) VALUES (1, 'Nancy Arena');

INSERT INTO Soiree (ID_Soiree, Date, ID_Lieu) VALUES (1, '2024-10-01', 1);

INSERT INTO Spectacle (ID_Spectacle, Titre, Artiste, Duree, Style, video, photo) VALUES
    (1, 'Rock Night', 'ACDC', '02:00:00', 'Classic Rock','https://www.youtube.com/watch?v=gEPmA3USJdI','acdc.png');

INSERT INTO SoireeToSpectacle (ID_Soiree, ID_Spectacle) VALUES (1, 1);

CREATE TABLE Utilisateur (
    ID_Utilisateur INT PRIMARY KEY AUTO_INCREMENT,
    mail VARCHAR(100) NOT NULL,
    MotDePasse VARCHAR(255) NOT NULL,
    Role ENUM('visiteur', 'staff', 'admin', 'organisateur') NOT NULL
);
INSERT INTO Utilisateur (Nom, MotDePasse, Role)
VALUES ('JohnDoe@gmail.com', 'hash_du_mot_de_passe', 'visiteur');

