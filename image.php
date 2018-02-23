<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 04/05/2017
 * Time: 14:32
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/image-handling.php');

session_start();

if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)) {
    header('Location: index');
}

$connection = getPDO();
$pseudo = $_SESSION["pseudo"];
$user = getUserFromPseudo($pseudo, $connection);

if (isset($_SESSION["imgId"]))
    $imgId = $_SESSION["imgId"];
else
    $imgId = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $imgId = $_GET["img"];
}

$image = getImageFromId($imgId, $connection);
if (!$image || !$user || $image->userId !== $user->id) {
    header('Location: home');
}

$_SESSION["imgId"] = $imgId;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   applyFilter($image, $_POST['filter'], $connection);
   header("Refresh:0");
}
?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>420px: blaze your pixels - Image</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
    <script src="javascript/front.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/front.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/popup.css">
</head>

<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/logged-header.html'); ?>

<section class="content">
    <h1>Edit your image</h1>
    <h2>Image details</h2>
    <div class="bigImage-container">
        <div class="bigImage-actions">
            <?php echo "<img src='$image->location'/>"; ?>
        </div>
        <div class="bigImage-actions">
            <div class="bigImage-details">
                R : <?php echo $image->avgR?>  G : <?php echo $image->avgG?>  B : <?php echo $image->avgB?><br/>
            </div>
            <div class="hseparator"></div>
            <div class="bigImage-filters">
                <div style="display: none;">
                C+ : More contrast
                    C- : Less contrast<br/>
                L+ : More luminosity
                    L- : Less luminosity<br/>
                Sé : Sepia filter
                    Gs : Greyscale filter<br/>
                Gb : Gaussian blur filter
                    Ed : Edge detection filter<br/>
                </div>
                <form action="image?img=<?php echo $imgId?>" method="post" class="bigImage-filters-container">
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="c+">
                        <span>C+</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="c-">
                        <span>C-</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="l+">
                        <span>L+</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="l-">
                        <span>L-</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="se">
                        <span>Sé</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="gs">
                        <span>Gs</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="gb">
                        <span>Gb</span>
                    </label>
                    <label>
                        <input type="radio" name="filter" class="bigImage-filters-item" value="ed">
                        <span>Ed</span>
                    </label>
                    <div class="bigImage-filters-button">
                        <input type="submit" class="button" value="SAVE">
                        <input type="submit" class="button" value="UPDATE">
                        <input type="submit" class="button" value="CANCEL">
                    </div>
                </form>
            </div>
            <div class="hseparator"></div>
            <form action="delete" method="post">
                <input class="button bigImage-delete" type="submit" value="DELETE" name="submit">
            </form>
        </div>
    </div>
</section>

</body>

</html>

<script>
</script>