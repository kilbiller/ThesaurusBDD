<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta viewport="width=device-width, initial-scale=1.0">
    <title>Thésaurus nouvelles technologies</title>
    <link rel="stylesheet" href="stylesheets/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheets/theme.css">
</head>
<body>
    <?php $page = "registration"?>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="page-header">
                    <h1>Inscription</h1>
                </div>
                <form role="registration" method="POST" action="">
                    <div class="form-group">
                        <label for="visitor_firstname">Nom</label>
                        <input type="text" class="form-control" id="visitor_firstname" placeholder="Entrez votre nom">
                    </div>
                    <div class="form-group">
                        <label for="visitor_lastname">Prénom</label>
                        <input type="text" class="form-control" id="visitor_lastname" placeholder="Entrez votre prénom">
                    </div>
                    <div class="form-group">
                        <label for="visitor_email">Adresse Email</label>
                        <input type="email" class="form-control" id="visitor_email" placeholder="Entrez votre adresse email">
                    </div>
                    <div class="form-group">
                        <label for="visitor_password1">Mot de passe</label>
                        <input type="password" class="form-control" id="visitor_password1" placeholder="Entrez un mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="visitor_password2">Confirmation du mot de passe</label>
                        <input type="password" class="form-control" id="visitor_password2" placeholder="Confirmez le mot de passe">
                    </div>
                    <button type="submit" class="btn btn-default">Envoyer</button>
                </form>
            </div>
        </div>
    </div><!-- /.container -->

    <!-- JAVASCRIPTS -->
    <script src="javascripts/jquery-2.0.3.min.js"></script>
    <script src="javascripts/bootstrap.min.js"></script>
    <!-- /JAVASCRIPTS -->

</body>
</html>