<?php
session_start();

/**On inclu d'abord le fichier de configuration */
include('../config/config.php');
include('../lib/bdd.lib.php');

$vue='login.phtml';
$title = 'Connexion';


$password = '';
$email ='';

/** On test si on réceptionne les données de login */
try
{
    
    if(array_key_exists('email',$_POST))
    {
        $errorForm = []; //Pas d'erreur pour le moment sur les données
        
        $email = $_POST['email'];
        $password =$_POST['password'];

        
        if(!filter_var($email,FILTER_VALIDATE_EMAIL) || $password=='')
            $errorForm[] = 'Merci de vérifier vos identifiants !';

        
        if(count($errorForm)==0)
        {
            
            $bdd = connexion();
            $sth = $bdd->prepare('SELECT id, nom, prenom, email, role, password FROM users WHERE email = :email');
            $sth->bindValue('email', $email,PDO::PARAM_STR);
            $sth->execute();
            $user =  $sth->fetch(PDO::FETCH_ASSOC);

            if(password_verify($password,$user['password']))
            {
                //Connexion de l'utilisateur
                $_SESSION['connected'] = true;
                $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['nom'].' '.$user['prenom'],'role'=>$user['role']];

                $_SESSION['SuccesFlash']= 'Vous êtes bien connecté';
                
                header('Location:index.php');
                exit();
            
            }
            else
            {
                $errorForm[] = 'Merci de vérifier vos identifiants !';
            }
        }
        
    }
    

}
catch(PDOException $e)
{
    $vue = 'erreur.phtml';
    $messageErreur = 'Une erreur de connexion a eu lieu :'.$e->getMessage();
}

include('views/layout.phtml');

