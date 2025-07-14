
CREATE DATABASE IF NOT EXISTS emprunt_objets;
USE emprunt_objets;


CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    genre ENUM('M', 'F', 'Autre') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    ville VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255) DEFAULT NULL
);

CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);


CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);


CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);


CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour DATE DEFAULT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

CREATE TABLE Rendre (
    id_remis INT AUTO_INCREMENT PRIMARY KEY,
    id_emprunt INT ,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATE NOT NULL,
    Etat INT NOT NULL,
    date_retour DATE DEFAULT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);




INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice Dupont', '1990-05-12', 'F', 'alice@example.com', 'Paris', 'mdpAlice', 'alice.jpg'),
('Bob Martin', '1985-08-23', 'M', 'bob@example.com', 'Lyon', 'mdpBob', 'bob.jpg'),
('Claire Bernard', '1992-11-30', 'F', 'claire@example.com', 'Marseille', 'mdpClaire', 'claire.jpg'),
('David Petit', '1988-02-14', 'M', 'david@example.com', 'Toulouse', 'mdpDavid', 'david.jpg');


INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');


INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES

('Vernis à ongles', 1, 1),
('Pince à épiler', 1, 1),
('Perceuse', 2, 1),
('Marteau', 2, 1),
('Clé à molette', 3, 1),
('Coffret de clés', 3, 1),
('Mixeur', 4, 1),
('Poêle', 4, 1),
('Fer à lisser', 1, 1),
('Ciseaux de cuisine', 4, 1),

-- Bob Martin (id_membre=2)
('Crème hydratante', 1, 2),
('Râpe à fromage', 4, 2),
('Tournevis', 2, 2),
('Scie', 2, 2),
('Clé anglaise', 3, 2),
('Pompe à vélo', 3, 2),
('Blender', 4, 2),
('Casserole', 4, 2),
('Sèche-cheveux', 1, 2),
('Spatule', 4, 2),

-- Claire Bernard (id_membre=3)
('Fond de teint', 1, 3),
('Pinceau à maquillage', 1, 3),
('Perceuse sans fil', 2, 3),
('Niveau à bulle', 2, 3),
('Clé plate', 3, 3),
('Compresseur', 3, 3),
('Robot culinaire', 4, 3),
('Moule à gâteau', 4, 3),
('Lisseur', 1, 3),
('Couteau de chef', 4, 3),

-- David Petit (id_membre=4)
('Rouge à lèvres', 1, 4),
('Pince à cheveux', 1, 4),
('Tournevis électrique', 2, 4),
('Perceuse à percussion', 2, 4),
('Clé dynamométrique', 3, 4),
('Pompe à eau', 3, 4),
('Grille-pain', 4, 4),
('Faitout', 4, 4),
('Brosse à cheveux', 1, 4),
('Cuillère en bois', 4, 4);

-- Images objets (juste des noms d'images fictifs)
INSERT INTO images_objet (id_objet, nom_image) VALUES
(1, 'vernis.jpg'),
(2, 'pince_epiler.jpg'),
(3, 'perceuse.jpg'),
(4, 'marteau.jpg'),
(5, 'cle_molette.jpg'),
(6, 'coffret_cles.jpg'),
(7, 'mixeur.jpg'),
(8, 'poele.jpg'),
(9, 'fer_lisser.jpg'),
(10, 'ciseaux_cuisine.jpg'),
(11, 'creme_hydratante.jpg'),
(12, 'rape_fromage.jpg'),
(13, 'tournevis.jpg'),
(14, 'scie.jpg'),
(15, 'cle_anglaise.jpg'),
(16, 'pompe_velo.jpg'),
(17, 'blender.jpg'),
(18, 'casserole.jpg'),
(19, 'seche_cheveux.jpg'),
(20, 'spatule.jpg'),
(21, 'fond_teint.jpg'),
(22, 'pinceau_maquillage.jpg'),
(23, 'perceuse_sans_fil.jpg'),
(24, 'niveau_bulle.jpg'),
(25, 'cle_plate.jpg'),
(26, 'compresseur.jpg'),
(27, 'robot_culinaire.jpg'),
(28, 'moule_gateau.jpg'),
(29, 'lisseur.jpg'),
(30, 'couteau_chef.jpg'),
(31, 'rouge_levres.jpg'),
(32, 'pince_cheveux.jpg'),
(33, 'tournevis_electrique.jpg'),
(34, 'perceuse_percussion.jpg'),
(35, 'cle_dynamometrique.jpg'),
(36, 'pompe_eau.jpg'),
(37, 'grille_pain.jpg'),
(38, 'faitout.jpg'),
(39, 'brosse_cheveux.jpg'),
(40, 'cuillere_bois.jpg');

-- Emprunts (10 emprunts)
INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(3, 2, '2024-04-01', '2024-04-10'),
(7, 1, '2024-04-05', NULL),
(15, 3, '2024-04-07', '2024-04-15'),
(22, 4, '2024-04-10', NULL),
(30, 1, '2024-04-12', '2024-04-20'),
(35, 2, '2024-04-15', NULL),
(40, 3, '2024-04-18', '2024-04-25'),
(10, 4, '2024-04-20', NULL),
(25, 1, '2024-04-22', '2024-04-30'),
(18, 2, '2024-04-25', NULL);
