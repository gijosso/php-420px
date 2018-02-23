<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 10/05/2017
 * Time: 14:49
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();

$connection = getPDO();
$logged = (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true);
$pseudo = "";
$rss = 0;
$color = "#ff4440";
$pct = 20;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
    if (isset($_POST['pseudo']) && $_POST['pseudo'] && verify_pseudo($_POST['pseudo'])) {
        $pseudo = $_POST['pseudo'];
    }
    if (isset($_POST['rss'])) {
        $rss = $_POST['rss'] === "RSS";
    }
    if (isset($_POST['color'])) {
        $color = $_POST['color'];
    }
    if (isset($_POST['pct'])) {
        $pct = $_POST['pct'];
    }
}
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

<?php if ($logged === true)
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/logged-header.html');
else
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/unlogged-header.html');
    include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/login-popup.html');
?>

<section class="content">
    <h1>Search - User content</h1>
    <h2>Results</h2>
    <div class="form-container">
    <form id="upload-form" method="post" action="search">
        Search by user name
        <br/>
        User name
        <input type="text" name="pseudo" value="<?php echo $pseudo ?>"><br/>
        Output for RSS (XML)
        <input type="checkbox" name="rss" value="RSS">
        <br/>
        OR search by color
        <br/>
        <input type="color" name="color" value=<?php echo $color ?>><br/>
        Accuracy percentage (1% => exact average color, 100% => all colors)<br/>
        <input type="number" name="pct"  min="1" max="100" value=<?php echo $pct ?>><br/>
        <input class="button" type="submit" name="submit" value="Search">
    </form>
    </div>

        <?php
        if ($pseudo !== "" ) {
            if ($rss > 0) {
                header('Location: rss?pseudo=' . $pseudo);
            } else {
                outputSearchedImages($pseudo, $connection);
            }
        }
        else if ($color !== 0) {
            list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
            outputColorSearchedImages($r, $g, $b, $pct, $connection);
        }
        ?>
</section>

</body>

</html>

<script>

</script>
