<?php
  require_once('startsession.php');
  $page_title = 'View Profile';
  require_once('navmenu.php');
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');

//redirects to index if not logged in
  if (!isset($_SESSION['user_id'])) {
	header("Location:index.php"); 
  }

  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Grab the profile data from the database
  if (!isset($_GET['user_id'])) {
    $query = "SELECT * FROM a3_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
  }
  else {
    $query = "SELECT * FROM a3_user WHERE user_id = '" . $_GET['user_id'] . "'";
  }
  $data = mysqli_query($dbc, $query);
  if (mysqli_num_rows($data) == 1) {
    $row = mysqli_fetch_array($data);

    echo '<table>';
    if (!empty($row['email'])) {
      echo '<tr><td class="label">Email:</td><td>' . $row['email'] . '</td></tr>';
    }
	  if (!empty($row['name'])) {
      echo '<tr><td class="label">Name:</td><td>' . $row['name'] . '</td></tr>';
    }
    if (!empty($row['join_date'])) {
      echo '<tr><td class="label">Join Date:</td><td>' . $row['join_date'] . '</td></tr>';
    }
    if (!empty($row['avatar'])) {
      echo '<tr><td class="label">Avatar:</td><td><img src="' . MM_UPLOADPATH . $row['avatar'] .
        '" alt="Profile Picture" /></td></tr>';
    }
    echo '</table>';
//link to change account
	echo '<p>Would you like to <a href="changeacct.php">edit your account</a>?</p>';
?>

	
<?php

  }
  else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
  }

  mysqli_close($dbc);
?>

<?php
  require_once('footer.php');
?>
