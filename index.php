<?php
  require_once('connectvars.php');
  session_start();
  require_once('navmenu.php');
  // no error message
  $error_msg = "";
  // Logs in user if they are not logged in
  if (!isset($_SESSION['user_id'])) {
    if (isset($_POST['submit'])) {
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      // Grabs the data
      $user_email = mysqli_real_escape_string($dbc, trim($_POST['email']));
      $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

      if (!empty($user_email) && !empty($user_password)) {
        // looks up the email
        $query = "SELECT user_id, email FROM a3_user WHERE email = '$user_email' AND password = SHA('$user_password')";
        $data = mysqli_query($dbc, $query);

        if (mysqli_num_rows($data) == 1) {
          $row = mysqli_fetch_array($data);
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['email'] = $row['email'];
          setcookie('user_id', $row['user_id'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
          setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));  // expires in 30 days
          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // error message if wrong login info
          $error_msg = 'Opps, you must enter a valid email and password to log in.';
        }
      }
      else {
        // no info message
        $error_msg = 'Sorry, you must enter your email and password to log in.';
      }
	    mysqli_close($dbc);

    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Ass3 - Home</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h3>Ass3 - Home</h3>

<?php
  if (empty($_SESSION['user_id'])) {
    echo '<p class="error">' . $error_msg . '</p>';
	//login field
?>

  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Log In</legend>
      <label for="email">Email:</label>
      <input type="text" name="email" value="<?php if (!empty($user_email)) echo $user_email; ?>" /><br />
      <label for="password">Password:</label>
      <input type="password" name="password" />
    </fieldset>
    <input type="submit" value="Log In" name="submit" />
  </form>

<?php
  }
  else {
    // welcomes user if sign in successfull
    echo('<p class="login">Welcome!</p>');
  }
?>

</body>
</html>