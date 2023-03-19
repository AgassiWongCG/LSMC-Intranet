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

    $hasRightToViewAll = $data["viewall"];
    if ($hasRightToViewAll <> '1') {
        header("Location: ./service.php");
        die();
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Tous les heures</title>
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


        ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
        mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");
        $idEffectifActuel = $data["id"];

        //                                                0      1          2       3      4
        $listEffectifs   = mysqli_query($connect, "SELECT id, firstname, lastname, grade, rank FROM effectif ORDER BY rank DESC");
        //                                                0      1          2              3        5       6            7       8          9
        $listServices    = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, dernier, firstname, lastname, status, adjusted, heure, minute, seconde FROM service WHERE past=0 AND dernier=0 AND toignore=0 ORDER BY effectif");

        $listServicesByEffectif     = mysqli_query($connect, "SELECT service.id, effectif.id, effectif.firstname, effectif.lastname, effectif.grade, effectif.rank, service.debutservice, service.finservice FROM service JOIN effectif ON service.effectif=effectif.id WHERE service.dernier=0 ORDER BY effectif.rank DESC, effectif.id ASC");
        $listServicesByEffectifBis  = mysqli_query($connect, "SELECT service.id, effectif.id, effectif.firstname, effectif.lastname, effectif.grade, effectif.rank, service.debutservice, service.finservice FROM service JOIN effectif ON service.effectif=effectif.id WHERE service.dernier=0 ORDER BY effectif.rank DESC, effectif.id ASC");

        $result2                = mysqli_query($connect, "SELECT id, effectif, debutservice, finservice, total, dernier, heure, minute, seconde, jour, firstname, lastname, status, adjusted FROM service WHERE dernier=0 AND past=0 AND toignore=0");

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
//            $total_seconde  += $diff->s;
//            $total_minute   += $diff->i;
//            $total_heure    += $diff->h;
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
                        <a href="./landing.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour Menu</a>
                        <a href="./service.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour Prise de Service</a>
                    </td>
                    <td style="width: 34%; color: #aec3b0;">
                        <h1>LISTE DES HEURES TOTALES PAR EFFECTIF</h1>
                        <!-- <h1>cService = <?php echo $data["service"];?> - cDeservice = <?php echo $data["deservice"];?></h1> -->
                    </td>
                    <td style="width: 33%;">
                        <a href="./alltime.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 200px;">Toutes les Heures</a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33%;"></td>
                    <td style="width: 34%;">
                        <div style="margin: 50px; padding: 10px 10px 20px 10px; background-color: #dcc1e9; border-radius: 10px;">
                            <h4 class="bold underline" style="padding: 10px; color: #01161e;">Total de la semaine de tous les effectifs</h4>
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
                    <td style="width: 33%;">
                    </td>
                </tr>

            </tbody>
        </table>

        <div id="tableHistorique" style="width: 100%; padding: 0px 25px;">

        <?php

            // Récupération des résultats

            while ($rowServ = $listServices->fetch_array())
            {
               $rowServices[] = $rowServ;
            }

            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowHistoriqueHeader">
                            <th>#</th>
                            <th>Effectif</th>
                            <th>Grade</th>
                            <th>Heure(s)</th>
                            <th>Minute(s)</th>
                            <th>Seconde(s)</th>
                        </tr>';

                    ///////////////////////////////////
                    $rank = 0;
                    while ($rowEffectif = mysqli_fetch_row($listEffectifs))
                    {
                        $rowPair = 'rowHistoriquePair';
                        $rowImpair = 'rowHistoriqueImpair';
                        $rowAppliedClass = '';
                        if ($rank % 2 === 0) { // PAIR
                            $rowAppliedClass = $rowPair;
                        }
                        if ($rank % 2 === 1) { // IMPAIR
                            $rowAppliedClass = $rowImpair;
                        }

                        $idEffectif         = $rowEffectif[0];
                        $fnEffectif         = $rowEffectif[1];
                        $lnEffectif         = $rowEffectif[2];
                        $gradeEffectif      = $rowEffectif[3];
                        $rankEffectif       = $rowEffectif[4];
                        $total_H            = 0;
                        $total_M            = 0;
                        $total_S            = 0;

                        foreach($rowServices as $rowService)
                        {
                            $effecId = $rowService["effectif"];
                            if (intval($effecId) === intval($idEffectif))
                            {
                                $start_datetime = new DateTime($rowService["debutservice"]);
                                $diff           = $start_datetime->diff(new DateTime($rowService["finservice"]));

                                if ($rowService["adjusted"] === '0')
                                {
                                    $total_S    += $diff->s;
                                    $total_M    += $diff->i;
                                    $total_H    += $diff->h;
                                }
                                else
                                {
//                                    echo "HELLO " . $rowService["adjusted"] . " --- " . $effecId . "<br/>";
//                                    echo "DAMN " . $rowService["heure"] . " --- " . $rowService["minute"] . " --- " . $rowService["seconde"];
                                    $total_S    = $total_S    + intval($rowService["seconde"]);
                                    $total_M    = $total_M    + intval($rowService["minute"]);
                                    $total_H    = $total_H    + intval($rowService["heure"]);
                                }
                            }
                        }
                        $minute_supp = 0;
                        if ($total_S > 59) {
                            $minute_supp = intval($total_S / 60);
                            $total_S = $total_S % 60;
                            $total_M = $total_M + $minute_supp;
                        }
                
                        $heure_supp = 0;
                        if ($total_M > 59) {
                            $heure_supp = intval($total_M / 60);
                            $total_M = $total_M % 60;
                            $total_H = $total_H + $heure_supp;
                        }

                        $fnEffectif = ucfirst($fnEffectif);
                        $lnEffectif = ucfirst($lnEffectif);
                        echo "  <tr class='$rowAppliedClass'>
                                    <th>$rank</th>
                                    <th>$fnEffectif $lnEffectif</th>
                                    <th>$gradeEffectif</th>
                                    <th>$total_H</th>
                                    <th>$total_M</th>
                                    <th>$total_S</th>
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