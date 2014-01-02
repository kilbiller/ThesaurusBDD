<?php
    require_once 'loader.php';

    if (isset($_GET['logged']))
        $logged = true;
    else
        $logged = false;

    $db = new PDO('oci:dbname=127.0.0.1/xe', 'thesaurus', 'thesaurus');
    $stmt = $db->query("select d.libelle, t1.COLUMN_VALUE.libelle FROM DESCRIPTEURVEDETTE d, TABLE(d.specialisations) t1");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*$conn = oci_connect('thesaurus', 'thesaurus', '127.0.0.1/xe');
    $stid = oci_parse($conn, 'select * from utilisateur');
    oci_execute($stid);
    //$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    oci_fetch_all($stid, $res);*/

    echo $twig->render('terme.twig', array('logged' => $logged, 'test' => $results));
?>
