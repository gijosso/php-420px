<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 24/04/2017
 * Time: 12:27
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: home');
}


$connection = getPDO();
$pseudo = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submit = $_POST["submit"];
    $pseudo = test_input($_POST["pseudo"]);
    $password = test_input($_POST["password"]);
}

?>

<!doctype html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <title>420px: blaze your pixels</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
    <script src="javascript/front.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/front.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/popup.css">
</head>

<body>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/login-popup.html') ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/unlogged-header.html'); ?>

<section class="content">
    <?php echo verify_user($pseudo, $password, $connection); ?>
    <h2 class="msg-error">For defense purposes</h2><br/>
    <div class="container">
        <?php
        $select = $connection->query('SELECT * FROM user');
        $select->setFetchMode(PDO::FETCH_OBJ);
        while ($user = $select->fetch()) {
            echo $user->id . ': ' . $user->pseudo . ' ^ ' . $user->password . '<br/>';
        }
        ?>
    </div>
</section>

</body>

</html>

<script>

</script>