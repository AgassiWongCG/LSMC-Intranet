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

    $hasRightToRegister = $data["register"];

?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Tous les effectifs</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
		<style>
            .rowEffectifHeader {
                background-color: #0F252E;
            }
            .rowEffectifPair {
                background-color: #124559;
            }
            .rowEffectifImpair {
                background-color: #3080A0;
            }
            .rowEffectifMe {
                background-color: #aa1ed3;
            }
            .editButtonClass {
                border-radius: 5px;
                background: #e0a800;
                border: 0px;
                color: #212529;
            }
        </style>
		<script language="javascript" type="text/javascript"></script>
		<style>
            body {
                color: #ffffff;
                background-color: #01161e;
            }
		</style>
	</head>
    <?php
        // Info : Donnée de connexion au serveur phpmyadmin
        $servername = "lsmcovptsg.mysql.db";   // URL mysql.db
        $username   = "lsmcovptsg";            // database Username
        $password   = "7hahHW582QbK7h";        // database Password
        $dbname     = "lsmcovptsg";            // database Name

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Vérifier la connexion
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Traitement du formulaire de suppression
        if (isset($_POST['delete'])) {
            $id = $_POST['id'];

//            echo "ID TO DELETE : " . $id . "<br>";
            $sql = "DELETE FROM effectif WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                echo "La ligne a été supprimée avec succès.";
            } else {
                echo "Erreur: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();

            header("Location: ./alleffectif.php");
        }

        if (isset($_POST["edit"])) {
            $id = $_POST['id'];

            $reqEffectif = $bdd->prepare("SELECT * FROM effectif WHERE id=$id");
            $reqEffectif->execute([$_SESSION["user"]]);
            $effectifToModify = $reqEffectif->fetch();

            $paramsAgregation = str_replace(", ", "*", $effectifToModify["agregation"]);

            $paramsRole = str_replace(", ", "*", $effectifToModify["role"]);

            $paramDeservice     = $effectifToModify["deservice"];
            $paramRegister      = $effectifToModify["register"];
            $paramTimeManager   = $effectifToModify["timemanager"];
            $paramViewAll       = $effectifToModify["viewall"];

            header("Location: ./modification.php?effectifId=" . $id . "&agregation=" . $paramsAgregation . "&role=" . $paramsRole . "&deservice=" . $paramDeservice . "&register=" . $paramRegister . "&timemanager=" . $paramTimeManager . "&viewall=" . $paramViewAll);

        }

        ($connect = mysqli_connect($servername, $username, $password)) or die("erreur de connection à MySQL");
        mysqli_select_db($connect, $dbname) or die("erreur de connexion à la base de données");

        //                                                       0      1          2        3     4      5     6     7      8           9           10      11          12        13    14
        $result                 = mysqli_query($connect, "SELECT id, firstname, lastname, phone, grade, bank, uid,  role, agregation, deservice, register, timemanager, viewall, sexe, undeletable FROM effectif ORDER BY rank DESC");

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
                        <h1>LISTE DES EFFECTIFS</h1>
                        <!-- <h1>cService = <?php echo $data["service"];?> - cDeservice = <?php echo $data["deservice"];?></h1> -->
                    </td>
                    <td style="width: 33%;">
                        &nbsp;
                    </td>
                </tr>
            </tbody>
        </table>

        <div id="tableEffectif" style="width: 100%; padding: 0px 25px;">

        <?php if ($data["register"] === '1') {

            function writeRights($received) {
                if ($received === '0') {
                    echo '<td><span class="bold" style="color: #ff2c41;">Non</span></td>';
                }
                else if ($received === '1'){
                    echo '<td><span class="bold" style="color: #33d457;">Oui</span></td>';
                }
            }

            // Récupération des résultats
            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowEffectifHeader bold">
                            <td>#</td>
                            <td>Effectif</td>
                            <td>Téléphone</td>
                            <td>Grade</td>
                            <td>Rôle(s)</td>
                            <td>Agrégation(s)</td>
                            <td>Compte Bancaire</td>
                            <td>Gestion des Service</td>
                            <td>Gestion des Comptes</td>
                            <td>Gestion des Heures</td>
                            <td>Droit Vision des Heures</td>
                            <td>Action(s)</td>
                        </tr>';

            $rank = 0;
            while ($row = $result->fetch_assoc()) {
                $rowPair    = 'rowEffectifPair';
                $rowImpair  = 'rowEffectifImpair';
                $rowMe      = 'rowEffectifMe';
                $rowAppliedClass = '';
                if ($rank % 2 === 0) { // PAIR
                    $rowAppliedClass = $rowPair;
                }
                if ($rank % 2 === 1) { // IMPAIR
                    $rowAppliedClass = $rowImpair;
                }
                if ($row["id"] === $data["id"]) { // IMPAIR
                    $rowAppliedClass = $rowMe;
                }

                echo "  <tr class='$rowAppliedClass'>";
                echo "      <td>" . $rank . "</td>";
                echo "      <td>" . ucfirst($row["firstname"]) . " " . ucfirst($row["lastname"]) . "</td>";
                echo "      <td>" . $row["phone"] . "</td>";
                echo "      <td>" . $row["grade"] . "</td>";
                echo "      <td>" . $row["role"] . "</td>";
                echo "      <td>" . $row["agregation"] . "</td>";
                echo "      <td>" . $row["bank"] . "</td>";
                writeRights($row["deservice"]);
                writeRights($row["register"]);
                writeRights($row["timemanager"]);
                writeRights($row["viewall"]);
                echo '<td>';
                    echo '<form method="post" action=""><input type="hidden" name="id" value="' . $row["id"] . '"><input type="submit" name="edit" class="editButtonClass" value="Modifier"></form>';
                echo '</td>';
                echo '</td>';
                echo "  </tr>";
                $rank += 1;
            }
        }
        ?>
        <?php if ($data["register"] === '0') {

            // Récupération des résultats
            echo '  <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">
                        <tr class="rowEffectifHeader bold">
                            <td>#</td>
                            <td>Effectif</td>
                            <td>Téléphone</td>
                            <td>Grade</td>
                            <td>Rôle(s)</td>
                            <td>Agrégation(s)</td>
                        </tr>';

            $rank = 0;
            while ($row = mysqli_fetch_row($result)) {
                $rowPair = 'rowEffectifPair';
                $rowImpair = 'rowEffectifImpair';
                $rowMe = 'rowEffectifMe';
                $rowAppliedClass = '';
                if ($rank % 2 === 0) { // PAIR
                    $rowAppliedClass = $rowPair;
                }
                if ($rank % 2 === 1) { // IMPAIR
                    $rowAppliedClass = $rowImpair;
                }
                $id                 = $row[0];
                $prenom             = ucfirst($row[1]);
                $nom                = ucfirst($row[2]);
                $phone              = $row[3];
                $grade              = $row[4];
                $bank               = $row[5];
                $uid                = $row[6];
                $role               = $row[7];
                $agregation         = $row[8];
                $deservice          = $row[9];
                $register           = $row[10];
                $timemanager        = $row[11];
                $viewall            = $row[12];
                $sexe               = $row[13];
                $undeletable        = $row[14];

                if ($id === $data["id"]) { // ME
                    $rowAppliedClass = $rowMe;
                }

                echo "<tr class='$rowAppliedClass'>
                        <td>$rank</td>
                        <td>$prenom $nom</td>
                        <td>$phone</td>
                        <td>$grade</td>
                        <td>$role</td>
                        <td>$agregation</td>
                    </tr>";
                $rank += 1;

                }
            }
        ?>
        </div>

        <php?
            //Déconnexion de la base de données
            mysqli_close($connect);
        ?>
	</body>
</html>