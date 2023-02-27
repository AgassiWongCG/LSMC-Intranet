<!DOCTYPE html>
<html>
  <head>
    <title>VoyageVoyage</title>
    <script language="javascript" type="text/javascript">
      function Testform() {
        var Nom, total
        Nom = document.forms[0].Nom.value
        total = document.forms[0].total.value
        if (Nom == "" || total == "") {
          alert("Veuillez mentionner votre nom et votre Nombre total de jour de vacance")
        }
      }
    </script>
  </head>
  <body>
    <h4>Réservation Voyage</h4>
    <form method="post" action="Réservationtest.php"> Nom : <input type="text" name="Nom" />
      <br /> Genre : <select name="Genre">
        <option value="homme">Homme</option>
        <option value="Femme">Femme</option>
      </select>
      <br /> Destination : <select name="destination">
        <option value="Afghanistan">Afghanistan</option>
        <option value="Irak">Irak</option>
        <option value="Corée du Nord">Corée du Nord</option>
        <option value="Somalie">Somalie</option>
        <option value="Syrie">Syrie</option>
        <option value="Yémen">Yémen</option>
        <option value="Libye">Libye</option>
        <option value="Mali">Mali</option>
        <option value="Sud-Soudan">Sud-Soudan</option>
      </select> Date début de vacance : <input type="date" name="debut"> Date fin de vacance : <input type="date" name="fin"> Nombre total de jour de vacance : <input type="text" name="total" />
      <br />
      <input type="submit" onmousover="Testform()" name="submit" value="Voyager" />
    </form> <?php
if (isset($_POST["submit"])) {
    ($connect = mysqli_connect("localhost", "root", "")) or
        die("erreur de connexion à MySQL");
    mysqli_select_db($connect, "voyagevoyage") or
        die("erreur de connexion à la bas de données");
    mysqli_query(
        $connect,
        "INSERT INTO coordonnées VALUES('$_POST[Nom]','$_POST[Genre]','$_POST[destination]','$_POST[debut]','$_POST[fin]','$_POST[total]')"
    );
    mysqli_close($connect);
}
($connect = mysqli_connect("localhost", "root", "")) or
    die("erreur de connection à MySQL");
mysqli_select_db($connect, "voyagevoyage") or
    die("erreur de connexion à la base de données");
// Création et envoi de la requête
$result = mysqli_query(
    $connect,
    "SELECT Nom, Genre, destination, debut, fin, total FROM coordonnées ORDER BY Nom ASC"
);
$nbrinscrits = mysqli_num_rows($result);
// Récupération des résultats
echo "
				<p>Nombre de réservation : $nbrinscrits</p>";
echo '
				<table border="1" cellpadding="2" cellspacing="2">
					<tr>
						<th>Nom</th>
						<th>Genre</th>
						<th>destination</th>
						<th>debut</th>
						<th>fin</th>
						<th>total</th>
					</tr>';
while ($row = mysqli_fetch_row($result)) {
    $Nom = $row[0];
    $Genre = $row[1];
    $destination = $row[2];
    $debut = $row[3];
    $fin = $row[4];
    $total = $row[5];
    echo "
					<tr>
						<td>$Nom</td>
						<td>$Genre</td>
						<td>$destination</td>
						<td>$debut</td>
						<td>$fin</td>
						<td>$total</td>
					</tr>";
}
//Déconnexion de la base de données
mysqli_close($connect);
?>
  </body>
</html>