<?php
  echo '<hr />';
  if (isset($_SESSION['email'])) {
  //navmenu for logged in
    echo '<a href="index.php">Home</a> <br /> ';
    echo '<a href="profile.php">Profile</a> <br /> ';
    echo '<a href="members.php">Members</a> <br /> ';
	echo '<a href="blog.php">Blog</a> <br /> ';
    echo '<a href="logout.php">Log Out (' . $_SESSION['email'] . ')</a>';
  }
  else {
    //navmenu for logged out
    echo '<a href="index.php">Log In</a> <br /> ';
    echo '<a href="signup.php">Sign Up</a>';
  }
  echo '<hr />';
?>
