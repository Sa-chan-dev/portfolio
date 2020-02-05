<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');

$vue='cv.phtml';
$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$title = 'ajout cv';
$fichier = '';

try{

    /* upload image ! */
    if(isset($_FILES['cvForm']['tmp_name']) != ''){
        $nomFichier=$_FILES['cvForm']['name'];
        $extensionFichier = pathinfo($nomFichier,PATHINFO_EXTENSION); 
        $taille_maxi = 1000000;
        $taille = filesize($_FILES['cvForm']['tmp_name']);
        $extensionsAutorisees = array('png','jpg','jpeg','pdf');

        // //Vérifications de sécurité
        if(!in_array($extensionFichier,$extensionsAutorisees)){
            $erreur = 'Vous devez uploader un fichier de type pdf, png, jpg, jpeg..';
        }
        if($taille>$taille_maxi){
            $erreur = 'Le fichier doit être inférieur...';
        }
        if(!isset($erreur)) {

            // suppresion de l'ancien cv du dossier cv
            $dossier = UPLOADS_DIR.'/'.'cv'.'/';
            videDossier($dossier);

            // suppresion de l'ancien cv de la bdd
            $bdd= connexion();
            $sth = $bdd->prepare('DELETE FROM `cv`');
            $sth->execute();

            $fichier = uploadFile('cvForm','cv');
            
            // injection bdd
            $bdd= connexion();
            $sth1= $bdd->prepare('INSERT INTO `cv`(lien_cv) VALUES (:lien_cv)');
            $sth1->bindValue(':lien_cv', $fichier, PDO::PARAM_STR);
            $sth1->execute();
    
            //message dynamique
            $_SESSION['SuccesFlash']= 'Le cv a bien été ajouté.';
            header('Location:index.php');
            exit();

        }

        
    }

    
}
catch(PDOException $e){
    $vue= 'erreur.phtml';
    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();

}
include('views/layout.phtml');