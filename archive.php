<?php
session_start();
require('gestionBD.inc.php');

$connexion = connexionBd();
$args = array();

$sql_recettes = 'SELECT 
					*, 
					(SELECT count(*) FROM php2_recettes WHERE id_auteur_recette = id_auteur) as nb_recette
				FROM 
					php2_recettes 
					INNER JOIN 
						php2_auteurs 
						ON id_auteur = id_auteur_recette
				WHERE 1 = 1

				';



if( isset($_POST['auteur']) ):
	$sql_recettes .= ' AND id_auteur = :auteur ';
	$args[':auteur'] = intval( $_POST['auteur'] );

	$req_auteur = $connexion->query( 'SELECT * FROM php2_auteurs WHERE id_auteur = ' . $args[':auteur'] );
	$auteur = $req_auteur->fetch();

	$_SESSION['auteur'] = $args[':auteur'];

endif;

if( isset($_POST['titre']) ):
	$sql_recettes .= " AND titre_recette LIKE :titre ";
	$args[':titre'] = '%' . $_POST['titre'] . '%';

	$_SESSION['titre'] = $_POST['titre'];
endif;


$sql_recettes .= ' ORDER BY 
					dt_crea_recette DESC';


$req_recettes = $connexion->prepare( $sql_recettes );
$req_recettes->execute($args);
$recettes = $req_recettes->fetchAll();

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
		<div class="jumbotron">
		  <h1 class="display-3">Nos recettes</h1>
		  <p class="lead"><?= count($recettes); ?> résultats disponibles</p>
		  	<?php
			if( isset($_POST['auteur']) || isset($_POST['titre']) ) :
			?>
			<hr class="my-4">
			<p>
				<strong>Filtre actif : </strong>
				<?php

				$sep = '';
				if( $_POST['auteur']) :
					echo 'Auteur = ' . $auteur['prenom_auteur'] . ' ' . $auteur['nom_auteur'];

				$sep = ', ';
				endif;

				if( $_POST['titre'] ) : 
					echo $sep . 'Mot clé = ' . $_POST['titre'];
				endif; 

				?>
				<br><a href="moteur.php">modifier la recherche</a>
			</p>
			<?php
			endif;
			?>
		 
		</div>
		
		
		
		<?php foreach($recettes as $recette) : ?>

			<h2><?= $recette['titre_recette']; ?></h2>
			<p>
				Par <?= $recette['prenom_auteur']; ?> <?= $recette['nom_auteur']; ?>
				<span class="badge badge-secondary"><?= intval($recette['nb_recette']); ?> recette<?= ($recette['nb_recette'] > 1) ? 's' : ''; ?></span>
			</p>
			<p class="text-muted">
				<?= strftime('%d %B %Y', strtotime($recette['dt_crea_recette'])); ?>,
				cela fait
				<?php
				$now = new DateTime();
				$crea = new DateTime($recette['dt_crea_recette']);
				echo $now->diff($crea)->days . ' jour(s)'; 
				?>
			</p>
			<a href="single.php?id=<?= intval($recette['id_recette']); ?>" class="btn btn-primary">En savoir plus</a>

			<hr>

		<?php endforeach; ?>
	</div>
</body>
</html>