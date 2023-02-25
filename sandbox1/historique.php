<?php
    session_start();
    require_once "config.php";

    // Info : Si la session existe pas soit si l'on est pas connecté on redirige
    if (!isset($_SESSION["user"])) {
        header("Location: index.php");
        die();
    }

    // Info : Récupération données de l'effectif connecté
    $req = $bdd->prepare("SELECT * FROM effectif WHERE token = ?");
    $req->execute([$_SESSION["user"]]);
    $data = $req->fetch();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Prise de Service</title>
		<script language="javascript" type="text/javascript">

		    // Info : Gestion d'erreur Prise de Service
// 			function errorCheck_PriseDeService() {
// 				var gotStatut
// 				var gotVehicule
// 				var gotCommentaire
//
// 				gotStatut       = document.forms[0].Statut.value
// 				gotVehicule     = document.forms[0].Vehicule.value
// 				gotCommentaire  = document.forms[0].Commentaire.value
// 				if (gotStatut == "" || gotStatut == null) {
// 					alert("Merci de choisir un statut valide.")
// 				}
// 				if (gotVehicule == "" || gotVehicule == null) {
// 					alert("Merci de choisir un véhicule valide.")
//                 }
// 			}

		    // Info : Gestion d'erreur Mise à Jour
// 			function errorCheck_MiseAJour() {
// 				var gotStatut
// 				var gotVehicule
// 				var gotCommentaire
//
// 				gotStatut       = document.forms[0].Statut.value
// 				gotVehicule     = document.forms[0].Vehicule.value
// 				gotCommentaire  = document.forms[0].Commentaire.value
// 				if (gotStatut == "" || gotStatut == null) {
// 					alert("Merci de choisir un statut valide.")
// 				}
// 				if (gotVehicule == "" || gotVehicule == null) {
// 					alert("Merci de choisir un véhicule valide.")
//                 }
// 			}

		    // Info : Gestion d'erreur Fin de Service
// 			function errorCheck_FinDeService() {
//
// 			}
		</script>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="./css/style.css">
	</head>

	<body style="width: 100%; text-align: center;">
        <a href="landing.php" class="btn btn-danger btn-lg">Retour Profil</a><br/>
<?php
    // Info : Donnée de connexion au serveur phpmyadmin
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "lsmc";

    if (isset($_POST["button_PriseDeService"])) {

        $nouveauStatut      = $_POST['Statut'];
        $nouveauVehicule    = $_POST['Vehicule'];
        $nouveauCommentaire = $_POST['Commentaire'];

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $idEffectifActuel = $data["id"];
        $serviceEffectifActuel = $data["service"];
        if ($serviceEffectifActuel === 0) {
            $sql = "UPDATE effectif SET service='1', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice=now() WHERE id=$idEffectifActuel";
        }
        else if ($serviceEffectifActuel === 1) {
            $sql = "UPDATE effectif SET service='0' WHERE id=$idEffectifActuel";
        }

        if ($conn->query($sql) === TRUE) {
          echo "Record updated successfully";
        } else {
          echo "Error updating record: " . $conn->error;
        }

        $conn->close();

        // Actualisation pour ne pas duppliquer la requête de formulaire
        header("Location: service.php");
    }

    if (isset($_POST["button_MiseAJour"])) {

        $nouveauStatut      = $_POST['Statut'];
        $nouveauVehicule    = $_POST['Vehicule'];
        $nouveauCommentaire = $_POST['Commentaire'];

        echo "<script>console.log('selected: " . $selected . "' );</script>";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $idEffectifActuel = $data["id"];
        $sql = "UPDATE effectif SET intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire' WHERE id=$idEffectifActuel";

        if ($conn->query($sql) === TRUE) {
          echo "Record updated successfully";
        } else {
          echo "Error updating record: " . $conn->error;
        }

        $conn->close();

        // Actualisation pour ne pas duppliquer la requête de formulaire
        header("Location: service.php");
    }

    if (isset($_POST["button_FinDeService"])) {

        $nouveauStatut      = "";
        $nouveauVehicule    = "";
        $nouveauCommentaire = "";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }

        $idEffectifActuel = $data["id"];
        $sql = "UPDATE effectif SET service='0', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice='' WHERE id=$idEffectifActuel";

        if ($conn->query($sql) === TRUE) {
          echo "Record updated successfully";
        } else {
          echo "Error updating record: " . $conn->error;
        }

        $conn->close();

        // Actualisation pour ne pas duppliquer la requête de formulaire
        header("Location: service.php");
    }

    ($connect = mysqli_connect("localhost", "root", "")) or die("erreur de connection à MySQL");
    mysqli_select_db($connect, "lsmc") or die("erreur de connexion à la base de données");
    $idEffectifActuel = $data["id"];

    //                                                       0      1          2              3        4      5       6      7         8       9
    $result                 = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour FROM service WHERE effectif=$idEffectifActuel");

    $result_total           = mysqli_query($connect, "SELECT SUM(seconde) AS seconde, SUM(minute) AS minute, SUM(heure) AS heure, SUM(jour) AS jour, dernier FROM service WHERE effectif=$idEffectifActuel AND dernier=0");

    while ($row = mysqli_fetch_row($result_total)) {
        echo "<span>TOTAL</span><br/>";
        $total_jour     = $row[3];
        $total_heure    = $row[2];
        $total_minute   = $row[1];
        $total_seconde  = $row[0];

        $minute_supp = 0;
        if ($total_seconde > 59) {
            $minute_supp = intval($total_seconde / 60);
            $total_seconde = $total_seconde % 60;
            $total_minute = $total_minute + $minute_supp;
        }

        $heure_supp = 0;
        if ($total_minute > 59) {
            $heure_supp = intval($total_minute / 60);
            $total_minute = $total_minute % 60;
            $total_heure = $total_heure + $heure_supp;
        }

        $jour_supp = 0;
        if ($total_minute > 23) {
            $jour_supp = intval($total_heure / 24);
            $total_heure = $total_heure % 24;
            $total_jour = $total_jour + $jour_supp;
        }

        echo "<span>$total_jour jour(s)</span><br/>";
        echo "<span>$total_heure heure(s)</span><br/>";
        echo "<span>$total_minute minute(s)</span><br/>";
        echo "<span>$total_seconde seconde(s)</span><br/>";
    }

    // Récupération des résultats
    echo '  <table border="1" cellpadding="2" cellspacing="2">
                <tr>
                    <th>Id</th>
                    <th>Début Service</th>
                    <th>Fin Service</th>
                    <th>Jour(s)</th>
                    <th>Heure(s)</th>
                    <th>Minute(s)</th>
                    <th>Seconde(s)</th>
                </tr>';
    while ($row = mysqli_fetch_row($result)) {
        $id                 = $row[0];
        $effectif           = $row[1];
        $debutservice       = $row[2];
        $finservice         = $row[3];
        $total              = $row[4];
        $dernier            = $row[5];
        $heure              = $row[6];
        $minute             = $row[7];
        $seconde            = $row[8];
        $jour            = $row[9];
        if ($finservice === '' || $finservice === null) {
            $finservice = "Non terminé";
        }
        if ($dernier === '1') {
            $jour = "?";
            $heure = "?";
            $minute = "?";
            $seconde = "?";
        }

    echo "<tr>
            <td>$id</td>
            <td>$debutservice</td>
            <td>$finservice</td>
            <td>$jour</td>
            <td>$heure</td>
            <td>$minute</td>
            <td>$seconde</td>
        </tr>";
    }

    //Déconnexion de la base de données
    mysqli_close($connect);
?>
	</body>
</html>