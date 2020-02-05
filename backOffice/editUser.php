<?php 

session_start();
if(!isset($_SESSION['connected']) || $_SESSION['connected'] !== true )
header('Location:login.php');

include('../config/config.php');
include('../lib/bdd.lib.php');


$vue = 'addUsers.phtml';
$title = 'Modifier un utilisateur';

$id='';
$nom = '';
$prenom = '';
$email = '';
$password = '';
$role = '';

try
{

/* 2 : Executer une requête */

// je vais chercher mon id d'article
    if (array_key_exists('id',$_GET)){
        /* 1 : Connexion au serveur de Base de Données */
        $dbh = connexion();
        $id= $_GET['id'];
        
        // recuperation des données de la bdd
        $sth = $dbh->prepare('SELECT nom, prenom, email, password, role FROM users WHERE id=:id');
        $sth->bindValue(':id', $id , PDO::PARAM_INT);
        $sth->execute();
        
        // injecter le select dans le form
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        
        $nom =$result['nom'];
        $prenom=$result['prenom'];
        $email =$result['email'];
        $password = $result['password'];
        $role =$result['role'];

        }

        if (array_key_exists('id',$_POST)){

            $errorForm = [];

            $id = $_POST['id'];
            $nom = $_POST['nomForm'];
            $prenom = $_POST['prenomForm'];
            $email = $_POST['emailForm'];
            $password = $_POST['passwordForm'];
            $role = $_POST['roleForm'];
            
              /* 4. traiter l'enregistrement */

            if($email == ''){
            
                $errorForm[] = 'L\'email ne peut-être vide !';
            }

            if(!empty($password) && strlen($password) < 8){

                $errorForm[] = 'Le mot de passe doit comporter 8 caractères minimum !';
            }

            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){

                $errorForm[] = 'L\'email n\'est pas correcte !';
            }


            if(count($errorForm) == 0){   
                $dbh = connexion(); 
                if($password!= ''){
                    //cryptage du password
                    
                    $cryptedPassword = password_hash($password,PASSWORD_DEFAULT);
                    $sth2 = $dbh->prepare('UPDATE users SET password =:password WHERE id = :id ');
                    $sth2->bindValue(':password', $cryptedPassword, PDO::PARAM_STR);
                }
                
                $sth2 = $dbh->prepare('UPDATE users SET nom = :nom, prenom = :prenom ,email = :email ,role = :role WHERE id = :id ');
                $sth2->bindValue(':nom',$nom, PDO::PARAM_STR );
                $sth2->bindValue(':prenom',$prenom, PDO::PARAM_STR );
                $sth2->bindValue(':email',$email , PDO::PARAM_STR ); 
                $sth2->bindValue(':role', $role , PDO::PARAM_STR );
                $sth2->bindValue(':id',$id, PDO::PARAM_INT );
                $sth2->execute();
                
                $_SESSION['SuccesFlash']= 'L\'utilisateur a bien été modifié.';
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
