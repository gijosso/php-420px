<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 04/05/2017
 * Time: 19:44
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/420px/php/client-handling.php');

session_start();
if (!(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true)) {
    header('Location: index');
}

$imgId = $_SESSION["imgId"];
$connection = getPDO();

$image = getImageFromId($imgId, $connection);

if ($image) {
    unlink($image->location);
    deleteImageFromId($imgId, $connection);
    $_SESSION["imgId"] = -1;
}
header('Location: index');
exit;
?>
