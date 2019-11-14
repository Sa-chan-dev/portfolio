<?php

session_start();
include('config/config.php');
include('functions.php');
include('lib/bdd.lib.php');


$errorForm = []; //Pas d'erreur pour le moment sur les données
$nom = '';
$prenom = '';
$mail = '';
$telephone= '';
$message='';


try{
    
    // On récupère les champs 
    if(array_key_exists('nom',$_POST)){
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $mail =trim($_POST['email']);
        $telephone = trim($_POST['telephone']);
        $message =trim($_POST['message']);


        //je verifie si les champs obligatoires ne sont pas vides
        

        if($nom == ''){
            $errorForm[]  = 'Merci de rentrer votre nom';
            
        }
        elseif($prenom == ''){
            $errorForm[]  = 'Merci de rentrer votre prenom';

            
        }
        elseif($mail == ''){
            $errorForm[]  = 'Merci de rentrer votre email';
            
        }
        elseif($message == ''){
            $errorForm[]  = 'Merci de rentrer un message';
        
        }
        
        //Si j'ai pas d'erreur j'insert dans la bdd 
        if(count($errorForm) == 0){ 
            
            
            $bdd = connexion();
            $sth = $bdd->prepare('INSERT INTO contacts
                        (email,message,nom,prenom,telephone)
                        VALUES (:email,:message,:nom,:prenom,:telephone)');

            //Liage (bind) des valeurs
            $sth->bindValue('email',$mail,PDO::PARAM_STR);
            $sth->bindValue('message',$message,PDO::PARAM_STR);
            $sth->bindValue('nom',$nom,PDO::PARAM_STR);
            $sth->bindValue('prenom',$prenom,PDO::PARAM_STR);
            $sth->bindValue('telephone',$telephone,PDO::PARAM_INT);
            $sth->execute();

            //message dynamique
            $_SESSION['SuccesFlash']='Merci, votre message a bien été envoyé, je vous répondrais dans les plus brefs délais !';
            
            //redirection sur la page voulue
            header('Location:index.php#redirection_form');
            exit(); //on arrête le script après redirection pour éviter que PHP ne continu son boulot inutilement !
        }


    }
}
catch(PDOException $e){
    //Si une exception est envoyée par PDO (exemple : serveur de BDD innaccessible) on arrive ici
    $errorForm[] = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}   

