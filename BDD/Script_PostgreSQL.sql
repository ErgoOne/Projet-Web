------------------------------------------------------------
--        Script Postgre BDD Projet_Web
-- Sur Pgadmin : Creer une BD par la suite allez sur Plugins puis psql console => Assurez vous que le prompt vous affiche bien le nom de
-- la BD que vous venez de creer. Tapez par la suite la cmd suivante : \i 'Chemin/vers/Sql_Proj_Script.sql'
-- Si pas d'erreurs uniquement des create table & alter table sur les réponses du serveur c'est ok.
------------------------------------------------------------

CREATE TYPE Type_Action_Pirate_Bateau AS ENUM ('PirateAttaquerBateau','PirateDeplacerBateau');
CREATE TYPE Type_Action_Joueur_Bateau AS ENUM ('ConstruireBateau','DétruireBateau','DeplacerBateau');
CREATE TYPE Type_Action_Joueur_Partie AS ENUM ('GagnerPartie','RejoindrePartie','CreerPartie');
CREATE TYPE Clan_Pirate AS ENUM ('Baronet Noir','Barbe Rouge','Calico Jack');
CREATE TYPE Region AS ENUM ('Amerique du nord','Amerique du sud','Afrique','Asie','Europe de l Est','Europe de l Ouest','Proche et moyen Orient');


------------------------------------------------------------
-- Table: Joueurs
------------------------------------------------------------
CREATE TABLE Joueurs(
	Email            VARCHAR (25) NOT NULL ,
	Pseudo           VARCHAR (25) NOT NULL UNIQUE,
	MotdePasse       VARCHAR (25) NOT NULL ,
	Region           REGION NOT NULL ,
	Date_Inscription DATE  NOT NULL ,
	Id_Avatar        VARCHAR (25) NOT NULL ,
	CONSTRAINT prk_constraint_Joueurs PRIMARY KEY (Email)
);


------------------------------------------------------------
-- Table: Partie
------------------------------------------------------------
CREATE TABLE Partie(
	Id_Partie  SERIAL NOT NULL ,
	Id_Ouragan INT  NOT NULL ,
	Nom_Type   VARCHAR (25) NOT NULL ,
	CONSTRAINT prk_constraint_Partie PRIMARY KEY (Id_Partie)
);


------------------------------------------------------------
-- Table: Bateaux
------------------------------------------------------------
CREATE TABLE Bateaux(
	Id_Bateau SERIAL NOT NULL ,
	Vie_Bat   INT  NOT NULL ,
	PosX_Bat  INT  NOT NULL ,
	PosY_Bat  INT  NOT NULL ,
	Id_Type   INT  NOT NULL ,
	CONSTRAINT prk_constraint_Bateaux PRIMARY KEY (Id_Bateau)
);


------------------------------------------------------------
-- Table: TypeBateaux
------------------------------------------------------------
CREATE TABLE TypeBateaux(
	Id_Type         SERIAL NOT NULL ,
	Nom_Type        VARCHAR (25) NOT NULL ,
	Chemin_Type     VARCHAR (120) NOT NULL ,
	Cout_Type       INT  NOT NULL ,
	Resistance_Type INT  NOT NULL ,
	Equipage   INT  NOT NULL ,
	Degats_Type     INT  NOT NULL ,
	Vie_max         INT  NOT NULL ,
	Nb_Cases_Dep    INT  NOT NULL ,
	CONSTRAINT prk_constraint_TypeBateaux PRIMARY KEY (Id_Type)
);


------------------------------------------------------------
-- Table: Ports
------------------------------------------------------------
CREATE TABLE Ports(
	Id_Port     VARCHAR (25) NOT NULL ,
	Vie_Port    INT  NOT NULL ,
	Degats_Port INT  NOT NULL ,
	PosX_Port   INT  NOT NULL ,
	PosY_Port   INT  NOT NULL ,
	CONSTRAINT prk_constraint_Ports PRIMARY KEY (Id_Port)
);


------------------------------------------------------------
-- Table: Avatar
------------------------------------------------------------
CREATE TABLE Avatar(
	Id_Avatar     VARCHAR (25) NOT NULL ,
	Chemin_Avatar VARCHAR (120) NOT NULL ,
	CONSTRAINT prk_constraint_Avatar PRIMARY KEY (Id_Avatar)
);


------------------------------------------------------------
-- Table: Ouragan
------------------------------------------------------------
CREATE TABLE Ouragan(
	Id_Ouragan     SERIAL NOT NULL ,
	Chemin_Ouragan VARCHAR (120) NOT NULL ,
	Degats_Ouragan INT  NOT NULL ,
	Rayon_Ouragan  INT  NOT NULL ,
	CONSTRAINT prk_constraint_Ouragan PRIMARY KEY (Id_Ouragan)
);


