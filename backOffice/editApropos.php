<?php 

session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');
include('../lib/function.php');


$vue = 'about.phtml';
$title = 'Modifier la description';

$id=null;
$description = '';

try
{
    $table= 'about';

/* 2 : Executer une requête */
// je vais chercher mon id d'article

    $result= loadTable($table);
    $id =$result[0]['id'];
    $description =$result[0]['description'];
    

    if (array_key_exists('id',$_POST)){

        $errorForm = [];
        $id = $_POST['id'];
        $description= $_POST['descriptionForm'];

            /* 4. traiter l'enregistrement */

        if($description == ''){
        
            $errorForm[] = 'La description ne peut être vide';
        }


        if(count($errorForm) == 0){  

            $dbh = connexion(); 
            $sth2 = $dbh->prepare('UPDATE about SET description = :description WHERE id = :id ');
            $sth2->bindValue(':description',$description, PDO::PARAM_STR );
            $sth2->bindValue(':id',$id, PDO::PARAM_INT );
            $sth2->execute();
            $_SESSION['SuccesFlash']= 'La description a bien été modifiée.';
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

