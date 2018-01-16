<?php
/**
 * Èë¿Ú
 *
 *
 *
 
 */
$site_url = strtolower('https://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/index.php')).'/gcshop/index.php');
@header('Location: '.$site_url);
include('gcshop/index.php');

