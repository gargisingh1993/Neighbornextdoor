 <html>
 <head>
 
 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<input type="button" value = "back" onclick="back()" class="btn btn-default">
<link rel="stylesheet" type="text/css" media="all" href="css/style.css">

 <link rel="stylesheet" href="bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </head>
  <body>

<script>
function back()
   {
           window.location.href = "managemessages.php";
   }

</script>
<style>
.messageabstract
 {
  border-radius: 5px;
    
  margin-top: 5px;
  background: #CCCC99;
  margin-left: 5px;
  width: 200px;
  display: fixed;
  color: #000;
  transition: background 0.3s 0s, color 0.5s 0s;
 }
.messageabstract:hover
 {
  background: #666633;
  color: #fff;
/*  border: 2px solid #66a3ff;*/
 }
 body
 {
  background: #fff;
 }
 h2{
  color: #000;
 }
 #hidden{
  display: none;
 }


</style>
<?php
session_start();
//include 'db_connect.php';
	$conn = getconnect();
	$currentusername = $_SESSION['username1'];
	$stmt1 = $conn->prepare ("select UserID from userlogin where username='$currentusername'");
	$stmt1->execute();
	$stmt1->bind_result($Uid);
	$stmt1->store_result();
	while($res=$stmt1->fetch())
	{
		$currentuserid = $Uid;
	}
	echo ($currentuserid);
	echo ("<h2>".$currentusername.", You can see threads here.</h2>");
	$query1 = "select distinct(ThreadID) from threadrecipient r natural join threaddetails d where r.Recipientlist = '$currentuserid' or d.AuthorID ='$currentuserid'";
	$ress = $conn->query($query1);
	$num_of_rows = $ress->num_rows;
	if($num_of_rows>0)
	while($row = $ress->fetch_assoc())
{			
			$threadid = $row['ThreadID'];
				$query2 = "select AuthorID, Subject, CategoryID from threaddetails where ThreadID = $threadid ";
				$res2=$conn->query($query2);
				if($res2->num_rows>0)
	{
   while($row2= $res2->fetch_assoc())
   {
		$idofuser = $row2['AuthorID'];
		$idofcat = $row2['CategoryID'];
		echo ($idofuser);
		$query3 = "select username from userprofile where UserID = $idofuser ";
		$res3=$conn->query($query3);
		$row3 =$res3->fetch_assoc(); 
		$query4 = "select CategoryName from category where CategoryID = $idofcat ";
		$res4=$conn->query($query4);
		$row4 =$res4->fetch_assoc(); 
		echo ('<div id = "mydiv" name = "mydiv" class = "messageabstract " onclick="clickedme(this)">Author:<u> '.$row3['username'].'</u><br> Subject: <u>'.$row2['Subject'].'</u> <br> Category: <u>'.$row4['CategoryName'].'</u><u id="hidden">'.$threadid.'</u></div>');
   }
  }
}
?>
<?php
 
?>

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
<script>
function clickedme(v)
 {
//  alert('clickedme');
	var v= document.getElementById('mydiv');
   var c = v.children;
    var txt = "";
    for (i = 0; i < c.length; i++) {
        txt = txt + c[i].innerHTML+"!";
    }
  alert(txt);
 }


</script>
</body>
</html>
