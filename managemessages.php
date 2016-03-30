<html>
<head>
<!--<link rel="stylesheet" type="text/css" href="bootstrap.css">-->
 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<input type="button" value = "back" onclick="back()" class="btn btn-default">
<link rel="stylesheet" type="text/css" media="all" href="css/styles.css">

 <link rel="stylesheet" href="bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</head>
<body>

<!--<script src="https://maps.googleapis.com/maps/api/js"></script>-->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBfd_t-yCoHmt8Tb3BobKI___rK_QW5Q9A"></script>
    <script>
      // google.maps.event.addDomListener(window, 'load', initialize);
      function initialize() {
        var markers = [];
        var mapCanvas = document.getElementById('map');
        var mapOptions = {
          center: new google.maps.LatLng(44.5403, -78.5463),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);
        google.maps.event.addListener(map,'click',function(event) {
          
          
          document.getElementById('lat').value = event.latLng.lat();    
      document.getElementById('lng').value = event.latLng.lng(); ; 
          
          
          for (i in markers)
      {
        markers[i].setMap(null);
      }
    markers.length = 0;
       // deletemarkers();
         marker=new google.maps.Marker({
  position:event.latLng,
  });
marker.setMap(map);
        markers.push(marker);
//          marker.addListener('click', function() {
//   
//  });
         
      });
        
      }

     
    </script>

<script>
function back()
   {
           window.location.href = "profile.php";
   }

</script>

<style>
      #map {
        width: 500px;
        height: 400px;
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
	
	echo "<h2>Welcome ".$currentusername."</h2>";
	$stmt1->close();

//echo '<br>This page is to model messages';



