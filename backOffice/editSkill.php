<?php 

session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');


$vue = 'addSkill.phtml';
$title = 'Modifier une compétence';

$errorForm = []; //Pas d'erreur pour le moment sur les données
$id=null;
$titre = '';
$icone= '';
$niveau= '';
$description= '';


try
{

/* 2 : Executer une requête */

// je vais chercher mon id d'article
    if (array_key_exists('id',$_GET)){
        $id= $_GET['id'];
        
        /* 1 : Connexion au serveur de Base de Données */
        $dbh = connexion();
        // recuperation des données de la bdd
        $sth = $dbh->prepare('SELECT titre, icone, niveau, description FROM skill WHERE id=:id');
        $sth->bindValue(':id', $id , PDO::PARAM_INT);
        $sth->execute();
        
        // injecter le select dans le form
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        $titre = $result['titre'];
        $icone = $result['icone'];
        $niveau= $result['niveau'];
        $description= $result['description'];
    
        }

        if (array_key_exists('id',$_POST)){

        $errorForm = [];
        $id = $_POST['id'];
        $titre = trim($_POST['titreForm']);
        $icone = $_FILES['iconeForm']['name'];
        $niveau = trim($_POST['levelForm']);
        $description = trim($_POST['descriptionForm']);
        

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

                    $fichier = uploadFile('iconeForm','competences');
                }

            }

            
            $bdd = connexion();
            // injection bdd
            $sth1= $bdd->prepare('UPDATE skill SET titre = :titre, icone = :icone, niveau = :niveau , description = :description  WHERE id=:id');
            $sth1->bindValue(':titre', $titre, PDO::PARAM_STR);
            $sth1->bindValue(':icone',$icone, PDO::PARAM_STR);
            $sth1->bindValue(':niveau',$niveau, PDO::PARAM_STR);
            $sth1->bindValue(':description',$description, PDO::PARAM_STR);
            $sth1->bindValue(':id',$id, PDO::PARAM_INT );
            $sth1->execute();
            
            //message dynamique
            $_SESSION['SuccesFlash']= 'Votre description a bien été modifiée.';

            header('Location:skills.php');
            exit();
        }
    }
}
    
catch(PDOException $e){
    
    $vue= 'erreur.phtml';

    $errorForm[] = 'Une erreur s\'est produite : '.$e->getMessage();
}

include('views/layout.phtml');
