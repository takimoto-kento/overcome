<?php 
session_start();

$_SESSION = array();
//空文字入れているから上手く動作しないかも
if (ini_set('session.use_cookies', '')) {
    $params = session_get_cookie_params();
    setcookie(session_name() . ' ', time() -42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

setcookie('email', '', time()-3600);

header('Location: ../index.html');
exit();
?>