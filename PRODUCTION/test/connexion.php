<?php
    session_start(); // Démarrage de la session
    require_once "./config.php"; // On inclut la connexion à la base de données

    if (!empty($_POST["pseudo"]) && !empty($_POST["password"])) {
        // Si il existe les champs email, password et qu'il sont pas vident
        // Patch XSS
        $pseudo = htmlspecialchars($_POST["pseudo"]);
        $password = htmlspecialchars($_POST["password"]);

        $pseudo = strtolower($pseudo); // email transformé en minuscule

        // On regarde si l'utilisateur est inscrit dans la table effectif
        $check = $bdd->prepare("SELECT pseudo, email, password, token FROM effectif WHERE pseudo = ?");
        $check->execute([$pseudo]);
        $data = $check->fetch();
        $row = $check->rowCount();

        // Si > à 0 alors l'utilisateur existe
        if ($row > 0) {
            // Si le mail est bon niveau format
            if ($pseudo != null) {
                // Si le mot de passe est le bon
                if (password_verify($password, $data["password"])) {
                    // On créer la session et on redirige sur ./landing.php
                    $_SESSION["user"] = $data["token"];
                    header("Location: ./landing.php");
                    die();
                } else {
                    header("Location: ./index.php?login_err=password");
                    die();
                }
            } else {
                header("Location: ./index.php?login_err=email");
                die();
            }
        } else {
            header("Location: ./index.php?login_err=already");
            die();
        }
    } else {
         // Info : Si le formulaire est envoyé sans aucune donnée
        header("Location: ./index.php");
        die();
    }
?>
