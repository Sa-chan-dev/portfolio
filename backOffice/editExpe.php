<?php 

session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');


$vue = 'addExpe.phtml';
$title = 'Modifier un Experience';

$id=null;
$anneeDeb = '';
$anneeFin = null;
$poste = '';
$entreprise = '';
$ville = '';
$description = null;


try
{

/* 2 : Executer une requête */

// je vais chercher mon id d'article
    if (array_key_exists('id',$_GET)){
        /* 1 : Connexion au serveur de Base de Données */
        $dbh = connexion();
        $id= $_GET['id'];
        
        // recuperation des données de la bdd
        $sth = $dbh->prepare('SELECT anneeDeb, anneeFin, poste, ville, entreprise, description FROM experience WHERE id=:id');
        $sth->bindValue(':id', $id , PDO::PARAM_INT);
        $sth->execute();
        
        // injecter le select dans le form
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        $anneeDeb = $result['anneeDeb'];
        $anneeFin = $result['anneeFin'];
        $poste = $result['poste'];
        $entreprise = $result['ville'];
        $ville = $result['entreprise'];
        $description = $result['description'];
    
        }

        if (array_key_exists('id',$_POST)){

            $errorForm = [];

            $id = $_POST['id'];
            $anneeDeb = $_POST['anneeDebForm'];
            $anneeFin = $_POST['anneeFinForm'];
            $poste = $_POST['posteForm'];
            $entreprise = $_POST['entrepriseForm'];
            $ville = $_POST['villeForm'];
            $description = $_POST['descriptionForm'];
            
            
              /* 4. traiter l'enregistrement */


            if($poste == ''){
            
                $errorForm[] = 'Le poste ne peut-être vide !';
            }
    
            if($anneeDeb == ''){
    
                $errorForm[] = 'l\'année est érronée !';
            }
    


            if(count($errorForm) == 0){   
                $bdd = connexion();
                // injection bdd
                $sth1= $bdd->prepare('UPDATE experience SET anneeDeb = :anneeDeb, anneeFin = :anneeFin ,poste = :poste ,ville = :ville , entreprise = :entreprise, description = :description WHERE id=:id');
                $sth1->bindValue(':anneeDeb', $anneeDeb, PDO::PARAM_INT);
                $sth1->bindValue(':anneeFin', $anneeFin, PDO::PARAM_INT);
                $sth1->bindValue(':poste',$poste, PDO::PARAM_STR);
                $sth1->bindValue(':ville',$ville, PDO::PARAM_STR);
                $sth1->bindValue(':entreprise',$entreprise, PDO::PARAM_STR);
                $sth1->bindValue(':description',$description, PDO::PARAM_STR);
                $sth1->bindValue(':id',$id, PDO::PARAM_INT );
                $sth1->execute();
                
                //message dynamique
                $_SESSION['SuccesFlash']= 'Votre expérience a bien été modifiée.';
    
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
