<?php
  require_once('startsession.php');
  require_once('connectvars.php');
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if (!isset($_SESSION['user_id'])) {
	header("Location:index.php");
	}
else {
		$name = 'members.csv';
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="'.$name.'"');
		$out = fopen("php://output",'w');
		// Process data and created an array called $data

		$query = "SELECT * FROM a3_user";
		$data = mysqli_query($dbc, $query);
		
		while ($row = mysqli_fetch_assoc($data)) {
			fputcsv($out, $row, ',', '"');
		}

		fclose($out);
		mysqli_close($dbc);
	
}
 ?>