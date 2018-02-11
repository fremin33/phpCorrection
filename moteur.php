<?php
session_start();
require('gestionBD.inc.php');

$connexion = connexionBd();

$auteurdefault = $titredefault = '';

if( isset($_SESSION['auteur']) ) :
	$auteurdefault = intval($_SESSION['auteur']);
endif;
if( isset($_SESSION['titre']) ) :
	$titredefault = $_SESSION['titre'];
endif;

$sql_auteurs = 'SELECT 
					* , count(*) as nb_recette
				FROM 
					php2_auteurs
					INNER JOIN 
						php2_recettes 						 
						ON id_auteur = id_auteur_recette 
				GROUP BY 
					id_auteur
				ORDER BY 
					prenom_auteur, nom_auteur';
$req_auteurs = $connexion->query( $sql_auteurs );
$auteurs = $req_auteurs->fetchAll();

?>


<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Moteur de recherche</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<h1>Moteur de recherche</h1>
		<form action="archive.php" method="post">
			 <div class="form-group">
			    <label for="auteur">Filtrer par auteur :</label>
			    <select name="auteur" id="auteur" class="form-control">
			    	<option value="">Tous les auteurs</option>
			    	<?php foreach($auteurs as $auteur) : ?>
						<option value="<?= $auteur['id_auteur']; ?>"<?php if( $auteurdefault == $auteur['id_auteur']) echo ' selected'; ?>><?= $auteur['prenom_auteur']; ?> <?= $auteur['nom_auteur']; ?> - <?= $auteur['nb_recette']; ?> recette(s)</option>
					<?php endforeach; ?>
			    </select>
			  </div>
			  <div class="form-group">
			    <label for="titre">Filtrer par titre :</label>
			    <input type="text" class="form-control" id="titre" name="titre" placeholder="lorem..." value="<?= htmlspecialchars($titredefault,ENT_QUOTES); ?>">
			  </div>
			  <button type="submit" class="btn btn-primary">Rechercher</button>
		</form>


		<h2 class="mt-5 display1">Vos recettes favorites</h2>
		<p class="lead">
			<?php

			if( isset($_COOKIE['recettes']) ):
				$recette_uids = $_COOKIE['recettes'];
				$req_recettes = $connexion->prepare( 'SELECT * FROM  php2_recettes WHERE id_recette IN (:uids)' );
				$req_recettes->execute( array(':uids' => $recette_uids) );
				$recettes = $req_recettes->fetchAll();

				foreach( $recettes as $recette ):
					echo '<a href="single.php?id=' . $recette['id_recette'] . '">' . $recette['titre_recette'] . '</a><br>';
				endforeach;
			else:
				echo 'Aucune recette en favori';
			endif;
			?>
		</p>

	</div>

	
</body>
</html>