<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>signup screen</title>
    
    
    
    
        <link rel="stylesheet" href="css/style.css">

    
    
    
  </head>

  <body>
  
	<div class="wrapper">
	<div class="container">
		<h1>Sign Up</h1>
		
		<form method="POST" class="form">
			<input type="text" name ="Username1" placeholder="Username" required>
			<input type="password" name="Password" placeholder="Password" required >
			<input type="password" name="Repassword" placeholder="RetypePassword" required >
			<input type="email" name="email" placeholder="email" required >
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="regis-signup">signup</button>
		</form>

    <?php
	$valid = "";
	$usern = "";
	$pas = "";
	$conn = "";
	$a = "";
	$err = ""; 
	$stmt = "";
	$mail = "";
	$ins1 = "";
	$regiserr="";
	$insuc = "";
	$uid="";
	
	
			
		if(isset($_POST['regis-signup']))
   {
       // get values from the post
       $user = $_POST["Username1"];
       $pas = $_POST["Password"]; 
       $repas = $_POST["Repassword"]; 
       $mail = $_POST["email"];  
     if($pas == $repas)
	 {    
         if($_SERVER["REQUEST_METHOD"] == "POST")
		 {
            $conn = getconnect(); // connect to database 
            //checks if user exists
             $insuc = newregis($conn,$mail,$pas,$user); 
			 echo ($insuc);
             if($insuc == false){ $regiserr = "Sorry! Registertion failed";}       
          }
      }
	  else {$regiserr = "Passwords do not match";}
    }
			
			
			// checking value of insuc and creating further session 
			if ($insuc)
   { 
	session_start();
    $_SESSION['username1'] = $_POST['Username1'];
	$_SESSION['uid']=$uid;
	header('location: createprofile.php');
   }
		?>
	
	
	
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
</div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script src="js/index.js"></script>

		
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
	
		function newregis($conn,$email,$pass,$user)
		{
		if(isset($_POST['Username1']))
		{
			$user = $_POST["Username1"];
			$conn = getconnect(); // connect to database 
			$query2 = "select username from userlogin where username = ?";
			$checkuser = $conn->prepare($query2);
			$checkuser->bind_param("s",$user);
			$checkuser->execute();
			$checkuser->bind_result($usn);   
			$checkuser->store_result();
			if(($checkuser->num_rows())>0)
			{
				echo("UserName Already exists , Please choose a different Username");
			}
			 
		else
		{
		
      $query = "INSERT INTO userlogin(User_email,User_Pass,username,recent_login) VALUES (?,?,?,now())";
      
      if($insertnew = $conn->prepare($query))
        {
            $insertnew->bind_param("sss",$mail,$pas,$usern);
            $mail = $email;
            $pas = $pass;
			$usern = $user;
            $insertnew->execute();
            // use this now for debugging
             if ( false===$insertnew )
              {
                 die('execute() failed: ' . htmlspecialchars($stmt->error));
                 $ins1 = false;
                }
             else { return true; }
            $insertnew->close();
          } 
        else{
            printf("Errormessage: %s\n", $conn->error);
         }
		/*$stmt1 = $conn->prepare ("select UserID from userlogin where username=?");
		$stmt1->bind_param("s",$user);
		$stmt1->execute();
		$stmt1->bind_result($Uid);
		$uid = $Uid;
		$stmt1->close();
		
		
		$query2 = $conn-> prepare("INSERT INTO userprofile(UserID) values (?)");
		$query2->bind_param("i",$uid);
		$query2->execute();
		$query2-> close();*/
	   
	   }
	   
	   
	   
		}
		}
		
		?>
    
    
	
    
  </body>
</html>