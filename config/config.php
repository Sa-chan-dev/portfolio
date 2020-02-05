<?php
/**
 * CONFIGURATION
 */
const DB_SGBD = 'mysql';
const DB_SGBD_URL = 'localhost';
const DB_DATABASE = 'mon_portefolio';
const DB_CHARSET = 'utf8';
const DB_USER = 'admin';
const DB_PASSWORD = 'admin';

// const DB_SGBD = 'mysql';
// const DB_SGBD_URL = 'localhost';
// const DB_DATABASE = 'mon_portefolio';
// const DB_CHARSET = 'utf8';
// const DB_USER = 'root';
// const DB_PASSWORD = '';


/** FILES */

//Répertoire chemin complet  (pour l'upload)
define('UPLOADS_DIR', realpath(dirname(__FILE__)."/../").'/assets/images/uploads');

//URL complète vers le répertoire upload (pour l'affichage des images dans l'HTML)
const UPLOADS_URL = '';