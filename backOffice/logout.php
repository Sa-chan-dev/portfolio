<?php
session_start();

/**On inclu d'abord le fichier de configuration */
include('../config/config.php');
include('../lib/bdd.lib.php');

$_SESSION['connected'] = false;
unset($_SESSION['connected']);
unset($_SESSION['user']);

header('Location:login.php');
exit();

?>