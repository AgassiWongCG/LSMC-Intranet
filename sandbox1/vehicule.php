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


?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Déclaration Véhicule HS</title>
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
                border: solid 2px black;
		    }
		    .selectMultipleInputStyle {
                width: 300px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
                border: solid 2px black;
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
                border: solid 2px black;
            }
            .normalTitleStyle {
                font-weight: bold;
                color : #01161e;
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

            window.addEventListener("load", function() {

                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const displayStatus = urlParams.get('status');
                const displayUsername = urlParams.get('username');
                const displayPassword = urlParams.get('password');
                console.log(displayUsername);
                console.log(displayPassword); // idSuccessTable

                if (displayStatus == "success") {
                    document.getElementById("idSuccessTable").style.display = "block";
                    document.getElementById("idReportTable").style.display = "none";
                }
                else {
                    document.getElementById("idSuccessTable").style.display = "none";
                    document.getElementById("idReportTable").style.display = "block";
                }

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

        // Info : Méthode pour créer
        if (isset($_POST["button_Create_Report"])) {

            // Info : Récupération des paramètres du formulaire
            $currentEMS_id = $data["id"];
            $currentEMS_firstname = strtolower($data['firstname']);
            $currentEMS_lastname = strtolower($data['lastname']);
            $currentEMS_vehicle = $_POST['vehicle'];
            $currentEMS_company = $_POST['company'];


            $insert = $bdd->prepare(
                "INSERT INTO outofservicevehicle (effectifid, firstname, lastname, vehicle, company) VALUES (:effectifid, :firstname, :lastname, :vehicle, :company)"
            );
            $insert->execute([
                "effectifid"    => $currentEMS_id,
                "firstname"     => $currentEMS_firstname,
                "lastname"      => $currentEMS_lastname,
                "vehicle"       => $currentEMS_vehicle,
                "company"       => $currentEMS_company,
            ]);

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./vehicule.php?status=success");
        }

    ?>
    <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
    	<tbody>
    		<tr>
    			<td style="width: 25%;">
                    <a href="./service.php" class="btn btn-info btn-lg" style="width: 300px;">Retour Prise de Service</a>
    			</td>
    			<td style="width: 50%; color: #aec3b0;">
    			    <h1>DÉCLARATION DE VÉHICULE HORS-SERVICE</h1>
    			</td>
    			<td style="width: 25%;">
                    &nbsp;
    			</td>
    		</tr>
        </tbody>
    </table>

    <div id="idSuccessTable">
        <table style="width: 100%; color: #01161e;">
            <tbody>
                <tr>
                    <td style="width: 20%;">&nbsp;</td>
                    <td style="width: 60%;">

                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #A4F9C8; border-radius: 10px;">

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Déclaration de véhicule Hors-Service Terminée</h4>

                            <table style="width: 100%; text-align: center;">
                                <tbody>
                                    <tr>
                                        <td style="width: 100%;">
                                            Le LSMC vous remercie de votre déclaration de véhicule hors-service.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </td>
                    <td style="width: 20%;">&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="idReportTable">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 60%;">

                        <form method="post" action="./vehicule.php">

                            <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #aec3b0; border-radius: 10px;">

                                <h4 class="bold underline" style="padding: 10px; color: #01161e;">Fiche de déclaration</h4>

                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td class="normalTitleStyle" style="width: 50%;">Véhicule hors-service</td>
                                            <td class="normalTitleStyle" style="width: 50%;">Compagnie de réparation</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="vehicle" class="selectInputStyle" required="required">
                                                    <option value="Ambulance 1" selected>Ambulance 1</option>
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
                                            <td>
                                                <select name="company" class="selectInputStyle" required="required">
                                                    <option value="Los Santos Custom" selected>Los Santos Custom</option>
                                                    <option value="Benny's">Benny's</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%;">
                                                <input type="submit" onmousover="" name="button_Create_Report" value="Valider la déclaration" class="btn btn-success btn-lg" style="width: 300px;"/>
                                            </td>
                                            <td style="width: 50%;">
                                                <a href="./service.php" class="btn btn-danger btn-lg" style="width: 300px;">Annuler</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </form>
                    </td>
                    <td style="width: 20%;"></td>
                </tbody>
            </table>
        </div>
    </body>
</html>