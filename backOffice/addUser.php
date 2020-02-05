<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');

$vue = 'addUsers.phtml';
$title = 'Ajout utilisateur';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$nom = '';
$prenom = '';
$email = '';
$password = '';
$role = '';

try{
    if (array_key_exists('prenomForm',$_POST)){
        
        // je vais chercher mon formulaire et l'injecter dans ma bdd
        $nom = trim($_POST['nomForm']);
        $prenom = trim($_POST['prenomForm']);
        $email = trim($_POST['emailForm']);
        $password = trim($_POST['passwordForm']);
        $role = trim($_POST['roleForm']);
        

        if($email == ''){
            
            $errorForm[] = 'L\'email ne peut-être vide !';
        }

        if(strlen($password) < 8){

            $errorForm[] = 'Le mot de passe doit comporter 8 caractères minimum !';
        }

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){

            $errorForm[] = 'L\'email n\'est pas correcte !';
        }

        if(count($errorForm) == 0)
        {    
            
            //cryptage du password
            $cryptedPassword = password_hash($password,PASSWORD_DEFAULT);
            /* 1 : Connexion au serveur de Base de Données */  
            $bdd = connexion();
            // injection bdd
            $sth= $bdd->prepare('INSERT INTO `users`(nom, prenom, email, password, role) VALUES ( :nom,:prenom,:email,:password,:role)');
            $sth->bindValue(':nom', $nom, PDO::PARAM_STR);
            $sth->bindValue(':prenom',$prenom, PDO::PARAM_STR);
            $sth->bindValue(':email',$email, PDO::PARAM_STR);
            $sth->bindValue(':password',$cryptedPassword, PDO::PARAM_STR);
            $sth->bindValue(':role',$role, PDO::PARAM_STR);
            $sth->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'L\'utilisateur a bien été ajouté.';

            header('Location:users.php');
            exit();
        }
    }
}
catch(PDOException $e){
    $vue= 'erreur.phtml';
    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();

}
    include('views/layout.phtml');