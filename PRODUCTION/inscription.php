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

    $hasRightToRegister = $data["register"];
    if ($hasRightToRegister <> '1') {
        header("Location: ./deconnexion.php");
        die();
    }

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
            .textInputStyle {
                width: 300px;
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
                    document.getElementById("idSuccessUsername").innerHTML = "Nom d'utilisateur : " + displayUsername;
                    document.getElementById("idSuccessPassword").innerHTML = "Mot de passe : " + displayPassword;
                }
                else {
                    document.getElementById("idSuccessTable").style.display = "none";
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
            $savePassword = $newEMS_password;
            $newEMS_password = password_hash(
                $newEMS_password,
                PASSWORD_BCRYPT,
                $cost
            );

            // On stock l'adresse IP
            $newEMS_ip = $_SERVER["REMOTE_ADDR"];

            $newEMS_grade = $_POST['grade'];
            $tmpEMS_agregation = $_POST['agregation'];
            $tmpEMS_role = $_POST['role'];
            $newEMS_agregation = "";
            $newEMS_role = "";

            $newEMS_deservice = $_POST['deservice'];
            $newEMS_register = $_POST['register'];
            ////
            $newEMS_rank = 0;
            if ($newEMS_grade === "Étudiant")           { $newEMS_rank = 1; }
            else if ($newEMS_grade === "Aide-Soignant") { $newEMS_rank = 2; }
            else if ($newEMS_grade === "Ambulancier")   { $newEMS_rank = 3; }
            else if ($newEMS_grade === "Infirmier")     { $newEMS_rank = 4; }

            else if ($newEMS_grade === "Externe")       { $newEMS_rank = 11; }
            else if ($newEMS_grade === "Interne")       { $newEMS_rank = 12; }
            else if ($newEMS_grade === "Résident")      { $newEMS_rank = 13; }

            else if ($newEMS_grade === "Généraliste")               { $newEMS_rank = 20; }
            else if ($newEMS_grade === "Urgentiste")                { $newEMS_rank = 21; }
            else if ($newEMS_grade === "Médecin-Chef")              { $newEMS_rank = 22; }

            else if ($newEMS_grade === "Chirurgien")                { $newEMS_rank = 23; }
            else if ($newEMS_grade === "Chirurgien Spécialisé")     { $newEMS_rank = 24; }

            else if ($newEMS_grade === "Chef de Service")       { $newEMS_rank = 31; }
            else if ($newEMS_grade === "Directeur de Centre")   { $newEMS_rank = 32; }
            else if ($newEMS_grade === "Directeur-Adjoint")     { $newEMS_rank = 33; }
            else if ($newEMS_grade === "Directeur")             { $newEMS_rank = 34; }

            $newEMS_timemanager = $_POST['timemanager'];
            $newEMS_viewall = $_POST['viewall'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            foreach ($tmpEMS_agregation as $value1) {
              $newEMS_agregation = $newEMS_agregation . "$value1, ";
            }
            $newEMS_agregation = rtrim($newEMS_agregation,', ');

            foreach ($tmpEMS_role as $value2) {
              $newEMS_role = $newEMS_role . "$value2, ";
            }
            $newEMS_role = rtrim($newEMS_role,', ');

            if ($data["undeletable"] === '1') {
                $insert = $bdd->prepare(
                    "INSERT INTO effectif(firstname, lastname, phone, bank, discord, uid, sexe, pseudo, password, grade, agregation, role, ip, token, deservice, register, timemanager, viewall, rank) VALUES(:firstname, :lastname, :phone, :bank, :discord, :uid, :sexe, :pseudo, :password, :grade, :agregation, :role, :ip, :token, :deservice, :register, :timemanager, :viewall, :rank)"
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
                    "timemanager"    => $newEMS_timemanager,
                    "viewall"    => $newEMS_viewall,
                    "rank"    => $newEMS_rank,
                ]);
            }
            else if ($data["undeletable"] === '0') {
                $insert = $bdd->prepare(
                    "INSERT INTO effectif(firstname, lastname, phone, bank, discord, uid, sexe, pseudo, password, grade, agregation, role, ip, token, rank) VALUES(:firstname, :lastname, :phone, :bank, :discord, :uid, :sexe, :pseudo, :password, :grade, :agregation, :role, :ip, :token, :rank)"
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
                    "rank"    => $newEMS_rank,
                ]);
            }

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./landing.php?status=success&username=" . $newEMS_pseudo . "&password=" . $savePassword);

        }

        if (isset($_POST["button_Recreate"])) {

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
            $savePassword = $newEMS_password;
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
            /////
            $newEMS_rank = 0;
            if ($newEMS_grade === "Étudiant")           { $newEMS_rank = 1; }
            else if ($newEMS_grade === "Aide-Soignant") { $newEMS_rank = 2; }
            else if ($newEMS_grade === "Ambulancier")   { $newEMS_rank = 3; }
            else if ($newEMS_grade === "Infirmier")     { $newEMS_rank = 4; }

            else if ($newEMS_grade === "Externe")       { $newEMS_rank = 11; }
            else if ($newEMS_grade === "Interne")       { $newEMS_rank = 12; }
            else if ($newEMS_grade === "Résident")      { $newEMS_rank = 13; }
            else if ($newEMS_grade === "Généraliste")   { $newEMS_rank = 14; }

            else if ($newEMS_grade === "Urgentiste")                { $newEMS_rank = 21; }
            else if ($newEMS_grade === "Médecin-Chef")              { $newEMS_rank = 22; }
            else if ($newEMS_grade === "Chirurgien")                { $newEMS_rank = 23; }
            else if ($newEMS_grade === "Chirurgien Spécialisé")     { $newEMS_rank = 24; }

            else if ($newEMS_grade === "Chef de Service")       { $newEMS_rank = 31; }
            else if ($newEMS_grade === "Directeur de Centre")   { $newEMS_rank = 32; }
            else if ($newEMS_grade === "Directeur-Adjoint")     { $newEMS_rank = 33; }
            else if ($newEMS_grade === "Directeur")             { $newEMS_rank = 34; }

            $newEMS_timemanager = $_POST['timemanager'];
            $newEMS_viewall = $_POST['viewall'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            // foreach ($tmpEMS_agregation as $value1) {
            //   $newEMS_agregation = $newEMS_agregation . "$value1, ";
            // }
            // $newEMS_agregation = rtrim($newEMS_agregation,', ');

            // foreach ($tmpEMS_role as $value2) {
            //   $newEMS_role = $newEMS_role . "$value2, ";
            // }
            // $newEMS_role = rtrim($newEMS_role,', ');

            $insert = $bdd->prepare(
                "INSERT INTO effectif(firstname, lastname, phone, bank, discord, uid, sexe, pseudo, password, grade, agregation, role, ip, token, deservice, register, timemanager, viewall, rank) VALUES(:firstname, :lastname, :phone, :bank, :discord, :uid, :sexe, :pseudo, :password, :grade, :agregation, :role, :ip, :token, :deservice, :register, :timemanager, :viewall, :rank)"
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
                "timemanager"    => $newEMS_timemanager,
                "viewall"    => $newEMS_viewall,
                "rank"    => $newEMS_rank,
            ]);

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./inscription.php?status=success&username=" . $newEMS_pseudo . "&password=" . $savePassword);
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

    <div id="idSuccessTable">
        <table style="width: 100%; color: #01161e;">
            <tbody>
                <tr>
                    <td style="width: 20%;">&nbsp;</td>
                    <td style="width: 60%;">

                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #A4F9C8; border-radius: 10px;">

                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Création du compte avec succès</h4>

                            <table style="width: 100%; text-align: center;">
                                <tbody>
                                    <tr>
                                        <td style="width: 100%;" id="">Site : https://www.lsmc.ovh/</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%;" id="idSuccessUsername"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%;" id="idSuccessPassword"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%;">Pense à changer ton mot de passe</td>
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
                                        <td class="normalTitleStyle">UID Ville</td>
                                        <td class="normalTitleStyle">ID Radio D</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" name="uid" class="textInputStyle" placeholder="UID Ville" required="required" autocomplete="off">
                                        </td>
                                        <td>
                                            <input type="text" name="discord" class="textInputStyle" placeholder="Discord ID" required="required" autocomplete="off">
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
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="color: black; margin-bottom: 10px;">Agréations possibles : MRG, MARU, MTT, ASG, Tech ASG, PSS, GOS, EMT, GSS, MDT</span><br/><br/>
                                            <input type="text" name="agregation" class="textInputStyleLarge" placeholder="Agrégation(s)" value="<?php echo $effectifToModify['agregation']?>" autocomplete="off">
                                    </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Rôle(s)</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <span style="color: black; margin-bottom: 10px;">Rôles possibles : Instructeur Médical, Formateur Médical, Apprenti Formateur, Mentor, Représentant du Personnel, Responsable IT</span><br/><br/>
                                            <input type="text" name="role" class="textInputStyleLarge" placeholder="Rôle(s)" value="<?php echo $effectifToModify['role']?>" autocomplete="off">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if($data["undeletable"] === '1') echo '
                                <h4 class="bold underline" style="padding: 10px; color: #01161e;">Privilège du Personnel</h4>

                                <table style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Fin de Service Forcée</td>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Création et Modification d&#8217;effectif</td>
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
                                        <tr>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Gestion des heures</td>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Vision des heures des effectifs</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="timemanager" class="selectInputStyle" required="required">
                                                    <option value="0" selected>Non</option>
                                                    <option value="1">Oui</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="viewall" class="selectInputStyle" required="required">
                                                    <option value="0" selected>Non</option>
                                                    <option value="1">Oui</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            '?>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 33%;">
                                            <input type="submit" onmousover="" name="button_Create" value="Créer le compte" class="btn btn-success btn-lg" style="width: 300px;"/>
                                        </td>
                                        <td style="width: 34%;">
                                            <input type="submit" onmousover="" name="button_Recreate" value="Sauvegarder et Recréer" class="btn btn-warning btn-lg" style="width: 300px;"/>
                                        </td>
                                        <td style="width: 33%;">
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
    </body>
</html>