if(isset($_POST['hiddensub']))
{
  $sub =  htmlspecialchars($_POST['hiddensub']);
  //echo ("body ".$_POST['hiddenbody']);
 $body = htmlspecialchars($_POST['hiddenbody']); // to make use of special characters as well
  //echo $_POST['hiddencat'];
  $categoryname = $_POST['hiddencat'];
 //echo $_POST['hiddenvisibility'];
  $access = $_POST['hiddenvisibility'];
  //we need to get the category id here
  $lat = $_POST['Lat'];
  $lng = $_POST['Lng'];
  //echo '<br>'.$lat . '<br>'.$lng.'<br>';
  $stmt = $conn->prepare("select CategoryID from category where CategoryName = ?");
  $stmt->bind_param("s",$categoryname);
  $stmt->execute();
  $stmt->bind_result($var1);
    /* fetch value */

   if( $stmt->fetch())
   {
     $catid = $var1;
   }
  $stmt->close();
$time = date("Y-m-d H:i:s"); // to get the time into the thread 
 
 //echo $currentuserid."    ".$sub."    ".$catid."    ".$access."     ".$time;
  
  $stmt = $conn->prepare("insert into threaddetails(AuthorID, Subject, CategoryID, Visibility, ThreadCreated) values (?,?,?,?,?)");
  $stmt->bind_param("isiis",$currentuserid,$sub,$catid,$access,$time);
  if($stmt->execute())
    echo '<br>sucessfully inserted into thread<br>';
  else 'message send failure';
  $stmt->close();
  
//   $stmt23 = $conn->prepare("insert into Message_content  (U_Id, Time_of_Reply, Body,Latitude,Longitude) values (?,?,?,?,?)");
//  $stmt23->bind_param("issdd",$currentuserid,$time,$body,$lat,$lng);
  
  
//  $stmt23 = $conn->prepare("insert into Message_content  (U_Id, Time_of_Reply, Body) values (?,?,?)");
//  $stmt23->bind_param("iss",$currentuserid,$time,$body);
//  if($stmt->execute())
    
    
    $query4 = $conn -> prepare("insert into threadreply (UserReplied, replycreatedtime, Body,replymarkedlat,replymarkedlong) values ($currentuserid,'$time','$body',$lat,$lng)");
  //echo $query;
  if($query4->execute())
    echo '<br>sucessfully inserted into Thread reply <br>';
  else '<br>message send failure<br>';
 // $stmt23->close();

$query = "select ThreadID from threaddetails where ThreadCreated = '$time'";
  $result= $conn->query($query);
  if($result->num_rows>0)
  {
    $row = $result->fetch_assoc();
    $threadvar = $row['ThreadID'];
  }
 echo("<br>thread id is".$threadvar."<br>");
 $choice = $_POST['hiddenfirstoption'];
  if($choice == 'Private')
  {
  $sub_choice = $_POST['hiddenfirstoption1'];
  //echo $sub_choice;
    $var = explode(",",$sub_choice);
    $result = count($var);
    for($i =0;$i<$result-1;$i++)
    {
      $sub_var = explode(";",$var[$i]);
       $result1 = count($sub_var);
//       for($j =0;$j<$result1;$j++)
//       {
//         echo $sub_var[$j];
//       }
     
      $query4 = "select l.UserID as UID from userlogin l,userprofile p where l.UserID = p.UserID and l.username = '$sub_var[0]' and p.username = '$sub_var[1]'";
     // echo $query4;

      $res = $conn->query($query4);
      if($res->num_rows>0)
      {
      $row= $res->fetch_assoc();
        $useridforthisthread = $row['UID'];
        //echo ("User Id is ".$useridforthisthread);
      }
      echo "<br>";
$var3 = "Private";
     $stmt3 = $conn->prepare("insert into threadrecipient values (?,?,'$var3')");
     $stmt3->bind_param("ii",$threadvar,$useridforthisthread);
    
     if($stmt3->execute())
      echo 'inserted sucessfully into thread receipient table<br>';
     else echo 'insert failure';
      $stmt3->close();
    }
  }
  if($choice == 'Block Members')
  {
    
   // echo 'block members selected';
    $query2 = "select BID from userprofile where UserID = '$currentuserid' and Block_Request_Accepted = '1'";
    $resultgargi = $conn->query($query2);
    if($resultgargi->num_rows>0)
    { 
      $row= $resultgargi->fetch_assoc();
		$userblockid = $row['BID'];
    echo 'the user is from block '.$userblockid;
    }
    
    $query = "select UserID from userprofile where UserID <> '$currentuserid' and Block_Request_Accepted = '1' and BID = '$userblockid'";
    $resultgargi2 = $conn->query($query);
    if($resultgargi2->num_rows>0)
    {
      while($row= $resultgargi2->fetch_assoc())
      {
        $var = "block";
		$uidofthisone = $row['UserID'];
        $stmt3 = $conn->prepare("insert into threadrecipient values ('$threadvar','$uidofthisone','$var')");
    
     if($stmt3->execute())
      echo 'inserted sucessfully';
     else echo 'insert failure';
      }
      $stmt3->close();
    }
    
    
  }
  if($choice == 'Neighbors')
  {
    $query = "select BID from userprofile where UserID = '$currentuserid' and Block_Request_Accepted = '1'";
    $resultgargi1 = $conn->query($query);
    if($resultgargi1->num_rows>0)
    {
      $row= $resultgargi1->fetch_assoc();
    $userblockid = $row['BID'];
   // echo 'the user is from block '.$userblockid;
    }
    $query = "select User2 from relationship where  User1 = '$currentuserid'";
    $resultgargi2 = $conn->query($query);
    if($resultgargi2->num_rows>0)
    {
      while($row= $resultgargi2->fetch_assoc())
      {
        $var = "neighbor";
        $stmt3 = $conn->prepare("insert into threadrecipient values (?,?,'$var')");
     $stmt3->bind_param("ii",$threadvar,$row['User2']);
    
     if($stmt3->execute())
      echo 'inserted sucessfully';
     else echo 'insert failure';
      }
      $stmt3->close();
    } 
  }
  if($choice == 'Friends')
  {
      echo 'friends selected';
    $query = "select BID from userprofile where UserID = '$currentuserid' and Block_Request_Accepted = '1'";
    $resultgargi1 = $conn->query($query);
    if($resultgargi1->num_rows>0)
    {
      $row= $resultgargi1->fetch_assoc();
    $userblockid = $row['BID'];
   // echo 'the user is from block '.$userblockid;
    }
    $query = "select UserReceived as A from friendrequest where (UserSent = '$currentuserid' or UserReceived = '$currentuserid') and Status = 'Friends' union select UserSent as A from friendrequest where (UserSent = '$currentuserid' or UserReceived = '$currentuserid') and Status = 'Friends'" ;
    $resultgargi2 = $conn->query($query);
    if($resultgargi2->num_rows>0)
    {
      while($row= $resultgargi2->fetch_assoc())
      {
        $var5 = "friends";
        $stmt3 = $conn->prepare("insert into threadrecipient values (?,?,'$var5')");
     $stmt3->bind_param("ii",$threadvar,$row['A']);
    
     if($stmt3->execute())
      echo 'inserted sucessfully into recipient table <br>';
     else echo 'insert failure';
      }
      $stmt3->close();
    } 
    
  }
  
  //else echo 'invalid option';
  
}
?>
<?php

