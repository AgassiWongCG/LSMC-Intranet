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

    $currentURL = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $url_components = parse_url($currentURL);
    parse_str($url_components['query'], $params);
    $effectifId = $params['effectifId'];

    $reqEffectif = $bdd->prepare("SELECT * FROM effectif WHERE id=$effectifId");
    $reqEffectif->execute([$_SESSION["user"]]);
    $effectifToModify = $reqEffectif->fetch();
    $rightEffectifDeservice     = $effectifToModify["deservice"];
    $rightEffectifRegister      = $effectifToModify["register"];
    $rightEffectifTimeManager   = $effectifToModify["timemanager"];
    $rightEffectifViewAll       = $effectifToModify["viewall"];

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
            window.addEventListener("load", function() {

                const url = getCurrentURL();

                if (url == 'https://www.lsmc.ovh/modification.php') {
                    document.location.href="https://www.lsmc.ovh/alleffectif.php?successedit=1";
                }

                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const displayEffectifId = urlParams.get('effectifId');
                const displayAgregation = urlParams.get('agregation');
                const displayRole       = urlParams.get('role');
                console.log('>' + displayAgregation + '<');
                console.log('>' + displayRole + '<');

                const displayDeservice      = urlParams.get('deservice');
                const displayRegister       = urlParams.get('register');
                const displayTimeManager    = urlParams.get('timemanager');
                const displayViewAll        = urlParams.get('viewall');

                document.getElementById("idInputEffectif").value = displayEffectifId;

                if (displayAgregation.includes("MRG")) { document.getElementById("MRG").selected = true; }
                if (displayAgregation.includes("MARU")) { document.getElementById("MARU").selected = true; }
                if (displayAgregation.includes("MTT")) { document.getElementById("MTT").selected = true; }
                if (displayAgregation.includes("ASG")) { document.getElementById("ASG").selected = true; }
                if (displayAgregation.includes("Tech ASG")) { document.getElementById("Tech ASG").selected = true; }
                if (displayAgregation.includes("PSS")) { document.getElementById("PSS").selected = true; }
                if (displayAgregation.includes("GOS")) { document.getElementById("GOS").selected = true; }
                if (displayAgregation.includes("EMT")) { document.getElementById("EMT").selected = true; }
                if (displayAgregation.includes("GSS")) { document.getElementById("GSS").selected = true; }

                if (displayRole.includes("Instructeur Médical")) { document.getElementById("Instructeur Médical").selected = true; }
                if (displayRole.includes("Formateur Médical")) { document.getElementById("Formateur Médical").selected = true; }
                if (displayRole.includes("Apprenti Formateur")) { document.getElementById("Apprenti Formateur").selected = true; }
                if (displayRole.includes("Mentor")) { document.getElementById("Mentor").selected = true; }

                if (displayDeservice == 0) { document.getElementById("deservicenon").selected = true; document.getElementById("deserviceoui").selected = false; }
                if (displayDeservice == 1) { document.getElementById("deservicenon").selected = false; document.getElementById("deserviceoui").selected = true; }

                if (displayRegister == 0) { document.getElementById("registernon").selected = true; document.getElementById("registeroui").selected = false; }
                if (displayRegister == 1) { document.getElementById("registernon").selected = false; document.getElementById("registeroui").selected = true; }

                if (displayTimeManager == 0) { document.getElementById("timemanagernon").selected = true; document.getElementById("timemanageroui").selected = false; }
                if (displayTimeManager == 1) { document.getElementById("timemanagernon").selected = false; document.getElementById("timemanageroui").selected = true; }

                if (displayViewAll == 0) { document.getElementById("viewallnon").selected = true; document.getElementById("viewalloui").selected = false; }
                if (displayViewAll == 1) { document.getElementById("viewallnon").selected = false; document.getElementById("viewalloui").selected = true; }

            });

            function getCurrentURL () {
                return window.location.href;
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

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST["button_Modifier"])) {

            $newEMS_id = $_POST['idInputEffectif'];
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

            foreach ($tmpEMS_agregation as $value1) {
                $newEMS_agregation = $newEMS_agregation . "$value1, ";
            }
            $newEMS_agregation = rtrim($newEMS_agregation,', ');

            foreach ($tmpEMS_role as $value2) {
                $newEMS_role = $newEMS_role . "$value2, ";
            }
            $newEMS_role = rtrim($newEMS_role,', ');

            $newEMS_id = strval( $newEMS_id );
            echo "ID TARGET = " . $newEMS_id . "<br/>";

//            if ($data["undeletable"] === '0') {

                $sql1    = "UPDATE effectif SET grade='" . $newEMS_grade . "', agregation='" . $newEMS_agregation . "', role='" . $newEMS_role . "', rank='" . $newEMS_rank . "' WHERE id='" . strval( $newEMS_id )  . "' LIMIT 1";

                echo "SQL = " . $sql1 . "<br/>";

                if ($conn->query($sql1) === TRUE) {
                    echo "EDIT Record updated successfully";
                } else {
                    echo "EDIT Error updating record: " . $conn->error;
                    header("Location: ./alleffectif.php?successedit=0");
                }
                $conn->close();

                header("Location: ./alleffectif.php?successedit=1");
//            }
//            else if ($data["undeletable"] === '1') {
//
//                $sql2    = "UPDATE effectif SET grade='" . $newEMS_grade . "', agregation='" . $newEMS_agregation . "', role='" . $newEMS_role . "', rank='" . $newEMS_rank . ", deservice=" . strval($newEMS_deservice) . ", register=" . strval($newEMS_register) . ", timemanager=" . strval($newEMS_timemanager) . ", viewall=" . strval($newEMS_viewall) . " WHERE id='" . strval( $newEMS_id )  . "' LIMIT 1";
//
//                echo "SQL = " . $sql2 . "<br/>";
//
//                if ($conn->query($sql2) === TRUE) {
//                    echo "EDIT Record updated successfully";
//                } else {
//                    echo "EDIT Error updating record: " . $conn->error;
//                    header("Location: ./alleffectif.php?successedit=0");
//                }
//                $conn->close();
//
//                header("Location: ./alleffectif.php?successedit=1");
//            }
        }


    ?>
    <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
    	<tbody>
    		<tr>
    			<td style="width: 33%;">
                    &nbsp;
    			</td>
    			<td style="width: 34%; color: #aec3b0;">
    			    <h1>MODIFICATION DE PERSONNEL</h1>
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
                    <form method="post" action="./modification.php">

                        <input type="hidden" id="idInputEffectif" name="idInputEffectif" value="">

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
                                            <input type="text" name="firstname" class="textInputStyle" placeholder="Prénom" value="<?php echo $effectifToModify['firstname']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="lastname" class="textInputStyle" placeholder="Nom" value="<?php echo $effectifToModify['lastname']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Téléphone</td>
                                        <td class="normalTitleStyle">Compte Bancaire</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" name="phone" class="textInputStyle" placeholder="Téléphone" value="<?php echo $effectifToModify['phone']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                        <td>
                                            <input type="number" name="bank" class="textInputStyle" placeholder="Compte Bancaire" value="<?php echo $effectifToModify['bank']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">UID Ville</td>
                                        <td class="normalTitleStyle">ID Radio D</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="number" name="uid" class="textInputStyle" placeholder="UID Ville" value="<?php echo $effectifToModify['uid']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="discord" class="textInputStyle" placeholder="Discord ID" value="<?php echo $effectifToModify['discord']?>" required="required" autocomplete="off" disabled>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="normalTitleStyle">Sexe</td>
                                        <td class="normalTitleStyle">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php if($effectifToModify["sexe"] === 'H') echo '<select name="sexe" class="selectInputStyle" required="required" disabled><option value="H" selected>Homme</option><option value="F">Femme</option></select>'?>
                                            <?php if($effectifToModify["sexe"] === 'F') echo '<select name="sexe" class="selectInputStyle" required="required" disabled><option value="H">Homme</option><option value="F" selected>Femme</option></select>'?>
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
                                                <?php if($effectifToModify["grade"] === 'Étudiant')                 echo '<option value="Étudiant" selected>Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Aide-Soignant')            echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant" selected>Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Ambulancier')              echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier" selected>Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Infirmier')                echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier" selected>Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Externe')                  echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe" selected>Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Interne')                  echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne" selected>Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Résident')                 echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident" selected>Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Généraliste')              echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste" selected>Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Urgentiste')               echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste" selected>Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Médecin-Chef')             echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef" selected>Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Chirurgien')               echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien" selected>Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Chirurgien Spécialisé')    echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé" selected>Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Chef de Service')          echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service" selected>Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Directeur de Centre')      echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre" selected>Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Directeur-Adjoint')        echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint" selected>Directeur-Adjoint</option> <option value="Directeur">Directeur</option>'?>
                                                <?php if($effectifToModify["grade"] === 'Directeur')                echo '<option value="Étudiant">Étudiant</option> <option value="Aide-Soignant">Aide-Soignant</option> <option value="Ambulancier">Ambulancier</option> <option value="Infirmier">Infirmier</option> <option value="Externe">Externe</option> <option value="Interne">Interne</option> <option value="Résident">Résident</option> <option value="Généraliste">Généraliste</option> <option value="Urgentiste">Urgentiste</option> <option value="Médecin-Chef">Médecin-Chef</option> <option value="Chirurgien">Chirurgien</option> <option value="Chirurgien Spécialisé">Chirurgien Spécialisé</option> <option value="Chef de Service">Chef de Service</option> <option value="Directeur de Centre">Directeur de Centre</option> <option value="Directeur-Adjoint">Directeur-Adjoint</option> <option value="Directeur" selected>Directeur</option>'?>
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
                                            <select name="agregation[]" class="selectMultipleInputStyle" size="9" multiple="multiple">
                                                <option id="MRG" value="MRG">M.R.G.</option>
                                                <option id="MARU" value="MARU">M.A.R.U.</option>
                                                <option id="MTT" value="MTT">M.T.T.</option>
                                                <option id="ASG" value="ASG">A.S.G.</option>
                                                <option id="Tech ASG" value="Tech ASG">Tech A.S.G.</option>
                                                <option id="PSS" value="PSS">P.S.S.</option>
                                                <option id="GOS" value="GOS">G.O.S.</option>
                                                <option id="EMT" value="EMT">E.M.T.</option>
                                                <option id="GSS" value="GSS">G.S.S.</option>
                                            </select>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <select name="role[]" class="selectMultipleInputStyle" multiple="multiple">
                                                <option id="Instructeur Médical"value="Instructeur Médical">Instructeur Médical</option>
                                                <option id="Formateur Médical" value="Formateur Médical">Formateur Médical</option>
                                                <option id="Apprenti Formateur" value="Apprenti Formateur">Apprenti Formateur</option>
                                                <option id="Mentor" value="Mentor">Mentor</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <?php if($data["undeletable"] === '1') echo '
                                <h4 class="bold underline" style="padding: 10px; color: #01161e; display: none;">Privilège du Personnel</h4>

                                <table style="width: 100%; display: none;">
                                    <tbody>
                                        <tr>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Fin de Service Forcée</td>
                                            <td class="normalTitleStyle" style="width: 50%;">Droit - Création, Modification et Suppression d&#8217;effectif</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select name="deservice" class="selectInputStyle" required="required">
                                                    <option id="deservicenon" value="0">Non</option>
                                                    <option id="deserviceoui" value="1">Oui</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="register" class="selectInputStyle" required="required">
                                                    <option id="registernon" value="0">Non</option>
                                                    <option id="registeroui" value="1">Oui</option>
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
                                                    <option id="timemanagernon" value="0">Non</option>
                                                    <option id="timemanageroui" value="1">Oui</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="viewall" class="selectInputStyle" required="required">
                                                    <option id="viewallnon" value="0">Non</option>
                                                    <option id="viewalloui" value="1">Oui</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            '?>

                            <?php if($data["undeletable"] === '0') echo '

                            '?>

                            <table style="width: 100%;">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;">
                                            <input type="submit" onmousover="" name="button_Modifier" value="Sauvegarder les modifications" class="btn btn-success btn-lg" style="width: 300px;"/>
                                        </td>
                                        <td style="width: 50%;">
                                            <a href="./alleffectif.php" class="btn btn-danger btn-lg" style="width: 300px;">Annuler les modifications</a>
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