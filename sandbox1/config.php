<?php
    try {

        // ******  Configuration LIVE - Debut ******
        $DBhost  = "lsmcovptsg.mysql.db";   // URL mysql.db
        $DBowner = "lsmcovptsg";            // database Username
        $DBName  = "lsmcovptsg";            // database Name
        $DBpw    = "7hahHW582QbK7h";        // database Password
        // ******  Configuration LIVE - Fin ******
        $DBconnect = "mysql:dbname=".$DBName.";host=".$DBhost;
        $bdd = new PDO($DBconnect, $DBowner, $DBpw);

    } catch (PDOException $e) {
        die("CONFIG.PHP - ERROR : " . $e->getMessage());
    }
?>