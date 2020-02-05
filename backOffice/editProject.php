<?php 

session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');


$vue = 'addProject.phtml';
$title = 'Modifier une Réalisation';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$titre = '';
$langage= null;
$url = '';
$image = '';
$description='';


try
{

/* 2 : Executer une requête */

// je vais chercher mon id d'article
    if (array_key_exists('id',$_GET)){
        $id= $_GET['id'];
        
        /* 1 : Connexion au serveur de Base de Données */
        $dbh = connexion();
        // recuperation des données de la bdd
        $sth = $dbh->prepare('SELECT titre, description, language, url, image FROM projects WHERE id=:id');
        $sth->bindValue(':id', $id , PDO::PARAM_INT);
        $sth->execute();
        
        // injecter le select dans le form
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        $titre = $result['titre'];
        $description = $result['description'];
        $langage = $result['language'];
        $url = $result['url'];
        $image = $result['image'];
    
        }

        if (array_key_exists('id',$_POST)){

        $errorForm = [];
        $id = $_POST['id'];
        $titre = trim($_POST['titreForm']);
        $description= trim($_POST['descriptionForm']);
        $langage = trim($_POST['langageForm']);
        $url = trim($_POST['urlForm']);
        $image = $_FILES['imageForm']['name'];
        

        if($titre == ''){
            
            $errorForm[] = 'Le titre ne peut-être vide !';
        }

        if($description == ''){
            
            $errorForm[] = 'La description ne peut-être vide !';
        }

        if(count($errorForm) == 0)
        {    
            
            // /* upload image ! */
            if(isset($_FILES['imageForm']['tmp_name']) != ''){
                
                $nomFichier=$_FILES['imageForm']['name'];
                $extensionFichier = pathinfo($nomFichier,PATHINFO_EXTENSION); 
                $taille_maxi = 10000000;
                $taille = filesize($_FILES['imageForm']['tmp_name']);
                $extensionsAutorisees = array('png','jpg','jpeg');

                // //Vérifications de sécurité
                if(!in_array($extensionFichier,$extensionsAutorisees)){
                    $erreur = 'Vous devez uploader un fichier de type  png, jpg, jpeg..';
                }
                if($taille>$taille_maxi){
                    $erreur = 'Le fichier doit être de taille inférieure...';
                }
                if(!isset($erreur)) {

                    $fichier = uploadFile('imageForm','projets');
                }

            }

            
            $bdd = connexion();
            // injection bdd
            $sth1= $bdd->prepare('UPDATE projects SET titre = :titre, description = :description ,language = :langage , url = :url , image = :image WHERE id=:id');
            $sth1->bindValue(':titre', $titre, PDO::PARAM_STR);
            $sth1->bindValue(':description', $description, PDO::PARAM_STR);
            $sth1->bindValue(':langage', $langage, PDO::PARAM_STR);
            $sth1->bindValue(':url',$url, PDO::PARAM_STR);
            $sth1->bindValue(':image',$image, PDO::PARAM_STR);
            $sth1->bindValue(':id',$id, PDO::PARAM_INT );
            $sth1->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'Votre réalisation a bien été modifiée.';

            header('Location:projects.php');
            exit();
        }
    }
}
    
catch(PDOException $e){
    
    $vue= 'erreur.phtml';

    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();
}

include('views/layout.phtml');
