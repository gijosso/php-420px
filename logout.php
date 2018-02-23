<?php
/**
 * Created by IntelliJ IDEA.
 * User: josso_t
 * Date: 27/04/2017
 * Time: 18:37
 */

session_start();
session_unset();
session_destroy();
header('Location: index');
exit;
?>