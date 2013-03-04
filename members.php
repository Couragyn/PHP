<?php
  require_once('startsession.php');
  $page_title = 'View Users';
  require_once('navmenu.php');
  require_once('header.php');
  require_once('appvars.php');
  require_once('connectvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  
//kicks out non-users
    if (!isset($_SESSION['user_id'])) {
	header("Location:index.php"); 
  }
  //imports csv
  if(isset($_POST['submit'])){
     $fname = $_FILES['csvf']['name'];
     $chk_ext = explode(".",$fname);
    
     if(strtolower($chk_ext[1]) == "csv"){
	 
    
         $filename = $_FILES['csvf']['tmp_name'];
         $handle = fopen($filename, "r");
		 
		 $diff = true;
		 $query = "SELECT * FROM a3_user";
		 $check = mysqli_query($dbc, $query);
		 
   
         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
		 //makes sure there is no existing user with email
			while ($row = mysqli_fetch_array($check)){
				if ($row['name'] == $data[0]){
					$diff = false;
					break;}
				}
				if ($diff == true){
					if ($data[3] != 1){
						$query = "INSERT into a3_user (name, email, password, join_date) values('$data[0]','$data[1]','$data[2]', NOW())";
						$data = mysqli_query($dbc, $query);
						}
					else{
						$query = "INSERT into a3_user (name, email, password, join_date) values('$data[0]','$data[1]',SHA('$data[2]'), NOW())";
						$data = mysqli_query($dbc, $query);
						}
				}
			
         }
   
         fclose($handle);
         echo "Successfully Imported";

     }
	 //error
     else
     {
         echo "Invalid File";

     }   
}
  
  
  //excludes the user logged in
  $query = "SELECT * FROM a3_user WHERE user_id != '".(int)$_SESSION['user_id']."'";
  $data = mysqli_query($dbc, $query);
  
  //shows all users (but yourself)
  echo '<table>';
  while ($row = mysqli_fetch_array($data)) {
    if (is_file(MM_UPLOADPATH . $row['avatar']) && filesize(MM_UPLOADPATH . $row['avatar']) > 0) {
      echo '<tr><td><img src="' . MM_UPLOADPATH . $row['avatar'] . '" alt="Avatar" /></td>';
	  echo '<td>' . $row['email'] . '</td>';
	  echo '<td>' . $row['name'] . '</td>';
	  echo '<td>' . $row['join_date'] . '</td>';

	  echo '<td><a href="delete.php?user_id='.$row['user_id'].'">Delete</a></td></tr>';
	  echo '<td><a href="blog.php?email='.$row['email'].'">View Blog</a></td></tr>';
    }


}
  echo '</table>';
  ?>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
    <fieldset>
      <legend>Upload CSV file</legend>
      <label for="new">CSV file:</label>
      <input type="file" id="csvf" name="csvf" />
    </fieldset>
    <input type="submit" value="Upload CSV" name="submit" />
  </form>
  <?php
  	  echo '<td><a href="export.php">Export CVS</a></td></tr>';


  require_once('footer.php');
?>