--Req
INSERT INTO utilisateur VALUES('kilbiller13@gmail.com','test', 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
INSERT INTO utilisateur VALUES('test@test.com','test', 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO descripteurVedette VALUES('Internet', CURRENT_TIMESTAMP, 0, 'Internet est cool',
    (select ref(u) from utilisateur u where email = 'kilbiller13@gmail.com'),
    NULL,NULL,NULL);

INSERT INTO descripteurVedette VALUES('Cloud computing',CURRENT_TIMESTAMP,0,'Informatique dans les nuages',
    (select ref(u) from utilisateur u where email = 'kilbiller13@gmail.com'),
    (select ref(d) from descripteurVedette d where libelle = 'Internet'),
    NULL,NULL);

UPDATE descripteurVedette
SET specialisations = specialList((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = 'Cloud computing'))
WHERE libelle = 'Internet';

INSERT INTO TABLE(SELECT specialisations FROM descripteurVedette WHERE libelle = 'Internet')
VALUES ((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = 'Cloud computing'));

select d.libelle, t1.COLUMN_VALUE.libelle, d.DEFINITION FROM DESCRIPTEURVEDETTE d, TABLE(d.specialisations) t1;
