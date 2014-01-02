<?php
    session_start();
    $_SESSION = array(); // On efface toutes les variables de la session
    session_destroy(); // Puis on détruit la session
    header("location: index.php" ) ; // On renvoie ensuite sur la page d'accueil
?>
