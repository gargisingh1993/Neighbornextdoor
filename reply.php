<?php
session_start();
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
if(isset($currentuserid))
{
 $uid = $currentuserid;
 //$name = $Name;
 $query01 = "Select `BID` from userprofile where UserID = '$Uid'";
    $res = $conn->query($query01);
    if($res->num_rows>0)
    {
    $row = $res->fetch_assoc(); 
      $bid = $row['BID'];
    }
}

if(isset($_POST['threadreply']))
{
  $threadidsend = $_POST['threadidsend'];
  $replymessage = $_POST['message'];

  $replysuccess= insertreply($conn, $threadidsend,$replymessage,$uid);
  if($replysuccess){header("Location: managemessages.php");}

}

function insertreply($conn,$threadidsend,$replymessage,$uid){
  $stmt1 = $conn->prepare("INSERT INTO threadreply(ThreadID,UserReplied,Body,replycreatedtime) values (?,?,?,NOW())");
  if($stmt1->bind_param("iis",$thd,$ud,$bod)){
  $thd = $threadidsend; $ud = $uid; $bod = $replymessage;
  if($stmt1->execute()){ 
  	return true;
     }else return false; 
  }
}	

if(isset($_POST['newpostsubmit']))
{
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];
  $subject = $_POST['subject'];
  $body = $_POST['body'];

 $newpostsuccess= insertnewpost($conn,$uid,$bid,$subject,$lat,$lng,$body);
 if($newpostsuccess){
 	header("Location: managemessages.php");
 }}

function insertnewpost($conn,$uid,$bid,$subject,$lat,$lng,$body)
{
  $stmt = $conn->prepare("INSERT INTO threadinfo(AuthorID,BID,Subject,CatID,Latpnt,Lngpnt,Visiblity,Datecreated) values (?,?,?,?,?,?,?,NOW())");
  if($stmt->bind_param("iisiddi",$ud,$ubd,$sb,$ct,$lt,$ln,$vs)){
  $ud = $uid; $ubd = $bid;$sb= $subject; $ct = 2; $lt = $lat; $ln = $lng; $vs = 1;
  if($stmt->execute()){ 
      $stmt->store_result();  
      $thrdid = "";
                 $query = "Select `ThreadID` from threadinfo where AuthorID= '$uid' and Subject = '$subject'";
                 $res = $conn->query($query);
                 if($res->num_rows>0)
                 {
                   $row = $res->fetch_assoc(); 
                   $thrdid = $row['ThreadID'];
                 }
             $stmt2 = $conn->prepare("INSERT INTO threadreply (ThreadID,UserReplied,Body,replytime) Values (?,?,?,NOW())");
             $stmt2->bind_param("iis",$td,$us,$bd);
             $td= $thrdid; $us=$uid; $bd = $body;
             if($stmt2->execute()){
             $stmt2->close();
             return true;
         }else return false;
             
           }
           }else return false;
    $conn->close();
}




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