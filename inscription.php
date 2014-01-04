<?php
    require_once 'loader.php';

    $error = null;
    $success = null;
    if(isset($_POST['user_email']) && isset($_POST['user_password1']) && isset($_POST['user_password2']))
    {
        if($_POST['user_password1'] != $_POST['user_password2'])
            $error = 'Les mots de passe ne sont pas identiques.';
        else
        {
            $stmt = $db->prepare('SELECT COUNT(u.email) AS "nb" FROM utilisateur u WHERE email = :email');
            $stmt->execute(array(':email' => $_POST['user_email']));
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if($res['nb'] >= 1)
                $error = 'Un utilisateur avec cette adresse email existe déjà.';
            else
            {
                $stmt = $db->prepare('  INSERT INTO utilisateur
                                        VALUES(:email, :mdp, :prenom, :nom, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
                $res = $stmt->execute(array(':email' => $_POST['user_email'],
                                            ':mdp' => crypt($_POST['user_password1'],$salt),
                                            ':prenom' => $_POST['user_prenom'],
                                            ':nom' => $_POST['user_nom']));

                if(!$res)
                    $error = $db->errorInfo()[0].' - '.$db->errorInfo()[1].' - '.$db->errorInfo()[2];
                else
                {
                    $_SESSION['email'] = $_POST['user_email'];
                    $success = "Inscription réussie. Vous êtes maintenant connecté.";
                }

            }
        }
    }

    if(isset($_SESSION['email']))
        header("location: index.php" ); // On renvoie ensuite sur la page d'accueil
    else
        echo $twig->render('inscription.twig', array('page' => 'inscription','error' => $error, 'success' => $success));

?>
