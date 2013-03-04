	<?php
	//deletes row, gets info from address bar
		require_once('connectvars.php');
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		
		//kicks out non-users
		if (!isset($_SESSION['user_id'])) {
			header("Location:index.php"); 
		  }
	
		$query = "SELECT email FROM a3_user WHERE user_id = '".$_GET['user_id']."'";
        $data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);
		
		$query3 = "DELETE FROM a4_blog WHERE email = '".$row['email']."'";
		$del2 = mysqli_query($dbc, $query3);
		
		$query2 = "DELETE FROM a3_user WHERE user_id = '".$_GET['user_id']."'";
		$del = mysqli_query($dbc, $query2);
		

		mysql_close($dbc);
	header("Location:members.php");
?>
