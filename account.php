<?php
    require_once 'loader.php';

    if (isset($_GET['logged']))
        $logged = true;
    else
        $logged = false;

    echo $twig->render('account.twig', array('logged' => $logged, 'go' => 'here'));
?>
