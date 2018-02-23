<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 01/05/2017
 * Time: 12:42
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/image-handling.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/resize.php');

session_start();
if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)) {
    header('Location: index');
}

$connection = getPDO();
$pseudo = $_SESSION["pseudo"];
$user = getUserFromPseudo($pseudo, $connection);
$img = [];


if (isset($_FILES['img']))
    $img = $_FILES['img'];
?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>420px: blaze your pixels - User</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
    <script src="javascript/front.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/front.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/popup.css">
</head>

<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/logged-header.html'); ?>

<section class="content">
    <h1>Upload your images</h1>
    <h2>Chose a file (jpg or png)</h2>
    <div class="form-container">
        <form id="upload-form" action="upload" method="post" multipart="" enctype="multipart/form-data">
            <input class="button" type="file" name="img[]" multiple>
            <input class="button" type="submit">
        </form>
        <a class="button" href="home">GO BACK</a>
    </div>
    <?php echo saveImages($img, $user, $connection); ?>
</section>

</body>

</html>

<script>

</script>
