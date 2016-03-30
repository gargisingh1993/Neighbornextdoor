<?php
session_start();
//echo ($_SESSION['userid']);
$id = $_SESSION['userid'];
include 'db_connect.php';
echo "<br>This is the profile page <br>";

 $query = "select * from User_profile where U_Id=$id";
    $res = $conn->query($query);
    if($res->num_rows>0)
    {
       $row = $res->fetch_assoc();
       $d= date($row['Date_Of_Birth']);
        $d= explode(" ",$d);
?>
<style>
      #map {
        width: 500px;
        height: 400px;
         border: 1px solid gray;
      }
    </style>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBfd_t-yCoHmt8Tb3BobKI___rK_QW5Q9A"></script>
    <script>
      function initialize() {
        var mapCanvas = document.getElementById('map');
          var myLatLng = {lat: <?php  echo $row['Latitude'] ?>, lng: <?php  echo $row['Longitude'] ?>};
        var mapOptions = {
          center: myLatLng,          
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);
          var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: 'Hello World!'
  });
        //map onclick listener
         marker.addListener('click', function(event) {
           var infowindow = new google.maps.InfoWindow({
    content: 'This is your current location'
  });
        //   alert("hamayya");
           
    infowindow.open(map, marker);
           
  });
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<body><br>
    <div id="map"></div>
   <br>
  <input type="button" name="changemap" value="Edit Location" onclick="changeloc()">
<input type="button" value="Edit Profile" onclick="caneditthis()" id="editbutton">
<form method="post" action="nextpage.php" >
Name: <input type="text" name="name"  id="text1" value="<?php echo $row['Username'];?>" disabled><br>
Gender: <input type="radio" name="gender" value="male" disabled id="radio1" checked >Male
<input type="radio" name="gender" value="female" disabled id="radio2">Female
   <br>
    Date of Birth:<input type="date" name="dob" id="dobid" value="<?php   
        echo $d[0]; ?>" disabled><br>
   Description: <textarea name="desc" rows="4" cols="50" disabled id="idfortextarea">

   <?php $row['U_Description'] = trim($row['U_Description']); echo $row['U_Description'];?>
</textarea>

   <br>
  
  Apartment No:<input type="text" name = "aptno" id="aptno"  value="<?php echo $row['Apt_No'];?>" disabled><br>
   Building No:<input type="text"  name = "bno" id="bldno" value="<?php echo $row['Building_No'];?>" disabled ><br>
  <input type="submit" name = "update" value="update" >
</form>
</body>

<?php
      if($row['Gender']=='Male') { ?><script>
document.getElementById('radio1').checked= true;  </script>
<?php }
      if($row['Gender']=='Female'){ ?><script> 
document.getElementById('radio2').checked= true;  </script>
<?php }
      
          }

else{ echo "no profile yet for the user"; 
    ?>
    <?php
    }
//echo ("<br>Do you want to create a profile now? <input type='button' value='click me!'>");
       ?>

<script>
function caneditthis()
  {
     if( document.getElementById('editbutton').value=='Edit Profile'){
        alert('you clicked me '+document.getElementById('editbutton').value );
     var mytextbox =  document.getElementById('text1');
     mytextbox.disabled=false;
     mytextbox.focus();
     document.getElementById('radio1').disabled=false;
     document.getElementById('radio2').disabled=false;
     document.getElementById('dobid').disabled=false;
     document.getElementById('idfortextarea').disabled=false;
//     document.getElementById('selectidblock').disabled=false;
//     document.getElementById('selectidhood').disabled=false;
     document.getElementById('aptno').disabled=false;
     document.getElementById('bldno').disabled=false;
     document.getElementById('editbutton').value='lock';
     return;
     }
       if( document.getElementById('editbutton').value=='lock'){
          alert('you clicked me '+document.getElementById('editbutton').value );
     var mytextbox =  document.getElementById('text1');
     mytextbox.disabled=true;
     mytextbox.focus();
     document.getElementById('radio1').disabled=true;
     document.getElementById('radio2').disabled=true;
     document.getElementById('dobid').disabled=true;
     document.getElementById('idfortextarea').disabled=true;
     document.getElementById('aptno').disabled=true;
     document.getElementById('bldno').disabled=true;
     document.getElementById('editbutton').value='Edit Profile';
     return;
     } 
  }
  function submitclicked(){
     var mytextbox =  document.getElementById('text1');
     var radio1 =  document.getElementById('radio1');
     var radio2 =   document.getElementById('radio2');
     var gender='';
     if(radio1.checked == true)  gender='male';
     if(radio2.checked == true ) gender='female';
     var entereddate = document.getElementById('dobid').value;
      var des = document.getElementById('idfortextarea').value;
      var apt = document.getElementById('aptno').value;
      var bld = document.getElementById('bldno').value;
     alert(mytextbox.value+' is ' + gender+ entereddate+ des+ apt+bld);
  }
  function changeloc()
  {window.location.href = "hamayya.php"+ '#' +  '<?php echo $row['Latitude'];?>'+'#'+'<?php echo $row['Longitude'];?>';
    //alert('you clicked me');
    
  }
</script>
<?php

if(isset($_POST['update'])){
  $name = $_POST['name'];
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $desc = $_POST['desc'];
  $aptno = $_POST['aptno'];
  $flatno = $_POST['bno'];
  
 // echo $name.$gender.$dob.$desc.$aptno.$flatno;
  $stmt = $conn->prepare("update User_profile set  Username = ?, Gender = ?, Date_Of_Birth = ?, U_Description = ?, Apt_No = ?, Building_No = ?, Time_Of_Updation = now() where U_Id = ?");
$stmt->bind_param("ssssiii", $name,$gender,$dob,$desc,$aptno,$flatno,$id);
if($stmt->execute()) echo ' profile updated sucessesfully';
  else echo 'update failure'; 
}
?>