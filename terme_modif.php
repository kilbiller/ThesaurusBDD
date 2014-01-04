<?php
    require_once 'loader.php';

    $error = null;
    $success = null;
    $terme = null;
    $specialisations = null;
    $synonymes = null;
    $termes = null;

    if(!empty($_POST['terme_definition']))
    {
        //Ajoute définition
        $stmt = $db->prepare('UPDATE descripteurVedette SET definition = :definition WHERE libelle = :terme');
        $stmt->execute(array(':terme' => $_GET['terme'], ':definition' => $_POST['terme_definition']));
        $success = "Définition modifiée.";
    }

    if(!empty($_POST['spe_suppr']))
    {
        //Supprime terme qui spécialise
        $stmt = $db->prepare('  DELETE FROM TABLE (SELECT specialisations FROM descripteurVedette WHERE libelle = :terme) spe
                                WHERE VALUE(spe) = (SELECT ref(d) FROM descripteurVedette d WHERE libelle = :specialisation)');
        $stmt->execute(array(':terme' => $_GET['terme'], ':specialisation' => $_POST['spe_suppr']));

        $stmt = $db->prepare('UPDATE descripteurVedette SET descripteurGen = null WHERE libelle = :specialisation');
        $stmt->execute(array(':specialisation' => $_POST['spe_suppr']));

        $success = "Terme spécialisateur supprimé.";
    }

    if(!empty($_POST['syno_suppr']))
    {
        //Supprime synonyme
        $stmt = $db->prepare('  DELETE FROM TABLE (SELECT synonymes FROM descripteurVedette WHERE libelle = :terme) syno
                                WHERE VALUE(syno) = :synonyme');
        $stmt->execute(array(':terme' => $_GET['terme'], ':synonyme' => $_POST['syno_suppr']));

        $success = "Synonyme supprimé.";
    }

    if(!empty($_POST['terme_suppr']))
    {
        //Supprime le terme chez tous les généralisateurs
        $stmt = $db->prepare('  UPDATE descripteurVedette
                                SET descripteurGen = null
                                WHERE descripteurGen = (SELECT REF(d) FROM descripteurVedette d WHERE libelle = :terme)');
        $stmt->execute(array(':terme' => $_POST['terme_suppr']));

        //Supprime le terme chez tous les spécialisateurs
        $stmt = $db->prepare('  SELECT d.libelle
                                FROM descripteurVedette d , TABLE(d.specialisations) spe
                                WHERE UPPER(spe.COLUMN_VALUE.libelle) = UPPER(:terme)
                                ORDER BY spe.COLUMN_VALUE.libelle');
        $stmt->execute(array(':terme' => $_POST['terme_suppr']));
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($results as $res)
        {
            $stmt = $db->prepare('  DELETE FROM TABLE ( SELECT specialisations FROM descripteurVedette
                                                        WHERE libelle = :specialisation) spe
                                    WHERE VALUE(spe) = (SELECT ref(d) FROM descripteurVedette d WHERE libelle = :terme)');
            $stmt->execute(array(':terme' => $_POST['terme_suppr'], ':specialisation' => $res['LIBELLE']));
        }

        //Supprime le terme
        $stmt = $db->prepare('  DELETE FROM descripteurVedette
                                WHERE libelle = :terme');
        $stmt->execute(array(':terme' => $_POST['terme_suppr']));

        header("location: index.php" ); // On renvoie ensuite sur la page d'accueil
    }

    //Terme qui généralise
    if(!empty($_POST['terme_generalisation']))
    {
        if($_POST['terme_generalisation'] == "null")
        {
            //Terme
            $stmt = $db->prepare('  SELECT d.descripteurGen.libelle AS "GEN_LIBELLE"
                                    FROM descripteurVedette d
                                    WHERE UPPER(d.libelle) = UPPER(:terme)');
            $stmt->execute(array(':terme' => $_GET['terme']));
            $t = $stmt->fetch(PDO::FETCH_ASSOC);

            if($t['GEN_LIBELLE'] != null)
            {
                //Supprime terme qui généralise
                $stmt = $db->prepare('UPDATE descripteurVedette SET descripteurGen = null WHERE libelle = :terme');
                $stmt->execute(array(':terme' => $_GET['terme']));

                $stmt = $db->prepare('  DELETE FROM TABLE ( SELECT specialisations
                                                            FROM descripteurVedette WHERE libelle = :generalisation) spe
                                        WHERE VALUE(spe) = (SELECT ref(d) FROM descripteurVedette d WHERE libelle = :terme)');
                $stmt->execute(array(':terme' => $_GET['terme'], ':generalisation' => $t['GEN_LIBELLE']));

                $success = "Terme général supprimé.";
            }
        }
        else
        {
            $stmt = $db->prepare('  UPDATE descripteurVedette
                                    SET descripteurGen = (  SELECT ref(d)
                                                            FROM descripteurVedette d
                                                            WHERE d.libelle = :generalisation)
                                    WHERE libelle = :terme');
            $stmt->execute(array(':terme' => $_GET['terme'],
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
                                                                            WHERE d.libelle = :terme))
                                        WHERE libelle = :generalisation');
            }
            else
            {
                //Utilise la methode insert into
                $stmt = $db->prepare('  INSERT INTO TABLE(  SELECT specialisations
                                                            FROM descripteurVedette
                                                            WHERE libelle = :generalisation)
                                        VALUES ((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = :terme))');
            }
            $stmt->execute(array(':terme' => $_GET['terme'], ':generalisation' => $_POST['terme_generalisation']));

            $success = "Terme général modifié.";
        }
    }

    //Terme qui spécialise
    if(!empty($_POST['terme_specialisation']))
    {
        $stmt = $db->prepare('  SELECT COUNT(spe.COLUMN_VALUE.libelle) AS "nb"
                                FROM descripteurVedette d, TABLE(d.specialisations) spe
                                WHERE d.libelle = :terme AND spe.COLUMN_VALUE.libelle = :specialisation');
        $stmt->execute(array(':terme' => $_GET['terme'], ':specialisation' => $_POST['terme_specialisation']));
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results['nb'] == 0)
        {
            $stmt = $db->prepare('  SELECT COUNT(spe.COLUMN_VALUE.libelle) AS "nb"
                                    FROM descripteurVedette d, TABLE(d.specialisations) spe
                                    WHERE d.libelle = :terme');
            $stmt->execute(array(':terme' => $_GET['terme']));
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            //Si le nested table n'est pas encore initialisé la requête change
            if($results['nb'] == 0)
            {
                $stmt = $db->prepare('  UPDATE descripteurVedette
                                        SET specialisations = specialList(( SELECT ref(d)
                                                        FROM descripteurVedette d
                                                        WHERE d.libelle = :specialisation))
                                        WHERE libelle = :terme');
            }
            else
            {
                $stmt = $db->prepare('  INSERT INTO TABLE(SELECT specialisations
                                                        FROM descripteurVedette
                                                        WHERE libelle = :terme)
                                        VALUES ((SELECT ref(d) FROM descripteurVedette d WHERE d.libelle = :specialisation))');
            }
            $stmt->execute(array(':terme' => $_GET['terme'], ':specialisation' => $_POST['terme_specialisation']));

             //Ajoute en tant que généralisation des spécialisations
            $stmt = $db->prepare('  UPDATE descripteurVedette
                                    SET descripteurGen = (  SELECT ref(d)
                                                            FROM descripteurVedette d
                                                            WHERE d.libelle = :terme)
                                    WHERE libelle = :specialisation');
            $stmt->execute(array(':terme' => $_GET['terme'],
                                 ':specialisation' => $_POST['terme_specialisation']));

            $success = "Terme ajouté aux spécialisations.";
        }
        else
            $error = "Le terme est déjà une spécialisation.";
    }

    //Ajoute un synonyme
    if(!empty($_POST['terme_synonyme']))
    {
        //Cherche si le synonyme est déja dans la liste
        $stmt = $db->prepare('  SELECT COUNT(syno.COLUMN_VALUE) AS "nb"
                                FROM descripteurVedette d, TABLE(d.synonymes) syno
                                WHERE d.libelle = :terme AND UPPER(syno.COLUMN_VALUE) = UPPER(:synonyme)');
        $stmt->execute(array(':terme' => $_GET['terme'], ':synonyme' => $_POST['terme_synonyme']));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if($res['nb'] == 0)
        {
            //Cherche si synonymes est initialisé ou pas
            $stmt = $db->prepare('  SELECT COUNT(syno.COLUMN_VALUE) AS "nb"
                                    FROM descripteurVedette d, TABLE(d.synonymes) syno
                                    WHERE d.libelle = :terme');
            $stmt->execute(array(':terme' => $_GET['terme']));
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            //Si le nested table n'est pas encore initialisé la requête change
            if($res['nb'] == 0)
            {
                $stmt = $db->prepare('  UPDATE descripteurVedette
                                        SET synonymes = synoList(:synonyme)
                                        WHERE libelle = :terme');
            }
            else
            {
                $stmt = $db->prepare('  INSERT INTO TABLE(  SELECT synonymes
                                                            FROM descripteurVedette
                                                            WHERE libelle = :terme)
                                        VALUES (:synonyme)');
            }
            $stmt->execute(array(':terme' => $_GET['terme'], ':synonyme' => $_POST['terme_synonyme']));

            $success = "Synonyme ajouté.";
        }
        else
            $error = "Le synonyme existe déja.";
    }

    if(isset($_SESSION['email']) && !empty($_GET['terme']))
    {
        //Terme
        $stmt = $db->prepare('  SELECT d.libelle, d.definition, d.descripteurGen.libelle AS "GEN_LIBELLE"
                                FROM descripteurVedette d
                                WHERE UPPER(d.libelle) = UPPER(:terme)');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $terme = $stmt->fetch(PDO::FETCH_ASSOC);

        //Specialisations
        $stmt = $db->prepare('  SELECT spe.COLUMN_VALUE.libelle AS "LIBELLE"
                                FROM descripteurVedette d , TABLE(d.specialisations) spe
                                WHERE UPPER(d.libelle) = UPPER(:terme)
                                ORDER BY spe.COLUMN_VALUE.libelle');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $specialisations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Synonymes
        $stmt = $db->prepare('  SELECT syno.COLUMN_VALUE AS "LIBELLE"
                                FROM descripteurVedette d , TABLE(d.synonymes) syno
                                WHERE UPPER(d.libelle) = UPPER(:terme)
                                ORDER BY syno.COLUMN_VALUE');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $synonymes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Autres termes
        $stmt = $db->prepare(   'SELECT d.libelle
                                FROM descripteurVedette d
                                WHERE d.libelle != :terme
                                ORDER BY d.libelle');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo $twig->render('terme_modif.twig', array('page' => 'terme_modif',
                                                 'terme' => $terme,
                                                 'termes' => $termes,
                                                 'specialisations' => $specialisations,
                                                 'synonymes' => $synonymes,
                                                 'error' => $error,
                                                 'success' => $success));
?>
