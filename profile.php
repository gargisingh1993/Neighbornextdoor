<?php
session_start();
	
	if(isset($_POST['logout']))
	{
		session_destroy();
		header("location: welcome.php");
		exit();
	  }
if(isset($_SESSION['username1']))
{
	//echo $_SESSION['username1'];
}
$user = $_SESSION['username1'];
?>


<?php
$user1 = $user;
$conn = getconnect();
$stmt1 = $conn->prepare("Select UserID from userlogin where username = '$user1'");
$stmt1->bind_result($uid);
$stmt1->execute();
$stmt1->store_result();
while($res = $stmt1->fetch())
          { 
            $userid = $uid;
          }
$stmt1-> close();
$stmt = $conn -> prepare("Select Name,sex,DOB,Bio,Maritalstatus from userprofile where UserID = ?");
$stmt->bind_param("i",$userid);
$stmt->bind_result($name,$gender,$dob,$bio,$ms);
$stmt ->execute();
$stmt ->store_result();
if($stmt->num_rows>0)
{	while($ress = $stmt->fetch())
	{
		$Name=$name;
		$Sex = $gender;
		$DOB = $dob;
		$Bio = $bio;
		$Mstatus=$ms;
		$d= date($DOB);
        $d= explode(" ",$d);
		//echo ($Sex);
	}
}
?>


<?php
      if($Sex=='male') 
	  { ?> <script>
document.getElementById('radio11').checked= true;
document.getElementById('radio22').checked= false;  </script>
<?php }
      if($Sex=='female')
	  { ?><script> 
document.getElementById('radio22').checked= true;
document.getElementById('radio11').checked= false;  </script>
<?php }
?>


<?php
// for updating the location of the user and his profile 
if(isset($_POST['update']))
{
	header('location: updateprofile.php');
}
?>

