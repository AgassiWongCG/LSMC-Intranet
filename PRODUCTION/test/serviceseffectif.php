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
    $dataID = $data["id"];
    $dataFN = $data["firstname"];
    $dataLN = $data["lastname"];

    $hasRightToViewAll = $data["viewall"];
    if ($hasRightToViewAll <> '1') {
        header("Location: ./deconnexion.php");
        die();
    }

    $currentURL = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $url_components = parse_url($currentURL);
    parse_str($url_components['query'], $params);
    $effectifId = $params['effectifId'];

    // Info : Récupération données de l'effectif connecté
    $req1 = $bdd->prepare("SELECT * FROM effectif WHERE id = $effectifId");
    $req1->execute();
    $data1 = $req1->fetch();

    $data1ID = $data1["id"];
    $data1FN = $data1["firstname"];
    $data1LN = $data1["lastname"];

?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Toutes mes heures</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
		<script language="javascript" type="text/javascript">

            window.addEventListener("load", function() {
                const queryString = window.location.search;
                const urlParams = new URLSearchParams(queryString);
                const displaySuccess = urlParams.get('sucessAdjusted');

                if (displaySuccess == "true") {
                    alert('Ajustement des heures sur cet effectif terminé.');
                }
            });

		</script>
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
            .textInputStyle {
                width: 83%;
                height: 40px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
            }
            .numberInputStyle {
                height: 40px;
                text-align: center;
                font-weight: bold;
                border-radius: 10px;
                margin-bottom: 20px;
            }
        </style>
	</head>
    <?php
        // Info : Donnée de connexion au serveur phpmyadmin
        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

        ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
        mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");

        //                                                       0      1          2              3        4      5       6      7         8       9    10          11         12      13
        $result                 = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$effectifId AND past=0 AND toignore=0");
        $result2                = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$effectifId AND past=0 AND toignore=0");

        //                                                       0      1          2              3        4      5       6      7         8       9    10          11         12      13
        $result_PAST            = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$effectifId AND past=1 AND toignore=0");
        $result2_PAST           = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE effectif=$effectifId AND past=1 AND toignore=0");

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

        /////////////// PAST START ///////////////////
        $total_jour_PAST     = 0;
        $total_heure_PAST    = 0;
        $total_minute_PAST   = 0;
        $total_seconde_PAST  = 0;

        while ($rowbis_PAST = mysqli_fetch_row($result2_PAST)) {
            $start_datetime = new DateTime($rowbis_PAST[2]);
            $diff = $start_datetime->diff(new DateTime($rowbis_PAST[3]));
            if ($rowbis_PAST[13] === '0')
            {
                $total_seconde_PAST  += $diff->s;
                $total_minute_PAST   += $diff->i;
                $total_heure_PAST    += $diff->h;
            }
            else if ($rowbis_PAST[13] === '1')
            {
                $total_seconde_PAST  = $total_seconde_PAST    + intval($rowbis_PAST[8]);
                $total_minute_PAST   = $total_minute_PAST     + intval($rowbis_PAST[7]);
                $total_heure_PAST    = $total_heure_PAST      + intval($rowbis_PAST[6]);
            }

        }
        $minute_supp_PAST = 0;
        if ($total_seconde_PAST > 59) {
            $minute_supp_PAST = intval($total_seconde_PAST / 60);
            $total_seconde_PAST = $total_seconde_PAST % 60;
            $total_minute_PAST = $total_minute_PAST + $minute_supp_PAST;
        }

        $heure_supp_PAST = 0;
        if ($total_minute_PAST > 59) {
            $heure_supp_PAST = intval($total_minute_PAST / 60);
            $total_minute_PAST = $total_minute_PAST % 60;
            $total_heure_PAST = $total_heure_PAST + $heure_supp_PAST;
        }
        /////////////// PAST END  ////////////////////

        // Info : Méthode pour appliquer l'ajustement des heures
        if (isset($_POST["button_Ajuster"])) {

            echo (isset($effectifId)) ? $effectifId : 'NOTING';

            // Info : Récupération des paramètres du formulaire
            $receivedAdjustH    = $_POST['adjustHour'];
            $receivedAdjustM    = $_POST['adjustMinute'];
            $receivedAdjustS    = $_POST['adjustSeconde'];

            $receivedAdjustH = intval($receivedAdjustH);
            $receivedAdjustM = intval($receivedAdjustM);
            $receivedAdjustS = intval($receivedAdjustS);

            $receivedComment    = $_POST['adjustComment'];

            // Info : Création de la connexion + Vérification de la connexion
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $cid = $_POST['id'];
            $cfn = $_POST['fn'];
            $cln = $_POST['ln'];
            $sqlAdjust          = "INSERT INTO `service` (`id`, `effectif`, `firstname`, `lastname`, `debutservice`, `finservice`, `heure`, `minute`, `seconde`, `dernier`, `status`, `adjusted`) VALUES (NULL, $cid, '$cfn', '$cln', NOW(), NOW(), $receivedAdjustH, $receivedAdjustM, $receivedAdjustS, '0', '$receivedComment', '1')";
            echo "sqlAdjust = " . $sqlAdjust;

            if ($conn->query($sqlAdjust) === TRUE) {
                echo "button_Ajuster Record inserted successfully";
            } else {
                echo "button_Ajuster Error inserting record: " . $conn->error;
            }
            $conn->close();

            // Info : Actualisation vers la même page pour ne pas duppliquer la requête de formulaire
            header("Location: ./serviceseffectif.php?effectifId=" . $cid . "&sucessAdjusted=true");
        }
    ?>
	<body style="width: 100%; text-align: center;">
        <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
            <tbody>
                <tr>
                    <td style="width: 33%;">
                        <a href="./alleffectif.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour liste des effectifs</a>
                    </td>
                    <td style="width: 34%; color: #aec3b0;">
                        <h1>TOUS LES SERVICES D'UN EFFECTIF</h1>
                    </td>
                    <td style="width: 33%;">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%;">

                        <div style="margin: 50px 50px 50px 50px; padding: 10px 10px 20px 10px; background-color: #c1dfe9; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Total de la semaine dernière</h4>
                            <table style="width: 100%; color: #01161e;">
                                <tbody>
                                    <tr>
                                        <td class="bold" style="width: 33%;">Heure(s)</td>
                                        <td class="bold" style="width: 34%;">Minute(s)</td>
                                        <td class="bold" style="width: 33%;">Seconde(s)</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $total_heure_PAST;?></td>
                                        <td><?php echo $total_minute_PAST;?></td>
                                        <td><?php echo $total_seconde_PAST;?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="margin: 50px 50px 50px 50px; padding: 10px 10px 20px 10px; background-color: #dcc1e9; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Total de la semaine actuelle</h4>
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

                        <?php if($data["timemanager"] === '1') echo '<form method="post" action="./serviceseffectif.php" style="width: 100%; text-align: center;">
                            <div style="margin: 0px 50px 50px 50px; padding: 10px 10px 20px 10px; background-color: #e9ae55; border-radius: 10px;">
                                <h4 class="bold underline" style="padding: 10px; color: #01161e;">Outil pour ajuster les heures</h4>
                                <table style="width: 100%; color: #01161e;">
                                    <tbody>
                                        <tr>
                                            <td class="bold" style="width: 33%;">Heure(s)</td>
                                            <td class="bold" style="width: 34%;">Minute(s)</td>
                                            <td class="bold" style="width: 33%;">Seconde(s)</td>
                                        </tr>
                                        <tr>
                                            <td class="bold" style="width: 33%;">
                                                <input type="number" id="adjustHour" name="adjustHour" min="-100" max="100" value="0" required="required" class="numberInputStyle">
                                            </td>
                                            <td class="bold" style="width: 34%;">
                                                <input type="number" id="adjustMinute" name="adjustMinute" min="-59" max="59" value="0" required="required" class="numberInputStyle">
                                            </td>
                                            <td class="bold" style="width: 33%;">
                                                <input type="number" id="adjustSecond" name="adjustSecond" min="-59" max="59" value="0" required="required" class="numberInputStyle">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="width: 100%; color: #01161e; margin: 15px 0px;">
                                    <tbody>
                                        <tr>
                                            <td class="bold" style="width: 100%;">
                                                Commentaire d ajustement :
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="bold" style="width: 100%;">
                                                <input type="hidden" name="id" value="' . $data1ID . '">
                                                <input type="hidden" name="fn" value="' . $data1FN . '">
                                                <input type="hidden" name="ln" value="' . $data1LN . '">
                                                <input type="text" id="adjustComment" name="adjustComment" value="Direction : Ajustement par ' . ucfirst($dataFN) . '&nbsp;' . ucfirst($dataLN) . '" required="required" class="textInputStyle">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table style="width: 100%; color: #01161e;">
                                    <tbody>
                                        <tr>
                                            <td class="bold" style="width: 33%;"></td>
                                            <td class="bold" style="width: 34%;">
                                                <input type="submit" onmousover="" name="button_Ajuster" value="Appliquer" class="btn btn-success btn-lg"/>
                                            </td>
                                            <td class="bold" style="width: 33%;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>' ?>
                    </td>
                    <td style="width: 33%;">

                    </td>
                </tr>
            </tbody>
        </table>

        <div id="tableHistorique" style="width: 100%; padding: 0px 25px;">
        <h4>LISTE DES HEURES DE LA SEMAINE ACTUELLE</h4>
        <br/>
        <?php

            // Récupération des résultats
            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowHistoriqueHeader">
                            <th style="width: 5%;">Id</th>
                            <th style="width: 17%;">Effectif</th>
                            <th style="width: 20%;">Statut</th>
                            <th style="width: 20%;">Début Service</th>
                            <th style="width: 20%;">Fin Service</th>
                            <th style="width: 6%;">Heure(s)</th>
                            <th style="width: 6%;">Minute(s)</th>
                            <th style="width: 6%;">Seconde(s)</th>
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
            echo '</table>';
        ?>
        </div>
        <br/>
        <br/>
        <h4>*****</h4>
        <br/>
        <div id="tableHistorique" style="width: 100%; padding: 0px 25px;">
        <h4>LISTE DES HEURES DE LA SEMAINE DERNIÈRE</h4>
        <br/>
        <?php

            // Récupération des résultats
            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowHistoriqueHeader">
                            <th style="width: 5%;">Id</th>
                            <th style="width: 17%;">Effectif</th>
                            <th style="width: 20%;">Statut</th>
                            <th style="width: 20%;">Début Service</th>
                            <th style="width: 20%;">Fin Service</th>
                            <th style="width: 6%;">Heure(s)</th>
                            <th style="width: 6%;">Minute(s)</th>
                            <th style="width: 6%;">Seconde(s)</th>
                        </tr>';

            $rank_PAST = 0;
            while ($row_PAST = mysqli_fetch_row($result_PAST)) {
                $rowPair_PAST = 'rowHistoriquePair';
                $rowImpair_PAST = 'rowHistoriqueImpair';
                $rowAppliedClass_PAST = '';
                if ($rank_PAST % 2 === 0) { // PAIR
                    $rowAppliedClass_PAST = $rowPair_PAST;
                }
                if ($rank_PAST % 2 === 1) { // IMPAIR
                    $rowAppliedClass_PAST = $rowImpair_PAST;
                }
                $id_PAST                 = $row_PAST[0];
                $effectif_PAST           = $row_PAST[1];
                $debutservice_PAST       = $row_PAST[2];
                $finservice_PAST         = $row_PAST[3];
                $total_PAST              = $row_PAST[4];
                $dernier_PAST            = $row_PAST[5];
                $heure_PAST              = $row_PAST[6];
                $minute_PAST             = $row_PAST[7];
                $seconde_PAST            = $row_PAST[8];
                $jour_PAST               = $row_PAST[9];
                $firstname_PAST          = ucfirst($row_PAST[10]);
                $lastname_PAST           = ucfirst($row_PAST[11]);
                $status_PAST             = $row_PAST[12];

                $start_datetime = new DateTime($debutservice_PAST);
                $diff = $start_datetime->diff(new DateTime($finservice_PAST));

//                echo $diff->days.' Days total<br>';
//                echo $diff->y.' Years<br>';
//                echo $diff->m.' Months<br>';
//                echo $diff->d.' Days<br>';
//                echo $diff->h.' Hours<br>';
//                echo $diff->i.' Minutes<br>';
//                echo $diff->s.' Seconds<br>';

                if ($finservice_PAST === '' || $finservice_PAST === null) {
                    $finservice_PAST = "Non terminé";
                }

                $heure_PAST      = $heure_PAST;
                $minute_PAST     = $minute_PAST;
                $seconde_PAST    = $seconde_PAST;

                if ($dernier_PAST === '1') {
                    $jour_PAST = "?";
                    $heure_PAST = "?";
                    $minute_PAST = "?";
                    $seconde_PAST = "?";
                }

                echo "<tr class='$rowAppliedClass_PAST'>
                        <td>$id_PAST</td>
                        <td>$firstname_PAST $lastname_PAST</td>
                        <td>$status_PAST</td>
                        <td>$debutservice_PAST</td>
                        <td>$finservice_PAST</td>
                        <td>$heure_PAST</td>
                        <td>$minute_PAST</td>
                        <td>$seconde_PAST</td>
                    </tr>";
                $rank_PAST += 1;
            }
            echo '</table>';
        ?>
        </div>
        <br/>
        <br/>
        <br/>
        <php?
            //Déconnexion de la base de données
            mysqli_close($connect);
        ?>
	</body>
</html>