------------------------------------------------------------
-- Table: Trophées
------------------------------------------------------------
CREATE TABLE Trophees(
	Id_Trophee     INT  NOT NULL ,
	Nom_Trophee    VARCHAR (25) NOT NULL ,
	Chemin_Trophee VARCHAR (120) NOT NULL ,
	CONSTRAINT prk_constraint_Trophees PRIMARY KEY (Id_Trophee)
);


------------------------------------------------------------
-- Table: Pirates
------------------------------------------------------------
CREATE TABLE Pirates(
	Id_Pirate   SERIAL NOT NULL ,
	Clan_Pirate CLAN_PIRATE NOT NULL ,
	Id_Partie   INT  NOT NULL ,
	CONSTRAINT prk_constraint_Pirates PRIMARY KEY (Id_Pirate)
);


------------------------------------------------------------
-- Table: TypePartie
------------------------------------------------------------
CREATE TABLE TypePartie(
	Nom_Type   VARCHAR (25) NOT NULL ,
	Nom_Partie VARCHAR (25) NOT NULL ,
	MotDePasse VARCHAR (25) NOT NULL ,
	CONSTRAINT prk_constraint_TypePartie PRIMARY KEY (Nom_Type)
);


------------------------------------------------------------
-- Table: PossederPorts
------------------------------------------------------------
CREATE TABLE PossederPorts(
	Email     VARCHAR (25) NOT NULL ,
	Id_Port   VARCHAR (25) NOT NULL ,
	Id_Partie INT  NOT NULL ,
	CONSTRAINT prk_constraint_PossederPorts PRIMARY KEY (Email,Id_Port,Id_Partie)
);


------------------------------------------------------------
-- Table: PossederBateaux
------------------------------------------------------------
CREATE TABLE PossederBateaux(
	Email     VARCHAR (25) NOT NULL ,
	Id_Partie INT  NOT NULL ,
	Id_Bateau INT  NOT NULL ,
	CONSTRAINT prk_constraint_PossederBateaux PRIMARY KEY (Email,Id_Partie,Id_Bateau)
);


------------------------------------------------------------
-- Table: CauserDegats
------------------------------------------------------------
CREATE TABLE CauserDegats(
	DateCauserDegats DATE  NOT NULL ,
	Id_Bateau        INT  NOT NULL ,
	Id_Ouragan       INT  NOT NULL ,
	CONSTRAINT prk_constraint_CauserDegats PRIMARY KEY (Id_Bateau,Id_Ouragan)
);


------------------------------------------------------------
-- Table: AvoirTrophée
------------------------------------------------------------
CREATE TABLE AvoirTrophee(
	Email      VARCHAR (25) NOT NULL ,
	Id_Trophee INT  NOT NULL ,
	CONSTRAINT prk_constraint_AvoirTrophee PRIMARY KEY (Email,Id_Trophee)
);


------------------------------------------------------------
-- Table: PossederPortsPirates
------------------------------------------------------------
CREATE TABLE PossederPortsPirates(
	Id_Port   VARCHAR (25) NOT NULL ,
	Id_Partie INT  NOT NULL ,
	Id_Pirate INT  NOT NULL ,
	CONSTRAINT prk_constraint_PossederPortsPirates PRIMARY KEY (Id_Port,Id_Partie,Id_Pirate)
);


------------------------------------------------------------
-- Table: PossederBateauxPirates
------------------------------------------------------------
CREATE TABLE PossederBateauxPirates(
	Id_Partie INT  NOT NULL ,
	Id_Pirate INT  NOT NULL ,
	Id_Bateau INT  NOT NULL ,
	CONSTRAINT prk_constraint_PossederBateauxPirates PRIMARY KEY (Id_Partie,Id_Pirate,Id_Bateau)
);


------------------------------------------------------------
-- Table: AttaquerPort
------------------------------------------------------------
CREATE TABLE AttaquerPort(
	DateAttaquerPort DATE  NOT NULL ,
	Id_Port          VARCHAR (25) NOT NULL ,
	Id_Bateau        INT  NOT NULL ,
	CONSTRAINT prk_constraint_AttaquerPort PRIMARY KEY (Id_Port,Id_Bateau)
);


------------------------------------------------------------
-- Table: PerdrePort
------------------------------------------------------------
CREATE TABLE PerdrePort(
	Email   VARCHAR (25) NOT NULL ,
	Id_Port VARCHAR (25) NOT NULL ,
	CONSTRAINT prk_constraint_PerdrePort PRIMARY KEY (Email,Id_Port)
);


