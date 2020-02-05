<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');


$vue = 'experiences.phtml';
$title = 'Gestion experiences';

try
{
    $table = 'experience';
    $experiences=loadTable($table);
    

}
catch(PDOException $e)
{
    
    $vue= 'erreur.phtml';

    $messageErreur = 'Une erreur s\'est produite : '.$e->getMessage();
}

include('views/layout.phtml');
?>