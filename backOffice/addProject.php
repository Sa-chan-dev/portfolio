<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');

$vue = 'addProject.phtml';
$title = 'Ajout Réalisation';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$titre = '';
$langage= null;
$url = '';
$image = '';
$description='';

try{
    if (array_key_exists('titreForm',$_POST)){
        
        // je vais chercher mon formulaire et l'injecter dans ma bdd
        $titre = trim($_POST['titreForm']);
        $description= trim($_POST['descriptionForm']);
        $langage = trim($_POST['langageForm']);
        $url = trim($_POST['urlForm']);
        $image = $_FILES['imageForm']['name'];
        
        if($titre == ''){
            
            $errorForm[] = 'Le titre ne peut-être vide !';
        }

        if($langage == ''){

            $errorForm[] = 'le langage ne peut-être vide  !';
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
            $sth= $bdd->prepare('INSERT INTO `projects`(titre,description,language, url, image) VALUES ( :titre,:description,:langage,:url,:image)');
            $sth->bindValue(':titre', $titre, PDO::PARAM_STR);
            $sth->bindValue(':description', $description, PDO::PARAM_STR);
            $sth->bindValue(':langage', $langage, PDO::PARAM_STR);
            $sth->bindValue(':url',$url, PDO::PARAM_STR);
            $sth->bindValue(':image',$image, PDO::PARAM_STR);
            $sth->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'Votre réalisation a bien été ajoutée.';

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