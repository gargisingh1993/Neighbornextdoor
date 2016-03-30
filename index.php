
<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>login screen</title>
    
    
    
    
        <link rel="stylesheet" href="css/style.css">

    
    
    
  </head>

  <body>

	<?php
	$valid = "";
    $name = "";
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
		
	if(isset($_POST['login']))
	{
       // get values from the post
       $user = $_POST["Username"];
       $pass = $_POST["Password"];  
       $pas = $pass;
	    if($_SERVER["REQUEST_METHOD"] == "POST"){
	   $conn = getconnect(); // connect to database 
         //checks if user exists
         $valid = checklogin($conn,$user,$pass);
         if($valid == false){ $err = "Invalid username or password";}   
         //echo $valid;
		 }
	} 
	
	// checking 
	if ($valid)
   { session_start();
    $_SESSION['username1'] = $user;
    //$_SESSION['UserID']=$uid;
    header("location: profile.php");
  //  setcookie('ssid',$_currentSessionId,0,"/");
    
    //echo "<script type='text/javascript'>window.location.href = 'profile.php';</script>"
    exit();
   }
   
	
	
		?>
		
						

	
	
    <div class="wrapper">
	<div class="container">
	
		<br><b><br><br><br><br><h1>Welcome to neighbor network login</h1><br>
		
		<form method="POST" class="form">
		<input type="text" name= "Username" placeholder="Username" required autofocus>
		<input type="password" name = "Password" placeholder="Password" required>
		<button type="submit" name="login">Login</button><br>
		<?php echo $err;?>
		</form>	
		</div>
	
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
	
	
	// to check login
	function checklogin($conn,$user,$pass)
    {
       //$hash= ""; $ups="";
       $query = "SELECT User_Pass FROM userlogin where username = ? and User_Pass= ?";
       
       if($stmt = $conn->prepare($query))
       {
        $stmt->bind_param("ss",$usern,$pas);
        $usern = $user;
		$pas = $pass;
        $stmt->execute();
        $stmt->bind_result($ups);   

        $stmt->store_result();
       
         if(($stmt->num_rows())>0) 
          {
               $querylastlogin = "UPDATE userlogin SET recent_login = now() WHERE username='$user'";
                if (mysqli_query($conn, $querylastlogin))
                 {  return $val = true; }
             }
          }
         else{          
          return $val = false;
        }        
                 
        $conn->close();
        $stmt->close();
       }

    ?>
	
    
    
  </body>
</html>
