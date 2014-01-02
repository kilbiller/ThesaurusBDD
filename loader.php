<?php
    session_start();
    $salt = 'fsdfzerrgasdgf5df4hg6dfg6q4qg';

    require_once 'vendor/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem('views/');
    $twig = new Twig_Environment($loader, array('debug' => true));
    $twig->addExtension(new Twig_Extension_Debug());
    $twig->addGlobal('SESSION', $_SESSION);


    $db = new PDO('oci:dbname=127.0.0.1/xe', 'thesaurus', 'thesaurus');

    /*$conn = oci_connect('thesaurus', 'thesaurus', '127.0.0.1/xe');
    $stid = oci_parse($conn, 'select * from utilisateur');
    oci_execute($stid);
    //$row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
    oci_fetch_all($stid, $res);*/
?>