------------------------------------------------------------
-- Table: ActionPiratesBateaux ##################
------------------------------------------------------------
CREATE TABLE ActionPiratesBateaux(
	Type_Action_Pirate_Bateau TYPE_ACTION_PIRATE_BATEAU NOT NULL ,
	DateActionPiratesBateaux  DATE  NOT NULL ,
	Id_Bateau                 INT  NOT NULL ,
	Id_Pirate                 INT  NOT NULL ,
	Id_Bateau_Pirate         INT  NOT NULL ,  
	CONSTRAINT prk_constraint_ActionPiratesBateaux PRIMARY KEY (Id_Bateau,Id_Pirate,Id_Bateau_Pirate)
);


------------------------------------------------------------
-- Table: PerdrePiratePort
------------------------------------------------------------
CREATE TABLE PerdrePiratePort(
	Id_Port   VARCHAR (25) NOT NULL ,
	Id_Pirate INT  NOT NULL ,
	CONSTRAINT prk_constraint_PerdrePiratePort PRIMARY KEY (Id_Port,Id_Pirate)
);


------------------------------------------------------------
-- Table: ActionJoueurPartie
------------------------------------------------------------
CREATE TABLE ActionJoueurPartie(
	TypeActionJoueurPartie TYPE_ACTION_JOUEUR_PARTIE NOT NULL ,
	DateActionJoueurPartie DATE  NOT NULL ,
	Email                  VARCHAR (25) NOT NULL ,
	Id_Partie              INT  NOT NULL ,
	CONSTRAINT prk_constraint_ActionJoueurPartie PRIMARY KEY (Email,Id_Partie)
);


------------------------------------------------------------
-- Table: AttaquerBateau
------------------------------------------------------------
CREATE TABLE AttaquerBateau(
	Id_Bateau_Attaquant INT  NOT NULL ,
	DateAttaqueBateau   DATE  NOT NULL ,
	Email               VARCHAR (25) NOT NULL ,
	Id_Bateau           INT  NOT NULL ,
	CONSTRAINT prk_constraint_AttaquerBateau PRIMARY KEY (Email,Id_Bateau)
);


------------------------------------------------------------
-- Table: ActionJoueurBateau
------------------------------------------------------------
CREATE TABLE ActionJoueurBateau(
	Type_Action_Joueur_Bateau TYPE_ACTION_JOUEUR_BATEAU NOT NULL ,
	DateActionJoueurBateau    DATE  NOT NULL ,
	Email                     VARCHAR (25) NOT NULL ,
	Id_Bateau                 INT  NOT NULL ,
	CONSTRAINT prk_constraint_ActionJoueurBateau PRIMARY KEY (Email,Id_Bateau)
);


------------------------------------------------------------
-- Table: EtreAmis
------------------------------------------------------------
CREATE TABLE EtreAmis(
	Email_J1         VARCHAR (25) NOT NULL ,
	Email_J2		 VARCHAR (25) NOT NULL ,
	CONSTRAINT prk_constraint_EtreAmis PRIMARY KEY (Email_J1,Email_J2)
);


------------------------------------------------------------
-- Table: DeplacerBateau
------------------------------------------------------------
CREATE TABLE DeplacerBateau(
	N_Pos_X       INT  NOT NULL ,
	N_Pos_Y       INT  NOT NULL ,
	DateDepBateau DATE  NOT NULL ,
	Email         VARCHAR (25) NOT NULL ,
	Id_Bateau     INT  NOT NULL ,
	CONSTRAINT prk_constraint_DeplacerBateau PRIMARY KEY (Email,Id_Bateau)
);



