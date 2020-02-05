<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');


$vue = 'users.phtml';
$title = 'Gestion utilisateurs';

try
{

/* 1 : Connexion au serveur de Base de Données */
    $table='users';
    $utilisateurs = loadTable($table) ;

}
catch(PDOException $e)
{
    
    $vue= 'erreur.phtml';

    $messageErreur = 'Une erreur s\'est produite : '.$e->getMessage();
}

include('views/layout.phtml');
