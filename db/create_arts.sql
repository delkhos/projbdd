CREATE EXTENSION pg_trgm;

CREATE TABLE Pays (
nom_pays                  varchar(250) UNIQUE PRIMARY KEY
);

CREATE TABLE Musee (
nom_musee                 varchar(250) UNIQUE primary key,
adresse                   varchar(250) NOT NULL,
prix_entree               real,
pays                      varchar(250) references Pays(nom_pays) NOT NULL
);

CREATE TABLE Users (
id                        SERIAL PRIMARY KEY,
username                  VARCHAR(50) NOT NULL UNIQUE,
password                  VARCHAR(255) NOT NULL,
admin                     BOOLEAN DEFAULT FALSE,
handeld_musee             varchar(250) references Musee(nom_musee),
created_at                date DEFAULT CURRENT_DATE
);

CREATE TABLE Don (
don_id                    SERIAL primary key,
date                      date DEFAULT CURRENT_DATE NOT NULL,
valeur                    real NOT NULL,
musee                     varchar(250) references Musee(nom_musee) NOT NULL,
mecene                    integer references Users(id)  NOT NULL
);

CREATE TYPE type_oeuvre AS ENUM ('peinture', 'sculpture', 'photographie');


CREATE TABLE Artiste (
nom_artiste               varchar(250) primary key  NOT NULL,
date_naissance            date  NOT NULL,
date_mort                 date,
nationalite               varchar(250) references Pays(nom_pays),
biographie                varchar(1000)
);

CREATE TABLE Courant_artistique (
nom_courant               varchar(250) UNIQUE primary key,
date_debut                integer NOT NULL,
date_fin                  integer,
description               varchar(1000)
);

CREATE TABLE Participation_courant(
nom_courant               varchar(250) references Courant_artistique(nom_courant) NOT NULL,
artiste                   varchar(250) references Artiste(nom_artiste) NOT NULL
);

CREATE TYPE type_expo AS ENUM('perm','tempop','tempoc','tempoa');

CREATE TABLE Expo_id (
expo_id                   SERIAL PRIMARY KEY,
expo_type                 type_expo NOT NULL
);

CREATE TABLE Exposition_permanente(
expo_id                   integer unique primary key references Expo_id(expo_id),
nom_expo                  varchar(250),
musee                     varchar(250) references Musee(nom_musee) NOT NULL,
);

CREATE TABLE Exposition_temporaire_pays (
expo_id                   integer unique primary key references Expo_id(expo_id),
nom_expo                  varchar(250) NOT NULL,  
musee                     varchar(250) references Musee(nom_musee) NOT NULL,
date_debut                date NOT NULL,
date_fin                  date NOT NULL,
pays                      varchar(250) references Pays(nom_pays) NOT NULL
);


CREATE TABLE Exposition_temporaire_artiste (
expo_id                   integer unique primary key references Expo_id(expo_id),
nom_expo                  varchar(250) NOT NULL,
musee                     varchar(250) references Musee(nom_musee) NOT NULL,
date_debut                date NOT NULL,
date_fin                  date NOT NULL,
artiste                   varchar(250) references Artiste(nom_artiste) NOT NULL
);

CREATE TABLE Exposition_temporaire_courant (
expo_id                   integer unique primary key references Expo_id(expo_id),
nom_expo                  varchar(250) NOT NULL,
musee                     varchar(250) references Musee(nom_musee) NOT NULL,
date_debut                date NOT NULL,
date_fin                  date NOT NULL,
courant       varchar(250) references Courant_artistique(nom_courant) NOT NULL
);

CREATE VIEW Exposition AS
  SELECT expo_id, nom_expo, musee FROM Exposition_permanente
    UNION
  SELECT expo_id, nom_expo, musee FROM Exposition_temporaire_artiste 
UNION
SELECT expo_id, nom_expo, musee FROM Exposition_temporaire_pays 
UNION
SELECT expo_id, nom_expo, musee FROM Exposition_temporaire_courant;

CREATE VIEW Exposition_sans_musee AS
  SELECT expo_id, nom_expo FROM Exposition_permanente
    UNION
  SELECT expo_id, nom_expo FROM Exposition_temporaire_artiste 
UNION
SELECT expo_id, nom_expo FROM Exposition_temporaire_pays 
UNION
SELECT expo_id, nom_expo FROM Exposition_temporaire_courant;

CREATE TABLE Participation_expo(
expo                      integer references Expo_id(expo_id),
musee                     varchar(250) references Musee(nom_musee)
);

CREATE TABLE Oeuvre (
oeuvre_id                 SERIAL primary key,
nom_oeuvre                varchar(250) NOT NULL,
date                      integer,
description               varchar(5000),
type                      type_oeuvre NOT NULL,
musee                     varchar(250) references Musee(nom_musee),
exposition                integer references Expo_id(expo_id),
artiste                   varchar(250) references Artiste(nom_artiste),
courant_artistique        varchar references Courant_artistique(nom_courant)
);
