<?php // starts the session and retrieves value from the previous page of the session variable 
session_start();
if(isset($_SESSION['username1']))
{
	//echo $_SESSION['username1'];
}
$conn = getconnect();
$user = $_SESSION['username1'];
$stmt1 = $conn->prepare("Select UserID from userlogin where username = '$user'");
$stmt1->bind_result($userid);
$stmt1->execute();
$stmt1->store_result();
while($res = $stmt1->fetch())
          { 
            $uid = $userid;
          }
$_SESSION['userid'] = $uid;

// to find the block id 
$stmt2 = $conn->prepare("Select BID from userprofile where UserID = '$uid'");
$stmt2->bind_result($bid);
$stmt2->execute();
$stmt2->store_result();
while($res = $stmt2->fetch())
          { 
            $Bid = $bid;
          }
?>




<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>Neighbor Management</title>
 <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
   <link rel="stylesheet" type = "text/css" href ="css/bootstrap-min.css">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script>
function goback()
  {
     window.location.href = "profile.php";
  }

</script>
</head>
<body>
<input type="button" value="back" onclick="goback()" class="btn btn-primary">
<script>
function goback()
  {
     window.location.href = "profile.php";
  }
</script>
<?php 
// now we write the query to add the neighbor for this session user 

if(isset($_POST['nameofneighbor']))
{
   echo 'neighbor added'.$_POST['nameofneighbor']."by" .$_SESSION['userid'];
  $touser = $_POST['nameofneighbor'];
   $query = "select UserID from userlogin where username = '$touser'";
   //echo $query;
   $result = $conn->query($query);
   if($result->num_rows>0)
   {
   $row = $result->fetch_assoc();
       echo "iiiiiii".$row['UserID'];
       $toid= $row['UserID'];
		$fromid = $userid;
   //$status = 'Pending';      
        // we should not resend requests
      $stmt = $conn->prepare("select User2 from relationship where User1 = ? and User2=?");
   $stmt->bind_param("ii",$fromid,$toid);
   $stmt->execute();
   $result = $stmt->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
   echo 'Neighbor already exists' ;
}
   else{

   $stmt = $conn->prepare("insert into relationship (User1,User2) values (?,?)");
   $stmt->bind_param("ii",$fromid,$toid);
   $stmt->execute();
   
   echo("query successful");
   }
   }
}
?>

<?php
$stmt1 = $conn->prepare("select userlogin.username from 
						userprofile natural join userlogin 
						where BID=? and userlogin.UserID <>'$uid' and userlogin.UserID not in 
						( select User2 from relationship where User1='$uid')  ");
$stmt1->bind_param("i",$Bid);
$stmt1->execute();
$result = $stmt1->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
echo ' <center><br><br><p>Add Neighbor:</p> <select class="container" id = "neighborselect"> <option selected>neighbor</option></center><br><br>';
   while ($row = $result->fetch_assoc()) {
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt1->free_result();
   /* close statement */
   $stmt1->close();
echo "</select>";
}
else echo 'All the block members are already added as neighbors!';
// starts the html for button of adding 
?>

<center>
<form method="post" action="neighbormanage.php" id="myForm" class="container">
   <br>
<input type="hidden" name="nameofneighbor" id="nameofneighbor" required >
  <p> Add the neighbor you think is your neighbor : </p>
<input type="button" value="Add neighbor" onclick="addneighbor()" name= "submitbutton" class="btn btn-primary"><br><br>
</form>
</center>

<script>
   
function addneighbor()
   {
      if(document.getElementById("neighborselect").value=='neighbor') { alert('please choose a friend');}
      else{
         console.log(document.getElementById("neighborselect").value);
      document.getElementById('nameofneighbor').value = document.getElementById("neighborselect").value;
      document.getElementById("myForm").submit();
      }
   }

</script>

<?php

// now this part shows the already added neighbors by the user of this session 

$stmt = $conn->prepare("select username from userlogin where UserID in (select User2 from relationship where User1 =?) ");
$stmt->bind_param("i",$uid);
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
echo ' <center><br><p>My Neighbors :</p> <select id = "neighborselect1"><option>neighbors</option></center>';
   while ($row = $result->fetch_assoc()) {
      echo $row['username'];
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt->free_result();
   /* close statement */
   $stmt->close();
echo "</select>";

?>

</body>
<div class="container">
<form class="container" method="POST" action="neighbormanage.php">




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
<html>