<?php
    require_once 'loader.php';

    if(isset($_POST['user_email']) && isset($_POST['user_password']))
    {
        $stmt = $db->prepare('SELECT COUNT(u.email) AS "nb" FROM utilisateur u WHERE email = :email and mdp = :mdp');
        $stmt->execute(array(':email' => $_POST['user_email'], ':mdp' => crypt($_POST['user_password'], $salt) ));
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if($results['nb'] >= 1)
            $_SESSION['email'] = $_POST['user_email'];

         //var_dump($results);
    }

    header("location: index.php" ) ; // On renvoie ensuite sur la page d'accueil
?>
