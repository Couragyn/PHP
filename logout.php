<?php
  // new session start
  session_start();
  if (isset($_SESSION['user_id'])) {
    // clears session array
    $_SESSION = array();

    // Delete the session cookie by expiring it more than an hour ago
    if (isset($_COOKIE[session_name()])) {      setcookie(session_name(), '', time() - 3600);    }
    // destroys the session!
    session_destroy();
  }
  setcookie('user_id', '', time() - 3600);
  setcookie('username', '', time() - 3600);
  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  header('Location: ' . $home_url);
?>
