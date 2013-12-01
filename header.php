<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Basculer navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Thesaurus</a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li <?php echoActiveClassIfRequestMatches("index") ?> ><a href="index.php">Liste des termes</a></li>
            <li><a href="">Ajouter Concept</a></li>
        </ul>

        <!-- Search menu -->
        <form class="navbar-form navbar-left" role="search" method="POST" action="search.php">
            <div class="form-group">
                <input type="text" class="form-control" id="search" name="search" placeholder="Rechercher terme">
            </div><span></span><!--Bug si on enleve le span ???-->
            <button type="submit" class="btn btn-default">Chercher</button>
        </form>

        <?php
            if(!isset($_GET["logged"]))
            {
        ?>
        <!-- When your not logged -->
        <ul class="nav navbar-nav navbar-right">
            <li <?php echoActiveClassIfRequestMatches("registration") ?> ><a href="registration.php">S'inscrire</a></li>
            <li class="dropdown">
                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Se connecter <b class="caret"></b></a>
                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
                    <span class="arrow"></span>
                    <form action="?logged=true" method="post" accept-charset="UTF-8">
                        <input id="user_email" style="margin-bottom: 15px;" type="text" name="user[email]" size="30" placeholder="Adresse email"/>
                        <input id="user_password" style="margin-bottom: 15px;" type="password" name="user[password]" size="30" placeholder="Mot de passe"/>
                        <input class="btn btn-default" style="clear: left; width: 100%; height: 32px; font-size: 13px; margin-bottom: 15px;" type="submit" name="commit" value="Connexion" />
                    </form>
                </div>
            </li>
        </ul>
        <?php
            }
            else
            {
        ?>
        <!-- When your logged -->
        <ul class="nav navbar-nav navbar-right">
	    <li><a href="account.php">Mon compte</a></li>
            <li><a href="logout.php">Se déconnecter</a></li>
        </ul>
        <p class="navbar-text navbar-right">Connecté en tant que Rémy Peru</p>
        <?php
            }
        ?>
    </div><!--/.nav-collapse -->
</div>


<?php
//Permet de changer la couleur du fond d'un menu dans la navbar si on a clické dessus
function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

?>
