<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 10/05/2017
 * Time: 18:39
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();

$connection = getPDO();
$pseudo = "";

if (isset($_GET['pseudo']))
    $pseudo = $_GET['pseudo'];
else
    header('Location: index');

outputRSS($pseudo, $connection);
