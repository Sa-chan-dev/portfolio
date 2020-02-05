<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');

$vue = 'addExpe.phtml';
$title = 'Ajout experience';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$anneeDeb = '';
$anneeFin = null;
$poste = '';
$entreprise = '';
$ville = '';
$description = null;

try{
    if (array_key_exists('anneeDebForm',$_POST)){
        
        // je vais chercher mon formulaire et l'injecter dans ma bdd
        $anneeDeb = trim($_POST['anneeDebForm']);
        $anneeFin = trim($_POST['anneeFinForm']);
        $poste = trim($_POST['posteForm']);
        $entreprise = trim($_POST['entrepriseForm']);
        $ville = trim($_POST['villeForm']);
        $description = trim($_POST['descriptionForm']);
        

        if($poste == ''){
            
            $errorForm[] = 'Le poste ne peut-être vide !';
        }

        if($anneeDeb == ''){

            $errorForm[] = 'l\'année est érronée !';
        }

        if(count($errorForm) == 0)
        {    
            
            $bdd = connexion();
            // injection bdd
            $sth= $bdd->prepare('INSERT INTO `experience`(anneeDeb, anneeFin, poste, ville, entreprise, description) VALUES ( :anneeDeb,:anneeFin,:poste,:ville,:entreprise,:description)');
            $sth->bindValue(':anneeDeb', $anneeDeb, PDO::PARAM_INT);
            $sth->bindValue(':anneeFin', $anneeFin, PDO::PARAM_INT);
            $sth->bindValue(':poste',$poste, PDO::PARAM_STR);
            $sth->bindValue(':ville',$ville, PDO::PARAM_STR);
            $sth->bindValue(':entreprise',$entreprise, PDO::PARAM_STR);
            $sth->bindValue(':description',$description, PDO::PARAM_STR);
            $sth->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'Votre expérience a bien été ajoutée.';

            header('Location:experiences.php');
            exit();
        }
    }
}
catch(PDOException $e){
    $vue= 'erreur.phtml';
    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();

}
    include('views/layout.phtml');