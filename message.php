<?php
session_start();
if(isset($_SESSION['username1']))
{
	//echo $_SESSION['username1'];
	$user = $_SESSION['username1'];
}

// to get the user id convert it into the seesion variable and get it passed before the submission 
$user1 = $user;
$conn = getconnect();
$stmt1 = $conn->prepare("Select UserID from userlogin where username = '$user'");
$stmt1->bind_result($uid);
$stmt1->execute();
$stmt1->store_result();
while($res = $stmt1->fetch())
          { 
            $userid = $uid;
          }
$stmt1-> close();
?>

<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>Messages</title>
  <link rel="stylesheet" type = "text/css" href ="css/bootstrap-min.css">
 <link rel="stylesheet" type="text/css" media="all" href="css/messagestyle.css">
 <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
</head>



<?php // this is the select the category of the new thread 
$conn = getconnect();
$query1 = $conn->prepare("Select CategoryName from category");
$query1->bind_result($cname);
$query1->execute();
$query1->store_result();

?>
<?php
// to show the present friends and select to who to send the direct msg
$stmt = $conn->prepare("select username from userlogin where UserID in  (select r.UserReceived from friendrequest r where r.UserSent = ? and r.Status= 'Friends' union select r.UserSent from friendrequest r where r.UserReceived = ? and r.Status= 'Friends' ) ");
$stmt->bind_param("ii", $uid,$uid);
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
  
?>


<?php  // inserting into the thread table 
if(isset($_POST['createthread']))
{
$stmt3 = $conn->prepare("insert into threadetails values('$userid','$subject','$category','visibility',now())");
$stmt3->execute();
}

?>

<body>
<?php 
if(isset($_POST['selectcategory']))
{
	$category = $_POST['selectcategory'];
	$query4-> $conn->prepare("select CategoryID from category where CategoryName='$category'");
	$query4->bind_result($catid);
	$query4->store_result();
}
if(isset($_POST['subject']))
{
	$subject = $_POST['subject'];
}
if(isset($_POST['receipients']))
{
	$receip = $_POST['receipients'];
	if($receip='block' or $receip='hood')
	{
		$visibility = 1;
		select UserID from userprofile where BID = '$bid'
	}
	
}

if(isset($_POST['startmessage'])) // to insert a new message into the table 
{ 
	$query = "insert into threaddetails(AuthorID,Subject,CategoryID,Visibility,Threadcreated) values ('$userid','$subject','$Catid','$Visibility',now())";
	$result= $conn->query($query);
	if($result)
	{
		$query9 = $conn->prepare("select ThreadID from threaddetails where ")
	}
	echo("control is here");
	header('location : messageview.php');
}

?>

<div class="form">
	<form class="form" method ="Post" >
	<center><p> Start a new thread with category: </p></center> <?php
	echo '<br> <select class="form" name="selectcategory" id = "selectcategory" ><option>general</option> ';
   while ($res = $query1->fetch()) {
      //echo $res['CategoryName'];
        echo "<option>".$cname."</option>";
   }?>
   </form>
   </div>
   <br><br>
<div class ="form">
		<form class="form" method="post">
<center><br><br><br><input type="text" name="subject" id="subject" placeholder="Subject">
		<center><p>To whom you wanna send this message :</p>
		<select class="form" id="receipients" name="receipients" ><option> block </option>
		<option>hood </option>  <option>friends </option>  <option>private</option></select></center>
		<?php	echo ' <center><p>My Friends:</p> <select class="form" name="friendselect" id = "friendselect"><option>friend</option></center>';
				while ($row = $result->fetch_assoc()) {
				//echo $row['username'];
				echo "<option>".$row['username']."</option>";
				}	 /* free results */
					$stmt->free_result();
					/* close statement */
					$stmt->close();
					echo "</select>";
		?>
		<textarea rows="3" cols="40" name="body" id="bodyofmessage" placeholder="type your message"></textarea>
		</form>
		<center>
		<form method="POST" action="message.php" id="myForm" class="container">
			<br>
		<input type="hidden" name="" id="selectedcategory" required >
		<center><p> Post the message </p></center>
		<input type="submit" name= "startmessage" class="btn btn-primary"><br><br>
		<br><br><br><br>
		<p> <input type = "button" name="list" value="List of messages not seen - click to open"> </p>
		</form>
		</center>
		</div>
</body>

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
</html>