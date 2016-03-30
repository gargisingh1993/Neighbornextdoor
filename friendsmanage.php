<?php
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
?>
<html>

<head>

<meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User Profile with Content Tabs </title>
 <link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
  <link rel="stylesheet" type = "text/css" href ="css/bootstrap-min.css">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

</head>
<body>
<div> 
<script>
function goback()
  {
     window.location.href = "profile.php";
  }
</script>

<?php
$temp = "";
//session_start();
//$uid = $_SESSION['userid'];
if(isset($_POST['hiddenfriendname'])&& $_POST['hiddenfriendname1']!="")
{
   $name = $_POST['hiddenfriendname1'];
   echo 'reject button is clicked'.$name;
   $query = "update friendrequest set Status= 'Rejected' where UserReceived = '$uid' and UserSent =(select UserID from userlogin where username = '$name')";
   $conn->query($query);
}
if(isset($_POST['hiddenfriendname1'])&& $_POST['hiddenfriendname']!="")
{
   $name = $_POST['hiddenfriendname'];
   echo 'accept button is clicked'.$name;
   $query = "update friendrequest set Status= 'Friends' where UserReceived = '$uid' and UserSent =(select UserID from userlogin where username = '$name')";
   $conn->query($query);
}

if(isset($_POST['nameofreceiver']))
{
   echo 'request sent to'.$_POST['nameofreceiver']."from " .$_SESSION['userid'];
  $touser = $_POST['nameofreceiver'];
   $query = "select UserID from userlogin where username = '$touser'";
   echo $query;
   $result = $conn->query($query);
   if($result->num_rows>0)
   {
   $row = $result->fetch_assoc();
       echo "iiiiiii".$row['UserID'];
       $toid= $row['UserID'];
   $fromid = $_SESSION['userid'];
   $status = 'Pending';      
        // we should not resend requests
      $stmt = $conn->prepare("select * from friendrequest where UserSent = ? and UserReceived = ? and Status!='Rejected'");
   $stmt->bind_param("ss",$fromid,$toid);
   $stmt->execute();
   $result = $stmt->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
   echo 'request already sent';
}
   else{

   $stmt = $conn->prepare("insert into friendrequest (UserSent,UserReceived,Timesent,Status) values (?,?,now(),?)");
   $stmt->bind_param("iis",$fromid,$toid,$status);
   $stmt->execute();
   }
   }
}

?>
<h3> Welcome <?php echo ($_SESSION['username1']); ?></h3>
<?php
$idofthisuser  = $_SESSION['userid'];
//echo "This page is for managing friend requests<br><br><br><br>";
$stmt = $conn->prepare("select username from userlogin where UserID <> (?) and UserID not in (select UserReceived from friendrequest where Status='Friends' and UserSent = (?)) and UserID  not in (select UserSent from friendrequest where Status='Friends' and UserReceived = (?))");
$stmt->bind_param("iii",$idofthisuser,$idofthisuser,$idofthisuser);
$stmt->execute();

$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
echo ' <center><p>Add Friend:</p> <select class="container" id = "friendselect"> <option selected>friend</option></center><br><br>';
   while ($row = $result->fetch_assoc()) {
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt->free_result();
   /* close statement */
   $stmt->close();
echo "</select>";
}
else echo 'All the users of this website are your friends!'; // if all users are already his friends 
?>
<center>
<form method="post" action="friendsmanage.php" id="myForm" class="container">
   <br>
<input type="hidden" name="nameofreceiver" id="nameofreceiver" required >
  <p> Send Friend Request:</p>
<input type="button" value="Send Request" onclick="sendrequest()" name= "submitbutton" class="btn btn-primary"><br><br>
</form>
</center>

<script>
   
function sendrequest()
   {
      if(document.getElementById("friendselect").value=='friend') { alert('please choose a friend');}
      else{
         console.log(document.getElementById("friendselect").value);
      document.getElementById('nameofreceiver').value = document.getElementById("friendselect").value;
      document.getElementById("myForm").submit();
      }
   }

</script>
<?php
//$stmt = $conn->prepare("select Username from Login where U_Id in  (select r.Acceptor from Request_For_friendship r where r.Sender = ? and r.Status= 'accepted') ");
$stmt = $conn->prepare("select username from userlogin where UserID in  (select r.UserReceived from friendrequest r where r.UserSent = ? and r.Status= 'Friends' union select r.UserSent from friendrequest r where r.UserReceived = ? and r.Status= 'Friends' ) ");
$stmt->bind_param("ii", $idofthisuser,$idofthisuser);
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
echo ' <center><p>My Friends:</p> <select id = "friendselect"><option>friend</option></center>';
   while ($row = $result->fetch_assoc()) {
      echo $row['username'];
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt->free_result();
   /* close statement */
   $stmt->close();
echo "</select>";

echo '<br>';
/////////////////////////////
$stmt = $conn->prepare("select username from userlogin where UserID in  (select r.UserSent from friendrequest r where r.UserReceived = ? and r.Status= 'Pending') ");
$stmt->bind_param("i", $idofthisuser);
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
echo '<br> <p>Pending Requests: </p> <select class="container" id = "friendselect1"><option selected>friend</option> ';
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

<br>
<br>
<input type="button" name="acceptrequest" id="acceptid" value="Accept Request" onclick="acceptid()" class="btn btn-primary">
<input type="button" name="rejectrequest" id="rejectid" value="Reject Request" onclick="rejectid()" class="btn btn-primary">
<br>
<form method="post" action="friendsmanage.php" id="myform2">
<input type="hidden" name="hiddenfriendname" id="hiddenfriendnameid" >
   <input type="hidden" name="hiddenfriendname1" id="hiddenfriendnameid1"  >
   </form>
<script>
function acceptid()
   {
      if(document.getElementById("friendselect1").value=='friend') {
        
         alert('please choose a friend to accept his request');}
      else{
         alert('button clicked');
          document.getElementById("hiddenfriendnameid").value=document.getElementById("friendselect1").value;
          document.getElementById("myform2").submit();
      }
   }
   function rejectid()
   {
      if(document.getElementById("friendselect1").value=='friend') { 
          
         alert('please choose a friend to reject his request');}
      else{
         alert('button clicked');
         document.getElementById("hiddenfriendnameid1").value=document.getElementById("friendselect1").value;
          document.getElementById("myform2").submit();
      }
   }
  

</script>
<div class="clearfix">
<form class="clearfix" method="POST" ><br><br><br>
<button type="submit" name="returntoprofile" id="returntoprofile"> return to profile </button>
</form>
</div>






<?php
if(isset($_POST['returntoprofile']))
{
	header('location: profile.php');
}
?>


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
</div>
</body>
</html>