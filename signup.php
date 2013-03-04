<?php
  // Insert the page header
  $page_title = 'Sign Up';
  require_once('startsession.php');
  require_once('navmenu.php');
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');

  
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	$name = ($_POST['name']);

    if (!empty($email) && !empty($name)){
      // makes sure there is no other person with this email
      $query = "SELECT * FROM a3_user WHERE email = '$email'";
      $data = mysqli_query($dbc, $query);
      if (mysqli_num_rows($data) == 0) {
        // puts data in database
		$password = str_shuffle("password");
        $query = "INSERT INTO a3_user (email, password, join_date, name) VALUES ('$email', SHA('$password'), NOW(), '$name')";
        mysqli_query($dbc, $query);
		
		$subject = "Account creation";
		$message = "Email: ".$email."\nPassword: ".$password;
		mail($email,$subject,$message);
		
        echo '<p>Your new account has been successfully created. An email has been sent with your login password. You\'re now ready to <a href="index.php">log in</a>.</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        // error message
        echo '<p class="error">An account already exists for this email. Please use a different address.</p>';
        $email = "";
      }
    }
	//error message
    else {
      echo '<p class="error">You fill in all fields.</p>';
    }
  }

  mysqli_close($dbc);
  
    //kicks out user not logged in
if (isset($_SESSION['email'])) {
	header("Location:index.php"); 
	} 
  
  else{
  //text form for email
?>

  <p>Please enter your information. A password will be sent to your email once submitted.</p>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <fieldset>
      <legend>Registration Info</legend>
      <label for="email">Email:</label>
      <input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /><br />
	  <label for="name">Name:</label>
      <input type="text" id="name" name="name" value=""/><br />
    </fieldset>
    <input type="submit" value="Sign Up" name="submit" />
  </form>

<?php
}
  require_once('footer.php');
?>
