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

    $hasRightToDelete = $data["undeletable"];
    if ($hasRightToDelete <> '1') {
        header("Location: ./deconnexion.php");
        die();
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>LSMC - Suppression EMS</title>
		<link rel="stylesheet" href="./css/style.css">
		<link rel="stylesheet" href="./css/tools.css">
		<style>
            body {
                color: #ffffff;
                background-color: #01161e;
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
            .rowEffectifMe {
                background-color: #aa1ed3;
            }
            .editButtonClass {
                border-radius: 5px;
                background: #e0a800;
                border: 0px;
                color: #212529;
            }
            .deleteButtonClass {
                border-radius: 5px;
                background: #ff1212;
                border: 0px;
                color: #ffffff;
            }
		</style>
        <script language="javascript" type="text/javascript">

            window.addEventListener("load", function() {
                alert("Attention\n\nIl n'y a pas de confirmation à la suppression d'une personne.");
            });

        </script>	</head>
	<body style="width: 100%; text-align: center;">
        <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
            <tbody>
                <tr>
                    <td style="width: 33%;">
                        <a href="./landing.php" class="btn btn-info btn-lg" style="margin: 0px 10px">Retour Profil</a>
                    </td>
                    <td style="width: 34%; color: #aec3b0;">
                        <h1>SUPPRESSION DES EFFECTIFS</h1>
                    </td>
                    <td style="width: 33%;">
                        &nbsp;
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
<?php

    // Connexion à la base de données
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
        echo "ID TO DELETE : " . $id . "<br>";
        $sql = "DELETE FROM effectif WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "La ligne a été supprimée avec succès.";
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }

    // Récupérer les données de la table
    $sql = "SELECT id, firstname, lastname, grade, role, agregation, phone, undeletable  FROM effectif ORDER BY rank DESC";
    $result = $conn->query($sql);

    // Afficher les données dans un tableau HTML
    if ($result->num_rows > 0) {
        echo '<div id="tableEffectif" style="width: 100%; padding: 0px 25px;">';
        echo '      <table border="1" cellpadding="10px" cellspacing="10px" style="width:100%">';
        echo "          <tr class='rowEffectifHeader bold'>
                        <th># - ID</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Grade</th>
                        <th>Rôle(s)</th>
                        <th>Agrégation(s)</th>
                        <th>Téléphone</th>
                        <th>Action(s)</th>
                    </tr>";

        $rank = 0;
        while($row = $result->fetch_assoc()) {
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

            if ($row["id"] === $data["id"]) { // ME
                $rowAppliedClass = $rowMe;
            }

            echo "  <tr class='$rowAppliedClass'>";
            echo "<td>" . $rank . " - " . $row["id"] . "</td>";
            echo "<td>" . $row["firstname"] . "</td>";
            echo "<td>" . $row["lastname"] . "</td>";
            echo "<td>" . $row["grade"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
            echo "<td>" . $row["agregation"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo '<td>';
            if ($row["undeletable"] === '0') {
                    echo '  <form method="post" action="">
                                <input type="hidden" name="id" value="' . $row["id"] . '">
                                <input type="submit" name="delete" class="deleteButtonClass" value="Supprimer">
                            </form>';
            }
            echo '</td>';
            echo "</tr>";
            $rank += 1;
        }
        echo "</table>";
        echo '</div>';
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
?>