<?php
  require_once('startsession.php');
  require_once('connectvars.php');  
  require_once('navmenu.php');
  require_once('appvars.php');
  
//kicks out non-users
    if (!isset($_SESSION['user_id'])) {
	header("Location:index.php"); 
  }
  //blog for other users
  if (isset($_GET['email'])){
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $query = "SELECT * FROM a3_user WHERE email = '".$_GET['email']."'";
  $data = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($data);
  $page_title = "User Blog for ".$row['name'];
  
    require_once('header.php');
//itle
	$query = "SELECT * FROM a4_blog WHERE email = '".$_GET['email']."' ORDER BY post_id DESC";
    $data = mysqli_query($dbc, $query);
	
	while ($row = mysqli_fetch_array($data)){
		echo '<h1>'.$row['title'].'</h1>';
		echo '<h3>'.$row['date'].'</h3>';
		echo '<p>'.$row['body'].'</p>';
		echo '<p>----------------------------------------------------</p>';

		}

	    require_once('footer.php');

  }
  //blog for user(self)
  else{
  //title
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $query = "SELECT * FROM a3_user WHERE user_id = '".$_SESSION['user_id']."'";
  $data = mysqli_query($dbc, $query);
  $row = mysqli_fetch_array($data);
  $page_title = "User Blog for ".$row['name'];
  
    require_once('header.php');

  

  
    if (isset($_POST['submit'])) {
    $title = $_POST['title'];
	$body = $_POST['body'];
	  if(!empty($title) && !empty($body)){
	  //replaces links and numbers
		  $body = preg_replace("#http://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}+[~a-zA-Z0-9\-\/]*\.[a-zA-Z]{2,3}#", '<a href=\'$0\'>$0</a>', $body);
		  $body = preg_replace("#([0-9]{3}\-)*[0-9]{3}\-[0-9]{4}#", '<font color="red"><i>$0</i></font>', $body);
		  //replaces email with link
		  $emcheck = preg_match_all("#[\s]+[\#][a-zA-Z0-9\-\_\.]+@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,3}[\s]+#", $body, $emarray);
		  foreach ($emarray[0] as $i){
			$i_valid = trim(trim($i),'#');
			$query = "SELECT email FROM a3_user";
			$data = mysqli_query($dbc, $query);
			while ($row = mysqli_fetch_array($data)){
				if ($row['email'] == $i_valid){
					$body = str_replace($i,' #<a href="blog.php?email='.$row['email'].'">'.$row['email'].'</a> ', $body);
					}
			 }
		  
		}
		  $query = "INSERT INTO a4_blog (email, title, body, date) VALUES ('".($_SESSION['email'])."', '".mysqli_real_escape_string($dbc, $title)."', '".mysqli_real_escape_string($dbc, $body)."', NOW())";

		  mysqli_query($dbc, $query);
			//refeshed page
		 header("Location:blog.php"); 

		  
		mysqli_close($dbc);
		exit();

	  }
	  //error message
	  else {
	    echo '<p class="error">You must fill all fields.</p>';
	  }
	  
	  
    mysqli_close($dbc);


    }
 
  
 ?>
   <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
     <fieldset>
      <legend>Blog Entry</legend>
      <label for="title">Title: (max 50 characters)</label>
      <input type="text" id="title" name="title" size="50" maxlength="50" value="" /><br />
	  <label for="body">Content: (max 400 characters)</label>
      <textarea name="body" id="body" size="400" maxlength="400" value=""></textarea><br />
    </fieldset>
	  <input type="submit" value="Post Blog" name="submit" />
  </form>
  

<?php
	$query = "SELECT * FROM a4_blog WHERE email = '".$_SESSION['email']."' ORDER BY post_id DESC";
    $data = mysqli_query($dbc, $query);
	
	while ($row = mysqli_fetch_array($data)){
		echo '<h1>'.$row['title'].'</h1>';
		echo '<h3>'.$row['date'].'</h3>';
		echo '<p>'.$row['body'].'</p>';
		echo '<p>----------------------------------------------------</p>';
		}

    mysqli_close($dbc);

}
  require_once('footer.php');
?>