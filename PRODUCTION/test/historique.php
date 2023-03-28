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
?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Toutes mes heures</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
		<script language="javascript" type="text/javascript"></script>
		<style>
            body {
                color: #ffffff;
                background-color: #01161e;
            }
            .rowHistoriqueHeader {
                background-color: #0F252E;
            }
            .rowHistoriquePair {
                background-color: #124559;
            }
            .rowHistoriqueImpair {
                background-color: #3080A0;
            }
        </style>
	</head>
    <?php
        // Info : Donnée de connexion au serveur phpmyadmin
        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

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
            header("Location: ./service.php");
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
            header("Location: ./service.php");
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
            header("Location: ./service.php");
        }

        ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
        mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");
        $idEffectifActuel = $data["id"];

        //                                                       0      1          2              3        4      5       6      7         8       9        10      11      12          13
        $result                 = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$idEffectifActuel AND past=0 AND toignore=0");
        $result2                = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$idEffectifActuel AND past=0 AND toignore=0");

//        echo "ID ACTUEL = " . $idEffectifActuel;

        $total_jour     = 0;
        $total_heure    = 0;
        $total_minute   = 0;
        $total_seconde  = 0;

        while ($rowbis = mysqli_fetch_row($result2)) {
            $start_datetime = new DateTime($rowbis[2]);
            $diff = $start_datetime->diff(new DateTime($rowbis[3]));
            if ($rowbis[13] === '0')
            {
                $total_seconde  += $diff->s;
                $total_minute   += $diff->i;
                $total_heure    += $diff->h;
            }
            else if ($rowbis[13] === '1')
            {
                $total_seconde  = $total_seconde    + intval($rowbis[8]);
                $total_minute   = $total_minute     + intval($rowbis[7]);
                $total_heure    = $total_heure      + intval($rowbis[6]);
            }


//            echo $diff->days.' Days total<br>';
//            echo $diff->y.' Years<br>';
//            echo $diff->m.' Months<br>';
//            echo $diff->d.' Days<br>';
//            echo $diff->h.' Hours<br>';
//            echo $diff->i.' Minutes<br>';
//            echo $diff->s.' Seconds<br>';

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
    ?>
	<body style="width: 100%; text-align: center;">
        <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
            <tbody>
                <tr>
                    <td style="width: 33%;">
                        <a href="./landing.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour Profil</a>
                        <a href="./service.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour Prise de Service</a>
                    </td>
                    <td style="width: 34%; color: #aec3b0;">
                        <h1>MES PRISES DE SERVICE</h1>
                        <!-- <h1>cService = <?php echo $data["service"];?> - cDeservice = <?php echo $data["deservice"];?></h1> -->
                    </td>
                    <td style="width: 33%;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #dcc1e9; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Total de la semaine</h4>
                            <table style="width: 100%; color: #01161e;">
                                <tbody>
                                    <tr>
                                        <td class="bold" style="width: 33%;">Heure(s)</td>
                                        <td class="bold" style="width: 34%;">Minute(s)</td>
                                        <td class="bold" style="width: 33%;">Seconde(s)</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $total_heure;?></td>
                                        <td><?php echo $total_minute;?></td>
                                        <td><?php echo $total_seconde;?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td style="width: 33%;"></td>
                </tr>
            </tbody>
        </table>

        <div id="tableHistorique" style="width: 100%; padding: 0px 25px;">
        <?php

            // Récupération des résultats
            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowHistoriqueHeader">
                            <th>Id</th>
                            <th>Effectif</th>
                            <th>Statut</th>
                            <th>Début Service</th>
                            <th>Fin Service</th>
                            <th>Heure(s)</th>
                            <th>Minute(s)</th>
                            <th>Seconde(s)</th>
                        </tr>';

            $rank = 0;
            while ($row = mysqli_fetch_row($result)) {
                $rowPair = 'rowHistoriquePair';
                $rowImpair = 'rowHistoriqueImpair';
                $rowAppliedClass = '';
                if ($rank % 2 === 0) { // PAIR
                    $rowAppliedClass = $rowPair;
                }
                if ($rank % 2 === 1) { // IMPAIR
                    $rowAppliedClass = $rowImpair;
                }
                $id                 = $row[0];
                $effectif           = $row[1];
                $debutservice       = $row[2];
                $finservice         = $row[3];
                $total              = $row[4];
                $dernier            = $row[5];
                $heure              = $row[6];
                $minute             = $row[7];
                $seconde            = $row[8];
                $jour               = $row[9];
                $firstname          = ucfirst($row[10]);
                $lastname           = ucfirst($row[11]);
                $status             = $row[12];

                $start_datetime = new DateTime($debutservice);
                $diff = $start_datetime->diff(new DateTime($finservice));

//                echo $diff->days.' Days total<br>';
//                echo $diff->y.' Years<br>';
//                echo $diff->m.' Months<br>';
//                echo $diff->d.' Days<br>';
//                echo $diff->h.' Hours<br>';
//                echo $diff->i.' Minutes<br>';
//                echo $diff->s.' Seconds<br>';

                if ($finservice === '' || $finservice === null) {
                    $finservice = "Non terminé";
                }

//                $heure      = $diff->h;
//                $minute     = $diff->i;
//                $seconde    = $diff->s;

                $heure      = $heure;
                $minute     = $minute;
                $seconde    = $seconde;
                
                if ($dernier === '1') {
                    $jour = "?";
                    $heure = "?";
                    $minute = "?";
                    $seconde = "?";
                }



                echo "<tr class='$rowAppliedClass'>
                        <td>$id</td>
                        <td>$firstname $lastname</td>
                        <td>$status</td>
                        <td>$debutservice</td>
                        <td>$finservice</td>
                        <td>$heure</td>
                        <td>$minute</td>
                        <td>$seconde</td>
                    </tr>";
                $rank += 1;
            }
        ?>
        </div>
        <php?
            //Déconnexion de la base de données
            mysqli_close($connect);
        ?>
	</body>
</html>