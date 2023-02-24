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
    //                                                       0      1          2              3        4      5       6      7         8
    $result                 = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde FROM service WHERE effectif=$idEffectifActuel");

    // Récupération des résultats
    echo '  <table border="1" cellpadding="2" cellspacing="2">
                <tr>
                    <th>Id</th>
                    <th>Effectif</th>
                    <th>Début Service</th>
                    <th>Fin Service</th>
                    <th>Total</th>
                    <th>Heure(s)</th>
                    <th>Minute(s)</th>
                    <th>Seconde(s)</th>
                    <th>Dernier ?</th>
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

    echo "<tr>
            <td>$id</td>
            <td>$effectif</td>
            <td>$debutservice</td>
            <td>$finservice</td>
            <td>$total</td>
            <td>$heure</td>
            <td>$minute</td>
            <td>$seconde</td>
            <td>$dernier</td>
        </tr>";
    }

    //Déconnexion de la base de données
    mysqli_close($connect);
?>
        <a href="landing.php" class="btn btn-danger btn-lg">Retour Profil</a>
	</body>
</html>