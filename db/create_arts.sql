CREATE TABLE pays (
indicateur_national       varchar(2) NOT NULL primary key,
nom                       varchar(80) NOT NULL 
);

CREATE TABLE musee (
nom                       varchar(80) NOT NULL primary key,
adresse                   varchar(80) NOT NULL ,
prix_entree               real,
pays                      varchar(2) references pays(indicateur_national) NOT NULL 
);

CREATE TYPE type_don AS ENUM ('mensuel', 'ponctuel');

CREATE TABLE mecene (
mecene_id                 SERIAL NOT NULL primary key,
patronyme                 varchar(80)
);

CREATE TABLE don (
don_id                    SERIAL NOT NULL primary key,
date                      date NOT NULL ,
valeur                    real NOT NULL ,
type                      type_don NOT NULL ,
musee                     varchar(80) references musee(nom) NOT NULL, 
mecene                    integer references mecene(mecene_id) NOT NULL 
);

CREATE TYPE type_oeuvre AS ENUM ('peinture', 'sculpture', 'photographie');

CREATE TABLE exposition (
expo_id                   SERIAL NOT NULL primary key,
nom                       varchar(80) NOT NULL ,
description               varchar(1000)
);

CREATE TABLE presente_musee_expo (
exposition                integer references exposition(expo_id) NOT NULL,
musee                     varchar(80) references musee(nom) NOT NULL
);

CREATE TABLE artiste (
artiste_id                SERIAL NOT NULL primary key,
nom                       varchar(80) NOT NULL ,
date_naissance            date NOT NULL ,
date_mort                 date,
nationalite               varchar(2) references pays(indicateur_national),
biographie                varchar(1000)
);

CREATE TABLE courant_artistique (
nom                       varchar(80) NOT NULL primary key,
date_debut                date NOT NULL ,
date_fin                  date,
description               varchar(1000),
pays_apparition           varchar(2) references pays(indicateur_national)
);

CREATE TABLE participation_courant(
nom_courant               varchar(80) references courant_artistique(nom) NOT NULL ,
artiste                   integer references artiste(artiste_id) NOT NULL 
);

CREATE TABLE exposition_permanente (
) INHERITS (exposition);

CREATE TABLE exposition_temporaire (
date_debut                date NOT NULL ,
date_fin                  date NOT NULL ,
musee_organisateur        varchar(80) references musee(nom) NOT NULL, 
prix_additionnel          real
) INHERITS (exposition);

CREATE TABLE exposition_temporaire_pays (
pays                      varchar(2) references pays(indicateur_national) NOT NULL 
) INHERITS (exposition_temporaire);

CREATE TABLE exposition_temporaire_artiste (
artiste                   integer references artiste(artiste_id) NOT NULL 
) INHERITS (exposition_temporaire);

CREATE TABLE exposition_temporaire_courant (
courant_artistique        varchar references courant_artistique(nom) NOT NULL 
) INHERITS (exposition_temporaire);

CREATE TABLE oeuvre (
nom                       varchar(80) NOT NULL primary key,
date_creation             date,
description               varchar(1000),
type                      type_oeuvre NOT NULL ,
musee_propriétaire        varchar(80) references musee(nom) NOT NULL ,
artiste                   integer references artiste(artiste_id),
courant_artistique        varchar references courant_artistique(nom)
);

CREATE TABLE presente_expo_oeuvre (
oeuvre                    varchar(80) references oeuvre(nom) NOT NULL ,
exposition                integer references exposition(expo_id) NOT NULL 
);




-- ajouter la présentation d'exposition
