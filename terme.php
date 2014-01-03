<?php
    require_once 'loader.php';

    if (isset($_GET['logged']))
        $logged = true;
    else
        $logged = false;


    $stmt = $db->query("select d.libelle, t1.COLUMN_VALUE.libelle FROM DESCRIPTEURVEDETTE d, TABLE(d.specialisations) t1");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo $twig->render('terme.twig', array('page' => 'terme', 'logged' => $logged, 'test' => $results));
?>
