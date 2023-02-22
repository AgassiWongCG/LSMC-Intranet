<?php
session_start();
require_once "config.php"; // ajout connexion bdd
// si la session existe pas soit si l'on est pas connecté on redirige
if (!isset($_SESSION["user"])) {
	header("Location:index.php");
	die();
}

// On récupere les données de l'utilisateur
$req = $bdd->prepare("SELECT * FROM effectif WHERE token = ?");
$req->execute([$_SESSION["user"]]);
$data = $req->fetch();
?>
<!DOCTYPE html>
<html>
  <head>
	<title>Prise de Service</title>
	<script language="javascript" type="text/javascript">
	  function Testform() {
		var Nom, total
		Nom = document.forms[0].Nom.value
		total = document.forms[0].total.value
		if (Nom == "" || total == "") {
		  alert("Veuillez mentionner votre nom et votre Nombre total de jour de vacance")
		}
	  }

      function Testform() {     
        var Nom, total
        Nom = document.forms[0].Nom.value
        total = document.forms[0].total.value
        if (Nom == "" || total == "") {
          alert("Veuillez mentionner votre nom et votre Nombre total de jour de vacance")
        }
      }
	</script>

	<!-- Bootstrap CSS -->  
	<link rel="stylesheet" href="./css/style.css">
  </head>
  <body>
	<h4>Prise de service</h4>
    <form method="post" action="service.php">
<table style="width: 100%; text-align: center;" border="1">
    <tbody>
        <tr>
            <td>Satut</td>
        </tr>
        <tr>
            <td><select name="Statut">
                    <option selected="selected" value="6">Code 6</option>
                    <option value="3">Code 3</option>
                    <option value="7">Code 7</option>
                    <option value="10">Code 10</option>
                    <option value="15">Code 15</option>
                    <option value="Autopsie">Autopsie</option>
                    <option value="Morgue">Morgue</option>
                    <option value="Fusillade">Fusillade</option>
                    <option value="Consultation">Consultation</option>
                    <option value="Entretien">Entretien</option>
                    <option value="Formation">Formation</option>
                    <option value="Examen">Examen</option>
                    <option value="Rendez-vous">Rendez-vous</option>
                    <option value="&Eacute;v&ecirc;nement">&Eacute;v&ecirc;nement</option>
                    <option value="R&eacute;union">R&eacute;union</option>
                    <option value="Gestion Direction">Gestion Direction</option>
                </select></td>
        </tr>
        <tr>
            <td>V&eacute;hicule utilis&eacute;</td>
        </tr>
        <tr>
            <td><select name="Vehicule">
                    <option selected="selected" value="Aucune">Aucun</option>
                    <option value="Ambulance 1">Ambulance 1</option>
                    <option value="Ambulance 2">Ambulance 2</option>
                    <option value="Vapide">Vapide</option>
                    <option value="4x4">4x4</option>
                    <option value="Caracara">Caracara</option>
                    <option value="Alamo">Alamo</option>
                    <option value="Predator">Predator</option>
                    <option value="Buffalo Direction">Buffalo Direction</option>
                    <option value="H&eacute;licopt&egrave;re">H&eacute;licopt&egrave;re</option>
                </select></td>
        </tr>
        <tr>
            <td>Unit&eacute; | Remarque | Zone</td>
        </tr>
        <tr>
            <td><input name="Commentaire" type="text" /></td>
        </tr>
    </tbody>
</table>

      <input type="submit" onmousover="Testform()" name="submit" value="Prise de Service" />
      <input type="submit" onmousover="Testform()" name="submit" value="Mettre à jour" />
      <input type="submit" onmousover="Testform()" name="submit" value="Fin de service" />
	</form> <?php
if (isset($_POST["submit"])) {
	($connect = mysqli_connect("localhost", "root", "")) or die("erreur de connexion à MySQL");
	mysqli_select_db($connect, "effectif") or die("erreur de connexion à la bas de données");
	mysqli_query($connect, "INSERT INTO effectif VALUES('$_POST[Nom]','$_POST[Genre]','$_POST[destination]','$_POST[debut]','$_POST[fin]','$_POST[total]')");
	mysqli_close($connect);
}
($connect = mysqli_connect("localhost", "root", "")) or die("erreur de connection à MySQL");
mysqli_select_db($connect, "lsmc") or die("erreur de connexion à la base de données");
//                                       0      1          2         3        4      5     6         7        8            9            10          11            12
$result = mysqli_query($connect, "SELECT id, hospital, firstname, lastname, grade, role, agregation, phone, intervention, commentaire, vehicule, debutservice, service FROM effectif WHERE service = true");
$nbrTotalService = mysqli_num_rows($result);
$nbrTotalService_LSMC = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'lsmc'"));
$nbrTotalService_BCMC = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'bcmc'"));
// Récupération des résultats
echo "<p>Nombre en Service : $nbrTotalService</p>";
echo "<p>Nombre en Service LSMC : $nbrTotalService_LSMC</p>";
echo "<p>Nombre en Service BCMC : $nbrTotalService_BCMC</p>";
echo '
				<table border="1" cellpadding="2" cellspacing="2">
					<tr>
						<th>Id</th>
						<th>Hôpital</th>
						<th>Prénom Nom</th>
						<th>Grade</th>
						<th>Rôle</th>
						<th>Agrégations</th>
						<th>Téléphone</th>
						<th>Intervention</th>
						<th>Commentaire</th>
						<th>Véhicule</th>
						<th>Début du service</th>
					</tr>';
while ($row = mysqli_fetch_row($result)) {
	$id                 = $row[0];
	$hopital            = $row[1];
	$prenom             = $row[2];
	$nom                = $row[3];
	$grade              = $row[4];
	$role               = $row[5];
	$agregations        = $row[6];
	$phone              = $row[7];
	$intervention       = $row[8];
	$commentaire        = $row[9];
	$vehicule           = $row[10];
	$debutservice       = $row[11];
	$service            = $row[12];
	echo "  <tr>
				<td>$id</td>
				<td>$phone</td>
				<td>$prenom $nom</td>
				<td>$grade</td>
				<td>$role</td>
				<td>$agregations</td>
				<td>$phone</td>
				<td>$intervention</td>
				<td>$commentaire</td>
				<td>$vehicule</td>
				<td>$debutservice</td>
			</tr>";
}
//Déconnexion de la base de données
mysqli_close($connect);
?>
  </body>
</html>