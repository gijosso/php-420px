<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 24/04/2017
 * Time: 12:49
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();

$connection = getPDO();
$submit = "";
$pseudo = "";
$password = "";
$confirm_password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submit = $_POST['submit'];
    $pseudo = test_input($_POST["pseudo"]);
    $password = test_input($_POST["password"]);
    $confirm_password = test_input($_POST["confirm_password"]);
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>420px: blaze your pixels - Sign up</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js" type="text/javascript"></script>
    <script src="javascript/front.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/front.css">
    <link rel="stylesheet" type="text/css" href="http://localhost/420px/css/popup.css">
</head>

<body>


<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/login-popup.html') ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/420px/html/unlogged-header.html');?>

<section class="content">
    <div class="container">
        <div class="signup-form">
            <h2>Please fill the following fields to sign up</h2>
            <form method="post" action="signup">
                Pseudo:<br>
                <label>
                    <input type="text" name="pseudo" value="<?php echo $pseudo ?>">
                </label><br>
                Password:<br>
                <label>
                    <input type="password" name="password" value="">
                </label>
                <br>Confirm password:<br>
                <label>
                    <input type="password" name="confirm_password" value="">
                </label>
                <br>
                <input type="submit" value="Sign up !" name="submit">
                <?php echo verify_signup_form($submit, $pseudo, $password, $confirm_password, $connection); ?>
            </form>
        </div>
    </div>
</section>

</body>

</html>

<script>

</script>