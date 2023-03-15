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
        <title>Espace membre</title>
        <!-- Required meta tags -->
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
        <div class="container">
            <div class="col-md-12">
                <?php if (isset($_GET["err"])) {
                    $err = htmlspecialchars($_GET["err"]);
                    switch ($err) {
                        case "current_password":
                            echo "<div class='alert alert-danger'>Le mot de passe actuel est incorrect</div>";
                            break;

                        case "success_password":
                            echo "<div class='alert alert-success'>Le mot de passe a bien été modifié</div>";
                            break;
                    }
                } ?>


                <div class="text-center">
                        <h1 class="p-5">Bonjour <?php echo $data[
                            "pseudo"
                        ]; ?> !</h1>
                        <hr/>
                        <a href="./deconnexion.php" class="btn btn-danger btn-lg">Déconnexion</a>
                        <a href="./service.php" class="btn btn-danger btn-lg">Prise de Service</a>
                        <a href="./historique.php" class="btn btn-danger btn-lg">Historique</a>
                        <?php if($data["register"] === '1') echo '<a href="./inscription.php" class="btn btn-danger btn-lg">Création de compte</a>'?>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#changepassword">
                          Changer mon mot de passe
                        </button>
                </div>
            </div>
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
