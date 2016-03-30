

<!DOCTYPE html>

<?php
session_start();

$uid = $name= $lat= $lng= $subject= $body= $ubd= $bid=$showthread="";

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
$query = "Select * from userprofile where UserID= '$Uid'";
    $res = $conn->query($query);
    if($res->num_rows>0)
 	{
 		$row = $res->fetch_assoc(); 
 	    $name = $row['Name'];
      $bid = $row['BID'];
 	}
?>
<html lang="en">
  <head>
    
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
 


        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Welcome <?php echo $name; ?></h1>
      <!-- <button id="newpost" type= "button" class="btn btn-success" data-toggle = "modal" data-target ="#postTopicModal" > Create New Post</button> -->
        
          <h2 class="sub-header"></h2>

        </div>
     
<div class= "section">
      </div>
<div class= "section">


</div><br><br>
<div class= "section">

<?php
   // code to display all threads
   $query = "Select * from userprofile where BID = '$bid'";
    $res = $conn->query($query);
    if($res->num_rows>0)
 	{
 		$row = $res->fetch_assoc(); 
      $id = $row['UserID'];
	}
   $authd = "";
   $query6 = "SELECT `ThreadID`,`AuthorID`,`Subject`,`ThreadCreated` from threaddetails where Authorid = '$id' order by ThreadCreated; ";
   $stmt3 = $conn->prepare($query6);
   $stmt3->execute();
   $stmt3->bind_result($thd,$authd,$sub,$dtc); 
   $stmt3->store_result();
   $stmt4 = $conn->prepare("Select Name from userprofile where UserID = ?");
   $stmt4->bind_param("i",$usr);
        while($stmt3->fetch())
          {
            $authorname = "";
            $usr = $authd;
            $stmt4->execute();
            $stmt4->bind_result($authorname);
            $stmt4->store_result();
            if($res = $stmt4->fetch()){ $authorname; }
            echo "<div id = ".$thd." onclick = 'loadmessages(".$thd.")'>";  
            echo "<div class='commentbody'>";
            echo "<div class = 'message'>";
            echo "<div class='floating-box'>Author: ".$authorname."</div><br>";
            echo "<div class='floating-box' style='width: 233px;'>Subject: ".$sub."</div><br>"; 
            echo "</div></div></div>";
          }
          $stmt3->close();
            ?>
          
          
          
          

      </div>
<div class= "sectionr">

<div class = "commentbodyy">
            <div class = "messages">
             
           </div>
           <h3> reply </h3>

              <form action = "reply.php" method = "POST">
                <input type="text" name="message" placeholder="Reply" required>
                <input type="hidden" name="threadidsend" id="threadidsend"value="">
                <button class="btn btn-success" type = "submit" name = "threadreply" id="threadreply">Reply</button>
              </form>
            </div>
</div>
</div>


<?php
//display messages related to this thread
?>

<div class="modal fade" id="postTopicModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">
                         Create a New Post
                        </h4>
                            </div>
                            <div class="modal-body">
                                <div style="height:200px">
                                    <div class="well" style="height:100%" id="googleMap">                             
                                    </div>
                                </div
                                <br>

                            <form id="submitnewpost" action="newpost.php" method= "POST"> 
                                <div>
                                  <input type="text" class= "form-control" style="width: 200px;" id="lat" name="lat" placeholder="latitude">
                                  <input type="text" class= "form-control" style="width: 200px;" id="lng" name="lng" placeholder="longitude">
                                  
                                </div>
                                <br>
                                <div>
                                 
                                
                                </div>
                                <div>
                                    <input type="text" id="subject" name = "subject" placeholder= "subject" class="form-control" rows="1">
                                    
                                </div>
                                <br>
                                <div>
                                    <input type="text" id="message" name="body" placeholder= "subject" class="form-control" rows="3">
                                    
                                </div>
                                <br>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Close
                                </button>
                                <button class="btn btn-primary" id="newpostsubmit" name = "newpostsubmit" type = "submit">Post</button>
                                    
                                
                        </div>
                    </div>
                </div>
                               </div>
                    </div>
                </div>
                </div>
                    </div>
                </div>




    <!-- Placed at the end of the document so the pages load faster -->

 
<script type="text/javascript">
function myFunction(){
  document.forms["submitnewpost"].submit();
}
</script>
<script>
/*$(function(){
    $('#submitnewpost').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: '/nextgate/home.php', //this is the submit URL
            type: 'POST', //POS POST
           data: $('form.submitnewpost').serialize(),
          dataType: "json",
            data: { 
              "lat" : $("#lat").value,
              "lon" : $("#lng").value
            },
            success: function(msg){
                 $("#postTopicModal").Modal('hide');
            },
            error: function(){alert("failure");}
        });
    });
});*/

function loadmessages(id){
  document.getElementById('threadidsend').value = id;
  $.post("showthreadmsg.php", {value: id}, function(data){
      var p = document.createElement("p");
      p.innerHTML = data;
      var txt = p.getElementsByClassName("messages");
      $(".messages").replaceWith(txt);
        // alert(txt);
    });
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
  </body>
</html>