if(isset($_POST['myBtn3']))
{
	header('location: homenew.php');
}

?>


<button type="button" class="btn btn-default" id="myBtn1">Start Thread</button>
<button type="button" class="btn btn-default" id="myBtn2"  onclick="viewthread()">See the thread's</button>
<!--<button type="button" class="btn btn-default" id="myBtn3" onclick="searchmessage()">Search thread's</button>
<button type="button" class="btn btn-default" id="myBtn3" onclick="showmessage()">See Thread's on map</button>-->
<form method="POST" >
<button type="submit" class="btn btn-default" id="myBtn3" name="myBtn3" method="POST"> reply to thread </button>
</form>


<br>
<br>
<br>
Your feed is here : 

<?php

$conn = getconnect();
$q= ("select recent_login from userlogin where UserID = $currentuserid");
$ress= $conn->query($q);
$row = $ress->fetch_assoc();
$lasttime = $row['recent_login'];
//echo '<br>last login at '.$lasttime.'<br>';

$query = "select ThreadID from threadreply m where m.UserReplied = $currentuserid union select ThreadID from threadrecipient r where r.Recipientlist = $currentuserid";
$res = $conn->query($query);
if($res->num_rows>0)
{
  while($row= $res->fetch_assoc())
  {
    //echo '<br>'.$row['T_Id'];
    $var = $row['ThreadID'];
  $query2 = "select replycreatedtime,Body from threadreply where ThreadID = $var";
   // echo $query2;
    $res2 = $conn->query($query2);
    if($res2->num_rows>0)
{
  while($row2= $res2->fetch_assoc())
  {
    if($row2['replycreatedtime']>$lasttime)
    echo '<br>Time:'.$row2['replycreatedtime'].' Body:'.$row2['Body'].'<br><hr>';
  }
    }
  }
}

?>


<script>
function showmessage()
  {
    window.location.href = 'showmsgonmap.php';
  }


</script>

<br>
<br>
<br>

<script>
  
  function searchmessage()
  {
    window.location.href='searchmessage.php';
  }
function viewthread()
  {
    window.location.href = "seethreads.php";
  }
</script>

