<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 10/05/2017
 * Time: 23:25
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();

$logged = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>420px: blaze your pixels - Home</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
    <script src="javascript/front.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/front.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/popup.css">
</head>

<body>


<?php if($logged)
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/logged-header.html');
else {
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/login-popup.html');
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/unlogged-header.html');
    }
?>

<section class="content">
    <h1>Oops ! This page doesn't exist ;)</h1>
    <div class="container single-button-container">
        <a class="button" href="index">Home</a>
    </div>
</section>

</body>

</html>

<script>

</script>