ALTER TABLE Joueurs ADD CONSTRAINT FK_Joueurs_Id_Avatar FOREIGN KEY (Id_Avatar) REFERENCES Avatar(Id_Avatar);
ALTER TABLE Partie ADD CONSTRAINT FK_Partie_Id_Ouragan FOREIGN KEY (Id_Ouragan) REFERENCES Ouragan(Id_Ouragan);
ALTER TABLE Partie ADD CONSTRAINT FK_Partie_Nom_Type FOREIGN KEY (Nom_Type) REFERENCES TypePartie(Nom_Type);
ALTER TABLE Bateaux ADD CONSTRAINT FK_Bateaux_Id_Type FOREIGN KEY (Id_Type) REFERENCES TypeBateaux(Id_Type);
ALTER TABLE Pirates ADD CONSTRAINT FK_Pirates_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE PossederPorts ADD CONSTRAINT FK_PossederPorts_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE PossederPorts ADD CONSTRAINT FK_PossederPorts_Id_Port FOREIGN KEY (Id_Port) REFERENCES Ports(Id_Port);
ALTER TABLE PossederPorts ADD CONSTRAINT FK_PossederPorts_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE PossederBateaux ADD CONSTRAINT FK_PossederBateaux_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE PossederBateaux ADD CONSTRAINT FK_PossederBateaux_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE PossederBateaux ADD CONSTRAINT FK_PossederBateaux_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE CauserDegats ADD CONSTRAINT FK_CauserDegats_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE CauserDegats ADD CONSTRAINT FK_CauserDegats_Id_Ouragan FOREIGN KEY (Id_Ouragan) REFERENCES Ouragan(Id_Ouragan);
ALTER TABLE AvoirTrophee ADD CONSTRAINT FK_AvoirTrophee_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE AvoirTrophee ADD CONSTRAINT FK_AvoirTrophee_Id_Trophee FOREIGN KEY (Id_Trophee) REFERENCES Trophees(Id_Trophee);
ALTER TABLE PossederPortsPirates ADD CONSTRAINT FK_PossederPortsPirates_Id_Port FOREIGN KEY (Id_Port) REFERENCES Ports(Id_Port);
ALTER TABLE PossederPortsPirates ADD CONSTRAINT FK_PossederPortsPirates_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE PossederPortsPirates ADD CONSTRAINT FK_PossederPortsPirates_Id_Pirate FOREIGN KEY (Id_Pirate) REFERENCES Pirates(Id_Pirate);
ALTER TABLE PossederBateauxPirates ADD CONSTRAINT FK_PossederBateauxPirates_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE PossederBateauxPirates ADD CONSTRAINT FK_PossederBateauxPirates_Id_Pirate FOREIGN KEY (Id_Pirate) REFERENCES Pirates(Id_Pirate);
ALTER TABLE PossederBateauxPirates ADD CONSTRAINT FK_PossederBateauxPirates_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE AttaquerPort ADD CONSTRAINT FK_AttaquerPort_Id_Port FOREIGN KEY (Id_Port) REFERENCES Ports(Id_Port);
ALTER TABLE AttaquerPort ADD CONSTRAINT FK_AttaquerPort_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE PerdrePort ADD CONSTRAINT FK_PerdrePort_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE PerdrePort ADD CONSTRAINT FK_PerdrePort_Id_Port FOREIGN KEY (Id_Port) REFERENCES Ports(Id_Port);
ALTER TABLE ActionPiratesBateaux ADD CONSTRAINT FK_ActionPiratesBateaux_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE ActionPiratesBateaux ADD CONSTRAINT FK_ActionPiratesBateaux_Id_Pirate FOREIGN KEY (Id_Pirate) REFERENCES Pirates(Id_Pirate);
ALTER TABLE ActionPiratesBateaux ADD CONSTRAINT FK_ActionPiratesBateaux_Id_Bateau_Pirate FOREIGN KEY (Id_Bateau_Pirate) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE PerdrePiratePort ADD CONSTRAINT FK_PerdrePiratePort_Id_Port FOREIGN KEY (Id_Port) REFERENCES Ports(Id_Port);
ALTER TABLE PerdrePiratePort ADD CONSTRAINT FK_PerdrePiratePort_Id_Pirate FOREIGN KEY (Id_Pirate) REFERENCES Pirates(Id_Pirate);
ALTER TABLE ActionJoueurPartie ADD CONSTRAINT FK_ActionJoueurPartie_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE ActionJoueurPartie ADD CONSTRAINT FK_ActionJoueurPartie_Id_Partie FOREIGN KEY (Id_Partie) REFERENCES Partie(Id_Partie);
ALTER TABLE AttaquerBateau ADD CONSTRAINT FK_AttaquerBateau_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE AttaquerBateau ADD CONSTRAINT FK_AttaquerBateau_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE ActionJoueurBateau ADD CONSTRAINT FK_ActionJoueurBateau_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE ActionJoueurBateau ADD CONSTRAINT FK_ActionJoueurBateau_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
ALTER TABLE EtreAmis ADD CONSTRAINT FK_EtreAmis_Email FOREIGN KEY (Email_J1) REFERENCES Joueurs(Email);
ALTER TABLE EtreAmis ADD CONSTRAINT FK_EtreAmis_Email_Joueurs FOREIGN KEY (Email_J2) REFERENCES Joueurs(Email);
ALTER TABLE DeplacerBateau ADD CONSTRAINT FK_DeplacerBateau_Email FOREIGN KEY (Email) REFERENCES Joueurs(Email);
ALTER TABLE DeplacerBateau ADD CONSTRAINT FK_DeplacerBateau_Id_Bateau FOREIGN KEY (Id_Bateau) REFERENCES Bateaux(Id_Bateau);