<div class="modal fade" id="myModalsignup" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4><span class="glyphicon glyphicon-envelope"></span> Thread</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form role="form" method="post" action="managemessages.php" id="composeform">
            <div class="form-group">
              <label for="usrname"><span class="glyphicon glyphicon-pencil"></span> Subject:</label>
              <input type="text" class="form-control" id="s" placeholder="Enter subject" name = "msgsubject" required>
            </div>
            <div class="form-group">
				<label for="text"><span class="glyphicon glyphicon-list-alt"></span> category:</label>
				<select class="form-control" id="selectid">
				<option>Crime</option>
				<option>Health</option>
				<option>Cautions</option>
				<option>General Information</option>
				<option>Sports</option>
				</select>
              <br>
               </div>
           <div class="form-group">
              <label for="psw"><span class="glyphicon glyphicon-user"></span> Send to:</label>
              <select class="form-control"  id="selectid1" onchange="onchangefunction()">
             <option>Block Members</option>
               <option> Neighbors</option>
               <option>Friends</option>
               <option>Private</option>
              </select>
              <br>
               </div>
           
           <style>
           #specialblock
 {
  display: none; !important
 }
           
           </style>
           <div class="form-group" id="specialblock">
              <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Select Recepients:</label>
              <select class="form-control" multiple  id="multipleselect">
               <option selected="selected">default</option>
               <?php
               $query = "select l.username as A,p.username as B from userlogin l, userprofile p where l.UserID= p.UserID and l.UserID<>$currentuserid";
               $result= $conn->query($query);
               if($result->num_rows>0)
               {
                while($row=$result->fetch_assoc())
                {
//                 $name1= $row['l.Username'];
//                 $name2 =$row['p.Username'];
                 $name1= $row['A'];
                 $name2 =$row['B'];
                 $cont = "<option>".$name1 .";".$name2."</option>";
                 echo $cont;
               
                }
               }
               
               ?>
              </select>
              <br>
            Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.
               </div>
           
           <script>
           function onchangefunction()
            {
             var v = document.getElementById('selectid1').value;
             var comp = "Private";
             var n = v.localeCompare(comp);
             if(n==0)
             {
              document.getElementById('specialblock').style.display='block';
             }
             else
              {
               document.getElementById('specialblock').style.display='none';
              }
            }
           </script>
           

		   
		   
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
           
           
              <div class="checkbox">
  <label>
    <input type="checkbox" value="" id="check">
     Visible to all 
  </label>
                <br>
                <label>
       <input type="checkbox" value="" id="tagmessage">
     Tag Message 
                </label>
</div>
    <div id="map">
            </div>
<input type="hidden" id="lat" name="Lat" value="null">
<input type="hidden" id="lng" name="Lng" value="null">
            <div class="form-group">
              <label for="psw"><span class="glyphicon glyphicon-tag"></span> Content:</label>
            <textarea rows="4"  id="area" cols="50"  class="form-control" placeholder="Enter message " required>

</textarea>
              
            </div>
              <button type="submit" class="btn btn-success btn-block" name="submitbutton" onclick="send()"><span class="glyphicon glyphicon-ok"></span> Post</button>
            <input type="hidden" id="hiddensub" name="hiddensub">
            <input type="hidden" id="hiddenbody" name="hiddenbody">
            <input type="hidden" id="hiddencat" name="hiddencat">
            <input type="hidden" id="hiddenvisibility" name="hiddenvisibility">
           <input type="hidden" id="hiddenfirstoption" name="hiddenfirstoption">
            <input type="hidden" id="hiddenfirstoption1" name="hiddenfirstoption1">
          </form>
        </div>
      </div>
    </div>
  </div> 



<script>
  
  $('#tagmessage').change(function(){

    if($(this).is(':checked'))
    {
        // Checkbox is checked
      document.getElementById('map').style.display='block';
      initialize();
    }
    else
    {
        // Checkbox is not checked.
       document.getElementById('map').style.display='none';
    }    

});

  
  function send()
  {  
    document.getElementById('hiddensub').value = document.getElementById('s').value;
     document.getElementById('hiddenbody').value = document.getElementById('area').value;
    document.getElementById('hiddencat').value = document.getElementById('selectid').value;
   document.getElementById('hiddenfirstoption').value = document.getElementById('selectid1').value;
 
    if(document.getElementById('check').checked)
    document.getElementById('hiddenvisibility').value = 1;
    else
      document.getElementById('hiddenvisibility').value = 0;
       
    

    var multipleoption = document.getElementById('multipleselect');
    var x = multipleoption.length;
    var c = multipleoption.children;
    var txt = "";
    for (i = 0; i < x; i++) {
      if(c[i].selected)
        txt = txt + c[i].value + ",";
    }
 document.getElementById('hiddenfirstoption1').value =txt;   
    
  }
$(document).ready(function(){
    $("#myBtn1").click(function(){
        $("#myModalsignup").modal();
    });
});
  function myfun()
  {
    $("#myModal").close;
  }
</script>

</body>
</html>
