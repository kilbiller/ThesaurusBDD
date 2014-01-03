--Creation
CREATE OR REPLACE TYPE utilisateur_t AS Object (email varchar(50),
                                                mdp varchar(20),
                                                prenom varchar(20),
                                                nom varchar(20),
                                                nombrePublications int(4),
                                                dateInscription date,
                                                derniereVisite date);
/
CREATE OR REPLACE TYPE descripteur_t AS Object (libelle varchar(30),
                                                dateCreation date,
                                                nombreConsultations int(4),
                                                definition varchar(250),
                                                utilisateur REF utilisateur_t) NOT FINAL;
/
CREATE OR REPLACE TYPE descripteurVedette_t UNDER descripteur_t (descripteurGen REF descripteurVedette_t,
                                                                 synonymes synoList,
                                                                 specialisations specialList);
/
CREATE OR REPLACE TYPE specialList AS TABLE OF REF descripteurVedette_t;
/
CREATE OR REPLACE TYPE synoList AS TABLE OF VARCHAR2(50);
/
CREATE TABLE utilisateur OF utilisateur_t;
/
CREATE TABLE descripteurVedette OF descripteurVedette_t
NESTED TABLE synonymes STORE AS syno_tab
NESTED TABLE specialisations STORE AS special_tab;
