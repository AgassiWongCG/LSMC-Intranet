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
    $result_total           = mysqli_query($connect, "SELECT SUM(seconde) AS seconde, SUM(minute) AS minute, SUM(heure) AS heure, SUM(jour) AS jour, dernier FROM service WHERE effectif=$idEffectifActuel AND dernier=0 AND past=0");

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
    header("refresh: 30");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Prise de Service</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
        <script src="http://code.jquery.com/jquery-latest.js"></script>
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

                $sqlPDS       = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = '0' AND id=$idEffectifActuel"));
                $sql2PDS      = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM service WHERE dernier = '1' AND effectif=$idEffectifActuel"));

                $prenomEffectifActuel       = $data["firstname"];
                $nomEffectifActuel          = $data["lastname"];

                echo "PRENOM = " . $prenomEffectifActuel . "<br/>";
                echo "NOM = " . $nomEffectifActuel . "<br/>";

                $sql    = "UPDATE effectif SET service='1', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice=now() WHERE id=$idEffectifActuel";
                $sql2   = "INSERT INTO `service` (`id`, `effectif`, `firstname`, `lastname`, `debutservice`, `finservice`, `heure`, `minute`, `dernier`, `status`) VALUES (NULL, $idEffectifActuel, '$prenomEffectifActuel', '$nomEffectifActuel', NOW(), NULL, 0, 0, '1', '$nouveauStatut')";

                echo "sqlPDS = " . $sqlPDS . "<br/>";
                echo "sql2PDS = " . $sql2PDS . "<br/>";

                if ($sqlPDS === 1 && $sql2PDS === 0) {
                    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
                        echo "button_PriseDeService Record updated successfully";
                    } else {
                        echo "button_PriseDeService Error updating record: " . $conn->error;
                    }
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

                $sqlMAJ       = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = '1' AND id=$idEffectifActuel"));


                $idEffectifActuel = $data["id"];
                $sql    = "UPDATE effectif SET intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice=now() WHERE id=$idEffectifActuel";

                $mysqli = new mysqli($servername, $username, $password, $dbname);
                $sql4 = "SELECT id, debutservice FROM effectif WHERE id=$idEffectifActuel LIMIT 1";
                $res4 = $mysqli->query($sql4);
                $datetimeToPut = null;
                while($row = $res4->fetch_assoc()){
                    $datetimeToPut = $row['debutservice'];
                }
                $start_datetime = new DateTime($datetimeToPut);
                $end_datetime   = new DateTime('NOW');
                $difference     = $start_datetime->diff($end_datetime);
                $heure = $difference->h;
                $minute = $difference->i;
                $seconde = $difference->s;

                $sql1   = "UPDATE service SET finservice=now(), heure=$heure, minute=$minute, seconde=$seconde, dernier='0' WHERE effectif=$idEffectifActuel AND dernier='1' LIMIT 1";
                $sql2   = "INSERT INTO `service` (`id`, `effectif`, `firstname`, `lastname`, `debutservice`, `finservice`, `heure`, `minute`, `dernier`, `status`) VALUES (NULL, $idEffectifActuel, '$prenomEffectifActuel', '$nomEffectifActuel', NOW(), NULL, 0, 0, '1', '$nouveauStatut')";

                echo "sqlMAJ = " . $sqlMAJ . "<br/>";

                if ($sqlMAJ === 1) {
                    if ($conn->query($sql) === TRUE && $conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
                        echo "button_MiseAJour Record updated successfully";
                    } else {
                        echo "button_MiseAJour Error updating record: " . $conn->error;
                    }
                }
                $conn->close();

                // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
                header("Location: ./service.php");
            }

            // Info : Méthode pour envoyer un effectif en Code 99
            if (isset($_POST["button_Code99"])) {

                $hasRightToDeservice = $data["deservice"];
                if ($hasRightToDeservice <> '1') {
                    header("Location: ./deconnexion.php");
                    die();
                }

                // Info : Récupération des paramètres du formulaire
                $idEffectifCible    = $_POST['CibleCode99'];

                // Info : Création de la connexion + Vérification de la connexion
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }

                $nouveauStatutEffectifCode99      = "";
                $nouveauVehiculeEffectifCode99    = "";
                $nouveauCommentaireEffectifCode99 = "";


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

                $idEffectifActuel = $data["id"];

                $sql199         = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = '1' AND id=$idEffectifCible"));
                $sql299         = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM service WHERE dernier = '1' AND effectif=$idEffectifCible"));

                echo "sql199 = " . $sql199 . "<br/>";
                echo "sql299 = " . $sql299 . "<br/>";

                $sql3   = "UPDATE effectif SET service='0', intervention='$nouveauStatutEffectifCode99', vehicule='$nouveauVehiculeEffectifCode99', commentaire='$nouveauCommentaireEffectifCode99', debutservice='' WHERE id=$idEffectifCible";
                $sql2   = "UPDATE service SET finservice=now(), heure=$heure, minute=$minute, seconde=$seconde, dernier='0' WHERE effectif=$idEffectifCible AND dernier='1' LIMIT 1";

                if ($sql199 === 1 && $sql299 === 1) {
                    if ($conn->query($sql3) === TRUE && $conn->query($sql2) === TRUE) {
                      echo "button_Code99 Record updated successfully";
                    } else {
                      echo "button_Code99 Error updating record: " . $conn->error;
                    }
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

                $idEffectifActuel = $data["id"];
                $sqlFDS     = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM effectif WHERE service = '1' AND id=$idEffectifActuel"));
                $sql2FDS    = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM service WHERE dernier = '1' AND effectif=$idEffectifActuel"));

                echo "sqlFDS = " . $sqlFDS . "<br/>";
                echo "sql2FDS = " . $sql2FDS . "<br/>";

                $sql    = "UPDATE effectif SET service='0', intervention='$nouveauStatut', vehicule='$nouveauVehicule', commentaire='$nouveauCommentaire', debutservice='' WHERE id=$idEffectifActuel";
                $sql2   = "UPDATE service SET finservice=now(), heure=$heure, minute=$minute, seconde=$seconde, dernier='0' WHERE effectif=$idEffectifActuel AND dernier='1' LIMIT 1";

                if ($sqlFDS === 1 && $sql2FDS === 1) {
                    if ($conn->query($sql) === TRUE && $conn->query($sql2) === TRUE) {
                      echo "button_FinDeService Record updated successfully";
                    } else {
                      echo "button_FinDeService Error updating record: " . $conn->error;
                    }
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
            $result                     = mysqli_query($connect, "SELECT id, hospital, firstname, lastname, grade, role, agregation, phone, intervention, commentaire, vehicule, debutservice, service FROM effectif WHERE service = true ORDER BY rank DESC");
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
        <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
            <tbody>
                <tr>
                    <td style="width: 33%;">
                        <a href="./landing.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 200px;">Retour Profil</a>
                        <a href="./historique.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 200px;">Mes Heures</a>
                    </td>
                    <td style="width: 34%; color: #aec3b0;">
                        <h1>PRISE DE SERVICE</h1>
                        <!-- <h1>cService = <?php echo $data["service"];?> - cDeservice = <?php echo $data["deservice"];?></h1> -->
                    </td>
                    <td style="width: 33%;">
                        <a href="./alleffectif.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 200px;">Toutes les Effectifs</a>
                        <?php if($data["viewall"] === '1') echo '
                            <a href="./alltime.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 200px;">Toutes les Heures</a>
                        '?>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 33%; vertical-align: top;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #aec3b0; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Fiche Récapitulative Hebdomadaire</h4>
                            <table style="width: 100%; color: #01161e;">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;" class="titleSummaryStyle">Prénom :</td>
                                        <td style="width: 50%;"><?php echo $prenomEffectifActuel;?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Nom :</td>
                                        <td><?php echo $nomEffectifActuel;?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Grade :</td>
                                        <td><?php echo $gradeEffectifActuel;?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Rôle(s) :</td>
                                        <td><?php echo $roleEffectifActuel;?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Agrégations :</td>
                                        <td><?php echo $agregationsEffectifActuel;?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Téléphone :</td>
                                        <td><?php echo $telephoneEffectifActuel;?></td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Heure(s) Totale(s) :</td>
                                        <td><?php echo $total_heure?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Minute(s) Totale(s) :</td>
                                        <td><?php echo $total_minute?></td>
                                    </tr>
                                    <tr>
                                        <td class="titleSummaryStyle">Seconde(s) Totale(s) :</td>
                                        <td><?php echo $total_seconde?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="./service.php" class="btn btn-info btn-lg" style="margin: 10px 10px; width: 200px;">Rafraîchir la page</a>
                    </td>
                    <td style="width: 34%;">
                        <form method="post" action="./service.php" style="width: 100%; text-align: center;">
                            <table style="width: 100%; text-align: center;">
                                <tbody>
                                    <tr>
                                        <td class="statutTitleStyle">
                                            Statut
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="Statut" class="selectInputStyle">
                                                <option value="Code 3">Code 3</option>
                                                <option selected="selected" value="Code 6">Code 6</option>
                                                <option value="Code 7">Code 7</option>
                                                <option value="Code 10">Code 10</option>
                                                <option value="Code 15">Code 15</option>
                                                <option value="Morgue">Morgue</option>
                                                <option value="Fusillade">Fusillade</option>
                                                <option value="Consultation">Consultation</option>
                                                <option value="Entretien">Entretien</option>
                                                <option value="Formation">Formation</option>
                                                <option value="Examen">Examen</option>
                                                <option value="Rendez-vous">Rendez-vous</option>
                                                <option value="Évênement">Évênement</option>
                                                <option value="Réunion">Réunion</option>
                                                <option value="Gestion Direction">Gestion Direction</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vehicleTitleStyle">
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vehicleTitleStyle">
                                            Véhicule utilisé
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="Vehicule" class="selectInputStyle">
                                                <option selected="selected" value="Aucun">Aucun</option>
                                                <option value="Ambulance 1">Ambulance 1</option>
                                                <option value="Ambulance 2">Ambulance 2</option>
                                                <option value="Vapid">Vapid</option>
                                                <option value="Caracara">Caracara</option>
                                                <option value="Alamo">Alamo</option>
                                                <option value="Predator">Predator</option>
                                                <option value="Buffalo Direction">Buffalo Direction</option>
                                                <option value="Hélicoptère">Hélicoptère</option>
                                                <option value="Corbillard">Corbillard</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="vehicleTitleStyle">
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="remarksTitleStyle">
                                            Unité | Remarques
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="Commentaire" class="textInputStyle"/>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <?php if($data["service"] === '0') echo '
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 100%; padding: 20px;">
                                            <input type="submit" onmousover="errorCheck_PriseDeService()" name="button_PriseDeService" value="Début de Service" class="btn btn-success btn-lg"/>
                                        </td>
                                    </tr>
                                </table>
                            '?>
                            <?php if($data["service"] === '1') echo '
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="width: 50%; text-align: right; padding: 20px;">
                                            <input type="submit" onmousover="errorCheck_MiseAJour()" name="button_MiseAJour" value="Mettre à jour" class="btn btn-warning btn-lg"/>
                                        </td>
                                        <td style="width: 50%; text-align: left; padding: 20px;">
                                            <input type="submit" onmousover="errorCheck_FinDeService()" name="button_FinDeService" value="Fin de service" class="btn btn-danger btn-lg"/>
                                        </td>
                                    </tr>
                                </table>
                            '?>
                        </form>
                    </td>
                    <td style="width: 33%; vertical-align: top;">

                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #dcc1e9; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Hôpital Los Santos Medical Center</h4>
                            <table style="width: 100%; color: #01161e;">
                                <tbody>
                                    <tr>
                                        <td class="bold" style="width: 33%;">En Service</td>
                                        <td class="bold" style="width: 33%;">Code 3</td>
                                        <td class="bold" style="width: 33%;">Code 6</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $nbrTotalService_LSMC ?></td>
                                        <td><?php echo $nbrTotalService_LSMC_Code3 ?></td>
                                        <td><?php echo $nbrTotalService_LSMC_Code6 ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <?php if($data["deservice"] === '1') echo '
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #ff8484; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #c82333;">Gestion Rapide Direction</h4>

                                <form method="post" action="./service.php" style="width: 100%; text-align: center;">
                                    <table style="width: 100%; text-align: center;">
                                        <tbody>
                                            <tr>
                                                <td class="code99TitleStyle">
                                                    Effectif ciblé
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select name="CibleCode99" class="selectInputStyle" style="width: 90% !important;">
                                                        <option selected="selected" value="Aucun">Aucun</option>'?>
                                                        <?php
                                                            while ($rowEffectifCode99 = mysqli_fetch_row($EffectifCibleCode99)) {
                                                                // id, firstname, lastname, intervention, service
                                                                $effectifCode99id                 = $rowEffectifCode99[0];
                                                                $effectifCode99firstname          = ucfirst($rowEffectifCode99[1]);
                                                                $effectifCode99lastname           = ucfirst($rowEffectifCode99[2]);
                                                                $effectifCode99intervention       = ucfirst($rowEffectifCode99[3]);
                                                                $effectifCode99service            = ucfirst($rowEffectifCode99[4]);

                                                                // Info : Reformattage Textuel
                                                                $fullEffectifLine   = $effectifCode99firstname." ".$effectifCode99lastname." - ".$effectifCode99intervention;
                                                                if($data["deservice"] === '1' && $data["id"] <> $effectifCode99id)
                                                                {
                                                                    echo "<option value='$effectifCode99id'>$fullEffectifLine</option>";
                                                                }
                                                            }
                                                        ?>
                                                    <?php if($data["deservice"] === '1') echo '
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <input type="submit" onmousover="errorCheck_Code99()" name="button_Code99" value="Fin de service forcée" class="btn btn-secondary btn-lg"/>
                                </form>'?>

                        </div>

                    </td>
                </tr>
            </tbody>
        </table>

        <br/>

    <div id="tableEffectif" style="width: 100%; padding: 0px 25px;">
        <table border="1" cellpadding="10px" cellspacing="10px" style="width: 100%;">
            <tr class="rowEffectifHeader">
                <th style="width: 13%">Prénom Nom</th>
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
        </table>
    </div>
    <?php
        //Déconnexion de la base de données
        mysqli_close($connect);
    ?>
	</body>
</html>