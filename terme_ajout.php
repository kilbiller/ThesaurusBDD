<?php
    require_once 'loader.php';

    $error = null;
    $success = null;
    if(isset($_POST['terme_nom']) && isset($_POST['terme_definition']))
    {
        $stmt = $db->prepare('SELECT COUNT(d.libelle) AS "nb" FROM descripteurVedette d WHERE UPPER(d.libelle) = UPPER(:nom)');
        $stmt->execute(array(':nom' => $_POST['terme_nom']));
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results['nb'] >= 1)
            $error = 'Ce terme est déjà présent dans le thesaurus.';
        else
        {
            //Ajoute libelle et définition
            $stmt = $db->prepare('  INSERT INTO descripteurVedette
                                    VALUES(:nom, CURRENT_TIMESTAMP, 0, :definition,
                                    (select ref(u) from utilisateur u where email = :email),
                                    NULL,NULL,NULL)');
            $stmt->execute(array(':nom' => $_POST['terme_nom'],
                                 ':definition' => $_POST['terme_definition'],
                                 ':email' => $_SESSION['email']));

            //Ajoute le terme qui généralise (si présent)
            if(isset($_POST['terme_generalisation']) && $_POST['terme_generalisation'] != "null")
            {
                $stmt = $db->prepare('  UPDATE descripteurVedette
                                        SET descripteurGen = (  SELECT ref(d)
                                                                FROM descripteurVedette d
                                                                WHERE d.libelle = :generalisation)
                                        WHERE libelle = :nom');
                $stmt->execute(array(':nom' => $_POST['terme_nom'],
                                     ':generalisation' => $_POST['terme_generalisation']));

                //Ajoute en tant que spécialisation du général
                $stmt = $db->prepare('  SELECT COUNT(spe.COLUMN_VALUE.libelle) AS "nb"
                                        FROM descripteurVedette d, TABLE(d.specialisations) spe
                                        WHERE d.libelle = :generalisation');
                $stmt->execute(array(':generalisation' => $_POST['terme_generalisation']));
                $results = $stmt->fetch(PDO::FETCH_ASSOC);

                //Si le nested table n'est pas encore initialisé la requête change
                if($results['nb'] == 0)
                {
                    //Utilise la methode update
                    $stmt = $db->prepare('  UPDATE descripteurVedette
                                            SET specialisations = specialList(( SELECT ref(d)
                                                                                FROM descripteurVedette d
                                                                                WHERE d.libelle = :nom))
                                            WHERE libelle = :generalisation');
                }
                else
                {
                    //Utilise la methode insert into
                    $stmt = $db->prepare('  INSERT INTO TABLE(  SELECT specialisations
                                                                FROM descripteurVedette
                                                                WHERE libelle = :generalisation)
                                            VALUES ((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = :nom))');
                }
                $stmt->execute(array(':nom' => $_POST['terme_nom'], ':generalisation' => $_POST['terme_generalisation']));
            }

            //Ajoute les termes qui spécialisent (si présents)
            if(isset($_POST['terme_specialisation']))
            {
                foreach($_POST['terme_specialisation'] as $spe)
                {
                    if($spe != $_POST['terme_generalisation'])
                    {
                        $stmt = $db->prepare('  SELECT COUNT(spe.COLUMN_VALUE.libelle) AS "nb"
                                            FROM descripteurVedette d, TABLE(d.specialisations) spe
                                            WHERE d.libelle = :nom');
                        $stmt->execute(array(':nom' => $_POST['terme_nom']));
                        $results = $stmt->fetch(PDO::FETCH_ASSOC);

                        //Si le nested table n'est pas encore initialisé la requête change
                        if($results['nb'] == 0)
                        {
                            $stmt = $db->prepare('  UPDATE descripteurVedette
                                                    SET specialisations = specialList(( SELECT ref(d)
                                                                    FROM descripteurVedette d
                                                                    WHERE d.libelle = :specialisation))
                                                    WHERE libelle = :nom');
                        }
                        else
                        {
                            $stmt = $db->prepare('  INSERT INTO TABLE(SELECT specialisations
                                                                    FROM descripteurVedette
                                                                    WHERE libelle = :nom)
                                                    VALUES ((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = :specialisation))');
                        }
                        $stmt->execute(array(':nom' => $_POST['terme_nom'], ':specialisation' => $spe));

                         //Ajoute en tant que généralisation des spécialisations
                        $stmt = $db->prepare('  UPDATE descripteurVedette
                                                SET descripteurGen = (  SELECT ref(d)
                                                                        FROM descripteurVedette d
                                                                        WHERE d.libelle = :nom)
                                                WHERE libelle = :specialisation');
                        $stmt->execute(array(':nom' => $_POST['terme_nom'],
                                             ':specialisation' => $spe));
                    }
                }
            }

            //Ajoute les synonymes (si présents)
            if(isset($_POST['terme_synonyme']))
            {
                foreach($_POST['terme_synonyme'] as $syno)
                {
                    if(!empty($syno))
                    {
                        //Cherche si le synonyme est déja dans la liste
                        $stmt = $db->prepare('  SELECT COUNT(syno.COLUMN_VALUE) AS "nb"
                                                FROM descripteurVedette d, TABLE(d.synonymes) syno
                                                WHERE d.libelle = :nom AND UPPER(syno.COLUMN_VALUE) = UPPER(:synonyme)');
                        $stmt->execute(array(':nom' => $_POST['terme_nom'], ':synonyme' => $syno));
                        $res = $stmt->fetch(PDO::FETCH_ASSOC);

                        if($res['nb'] == 0)
                        {
                            //Cherche si synonymes est initialisé ou pas
                            $stmt = $db->prepare('  SELECT COUNT(syno.COLUMN_VALUE) AS "nb"
                                                    FROM descripteurVedette d, TABLE(d.synonymes) syno
                                                    WHERE d.libelle = :nom');
                            $stmt->execute(array(':nom' => $_POST['terme_nom']));
                            $res = $stmt->fetch(PDO::FETCH_ASSOC);

                            //Si le nested table n'est pas encore initialisé la requête change
                            if($res['nb'] == 0)
                            {
                                $stmt = $db->prepare('  UPDATE descripteurVedette
                                                        SET synonymes = synoList(:synonyme)
                                                        WHERE libelle = :nom');
                            }
                            else
                            {
                                $stmt = $db->prepare('  INSERT INTO TABLE(  SELECT synonymes
                                                                            FROM descripteurVedette
                                                                            WHERE libelle = :nom)
                                                        VALUES (:synonyme)');
                            }
                            $stmt->execute(array(':nom' => $_POST['terme_nom'], ':synonyme' => $syno));
                        }
                    }
                }
            }

            $success = "Le terme a été ajouté avec succès au thesaurus.";
        }
    }

    //Liste de termes pour généralisation
    $stmt = $db->prepare(   'SELECT d.libelle
                            FROM descripteurVedette d
                            ORDER BY d.libelle');
    $stmt->execute();
    $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('terme_ajout.twig', array('page' => 'terme_ajout',
                                                 'error' => $error,
                                                 'success' => $success,
                                                 'termes' => $termes));
?>
