<html>
<head>
<link rel="stylesheet" type = "text/css" href ="css/bootstrap-min.css">
 <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
 <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
 </head>
 <body>
 <?php
		
			function getconnect(){
       // connect to database
       $servername = "localhost";
       $username = "root";
       $password = "";
       $database = "neighbor_network";

       $con = new mysqli($servername,$username, $password, $database);
      //check connection
       if ($con->connect_error){ 
           echo "conct err";
           die("Connection failed: " . $con->connect_error);
       }else {return $con;}
    }
	
 ?>
 
 <div id="viewmessage" class="clearfix">
	<form class="clearfix" method="POST" action ="messageview.php">
	<input type="text" name="nameofusereplying" id="nameofusereplying" placeholder="nameofusereplying" value="<?php echo '$userid'; ?>">
	<input type="textbox" name="reply" id="reply" placeholder="reply" value="<?php echo '$reply'; ?>"