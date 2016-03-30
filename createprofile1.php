<?php
session_start();
$username = $_SESSION['username1'];
echo ($username);
?>
<?php 
	$conn = getconnect();
	$stmt1 = $conn->prepare ("select UserID from userlogin where username=?");
	$stmt1->bind_param("s",$username);
	$stmt1->execute();
	$stmt1->bind_result($Uid);
	while($res=$stmt1->fetch())
	{
		$uid=$Uid;
	}
	//$name = $_POST["Name"];
    //echo $name;
			
	if(isset($_POST['submit']))
	{		
		if(isset($_POST["Name"]))
		{
			$name = $_POST["Name"];
		}
		if(isset($_POST["DOB"]))
		{
			$dofb = $_POST["DOB"];
		}
		if(isset($_POST["MStatus"]))
		{
			$ms = $_POST["MStatus"];
		}
		if(isset($_POST["Intro"]))
		{
			$intro = $_POST["Intro"];
		}
				if(isset($_POST["male"]))
				{
					$gen = "Male";
				}
				elseif(isset($_POST["female"]))
				{
					$gen = "Female";
					}
			
			//$conn = getconnect(); // connect to the database 
			$stmt = $conn->prepare("update userprofile set Name=(?),DOB=(?),sex=(?),Maritalstatus=(?),Bio=(?),profilecreated= now() where UserID = (?)");
			$stmt->bind_param("sssssi",$name,$dofb,$gen,$ms,$intro,$uid); 
			$stmt->execute();
			$stmt->close();
			echo ("control is here");
			header("Location: location.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Add information to Your New Profile</h2>
  <form class="form-horizontal" role="form" method="POST" action ="createprofile.php">
    <div class="form-group">
      <label class="control-label col-sm-2" for="Name">Name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="Name" name="Name" placeholder="Enter Name" required autofocus >
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="DOB">Date of Birth</label>
      <div class="col-sm-10">          
        <input type="date" class="form-control" id="DOB" name="DOB" placeholder="Enter Date of Birth" required>
      </div>
    </div>
	<!-- for new fields
	<div class="form-group">
      <label class="control-label col-sm-2" for="Gender">Gender</label>
      <div class="col-sm-10">   
			<input type ="text" class ="form-control" id="Gender" placeholder="Gender">
		</div>
	</div>-->
	<div data-role="main" class="ui-content">
    <!--<form method="post" action="demoform.asp">-->
      <fieldset data-role="controlgroup">
      <label class="control-label col-sm-2" for="male"></label>
        <label for="male">Male</label>
        <input type="radio" name="gender" id="male" value="male" checked>
       <label for="female">Female</label>
        <input type="radio" name="gender" id="female" value="female">
      </fieldset>
	  </div><br>
	<div class="form-group">
      <label class="control-label col-sm-2" for="MStatus"> Marital Status</label>
      <div class="col-sm-10">   
			<!--<input type ="text" class ="form-control" id="MStatus" placeholder="Marital Status">-->
			<select class="form-control" id="MStatus" name="MStatus">
				<option value="Married">Married</option>
				<option value="Single">Single</option>
				<option value="Complicated">Complicated</option>
				<option value="Wont disclose">Wont disclose</option>
			</select>

		</div>
	</div>
	<div class="form-group">        
	  <label class="control-label col-sm-2" for="Intro">Intro</label>
	  <div class="col-sm-10">
	<textarea rows="4" cols="50" id="Intro" name ="Intro" class="form-control" >
	</textarea>
	</div>
      </div>
	
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label><input name = "checkbox" type="checkbox" required>Check me to submit</label>
        </div>
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <input type="submit" name ="submit" class="btn btn-default" value="submit">
      </div>
    </div>
	
  </form>
</div>

<?php

function getconnect() 
	{
	
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

</body>
</html>

