<?php
    session_start();
    require_once "./config.php";

    // Info : Si la session existe pas soit si l'on est pas connecté on redirige
    if (!isset($_SESSION["user"])) {
        header("Location: ./index.php");
        die();
    }

    // Info : Récupération données de l'effectif connecté
    $req = $bdd->prepare("SELECT * FROM effectif WHERE token = ?");
    $req->execute([$_SESSION["user"]]);
    $data = $req->fetch();

    // Info : Donnée de connexion au serveur phpmyadmin
    $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
    $username   = "lsmcovptsg";            // database Username
    $password   = "7hahHW582QbK7h";        // database Password
    $dbname     = "lsmcovptsg";            // database Name

    ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
    mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");
    $idEffectifActuel           = $data["id"];
    $prenomEffectifActuel       = $data["firstname"];
    $nomEffectifActuel          = $data["lastname"];
    $hopitalEffectifActuel      = $data["hospital"];
    $gradeEffectifActuel        = $data["grade"];
    $roleEffectifActuel         = $data["role"];
    $agregationsEffectifActuel  = $data["agregation"];
    $telephoneEffectifActuel    = $data["phone"];

    // Info : Reformattage Textuel
    $hopitalEffectifActuel      = strtoupper($hopitalEffectifActuel);
    $prenomEffectifActuel       = ucfirst($prenomEffectifActuel);
    $nomEffectifActuel          = ucfirst($nomEffectifActuel);
    $gradeEffectifActuel        = ucfirst($gradeEffectifActuel);
    $roleEffectifActuel         = ucfirst($roleEffectifActuel);
    $telephoneEffectifActuel    = substr_replace($telephoneEffectifActuel, ' ', 3, 0);
    $telephoneEffectifActuel    = substr_replace($telephoneEffectifActuel, ' ', 6, 0);
    if ($agregationsEffectifActuel === '') { $agregationsEffectifActuel = 'Aucune'; }

    //                                                       0      1          2              3        4      5       6      7         8       9
    $result                 = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour FROM service WHERE effectif=$idEffectifActuel");
    $result_total           = mysqli_query($connect, "SELECT SUM(seconde) AS seconde, SUM(minute) AS minute, SUM(heure) AS heure, SUM(jour) AS jour, dernier FROM service WHERE effectif=$idEffectifActuel AND dernier=0");

    while ($currentService = mysqli_fetch_row($result_total)) {

        $total_jour     = $currentService[3];
        $total_heure    = $currentService[2];
        $total_minute   = $currentService[1];
        $total_seconde  = $currentService[0];

        if (!$total_heure) {
            $total_heure = 0;
        }
        if (!$total_minute) {
            $total_minute = 0;
        }
        if (!$total_seconde) {
            $total_seconde = 0;
        }

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

//         $jour_supp = 0;
//         if ($total_heure > 23) {
//             $jour_supp = intval($total_heure / 24);
//             $total_heure = $total_heure % 24;
//             $total_jour = $total_jour + $jour_supp;
//         }

    }
    $page = $_SERVER['PHP_SELF'];
    $sec = "1";
