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
	<?php $page = "search"?>	
    <?php include 'header.php'; ?>

	

	<div class="container">
	<div class="row">
	<div class="col-md-10">
	<div class="page-header">
	<h1>Résultat de la recherche</h1>
	<?php
		if($_POST['search']) $search=$_POST['search'];
		$nb = 2;// le nombre d'enregistrement à générer avec une requete à la base
	?>

	<h2><?php echo $nb; ?> enregistrement<?php if($nb>1) echo 's'; ?> correspondant à <?php echo $search; ?></h2>
	</div>

	<div class="panel panel-default">
	<!-- Default panel contents -->
	<div class="panel-heading concept"><i class="glyphicon glyphicon-chevron-right"></i> Terme : livre (nm) <span class="badge">3</span></div>
	<!-- List group -->
	<ul class="list-group terme">
	<li class="list-group-item">Terme général : <a>ouvrage</a></li>
	<li class="list-group-item">Termes spécifiques : <a>magazine</a>, <a>BD</a>, <a>comic</a></li>
<li class="list-group-item">Termes synonymes : <a>écrit</a>, <a>livret</a>, <a>registre</a></li>
	</ul>
	</div>

	<div class="panel panel-default">
	<!-- Default panel contents -->
	<div class="panel-heading concept"><i class="glyphicon glyphicon-chevron-right"></i> Terme : livre (nf) <span class="badge">2</span></div>
	<!-- List group -->
	<ul class="list-group terme">
	<li class="list-group-item">Terme général : <a>monnaie</a></li>
	<li class="list-group-item">Termes synonymes : <a>pound</a></li>
	</ul>
	</div>


	</div>
	</div>
	</div><!-- /.container -->

	<!-- JAVASCRIPTS -->
	<script src="javascripts/jquery-2.0.3.min.js"></script>
	<script src="javascripts/bootstrap.min.js"></script>
	<script src="javascripts/script.js"></script>
	<!-- /JAVASCRIPTS -->

</body>
</html>
