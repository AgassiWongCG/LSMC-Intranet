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
    $sql = "SELECT id, firstname, lastname, grade, role, agregation, phone  FROM effectif";
    $result = $conn->query($sql);

    // Afficher les données dans un tableau HTML
    if ($result->num_rows > 0) {
        echo "<table border=1 style='width: 100%;'>";
        echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Grade</th><th>Agrégation</th><th>Téléphone</th><th>Supprimer</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["firstname"] . "</td>";
            echo "<td>" . $row["lastname"] . "</td>";
            echo "<td>" . $row["grade"] . "</td>";
            echo "<td>" . $row["role"] . "</td>";
            echo "<td>" . $row["agregation"] . "</td>";
            echo "<td>" . $row["phone"] . "</td>";
            echo '<td><form method="post" action=""><input type="hidden" name="id" value="' . $row["id"] . '"><input type="submit" name="delete" value="Supprimer"></form></td>';
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Aucun résultat trouvé.";
    }

    // Fermer la connexion à la base de données
    $conn->close();
?>