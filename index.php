<?php

//ouvre une session qui permettra de stocker des informations
session_start();  

$title = 'Accueil';  

include('config/config.php');
include('lib/bdd.lib.php');
include('lib/function.php');
include('views/layout.phtml');