<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

/**On inclu d'abord le fichier de configuration */
include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');

// userIsConnected();

/** On définie nos variables nécessaire pour la vue et le layout */
$vue = 'readMessage.phtml';      //vue qui sera affichée dans le layout
$title = 'Tous les messages';  //titre de la page qui sera mis dans title et h1 dans le layout


try
{
    $table= 'contacts'; 
    $messages = loadTable($table);
}
catch(PDOException $e)
{
    $vue = 'erreur.phtml';
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $messageErreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('views/layout.phtml');