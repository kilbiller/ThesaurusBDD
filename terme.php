<?php
    require_once 'loader.php';

    $terme = null;
    $specialisations = null;
    $synonymes = null;
    if(isset($_GET['terme']))
    {
        //Terme
        $stmt = $db->prepare('SELECT d.libelle, d.definition, d.descripteurGen.libelle AS "GEN_LIBELLE"
        FROM descripteurVedette d
        WHERE UPPER(d.libelle) = UPPER(:terme)');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $terme = $stmt->fetch(PDO::FETCH_ASSOC);

        //Specialisations
        $stmt = $db->prepare('SELECT spe.COLUMN_VALUE.libelle AS "LIBELLE"
        FROM descripteurVedette d , TABLE(d.specialisations) spe
        WHERE UPPER(d.libelle) = UPPER(:terme)
        ORDER BY spe.COLUMN_VALUE.libelle');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $specialisations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Synonymes
        $stmt = $db->prepare('SELECT syno.COLUMN_VALUE AS "LIBELLE"
        FROM descripteurVedette d , TABLE(d.synonymes) syno
        WHERE UPPER(d.libelle) = UPPER(:terme)
        ORDER BY syno.COLUMN_VALUE');
        $stmt->execute(array(':terme' => $_GET['terme']));
        $synonymes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo $twig->render('terme.twig', array('page' => 'terme', 'terme' => $terme,
                                           'specialisations' => $specialisations,
                                           'synonymes' => $synonymes));
?>
