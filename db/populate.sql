
INSERT INTO Pays (nom_pays)
  VALUES ('France');
INSERT INTO Pays (nom_pays)
  VALUES ('Espagne');
INSERT INTO Pays (nom_pays)
  VALUES ('Russie');
INSERT INTO Pays (nom_pays)
  VALUES ('Etats-Unis');
INSERT INTO Pays (nom_pays)
  VALUES ('Allemagne');
INSERT INTO Pays (nom_pays)
  VALUES ('Japon');
INSERT INTO Pays (nom_pays)
  VALUES ('Suisse');
INSERT INTO Pays(nom_pays)
  VALUES ('Pays-Bas');

INSERT INTO Artiste (nom_artiste, date_naissance, date_mort, nationalite, biographie)
  VALUES ('Wassily Kandinsky','1866-10-22','1944-12-13','Russie','L''un des peintres russes les plus importants du XXe siecle, considéré comme l''auteur de la première oeuvre d''art abstrait.');

INSERT INTO Artiste (nom_artiste, date_naissance, date_mort, nationalite, biographie)
  VALUES('Auguste Rodin','1840-10-12','1917-10-17','France','Rodin est un sculpteur dont la capacité d''organisation exceptionelle lui a permis de laisser une oeuvre hors norme. Il est l''un despères de la sculpture moderne, par ses travaux sur les formes et les lumières.');

INSERT INTO Artiste (nom_artiste,date_naissance,date_mort,nationalite,biographie)
  VALUES ('Andy Warhol', '1928-08-06', '1987-02-22', 'Etats-Unis','Peintre, producteur musical, auteur et réalisateur, c''est un artiste très controversé à son époque, mais désormais considéré comme le plus grand artiste du XXe siècle, et une figure phare du pop art');

INSERT INTO Artiste (nom_artiste, date_naissance ,date_mort ,nationalite ,biographie)
  VALUES ('Paul Cézanne','1839-01-19','1906-10-22','France','Un membre un temps du mouvement impressionniste et considéré comme le précurseur du post-impressionnisme et du cubisme.');

INSERT INTO Artiste (nom_artiste, date_naissance ,date_mort ,nationalite ,biographie)
  VALUES ('Vincent Van Gogh','1853-03-20','1890-07-29','Pays-Bas','Passionné et autodidacte, ses peintures reflètent le cours de ses recherches et sa culture picturale grandissante. Son instabilité menale peut se ressentir à l''évolution de ses autoportraits.');


  
INSERT INTO Courant_artistique (nom_courant, date_debut,date_fin,description)
  VALUES ('Impressionnisme',1874,1886,E'Ce mouvement pictural est principalement caractérisé par des tableaux de petit format, des traits de pinceau visibles, la composition ouverte, l''utilisation d''angles de vue inhabituels, une tendance à noter les impressions fugitives, la mobilité des phénomènes climatiques et lumineux, plutôt que l''aspect stable et conceptuel des choses, et à les reporter directement sur la toile.');

INSERT INTO Courant_artistique (nom_courant, date_debut, date_fin, description)
  VALUES ('Art abstrait', 1910, 1950, 'L''art abstrait est une forme d''art qui cherche à contracter le réel, afin d''en souligner les dechirures au lieu d''essayer de représenter les apparances visibles du monde extérieur'); 


INSERT INTO Courant_artistique (nom_courant, date_debut,date_fin,description)
  VALUES ('Art moderne',1870,1950,'L''art moderne est une forme d''art qui se veut en rupture avec le classicisme, utilisant des techniques et des procédés issus pour la plupart de la révolution industrielle.');



INSERT INTO Participation_courant (nom_courant,artiste)
  VALUES ('Impressionnisme','Paul Cézanne');
INSERT INTO Participation_courant (nom_courant, artiste)
  VALUES ('Art abstrait', 'Wassily Kandinsky');
INSERT INTO Participation_courant(nom_courant, artiste)
  VALUES('Art moderne', 'Andy Warhol');
INSERT INTO Participation_courant(nom_courant, artiste)
  VALUES('Art moderne', 'Auguste Rodin');
INSERT INTO Participation_courant (nom_courant, artiste)
  VALUES ('Impressionnisme','Vincent Van Gogh');



INSERT INTO Musee (nom_musee , adresse , prix_entree ,pays)
  VALUES ('George Pompidou','15 rue de Paris 75001 Paris', 2.5 , 'France');
INSERT INTO Musee (nom_musee, adresse, prix_entree, pays) 
  VALUES ('Musee Guggenheim','Abandoibarra Et. 2 48001 Bilbao',63,'Espagne');
