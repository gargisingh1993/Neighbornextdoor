<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>Block Management</title>
<link rel="stylesheet" type="text/css" href="bootstrap.css">
<link rel="stylesheet" type="text/css" media="all" href="css/styles.css">
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script
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
session_start();
$conn = getconnect();
$currentusername = $_SESSION['username1'];
$user1 =  $_SESSION['username1'];
$stmt1 = $conn->prepare("Select UserID from userlogin where username = '$user1'");
$stmt1->bind_result($uid);
$stmt1->execute();
$stmt1->store_result();
while($res = $stmt1->fetch())
          { 
            $userid = $uid;
          }
$stmt1-> close();
$currentuserid = $userid;

if(isset($_POST['accepthidden']))
{
   echo 'this is set';
   $touser = $_POST['accepthidden'];
   $query = "select UserID from userlogin where Username = '$touser'";
   $result2 = $conn->query($query);
   if($result2->num_rows>0)
   {
      $row5= $result2->fetch_assoc();
	  
      $temp7= $row5['UserID'];
   }
   echo 'id is '.$temp7;
   $timeofacceptance = date("Y-m-d H:i:s");

   //$time = now();
   $myquery = "update joiningrequest set RequestStatus = 'accepted', AcceptedatTime = '$timeofacceptance' where  UserFrom = $temp7 and AcceptedBY = $currentuserid ";
   echo $myquery;
   $conn->query($myquery);
   
}
echo '<h3>Welcome '.$currentusername."<br></h3>";
if(isset($_POST['hiddenfield1']))
{
   $touser = $_POST['hiddenfield1'];
   
   $query = "select UserID from userlogin where username = '$touser'";
   $result2 = $conn->query($query);
   if($result2->num_rows>0)
   {
      $row1= $result2->fetch_assoc();
      $temp1= $row1['UserID'];
   
   $query = "select BID from userprofile where UserID = $temp1 and Block_Request_Accepted = 1";
    //  echo $query;
      $result1 = $conn->query($query);
      $major1= $result1->num_rows;
      if($major1>0)
      {
         $row = $result1->fetch_assoc();
         $block= $row['BID'];
      
   
   echo 'we need to do something here '.$touser;
//         echo ("<br>".$currentuserid."<br>".$temp1."<br>".$block."<br> end of test");
   //write the insert query
   $stmt3 = $conn->prepare("insert into joiningrequest values ($currentuserid,$temp1,$block,'0000-00-00 00:00:00','pending',now())");
   if($stmt3->execute()) echo'single insert successful';
   else 'single insert failure';
   }
     
}
   
}
if(isset($_POST['hiddenfield']))
{
   //count for pending requests
   
   $stmt = $conn->prepare("select UserID from userlogin where UserID in (select UserID from userprofile where UserID<> '$currentuserid' and Block_Request_Accepted = 1 and BID = (select BID from userprofile where UserID = '$currentuserid' ))");
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
   $major = $num_of_rows;
   
   
   
if($num_of_rows>0)
{
  // echo 'number of rows :'.$num_of_rows;
   while($row = $result->fetch_assoc())
   {
     $temp = $row['UserID'];
     // echo $temp;
      $query = "select BID from userprofile where UserID = $temp";
    //  echo $query;
      $result1 = $conn->query($query);
      $major1= $result1->num_rows;
      if($major1>0)
      {
         $row = $result1->fetch_assoc();
         $block= $row['BID'];
        // echo $block;
        $query1 = "insert into joiningrequest values ($currentuserid,$temp,$block,'0000-00-00 00:00:00',now(),'pending')";
        // echo "<br>".$query1;
         
         if($conn->query($query1)) $flag = 'true';
         else $flag = 'false';
         
      }   
   }
   if($flag=='true') echo '<br>success';
   else echo '<br>failure';
}
    else echo '<br>There are no users in this block';
   
}
$query = "select * from userprofile where UserID = $currentuserid and Block_Request_Accepted= 0";
$res = $conn->query($query);
if($res->num_rows>0)
{

echo ("<br><center><p>Do you want to send a block joining request to all the block members?</p><center>");
echo('<form method="post" action="blockrequest.php" id="formrequest">');
echo('<br>');
echo('<center><input type="button" id="sendtoall" name="sendtoall" onclick="sendtoallmembers()" value="Send Request" class="btn btn-primary"></center><br><br>');
echo('<input type="hidden" name="hiddenfield" id="hiddenfield">');
echo('</form>');
}
?>
<script>
function sendtoallmembers()
   {
     // alert('you are about to send requests to all the block members');
      document.getElementById('hiddenfield').value='all';
      document.getElementById('formrequest').submit();
   }

