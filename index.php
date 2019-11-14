<?php

//ouvre une session qui permettra de stocker des informations
session_start();  

$title = 'Accueil';  

include('config/config.php');
include('views/layout.phtml');
include('lib/bdd.lib.php');
