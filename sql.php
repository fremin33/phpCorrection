<?php
require('gestionBD.inc.php');

$connexion = connexionBd();

$sql_auteurs = 'SELECT * FROM php2_auteurs';
$req_auteurs = $connexion->query( $sql_auteurs );
$auteurs = $req_auteurs->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Nos recettes</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>

<table border="1">
<?php foreach($auteurs as $auteur) :?>
	
	<tr>
		<td><?= $auteur['prenom_auteur']; ?></td>
		<td><?= $auteur['nom_auteur']; ?></td>
		<td><?= $auteur['email_auteur']; ?></td>
		<td><?= $auteur['dt_naissance_auteur']; ?></td>
	</tr>

<?php endforeach; ?>
</table>

</body>
</html>