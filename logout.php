<?php

if (isset($_GET['logout'])) {
  /* Unset all $_SESSION variables */
 $_SESSION = array();
  // /* Clear the Session Cookie */
  $cookie_par = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 86400,
    $cookie_par['path'],
    $cookie_par['domain'],
    $cookie_par['secure'],
    $cookie_par['httponly']
  );

  // /* Destroy the session data */
  // session_destroy();
  header('location: /login.php');
}
