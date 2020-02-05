<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');

$vue = 'readMessage.phtml';

try
{

/* 1 : Connexion au serveur de Base de Données */
    
    $dbh = connexion();

/* 2 : Executer une requête */
// je vais chercher mon formulaire et l'injecter dans ma bdd
    if (array_key_exists('id',$_GET)){//on teste sur un des champs jamais sur submit
        
        $idMessage= $_GET['id'];

//  supprimer de la bdd

        $sth1 = $dbh->prepare('DELETE FROM `contacts` WHERE id =:idMessage');
        $sth1->bindValue(':idMessage', $idMessage);
        $sth1->execute();

        //message dynamique
        $_SESSION['SuccesFlash']='Votre message a bien été supprimé.';
        
        header('Location:readMessage.php');
        exit();
    
    }


}
catch(PDOException $e){
    
    $vue= 'erreur.phtml';

    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();
}

include('views/layout.phtml');