<?php
// query for getting the friends names printed  : 
	$query3 = $conn->prepare("select username from userlogin where UserID in  
							 (select r.UserReceived from friendrequest r where r.UserSent = ? and r.Status= 'Friends' 
							 union select r.UserSent from friendrequest r where r.UserReceived = ? and r.Status= 'Friends' ) ");
	$query3->bind_param("ii",$userid,$userid);
	$query3->bind_result($receivedname);
	$query3->execute();
	$query3->store_result();
	/*while($ress = $query3->fetch())
          {	
             echo $receivedname; echo("\n"); 			
          }*/
?>

<?php
// query for getting the neighbor information names printed  :
	$query4 = $conn->prepare("Select username
								from userlogin where UserID 
								IN (select User2 from relationship where User1 = ? )");
	$query4->bind_param("i",$userid);
	$query4->bind_result($neighborname);
	$query4->execute();
	$query4->store_result();
	
	
	
?>
<?php 
if(isset($_POST['updatefriend']))
{
	header('location: friendsmanage.php');
}

if(isset($_POST['updateneighbor']))
{
	header('location: neighbormanage.php');
}


if(isset($_POST['threadcreate']))
{
	header('location: managemessages.php');
}

if(isset($_POST['blockrequest']))
{
	header('location: blockrequest.php');
}
?>





<!-- html starts here -->


<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User Profile with Content Tabs </title>
  <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
  <link rel="stylesheet" type = "text/css" href ="css/bootstrap-min.css">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
</head>

<body>
  
  <div id="w">
    <div id="content" class="clearfix"> 
	<form method="POST" class="form">
		<button type="submit" name="logout" id="logout" class="btn-default"> Logout </button>
      <div id="userphoto"><img src="images/avatar.png" alt="default avatar"></div>
      <h1>Your Profile </h1>

      <!--<nav id="profiletabs">
        <ul class="clearfix">
          <li><a href="#"  id="show_bio" class="bio">Bio</a></li>
          <li><a href="#"  id = "show_activity">Activity</a></li>
          <li><a href="#"  id="show_friend">Friends</a></li>
          <li><a href="#"  id="show_set">Settings</a></li>
        </ul>
      </nav>-->
    
		<div id="bio" class="biodetails">
		<p>Username:</p> <input type="text" name="name"  id="text1" value="<?php echo $Name;?>" disabled><br>
		<br>
		<p>Gender:</p> <input type="radio" name="gender" value="male"  id="radio11">Male
				<input type="radio" name="gender" value="female"  id="radio22">Female
					<br>
					<br>
		<p>Date of Birth:</p><input type="date" name="DOB" id="dobid" value="<?php   
        echo $d[0]; ?>" disabled><br><br>
		<p>Marital Status:</p><input type="Text" name = "Mstatus" value = "<?php echo $Mstatus;?>" disabled><br>
		<br>
		<p>Description:</p> <textarea name="Description" rows="4" cols="50" disabled id="idfortextarea">
		<?php $Bio= trim($Bio); echo $Bio;?>
					</textarea>
					
					
		</form>
		</div>
		<br><br><br>
		<div id ="details">
			<form method="POST" class="details"> 
			<p> do you want to update your profile : 
			<button type="submit" name="update" id="update" class="btn btn-default"> click to update </button>
			</p>
			</form>
			</div>
   
      <br>
	  <br>
	  <br>
      <div id="activity" class="Activity">
	  <form method="post">
        <p>Most recent threads:</p>
		
		<button type="submit" name="threadcreate" id="threadcreate" class="btn btn-default" > create a new message </button>
		</form>
      </div>
      <br>
	  <br>
	  <br>
      <div id="friends" class="friend">
        <ul id="friendslist" class="clearfix">
		<br>
		<p> Friends list:</p>
		
         <!-- <li><a href="#"><img src="images/avatar.png" width="22" height="22"> Username</a></li>
          <li><a href="#"><img src="images/avatar.png" width="22" height="22"> SomeGuy123</a></li>
          <li><a href="#"><img src="images/avatar.png" width="22" height="22"> PurpleGiraffe</a></li> -->
		  <li><?php // printing the friends name's 
				while($ress = $query3->fetch())
          {	
			
			 echo (" $receivedname <br>"); 	
				
          }			
				?></li>
		  
        </ul><br>
		<form method = "POST">
		<button type = "submit" name="updatefriend" class = "btn btn-default"> manage your friends </button><br>
		</form>
      </div>
	  
	  <div id="neighbours" class ="neighbour">
	  <ul id="neighbourlist" class="clearfix">
	  <br>
	  <p> Neighbours list: </p>
	  
	  <li><?php  // printing the neighbor list 
		while($res = $query4->fetch())
	{
		echo ("$neighborname <br><br>"); echo("\n");
	}
			?></li><br>
	  </ul>
	  <form method ="POST" >
	  <button type="submit" name="updateneighbor" class="btn-default"> Manage your neighbors </button><br><br>
	  </form>
	  
	  </div>
	  
      <br>
	  <br>
	  <br>
	  
	  <div id="block" class ="block">
	  <form method ="POST" >
	  <button type="submit" name="blockrequest" class="btn-default"> Manage request to become a part of the block </button><br><br>
	  </form>
	  
	  </div>
	  
      <!--<div id="settings" class="set">
        <p>Edit your user settings:</p>
		<form method="POST" class ="form">
		<input type="Text" name="Name" placeholder="Name"><br>
		<input type = "text" name="sex" placeholder="Gender"><br>
		<input type = "E-mail" name="email" placeholder="E-mail"><br>
		<input type ="date" name="DOB" placeholder="DOB"><br>
		<input type ="Text" name="Mstatus" placeholder="Marital statues"><br>
		<button type ="submit" name="Update">Update</button> 
		</form>
      </div>-->
    </div><!-- @end #content -->
  </div><!-- @end #w -->

  
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
	
	<?php
	
	// destroying the session at logout 

?>

</body>
</html>