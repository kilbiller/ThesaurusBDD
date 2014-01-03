<?php
    require_once 'loader.php';

    $termes = null;
    if(!empty($_POST['search']))
    {
        $stmt = $db->prepare(   "SELECT d.libelle
                                FROM descripteurVedette d
                                WHERE UPPER(d.libelle) like UPPER(:terme)
                                ORDER BY d.libelle");
        $stmt->execute(array(':terme' => '%'.$_POST['search'].'%'));
        $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo $twig->render('recherche.twig', array('termes' => $termes));
?>
