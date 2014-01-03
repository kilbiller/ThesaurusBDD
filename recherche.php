<?php
    require_once 'loader.php';

    $termes = null;
    $synonymes = null;
    if(!empty($_POST['search']))
    {
        //Termes
        $stmt = $db->prepare('  SELECT d.libelle
                                FROM descripteurVedette d
                                WHERE UPPER(d.libelle) LIKE UPPER(:terme)
                                ORDER BY d.libelle');
        $stmt->execute(array(':terme' => '%'.$_POST['search'].'%'));
        $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Synonymes
        $stmt = $db->prepare('  SELECT d.libelle, syno.COLUMN_VALUE AS "SYNONYME"
                                FROM descripteurVedette d, TABLE(d.synonymes) syno
                                WHERE UPPER(syno.COLUMN_VALUE) LIKE UPPER(:synonyme)
                                ORDER BY syno.COLUMN_VALUE');
        $stmt->execute(array(':synonyme' => '%'.$_POST['search'].'%'));
        $synonymes = $stmt->fetchAll(PDO::FETCH_ASSOC);



    }

    echo $twig->render('recherche.twig', array('termes' => $termes, 'synonymes' => $synonymes));
?>
