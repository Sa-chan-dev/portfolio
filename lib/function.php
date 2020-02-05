<?php

// Recupére les infos d'une table en base de donnée
function loadTable($table){
    
    //Connexion au serveur de Base de Données 
    $dbh = connexion();
    // recuperation des données de la bdd
    $sth = $dbh->prepare('SELECT * FROM '.$table);
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;

}


function loadTableDesc($table){
    
    //Connexion au serveur de Base de Données 
    $dbh = connexion();
    // recuperation des données de la bdd
    $sth = $dbh->prepare('SELECT * FROM '.$table.' ORDER BY id DESC');
    $sth->execute();
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $result;

}

// upload image déplace un fichier transmis dans un répertoire du serveur

function uploadFile($fichier, $dossier){
    if($_FILES[$fichier]['error'] == UPLOAD_ERR_OK){
        $tmp_name = $_FILES[$fichier]['tmp_name'];
        $name = basename($_FILES[$fichier]['name']);
        $upload_directory = UPLOADS_DIR.'/'.$dossier.'/';
        //création du dossier qui accueillera l'upload si il n'existe pas
        if(!file_exists($upload_directory)){
            mkdir($upload_directory, 0755, true);
            //note : move_upload_file a besoin du nom du fichier , et de la destination complete dossier +fichier et pas juste du dossier
            move_uploaded_file($tmp_name, $upload_directory.$name.'/');
            return $name;
        }else{

            move_uploaded_file($tmp_name, $upload_directory.$name);
            return $name;
        }
    } 
}

//vider un dossier
function videDossier($dossier){
    
    $ouverture=opendir($dossier);
    $fichier=readdir($ouverture);
    while ($fichier=readdir($ouverture)) {
    unlink("$dossier/$fichier");
    }
    closedir($ouverture);
}


