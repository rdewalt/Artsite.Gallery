<?php
include_once 'library.inc';
unset ($_SESSION);
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
setcookie("user_id",'x',1);
setcookie("username",'x',1);
setcookie("fridgeCode",'x',1);
setcookie("PHPSESSID",'x',1);
session_destroy();
header('Location: /index.php');