<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 24/04/2017
 * Time: 12:46
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();

if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)) {
    header('Location: index');
}

$connection = getPDO();
$pseudo = $_SESSION["pseudo"];
$user = getUserFromPseudo($pseudo, $connection);

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


<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/logged-header.html');?>

<section class="content">
    <h1><?php echo $pseudo ?> - Your page</h1>
    <h2>Your gallery</h2>
    <ul id="gallery">
    <?php
    $select = getImagesFromId($user->id, $connection);
        while ($image = $select->fetch()) {
            echo "<li>"
                . "<a href='image?img=" . $image->id . "'>"
                . "<img src='$image->location'/>"
                . "</a>"
                . "</li>";
    }?>
    </ul>
</section>

</body>

</html>

<script>

</script>