</script>
<?php
$stmt = $conn->prepare("select username from userlogin where UserID in (select UserID from userprofile where UserID <> '$currentuserid' and Block_Request_Accepted = 1 and BID = (select BID from userprofile where UserID = '$currentuserid' ))");
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
echo ' <center><p>Members of my block: <p><select id = "neighborselect"> <option selected>neighbor</option> </center>';
   while ($row = $result->fetch_assoc()) {
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt->free_result();
   /* close statement */
   $stmt->close();
echo "</select>";
}
?>


<?php
$stmt = $conn->prepare("select username from userlogin where UserID in (select UserID from userprofile where UserID <> '$currentuserid' and Block_Request_Accepted = 1 and BID = (select BID from userprofile where UserID = '$currentuserid'and Block_Request_Accepted = 0)) and UserID not in (select AcceptedBY from  joiningrequest where UserFrom = '$currentuserid' union select UserID from userprofile where UserID = $currentuserid and Block_Request_Accepted = 1) ");
$stmt->execute();
$result = $stmt->get_result();
$num_of_rows = $result->num_rows;
if($num_of_rows>0)
{
  // echo "<br>Do you want to send a block joining request to a particular block member?";
   echo( "<form method='post' action='blockrequest.php' id='formrequest1'>");

echo (" <br><center><p>Can Request: </p><select id = 'neighborselect1'> <option selected>neighbor</option></center>");
   while ($row = $result->fetch_assoc()) {
        echo "<option>".$row['username']."</option>";
   }
   /* free results */
   $stmt->free_result();
   /* close statement */
   $stmt->close();
echo "</select>";
   echo ("<br><br><center><input type='button' id='sendtoone' name='sendtoone' onclick='sendtoonemember()' value='Send Request' class='btn btn-primary'></center>");
echo ("<input type='hidden' name='hiddenfield1' id='hiddenfield1'>");
echo ("</form>");
}
?>

<?php 
$query4 = $conn->prepare("select BID from userprofile where UserID ='$userid'");
$query4->bind_result($block);
$query4->execute();
$query4->store_result();
?>


<?php  
// to delete the user from the table joiningrequest once he decides to leave 
if(isset($_POST['delete']))
{
$query6 = $conn->prepare("delete from joiningrequest where BID='$block'");
$result = $query6-> execute();
echo ("successfully deleted");
$query3 = $conn->prepare("delete from joiningrequest where BID='$block' and requestattime=now()");
$query3 ->execute();
if($result)
{
	$query7 = $conn-> prepare("update table userprofile set Block_Request_Accepted ='0' where BID='$block'"); 
}
else echo("failure");
}
?>

<script>
function sendtoonemember()
  
   {  document.getElementById('hiddenfield1').value=document.getElementById('neighborselect1').value;
      if(document.getElementById('neighborselect1').value=='neighbor')
      {
         alert('please select a neighbor to continue');
      }
    else{
      alert('you are about to send request to one of the block member');
       document.getElementById('formrequest1').submit();
     
     // document.getElementById('formrequest1').submit();
    }
   }
</script>

<?php

$query = "select username from userlogin where UserID in (select UserFrom from joiningrequest where AcceptedBY = '$currentuserid' and RequestStatus = 'pending')";
$result3 = $conn->query($query);
$rowcount = $result3->num_rows;
if($rowcount>0)
{
    echo '<br><center><p>Pending Block Requests: </p><select id = "pendingrequests"><option>neighbor</option></center>';
   while($row4 = $result3->fetch_assoc())
   {
   
  echo "<option>".$row4['username']."</option>";
   
   }
   echo '</select>';
   echo '<br><br><center><input type="button" value="Accept Request" id= "acceptbutton" name= "acceptbutton" onclick="acceptrequest()" class="btn btn-primary"></center>';
}

?>

<form id="acceptform" name="acceptform" method="post" action="blockrequest.php">
<input type="hidden" name="accepthidden" id="accepthidden">
</form>
<form id="deleteyourself" name="deleteyourself" method="post"  action ="blockrequest.php">
<input type="submit" name="delete" id="delete" value="tap too delete yourself">
</form>
<!--<input type="button" value="Accept Request" id= "acceptbutton" name= "acceptbutton" onclick="acceptrequest()">-->
<script>
function acceptrequest()
   {
      if(document.getElementById('pendingrequests').value == 'neighbor')
         {
            alert('please choose a request to respond');
         }
      else{
      document.getElementById('accepthidden').value = document.getElementById('pendingrequests').value;
         document.getElementById('acceptform').submit();
   
         }
   }

</script>
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