INSERT INTO Musee (nom_musee, adresse, prix_entree, pays)
  VALUES ('Chôkoku no mori Hakone','1121 Ninotaira Hakone',8,'Japon');
INSERT INTO Musee (nom_musee, adresse, prix_entree, pays)
  VALUES ('Hamburger Bahnhof Museum','Invalidenstrasse 50-51  10557 Berlin',16,'Allemagne');
INSERT INTO Musee (nom_musee, adresse, prix_entree, pays)
  VALUES('Fondation Pierre Gianadda','59 Rue du Forum 1920 Martigny',16,'Suisse');



INSERT INTO Expo_id(expo_type) VALUES ('tempop');
INSERT INTO Exposition_temporaire_pays (expo_id, nom_expo, musee, date_debut, date_fin, pays)
  VALUES ( currval('Expo_id_expo_id_seq'), 'Belle France', 'George Pompidou','2021-04-12','2021-10-18','France');

INSERT INTO Expo_id(expo_type) VALUES ('tempoa');
INSERT INTO Exposition_temporaire_artiste (expo_id, nom_expo, musee, date_debut, date_fin, artiste)
  VALUES ( currval('Expo_id_expo_id_seq'), 'Cézanne et sa grandeur', 'George Pompidou','2021-02-26','2021-10-22','Paul Cézanne');

INSERT INTO Expo_id (expo_type) VALUES ('tempoa');
INSERT INTO Exposition_temporaire_artiste (expo_id, nom_expo, musee, date_debut, date_fin, artiste)
  VALUES ( currval('Expo_id_expo_id_seq'), 'Kandinsky', 'Musee Guggenheim','2020-10-20','2021-05-23','Wassily Kandinsky');

INSERT INTO Expo_id(expo_type) VALUES ('tempoc');
INSERT INTO Exposition_temporaire_courant (expo_id, nom_expo, musee, date_debut, date_fin, courant)
  VALUES ( currval('Expo_id_expo_id_seq'), 'Impressionnisme impressionnant', 'George Pompidou','2021-06-10','2022-06-09','Impressionnisme');

INSERT INTO Expo_id(expo_type) VALUES ('perm');
INSERT INTO Exposition_permanente (expo_id, nom_expo, musee)
  VALUES ( currval('Expo_id_expo_id_seq'), 'Les nouveaux arts', 'George Pompidou');

INSERT INTO Expo_id(expo_type) VALUES ('perm');
INSERT INTO Exposition_permanente (expo_id,nom_expo,musee)
  VALUES (currval('Expo_id_expo_id_seq'),'Collection de sculptures de Hakone','Chôkoku no mori Hakone');

INSERT INTO Expo_id(expo_type)VALUES ('perm');
INSERT INTO Exposition_permanente(expo_id,nom_expo,musee)
  VALUES (currval('Expo_id_expo_id_seq'),'Museum für Gegenwart','Hamburger Bahnhof Museum');

INSERT INTO Expo_id(expo_type) VALUES('tempoa');
INSERT INTO Exposition_temporaire_artiste(expo_id, nom_expo, musee, date_debut, date_fin, artiste)
  VALUES (currval('Expo_id_expo_id_seq'), 'Vincent Van Gogh, sa vie et son oeuvre', 'Fondation Pierre Gianadda','23-05-2018','23-11-2018', 'Vincent Van Gogh');



INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Les Grandes Baigneuses',1906,'tableau impressionniste','peinture','George Pompidou',1,'Paul Cézanne','Impressionnisme');

INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Le Garçon au gilet rouge',1890,'tableau impressionniste','peinture','George Pompidou',2,'Paul Cézanne','Impressionnisme');

INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Le panier de pommes',1893,'tableau impressionniste','peinture','George Pompidou',3,'Paul Cézanne','Impressionnisme');

INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Château Noir',1904,'tableau impressionniste','peinture','George Pompidou',4,'Paul Cézanne','Impressionnisme');

INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Monument à Balzac',1893,'statue de bronze','sculpture','Chôkoku no mori Hakone',6,'Auguste Rodin' ,'Art moderne');

INSERT INTO Oeuvre (nom_oeuvre,date,description,type, musee,exposition,artiste,courant_artistique)
  VALUES ('Mao',1972,'portrait','peinture','Hamburger Bahnhof Museum',7,'Andy Warhol','Art moderne');

INSERT INTO Oeuvre (nom_oeuvre, date, description, type, musee, exposition, artiste, courant_artistique)
  VALUES('Champ de blé aux corbeaux',1890, 'tableau impressionniste','peinture', 'Fondation Pierre Gianadda',8,'Vincent Van Gogh','Impressionnisme');

