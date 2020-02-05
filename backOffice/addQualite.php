
<?php
session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');

$vue = 'addQualite.phtml';
$title = 'Ajout Qualité';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$titre = '';
$icone= '';

try{
    if (array_key_exists('titreForm',$_POST)){
        
        // je vais chercher mon formulaire et l'injecter dans ma bdd
        $titre = trim($_POST['titreForm']);
        $icone = $_FILES['iconeForm']['name'];
        
        if($titre == ''){
            
            $errorForm[] = 'Le titre ne peut-être vide !';
        }

        if(count($errorForm) == 0)
        {    
            
            // /* upload image ! */
            if(isset($_FILES['iconeForm']['tmp_name']) != ''){
                $nomFichier=$_FILES['iconeForm']['name'];
                $extensionFichier = pathinfo($nomFichier,PATHINFO_EXTENSION); 
                $taille_maxi = 10000000;
                $taille = filesize($_FILES['iconeForm']['tmp_name']);
                $extensionsAutorisees = array('png','svg');

                // //Vérifications de sécurité
                if(!in_array($extensionFichier,$extensionsAutorisees)){
                    $erreur = 'Vous devez uploader un fichier de type  png, svg..';
                }
                if($taille>$taille_maxi){
                    $erreur = 'Le fichier doit être de taille inférieure...';
                }
                if(!isset($erreur)) {

                    $fichier = uploadFile('iconeForm','qualites');
                }

            }

            
            $bdd = connexion();
            // injection bdd
            $sth= $bdd->prepare('INSERT INTO `qualite`(titre,icone) VALUES ( :titre,:icone)');
            $sth->bindValue(':titre', $titre, PDO::PARAM_STR);
            $sth->bindValue(':icone',$icone, PDO::PARAM_STR);
            $sth->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'Votre qualité a bien été ajoutée.';

            header('Location:qualite.php');
            exit();
        }
    }
}
catch(PDOException $e){
    $vue= 'erreur.phtml';
    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();

}
    include('views/layout.phtml');