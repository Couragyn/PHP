<?php
  require_once('startsession.php');
  require_once('navmenu.php');
  $page_title = 'Edit Avatar';
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');

  // kicks out users that are not logged in
  if (!isset($_SESSION['user_id'])) {
	header("Location:index.php");
  }
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
	$name = ($_POST['name']);
	$query = "UPDATE a3_user SET name = '$name' WHERE user_id = '".$_SESSION['user_id']."'";
	$data = mysqli_query($dbc, $query);

    $current = mysqli_real_escape_string($dbc, trim($_POST['current']));
    $new = mysqli_real_escape_string($dbc, trim($_FILES['new']['name']));
    $new_type = $_FILES['new']['type'];
    $new_size = $_FILES['new']['size']; 
    list($new_width, $new_height) = getimagesize($_FILES['new']['tmp_name']);
	function findexts ($fname) { 
		$fname = strtolower($fname) ; 
		$exts = split("[/\\.]", $fname) ; 
		$n = count($exts)-1; 
		$exts = $exts[$n]; 
		return $exts; 
	}
    $error = false;

    // Moves pic to correct location with new name
	// set up so the file has the same name as the users id(key). the only case where it would have mopre than one is with different filetypes
    if (!empty($new)) {
      if ((($new_type == 'image/gif') || ($new_type == 'image/jpeg') || ($new_type == 'image/pjpeg') ||
        ($new_type == 'image/png')) && ($new_size > 0) && ($new_size <= MM_MAXFILESIZE) &&
        ($new_width <= MM_MAXIMGWIDTH) && ($new_height <= MM_MAXIMGHEIGHT)) {        if ($_FILES['file']['error'] == 0) {		  $query = "SELECT * FROM a3_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
		  $data = mysqli_query($dbc, $query);
	      $row = mysqli_fetch_array($data);
		  $newname = $row['user_id'];
		  findexts($new);
		  $nameext = $newname.'.'.findexts($new);
          $target = MM_UPLOADPATH . $nameext;
		  move_uploaded_file(($_FILES['new']['tmp_name']), $target);
		  //FIXED PERMISSION PROBLEM!!!
		  chmod($target, 0777);

		  
		  $querypic = "UPDATE a3_user SET avatar = '$nameext' WHERE user_id = '" . $_SESSION['user_id'] . "'";
          mysqli_query($dbc, $querypic);
        
		  mysqli_close($dbc);\
		  //sends user back to profile
		  header("Location:profile.php");

        }      }      else {
        @unlink($_FILES['new']['tmp_name']);
        $error = true;        echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
          ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';      }
    }
	header("Location:profile.php");

	  
    }
  else {
    // grabs data from the database
    $query = "SELECT * FROM a3_user WHERE user_id = '" . $_SESSION['user_id'] . "'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      $current = $row['avatar'];
    }
    else {
      echo '<p class="error">There was a problem accessing your profile.</p>';
    }
  }

  mysqli_close($dbc);
?>

  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
    <fieldset>
      <legend>Profile editor</legend>
	  <label for="name">Name:</label>
      <input type="text" id="name" name="name" value=""><br />
      <input type="hidden" name="current" value="<?php if (!empty($current)) echo $current; ?>" />
      <label for="new">Picture:</label>
      <input type="file" id="new" name="new" />
      <?php if (!empty($current)) {
        echo '<img class="profile" src="' . MM_UPLOADPATH . $current . '" alt="Avatar" />';
      } ?>
    </fieldset>
    <input type="submit" value="Change Profile" name="submit" />
  </form>

<?php
  require_once('footer.php');
?>
