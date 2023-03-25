<?php
    session_start();
    require_once "./config.php"; // ajout connexion bdd
    // si la session existe pas soit si l'on est pas connecté on redirige
    if (!isset($_SESSION["user"])) {
        header("Location: ./index.php");
        die();
    }

    // On récupere les données de l'utilisateur
    $req = $bdd->prepare("SELECT * FROM effectif WHERE token = ?");
    $req->execute([$_SESSION["user"]]);
    $data = $req->fetch();

?>
<!doctype html>
<html lang="en">
    <head>
		<title>LSMC - Menu Principal</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="./css/style.css">
        <style>
            body {
                color: #ffffff;
                background-color: #01161e;
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
                    var copyText = 'Création du compte terminé\n\nSite : https://www.lsmc.ovh/\nID : ' + displayUsername + '\nMot de passe : ' + displayPassword + '\n\nPense à changer ton mot de passe.';
                    navigator.clipboard.writeText(copyText);
                    alert('Création du compte terminé\n\nSite : https://www.lsmc.ovh/\nID : ' + displayUsername + '\nMot de passe : ' + displayPassword + '\n\nLe contenu a automatiquement été copié dans votre presse-papier.');
                }
            });

        </script>
    </head>
    <body>
    <?php
        if (isset($_GET["err"])) {
            $err = htmlspecialchars($_GET["err"]);

            switch ($err) {
                case "current_password":
                echo "<div class='alert alert-danger'>Le mot de passe actuel est incorrect</div>";
                break;

                case "success_password":
                echo "<div class='alert alert-success'>Le mot de passe a bien été modifié</div>";
                break;
            }
        }
    ?>
    <table style="width: 100%; text-align: center; margin: 20px 0px 20px 0px;">
        <tbody>
            <tr>
                <td style="width: 33%;">
                    <a href="./service.php" class="btn btn-success btn-lg" style="margin: 0px 10px; width: 280px;">Prise de Service</a>
                    <a href="./historique.php" class="btn btn-info btn-lg" style="margin: 0px 10px; width: 280px;">Historique</a>
                </td>
                <td style="width: 34%; color: #aec3b0;">
                    <h1>MENU PRINCIPAL</h1>
                </td>
                <td style="width: 33%;">
                    <button type="button" class="btn btn-warning btn-lg" style="margin: 0px 10px; width: 280px;" data-toggle="modal" data-target="#changepassword">Changer mon mot de passe</button>
                    <a href="./deconnexion.php" class="btn btn-danger btn-lg" style="margin: 0px 10px; width: 280px;">Déconnexion</a>
                </td>
            </tr>
            <tr>
                <td style="width: 33%;">
                    <a href="./profilpicture.php" class="btn btn-warning btn-lg" style="margin: 0px 10px; width: 280px;">Changer ma Photo de Profil</a>
                </td>
                <td style="width: 34%; color: #aec3b0;">
                </td>
                <td style="width: 33%;">
                    <?php if($data["register"] === '1') echo '<a href="./inscription.php" class="btn btn-success btn-lg" style="margin: 0px 10px; width: 280px;">Ajouter un EMS</a>'?>
                    <?php if($data["undeletable"] === '1') echo '<a href="./suppression.php" class="btn btn-danger btn-lg" style="margin: 0px 10px; width: 280px;">Supprimer un EMS</a>'?>
                </td>
            </tr>
            <tr>
                <td style="width: 33%;">
                </td>
                <td style="width: 34%;">
                </td>
                <td style="width: 33%;">
                </td>
            </tr>
            <tr>
                <td style="width: 33%;">
                </td>
                <td style="width: 34%;">
                    <h1 class="p-5">Bonjour <?php echo ucfirst($data["firstname"]); ?> <?php echo ucfirst($data["lastname"]); ?> !</h1>
                </td>
                <td style="width: 33%;">
                </td>
            </tr>
        </tbody>
    </table>

    <div style="padding: 1% 1%; background: #e9d1c1; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>NOUVELLE</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>20 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Photo de profil de votre compte</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Bonjour à toutes et à tous,
                        <br/><br/>
                        Il est désormais possible d'ajouter une photo de profil sur votre compte.
                        <br/><br/>
                        Rappel : Vous êtes responsables de la photo que vous publiez sur votre profil. Toutes photos ne respectant pas les règles de l'hôpital peut mener à des sanctions jusqu'au licenciement. Mettez une photo simple pour que vos collègues puissent vous reconnaître.
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>Agassi WONG<br/>Responsable IT</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>
    <div style="padding: 1% 1%; background: #dcc1e9; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>INFORMATION</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>20 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Compte-Rendu Réunion du 19 Mars 2023</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Voici le compte-rendu de la réunion de dimanche dernier.
                        <br/><br/>
                        <a href="https://docs.google.com/document/d/1sJWhHa7BsJPympHcgasF0g7pWks-A6LipVh-rmw_mlM/edit" target="_blank">Afficher le Compte-Rendu du 19 Mars 2023</a>
                        <br/><br/>
                        Bien à vous,
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>La Direction LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>

    <div style="padding: 1% 1%; background: #dcc1e9; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>INFORMATION</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>12 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Compte-Rendu Réunion du 12 Mars 2023</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Voici le compte-rendu de la réunion de dimanche dernier.
                        <br/><br/>
                        <a href="https://docs.google.com/document/d/1UShAJ-kM2mob4-pDuUvw1jByo5AodbfKsGVAcFyC3zo/edit?usp=sharing" target="_blank">Afficher le Compte-Rendu du 12 Mars 2023</a>
                        <br/><br/>
                        Bien à vous,
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>La Direction LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>

    <div style="padding: 1% 1%; background: #dcc1e9; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>INFORMATION</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>11 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Réinitialisation des heures de services</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Bonjour à toutes et à tous,
                        <br/><br/>
                        Les heures de services de la semaine du 04 Mars 2023 au 11 Mars 2023 (fin d'après midi) ont été comptabilisé pour le calcul de votre prime de dimanche soir. Il est donc tout à fait normal que vos heures soient reparties à 0 heures, 0 minutes et 0 secondes.
                        <br/><br/>
                        Merci,
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>La Direction LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>

    <div style="padding: 1% 1%; background: #82e7d4; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>MAINTENANCE</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>11 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Nouveautés et Corrections</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Bonjour à toutes et à tous,
                        <br/><br/>
                        Les heures de la Radio D sur #Services ont été injectées dans votre historique.
                        <br/><br/>
                        La direction dispose maintenant d'un outil pour voir les heures totals par effectif.
                        <br/><br/>
                        Les heures totales de la semaine de l'hôpital sont maintenant affichées de manières exactes.
                        <br/><br/>
                        Merci,
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>Responsable IT - Agassi WONG</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>

    <div style="padding: 1% 1%; background: #dcc1e9; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>INFORMATION</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>07 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Compte-Rendu Réunion du 05 Mars 2023</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        Voici le compte-rendu de la réunion de dimanche dernier.
                        <br/><br/>
                        <a href="https://docs.google.com/document/d/1sQwMAJmXVz_gjC_SmxmmWcFGaxchkpuW7wKz4hl9uXw/edit?usp=sharing" target="_blank">Afficher le Compte-Rendu du 05 Mars 2023</a>
                        <br/><br/>
                        Bien à vous,
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>La Direction LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <br/>

    <div style="padding: 1% 1%; background: #e9d1c1; border-radius: 20px; margin: 0% 25%; color: #01161e;">
        <table style="width: 100%; text-align: center;" cellpadding="10px" cellspacing="10px" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 20%; vertical-align: middle;"><h5>NOUVELLE</h5></td>
        			<td style="width: 20%; vertical-align: middle;"><h5>05 Mars 2023</h5></td>
        			<td style="width: 60%; vertical-align: middle;"><h5>Ouverture du nouvel Intranet LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>

        <table style="width: 100%;" border="0">
            <tbody>
                <tr>
                    <td cellpadding="10px" cellspacing="10px" class="justify" style="width: 100%;">
                        La Direction vous souhaite la bienvenue sur le nouvel intranet du LSMC.
                        <br/><br/>
                        Nous sommes ouverts aux demandes de suggestions et d'amélioration de l'intranet. Nous comptons sur vous nous remonter des problèmes venant de l'intranet.
                        <br/><br/>
                        <span class="bold" style="color: red; font-weight: bold;">N'oubliez pas de changer votre mot de passe.</span>
                        <br/><br/>
                        Nous vous souhaitons un bon début de service à toutes et à tous.
                    </td>
                </tr>
            </tbody>
        </table>

        <table cellpadding="10px" cellspacing="10px" style="width: 100%;" border="0">
        	<tbody>
        		<tr>
        			<td style="width: 70%;">&nbsp;</td>
        			<td style="width: 30%; vertical-align: middle;"><h5>La Direction LSMC</h5></td>
        		</tr>
        	</tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="changepassword" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: black;">Changer mon mot de passe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./changepassword.php" method="POST">
                        <label for='current_password' style="color: black;">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required/>
                        <br />
                        <label for='new_password' style="color: black;">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required/>
                        <br />
                        <label for='new_password_retype' style="color: black;">Re tapez le nouveau mot de passe</label>
                        <input type="password" id="new_password_retype" name="new_password_retype" class="form-control" required/>
                        <br />
                        <button type="submit" class="btn btn-success">Sauvegarder</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="avatar" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Changer mon avatar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="./layouts/change_avatar.php" method="POST" enctype="multipart/form-data">
                        <label for="avatar">Images autorisées : png, jpg, jpeg, gif - max 20Mo</label>
                        <input type="file" name="avatar_file">
                        <br />
                        <button type="submit" class="btn btn-success">Modifier</button>
                    </form>
                </div>
                <br />
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
