<?php

require('gestionBD.inc.php');

$connexion = connexionBd();

$favoris = array();
if( isset($_COOKIE['recettes']) ):
	$favoris = explode(',',$_COOKIE['recettes']);
endif;

if( isset($_GET['id']) ):
	$id_recette = intval($_GET['id']);
else:
	$id_recette = 0;
endif;	

if( isset($_GET['favoriadd']) ):	
	$favoris[] = $id_recette;
	$favoris = implode(',',$favoris);
	setcookie('recettes', $favoris, strtotime('+1 year'));
	header('Location: single.php?id='.$id_recette);
	die();
endif;


if( isset($_GET['favoridel']) ):	
	$keytodelete = array_search($id_recette,$favoris);
	if($keytodelete!==false){
	    unset($favoris[$keytodelete]);
	}
	$favoris = implode(',',$favoris);
	setcookie('recettes', $favoris, strtotime('+1 year'));
	header('Location: single.php?id='.$id_recette);
	die();
endif;

$sql_recette = 'SELECT * FROM php2_recettes INNER JOIN php2_auteurs ON id_auteur = id_auteur_recette  WHERE id_recette = :uid';
$req_recette = $connexion->prepare( $sql_recette );
$req_recette->execute(array(':uid' => $id_recette));
$recette = $req_recette->fetch();

?>


<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nos recettes</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		
		<?php if( $recette ): ?>

			<h1><?= $recette['titre_recette']; ?></h1>
			<p><small>Par <?= $recette['prenom_auteur']; ?> <?= $recette['nom_auteur']; ?></small>
				<br>
				<?php if( in_array($recette['id_recette'], $favoris ) ) : ?>
					<a href="single.php?favoridel=1&id=<?= $recette['id_recette']; ?>">Supprimer des favoris</a>
				<?php else: ?>
					<a href="single.php?favoriadd=1&id=<?= $recette['id_recette']; ?>">Mettre en favori</a>
				<?php endif; ?>
			</p>
			<hr>
			<p><?= nl2br($recette['texte_recette']); ?></p>
			<hr>
			<p class="text-muted"><?= strftime('%d %B %Y', strtotime($recette['dt_crea_recette'])); ?></p>

		<?php else: ?>

			<p>Aucune recette avec cet identifiant !</p>

		<?php endif; ?>	

		<a href="archive.php" class="btn btn-secondary">retour</a>
	</div>
</body>
</html>
