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

    $currentPhoto = $data["profilpicture"];

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
		<title>LSMC - Inscription EMS</title>
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
            .textURLInputStyle {
                width: 90%;
                height: 40px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
                border: solid 2px black;
            }
            .textInputStyleLarge {
                width: 80%;
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
            .profilePictureClass {
                margin: 10px;
                width:  200px;
                height: 200px;
                border-radius: 5px;
                border: solid 2px;
            }
		</style>
        <script language="javascript" type="text/javascript">

		</script>
	</head>
	<body style="width: 100%; text-align: center;">
	<?php

        // Info : Donnée de connexion au serveur PHPMyAdmin
        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Info : Méthode pour MAJ Photo
        if (isset($_POST["button_UpdatePhoto"])) {

            // Info : Récupération des paramètres du formulaire
            $newEMS_profilpicture = htmlspecialchars($_POST['pictureurl']);
            $newEMS_id = $data["id"];

            $sql1    = "UPDATE effectif SET profilpicture='" . $newEMS_profilpicture . "' WHERE id='" . $newEMS_id . "' LIMIT 1";

            echo "SQL = " . $sql1 . "<br/>";

            if ($conn->query($sql1) === TRUE) {
                echo "EDIT Record updated successfully";
            } else {
                echo "EDIT Error updating record: " . $conn->error;
//                header("Location: ./alleffectif.php?successedit=0");
            }
            $conn->close();

            header("Location: ./profilpicture.php?status=success");

        }

        // Info : Méthode pour MAJ Photo
        if (isset($_POST["button_ErasePhoto"])) {

            // Info : Récupération des paramètres du formulaire
            $newEMS_profilpicture = htmlspecialchars("https://i.imgur.com/KsuR9ML.jpg");
            $newEMS_id = $data["id"];

            $sql1    = "UPDATE effectif SET profilpicture='" . $newEMS_profilpicture . "' WHERE id='" . $newEMS_id . "' LIMIT 1";

            echo "SQL = " . $sql1 . "<br/>";

            if ($conn->query($sql1) === TRUE) {
                echo "EDIT Record updated successfully";
            } else {
                echo "EDIT Error updating record: " . $conn->error;
//                header("Location: ./alleffectif.php?successedit=0");
            }
            $conn->close();

            header("Location: ./profilpicture.php?status=success");

        }
    ?>
    <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
    	<tbody>
    		<tr>
    			<td style="width: 33%;">
                    &nbsp;
    			</td>
    			<td style="width: 34%; color: #aec3b0;">
    			    <h1>MA PHOTO DE PROFIL</h1>
    			</td>
    			<td style="width: 33%;">
                    &nbsp;
    			</td>
    		</tr>
    	</tbody>
    </table>

    <table style="width: 100%;">
        <tbody>
            <tr>
                <td style="width: 20%;"></td>
                <td style="width: 60%;">
                    <form method="post" action="./profilpicture.php">

                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #aec3b0; border-radius: 10px;">

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Photo de Profil</h4>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Ma photo de profil actuelle</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php echo "<img class='profilePictureClass' src='" . $currentPhoto . "' width='100px' height='100px'/>" ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Lien URL de la nouvelle photo</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="url" name="pictureurl" class="textURLInputStyle" placeholder="Insérer l'adresse de la nouvelle image ici." required="required" autocomplete="off">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;">
                                            <input type="submit" onmousover="" name="button_UpdatePhoto" value="Mettre à jour ma photo" class="btn btn-success btn-lg" style="width: 300px;"/>
                                        </td>
                                        <td style="width: 50%;">
                                            <a href="./landing.php" class="btn btn-danger btn-lg" style="width: 300px;">Annuler</a>
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

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 60%;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #f5b739; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Rappel</h4>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Vous êtes responsables de la photo que vous publiez sur votre profil. Toutes photos ne respectant pas les règles de l'hôpital peut mener à des sanctions jusqu'au licenciement. Mettez une photo simple pour que vos collègues puissent vous reconnaître.</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">
                                            <table style="width: 100%;">
                                            	<tbody>
                                            		<tr>
                                            			<td style="width: 33%;">
                                            			    <img class='profilePictureClass' src='https://i.imgur.com/ljURGlZ.png' width='200px' height='200px'/><br/>Photo Conforme
                                                        </td>
                                            			<td style="width: 34%;">
                                            			    <img class='profilePictureClass' src='https://i.imgur.com/vfkQE3E.png' width='200px' height='200px'/><br/>Photo Conforme
                                                        </td>
                                            			<td style="width: 33%;">
                                            			    <img class='profilePictureClass' src='https://i.imgur.com/YcYiGbX.png' width='200px' height='200px'/><br/>Photo Conforme
                                                        </td>
                                            		</tr>
                                            		<tr>
                                            			<td style="width: 33%;">
                                                            <img class='profilePictureClass' src='https://i.imgur.com/E6mzBCD.png' width='200px' height='200px'/><br/>Photo Non Conforme
                                                        </td>
                                            			<td style="width: 34%;">
                                                            <img class='profilePictureClass' src='https://i.imgur.com/wsRPfns.png' width='200px' height='200px'/><br/>Photo Non Conforme
                                                        </td>
                                            			<td style="width: 33%;">
                                                            <img class='profilePictureClass' src='https://i.imgur.com/dRRHBdD.png' width='200px' height='200px'/><br/>Photo Non Conforme
                                                        </td>
                                            		</tr>
                                            	</tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="width: 20%;"></td>
                <tr/>
            </tbody>
        </table>

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 60%;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #ff83ed; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Pré-requis</h4>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">- Une photo de vous en taille 100 (verticale) x 100 (horizontale) pixels.</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">- Un compte sur Imgur pour pouvoir héberger votre photo de profil en ligne.<br/>Lien disponible ici : <a href="https://imgur.com/" target="_blank">https://imgur.com/</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="width: 20%;"></td>
                <tr/>
            </tbody>
        </table>

        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 20%;"></td>
                    <td style="width: 60%;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #84e4eb; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Tutoriel</h4>
                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Étape 1 : Sur Imgur, vous allez vous connecter à votre compte.</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Étape 2 : Cliquez sur [+] New Post.</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Étape 3 : Glissez votre photo depuis votre ordinateur.</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Étape 4 : Clique Droit > Choisissez "Copier l'adresse de l'image".</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 100%;">Étape 5 : Collez le lien sur le champ "Lien URL de la nouvelle photo" de la page de changement de photo de profil.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="width: 20%;"></td>
                <tr/>
            </tbody>
        </table>

    </body>
</html>