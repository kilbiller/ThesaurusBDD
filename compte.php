<?php
    require_once 'loader.php';

    $error = null;
    $success = null;
    $user = null;

    if(isset($_SESSION['email']))
    {
        if(!empty($_POST['prenom']))
        {
            $stmt = $db->prepare('UPDATE utilisateur SET prenom = :prenom WHERE email = :email');
            $res = $stmt->execute(array(':email' => $_SESSION['email'], ':prenom' => $_POST['prenom']));

            if(!$res)
                $error = $db->errorInfo()[0].' - '.$db->errorInfo()[1].' - '.$db->errorInfo()[2];
            else
                $success = "Prénom modifié.";
        }
        if(!empty($_POST['nom']))
        {
            $stmt = $db->prepare('UPDATE utilisateur SET nom = :nom WHERE email = :email');
            $res = $stmt->execute(array(':email' => $_SESSION['email'], ':nom' => $_POST['nom']));

            if(!$res)
                $error = $db->errorInfo()[0].' - '.$db->errorInfo()[1].' - '.$db->errorInfo()[2];
            else
                $success = "Nom modifié";
        }
        if(!empty($_POST['email']))
        {
            if($_POST['email'] != $_SESSION['email'])
            {
                $stmt = $db->prepare('SELECT COUNT(u.email) AS "nb" FROM utilisateur u WHERE email = :email');
                $stmt->execute(array(':email' => $_POST['email']));
                $res = $stmt->fetch(PDO::FETCH_ASSOC);

                if($res['nb'] >= 1)
                    $error = 'Un utilisateur avec cette adresse email existe déjà.';
                else
                {
                    $stmt = $db->prepare('UPDATE utilisateur SET email = :new_email WHERE email = :email');
                    $res = $stmt->execute(array(':email' => $_SESSION['email'], ':new_email' => $_POST['email']));

                    if(!$res)
                        $error = $db->errorInfo()[0].' - '.$db->errorInfo()[1].' - '.$db->errorInfo()[2];
                    else
                    {
                        $_SESSION['email'] = $_POST['email'];
                        $success = "Adresse email modifiée.";
                    }

                }
            }
        }
        if(!empty($_POST['password1']) && !empty($_POST['password2']))
        {
            if($_POST['password1'] != $_POST['password2'])
                $error = 'Les mots de passe ne sont pas identiques.';
            else
            {
                $stmt = $db->prepare('UPDATE utilisateur SET mdp = :mdp WHERE email = :email');
                $res = $stmt->execute(array(':email' => $_SESSION['email'], ':mdp' => crypt($_POST['password1'],$salt) ));

                if(!$res)
                    $error = $db->errorInfo()[0].' - '.$db->errorInfo()[1].' - '.$db->errorInfo()[2];
                else
                    $success = "Mot de passe modifié.";
            }
        }

        $stmt = $db->prepare('SELECT u.email, u.mdp, u.prenom, u.nom FROM utilisateur u WHERE email = :email');
        $stmt->execute(array(':email' => $_SESSION['email']));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    echo $twig->render('compte.twig', array('page' => 'compte', 'user' => $user , 'error' => $error, 'success' => $success));
?>