//    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Prise de Service</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
		<style>
            /* PALETTE COULEURS */
		    /* https://coolors.co/01161e-124559-598392-aec3b0-eff6e0 */
		    body {
		        color: #ffffff;
		        background-color: #01161e;
		    }
		    .selectInputStyle {
                width: 300px;
                height: 40px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
		    }
 		    .code99TitleStyle {
                font-size: 1.5rem;
                font-weight: bold;
                color : #d90368;
 		    }
            .textInputStyle {
                width: 300px;
                height: 40px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
            }
            .statutTitleStyle {
                font-size: 1.5rem;
                font-weight: bold;
                color : #9e6240;
            }
            .vehicleTitleStyle {
                font-size: 1.5rem;
                font-weight: bold;
                color : #dea47e;
            }
            .remarksTitleStyle {
                font-size: 1.5rem;
                font-weight: bold;
                color : #cd4631;
            }
            .rowEffectifHeader {
                background-color: #0F252E;
            }
            .rowEffectifPair {
                background-color: #124559;
            }
            .rowEffectifImpair {
                background-color: #3080A0;
            }
            .titleSummaryStyle {
                font-weight: bold;
            }
		</style>
        <script language="javascript" type="text/javascript">

		    // Info : Gestion d'erreur Prise de Service
			function errorCheck_PriseDeService() {
				var gotStatut
				var gotVehicule
				var gotCommentaire

				gotStatut       = document.forms[0].Statut.value
				gotVehicule     = document.forms[0].Vehicule.value
				gotCommentaire  = document.forms[0].Commentaire.value
				if (gotStatut == "" || gotStatut == null) {
					alert("Merci de choisir un statut valide.")
				}
				if (gotVehicule == "" || gotVehicule == null) {
					alert("Merci de choisir un véhicule valide.")
                }
			}

		    // Info : Gestion d'erreur Mise à Jour
			function errorCheck_MiseAJour() {
				var gotStatut
				var gotVehicule
				var gotCommentaire

				gotStatut       = document.forms[0].Statut.value
				gotVehicule     = document.forms[0].Vehicule.value
				gotCommentaire  = document.forms[0].Commentaire.value
				if (gotStatut == "" || gotStatut == null) {
					alert("Merci de choisir un statut valide.")
				}
				if (gotVehicule == "" || gotVehicule == null) {
					alert("Merci de choisir un véhicule valide.")
                }
			}

		    // Info : Gestion d'erreur Fin de Service
			function errorCheck_FinDeService() {

			}

            // Info : Gestion d'erreur Code 99
            function errorCheck_Code99() {

            }

            $(document).ready(function(){
              refreshTable();
            });

		</script>
	</head>
	<body style="width: 100%; text-align: center;">
	<?php

        // Info : Donnée de connexion au serveur PHPMyAdmin
        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

        // Info : Méthode dès que l'on appuie sur Prise de Service
        if (isset($_POST["button_PriseDeService"])) {

            // Info : Récupération des paramètres du formulaire
            $nouveauStatut      = $_POST['Statut'];
            $nouveauVehicule    = $_POST['Vehicule'];
            $nouveauCommentaire = $_POST['Commentaire'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $idEffectifActuel = $data["id"];
            $serviceEffectifActuel = $data["service"];
            $sql    = "UPDATE effectif SET service='1', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice=now() WHERE id=$idEffectifActuel";
            $sql2   = "INSERT INTO `service` (`id`, `effectif`, `debutservice`, `finservice`, `heure`, `minute`, `dernier`) VALUES (NULL, $idEffectifActuel, NOW(), NULL, 0, 0, '1')";

            if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
                echo "Record updated successfully";
            } else {
                echo "Error updating record: " . $conn->error;
            }
            $conn->close();

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./service.php");
        }

        // Info : Méthode dès que l'on appuie sur Mettre à Jour
        if (isset($_POST["button_MiseAJour"])) {

            // Info : Récupération des paramètres du formulaire
            $nouveauStatut      = $_POST['Statut'];
            $nouveauVehicule    = $_POST['Vehicule'];
            $nouveauCommentaire = $_POST['Commentaire'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
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

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./service.php");
        }

        // Info : Méthode pour envoyer un effectif en Code 99
        if (isset($_POST["button_Code99"])) {

            // Info : Récupération des paramètres du formulaire
            $idEffectifCible    = $_POST['CibleCode99'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $sql = "UPDATE effectif SET service='0' WHERE id=$idEffectifCible";
//             if ($data["deservice"] === 1) {
//
//                 if ($conn->query($sql) === TRUE) {
//                     echo "Record updated successfully";
//                 } else {
//                     echo "Error updating record: " . $conn->error;
//                 }
//                 $conn->close();
//             }
//
//             // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
//             header("Location: ./service.php");

            ///////////////////////

            $nouveauStatutEffectifCode99      = "";
            $nouveauVehiculeEffectifCode99    = "";
            $nouveauCommentaireEffectifCode99 = "";

            $idEffectifActuel = $data["id"];
            $sql3    = "UPDATE effectif SET service='0', intervention='$nouveauStatutEffectifCode99', vehicule='$nouveauVehiculeEffectifCode99', commentaire='$nouveauCommentaireEffectifCode99', debutservice='' WHERE id=$idEffectifCible";

//             $querriedEffectif = mysqli_query($connect, "SELECT id, debutservice FROM effectif WHERE id=$idEffectifCible LIMIT 1");
            /////////////
            $mysqli = new mysqli($servername, $username, $password, $dbname);

            $sql4 = "SELECT id, debutservice FROM effectif WHERE id=$idEffectifCible LIMIT 1";
            $res4 = $mysqli->query($sql4);
            $datetimeToPut = null;
            while($row = $res4->fetch_assoc()){
                $datetimeToPut = $row['debutservice'];
            }
            /////////////

            $start_datetime = new DateTime($datetimeToPut);
            $end_datetime   = new DateTime('NOW');
            $difference     = $start_datetime->diff($end_datetime);
            echo $difference->days.' days total<br>';
            echo $difference->y.' years<br>';
            echo $difference->m.' months<br>';
            echo $difference->d.' days<br>';
            echo $difference->h.' hours<br>';
            echo $difference->i.' minutes<br>';
            echo $difference->s.' seconds<br>';
            $heure = $difference->h;
            $minute = $difference->i;
            $seconde = $difference->s;

            $sql2   = "UPDATE service SET finservice=now(), heure=$heure, minute=$minute, seconde=$seconde, dernier='0' WHERE effectif=$idEffectifCible AND dernier='1' LIMIT 1";

            if ($conn->query($sql) === TRUE && $conn->query($sql3) === TRUE && $conn->query($sql2) === TRUE) {
              echo "Record updated successfully";
            } else {
              echo "Error updating record: " . $conn->error;
            }
            $conn->close();

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./service.php");
        }

        if (isset($_POST["button_FinDeService"])) {

            $nouveauStatut      = "";
            $nouveauVehicule    = "";
            $nouveauCommentaire = "";

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $idEffectifActuel = $data["id"];
            $sql    = "UPDATE effectif SET service='0', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice='' WHERE id=$idEffectifActuel";

            $start_datetime = new DateTime($data["debutservice"]);
            $end_datetime   = new DateTime('NOW');
            $difference     = $start_datetime->diff($end_datetime);
//             echo $difference->days.' days total<br>';
//             echo $difference->y.' years<br>';
//             echo $difference->m.' months<br>';
//             echo $difference->d.' days<br>';
//             echo $difference->h.' hours<br>';
//             echo $difference->i.' minutes<br>';
//             echo $difference->s.' seconds<br>';
            $heure = $difference->h;
            $minute = $difference->i;
            $seconde = $difference->s;

            $sql2   = "UPDATE service SET finservice=now(), heure=$heure, minute=$minute, seconde=$seconde, dernier='0' WHERE effectif=$idEffectifActuel AND dernier='1' LIMIT 1";

            if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
              echo "Record updated successfully";
            } else {
              echo "Error updating record: " . $conn->error;
            }
            $conn->close();

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./service.php");
        }

        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

        ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
        mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");
        //                                                           0      1          2         3        4      5     6         7        8            9            10          11            12
        $result                     = mysqli_query($connect, "SELECT id, hospital, firstname, lastname, grade, role, agregation, phone, intervention, commentaire, vehicule, debutservice, service FROM effectif WHERE service = true");
        $nbrTotalService            = mysqli_num_rows($result);

        // LSMC
        $nbrTotalService_LSMC       = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'lsmc'"));
        $nbrTotalService_LSMC_Code3 = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'lsmc' AND intervention = 'Code 3'"));
        $nbrTotalService_LSMC_Code6 = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'lsmc' AND intervention = 'Code 6'"));

        // BCMC
        $nbrTotalService_BCMC       = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'bcmc'"));
        $nbrTotalService_BCMC_Code3 = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'bcmc' AND intervention = 'Code 3'"));
        $nbrTotalService_BCMC_Code6 = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = true AND hospital = 'bcmc' AND intervention = 'Code 6'"));

        // CODE 99 LISTE
        $EffectifCibleCode99 = null;
        if ($data["hospital"] === 'lsmc') {
            $EffectifCibleCode99        = mysqli_query($connect, "SELECT id, firstname, lastname, intervention, service FROM effectif WHERE service = true AND hospital='lsmc' ");
        }
        if ($data["hospital"] === 'bcmc') {
            $EffectifCibleCode99        = mysqli_query($connect, "SELECT id, firstname, lastname, intervention, service FROM effectif WHERE service = true AND hospital='bcmc' ");
        }
    ?>
    <div id="tableEffectif" style="width: 100%; padding: 0px 25px;">
        <table border="1" cellpadding="10px" cellspacing="10px" style="width: 100%;">
            <tr class="rowEffectifHeader">
                <th style="width: 3%">Id</th>
                <th style="width: 10%">Prénom Nom</th>
                <th style="width: 10%">Grade</th>
                <th style="width: 15%">Rôle(s)</th>
                <th style="width: 15%">Agrégation(s)</th>
                <th style="width: 7%">Téléphone</th>
                <th style="width: 10%">Intervention</th>
                <th style="width: 10%">Commentaire</th>
                <th style="width: 10%">Véhicule</th>
                <th style="width: 10%">Début du service</th>
            </tr>
            <?php
                $rank = 0;
                while ($row = mysqli_fetch_row($result)) {
                    $rowPair = 'rowEffectifPair';
                    $rowImpair = 'rowEffectifImpair';
                    $rowAppliedClass = '';
                    if ($rank % 2 === 0) { // PAIR
                        $rowAppliedClass = $rowPair;
                    }
                    if ($rank % 2 === 1) { // IMPAIR
                        $rowAppliedClass = $rowImpair;
                    }
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

                // Info : Reformattage Textuel
                $hopital = strtoupper($hopital);
                $prenom = ucfirst($prenom);
                $nom = ucfirst($nom);
                $grade = ucfirst($grade);
                $role = ucfirst($role);
                if ($agregations === '') { $agregations = 'Aucune'; }
                $phone = substr_replace($phone, ' ', 3, 0);
                $phone = substr_replace($phone, ' ', 6, 0);
                if ($commentaire === '') { $commentaire = 'Aucun'; }

                $rowYear        = substr($debutservice, 0, 4);
                $rowMonth       = substr($debutservice, 5, 2);
                $rowDay         = substr($debutservice, 8, 2);
                $rowHour        = substr($debutservice, 11, 2);
                $rowMinute      = substr($debutservice, 14, 2);
                $debutservice   = $rowDay.'/'.$rowMonth.'/'.$rowYear.' à '.$rowHour.'h'.$rowMinute;

                echo "
                    <tr class='$rowAppliedClass'>
                        <td>$id</td>
                        <td>$prenom $nom</td>
                        <td>$grade</td>
                        <td>$role</td>
                        <td>$agregations</td>
                        <td>$phone</td>
                        <td>$intervention</td>
                        <td>$commentaire</td>
                        <td>$vehicule</td>
                        <td>$debutservice</td>
                    </tr>
                ";
                $rank += 1;
                }
            ?>
        </div>
	</body>
</html>