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

    if (!$data["register"] === 1) {
        header("Location: ./index.php");
        die();
    }

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
        if (isset($_POST["button_Create"])) {

            // Info : Récupération des paramètres du formulaire
            $newEMS_firstname = strtolower($_POST['firstname']);
            $newEMS_lastname = strtolower($_POST['lastname']);
            $newEMS_phone = $_POST['phone'];
            $newEMS_bank = $_POST['bank'];
            $newEMS_discord = $_POST['discord'];
            
            $newEMS_uid = $_POST['uid'];
            $newEMS_sexe = $_POST['sexe'];
//            $newEMS_dateentreehopital = new Date('NOW');
            $newEMS_pseudo = htmlspecialchars($newEMS_firstname . $newEMS_lastname);
            $newEMS_password = htmlspecialchars($newEMS_uid . $newEMS_bank);

            // On hash le mot de passe avec Bcrypt, via un coût de 12
            $cost = ["cost" => 12];
            $newEMS_password = password_hash(
                $newEMS_password,
                PASSWORD_BCRYPT,
                $cost
            );

            // On stock l'adresse IP
            $newEMS_ip = $_SERVER["REMOTE_ADDR"];

            $newEMS_grade = $_POST['grade'];
            $newEMS_agregation = $_POST['agregation'];
            $newEMS_role = $_POST['role'];

            $newEMS_deservice = $_POST['deservice'];
            $newEMS_register = $_POST['register'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            echo "EMS_firstname: " . $newEMS_firstname . "<br/>";
            echo "EMS_lastname: " . $newEMS_lastname . "<br/>";
            echo "EMS_phone: " . $newEMS_phone . "<br/>";
            echo "EMS_bank: " . $newEMS_bank . "<br/>";
            echo "EMS_discord: " . $newEMS_discord . "<br/>";
            echo "EMS_uid: " . $newEMS_uid . "<br/>";
            echo "EMS_sexe: " . $newEMS_sexe . "<br/>";
            echo "EMS_pseudo: " . $newEMS_pseudo . "<br/>";
            echo "EMS_password: " . $newEMS_password . "<br/>";
            echo "EMS_grade: " . $newEMS_grade . "<br/>";
            echo "EMS_agregation: " . $newEMS_agregation . "<br/>";
            echo "EMS_role: " . $newEMS_role . "<br/>";
            echo "EMS_deservice: " . $newEMS_deservice . "<br/>";
            echo "EMS_register: " . $newEMS_register . "<br/>";

            $sql   = "INSERT INTO `effectif` (`id`, `firstname`, `lastname`, `phone`, `bank`, `discord`, `uid`, `sexe`, `pseudo`, `password`, `grade`) VALUES (NULL, $newEMS_firstname, $newEMS_lastname, $newEMS_phone, $newEMS_bank, $newEMS_discord, $newEMS_uid, $newEMS_sexe, $newEMS_pseudo, $newEMS_password, $newEMS_grade)";

            $insert = $bdd->prepare(
//                "INSERT INTO effectif(firstname, lastname, phone, bank, discord, uid, sexe, pseudo, password, grade) VALUES(:firstname, :lastname, :phone, :bank, :discord, :uid, :sexe, :pseudo, :password, :grade)"
                "INSERT INTO effectif(firstname, lastname, phone, bank, discord, uid, sexe, pseudo, password, grade, agregation, role, ip, token, deservice, register) VALUES(:firstname, :lastname, :phone, :bank, :discord, :uid, :sexe, :pseudo, :password, :grade, :agregation, :role, :ip, :token, :deservice, :register)"
            );
            $insert->execute([
                "firstname" => $newEMS_firstname,
                "lastname" => $newEMS_lastname,
                "phone" => $newEMS_phone,
                "bank" => $newEMS_bank,
                "discord" => $newEMS_discord,
                "uid" => $newEMS_uid,
                "sexe" => $newEMS_sexe,
                "pseudo" => $newEMS_pseudo,
                "password" => $newEMS_password,
                "grade" => $newEMS_grade,
                "agregation" => $newEMS_agregation,
                "role" => $newEMS_role,
                "ip"    => $newEMS_ip,
                "token" => bin2hex(openssl_random_pseudo_bytes(64)),
                "deservice"    => $newEMS_deservice,
                "register"    => $newEMS_register,
            ]);

//            if ($conn->query($sql) === TRUE) {
//                echo "Record inserted successfully";
//                header("Location: ./landing.php");
//                die();
//            } else {
//                echo "Error inserting record: " . $conn->error;
//            }
//            $conn->close();

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire

        }

        if (isset($_POST["button_Recreate"])) {

        }

    ?>
    <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
    	<tbody>
    		<tr>
    			<td style="width: 33%;">
                    &nbsp;
    			</td>
    			<td style="width: 34%; color: #aec3b0;">
    			    <h1>INSCRIPTION DU PERSONNEL DE LSMC</h1>
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
                    <form method="post" action="./inscription.php">

                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #aec3b0; border-radius: 10px;">

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Données du Personnel</h4>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 50%;">Prénom</td>
                                        <td class="normalTitleStyle" style="width: 50%;">Nom</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="firstname" class="textInputStyle" placeholder="Prénom" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" name="lastname" class="textInputStyle" placeholder="Nom" required="required" autocomplete="off">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Téléphone</td>
                                        <td class="normalTitleStyle">Compte Bancaire</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" name="phone" class="textInputStyle" placeholder="Téléphone" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="number" name="bank" class="textInputStyle" placeholder="Compte Bancaire" required="required" autocomplete="off">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">ID Radio D</td>
                                        <td class="normalTitleStyle">UID Ville</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="discord" class="textInputStyle" placeholder="Discord ID" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="number" name="uid" class="textInputStyle" placeholder="UID Ville" required="required" autocomplete="off">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Sexe</td>
                                        <td class="normalTitleStyle">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="sexe" class="selectInputStyle" required="required">
                                                <option value="H" selected>Homme</option>
                                                <option value="F">Femme</option>
                                            </select>
                                        </td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Données Professionnelles</h4>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle">Grade</td>
                                        <td class="normalTitleStyle">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="grade" class="selectInputStyle" required="required">
                                                <option value='Étudiant' selected>Étudiant</option>
                                                <option value='Aide-Soignant'>Aide-Soignant</option>
                                                <option value='Ambulancier'>Ambulancier</option>
                                                <option value='Infirmier'>Infirmier</option>
                                                <option value='Externe'>Externe</option>
                                                <option value='Interne'>Interne</option>
                                                <option value='Résident'>Résident</option>
                                                <option value='Généraliste'>Généraliste</option>
                                                <option value='Urgentiste'>Urgentiste</option>
                                                <option value='Médecin-Chef'>Médecin-Chef</option>
                                                <option value='Chirurgien'>Chirurgien</option>
                                                <option value='Chirurgien Spécialisé'>Chirurgien Spécialisé</option>
                                                <option value='Chef de Service'>Chef de Service</option>
                                                <option value='Directeur de Centre'>Directeur de Centre</option>
                                                <option value='Directeur-Adjoint'>Directeur-Adjoint</option>
                                                <option value='Directeur'>Directeur</option>
                                            </select>
                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Agrégation(s)</td>
                                        <td class="normalTitleStyle">Rôle(s)</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="agregation" class="selectMultipleInputStyle" required="required" size="9" multiple>
                                                <option value='MRG'>M.R.G.</option>
                                                <option value='MARU'>M.A.R.U.</option>
                                                <option value='MTT'>M.T.T.</option>
                                                <option value='ASG'>A.S.G.</option>
                                                <option value='Tech ASG'>Tech A.S.G.</option>
                                                <option value='PSS'>P.S.S.</option>
                                                <option value='GOS'>G.O.S.</option>
                                                <option value='EMT'>E.M.T.</option>
                                                <option value='GSS'>G.S.S.</option>
                                            </select>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <select name="role" class="selectMultipleInputStyle" required="required" multiple>
                                                <option value='Instructeur Médical'>Instructeur Médical</option>
                                                <option value='Formateur Médical'>Formateur Médical</option>
                                                <option value='Apprenti Formateur'>Apprenti Formateur</option>
                                                <option value='Mentor'>Mentor</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Privilège du Personnel</h4>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td class="normalTitleStyle" style="width: 50%;">Droit - Fin de Service Forcée</td>
                                        <td class="normalTitleStyle" style="width: 50%;">Droit - Création de Compte</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="deservice" class="selectInputStyle" required="required">
                                                <option value="0" selected>Non</option>
                                                <option value="1">Oui</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="register" class="selectInputStyle" required="required">
                                                <option value="0" selected>Non</option>
                                                <option value="1">Oui</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 33%;">
                                            <input type="submit" onmousover="" name="button_Create" value="Créer le compte" class="btn btn-success btn-lg" style="width: 300px;"/>
                                            </td>
                                        <td style="width: 34%;">
                                            <button type="submit" class="btn btn-primary btn-lg" style="width: 300px;">Sauvegarder et recréer</button>
                                        </td>
                                        <td style="width: 33%;">
                                            <a href="./landing.php" class="btn btn-info btn-lg" style="width: 300px;">Annuler</a>
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
    </body>
</html>