<?php
//include 'db_connect.php';
// connect to database
       $servername = "localhost";
       $username = "root";
       $password = "";
       $database = "neighbor_network";

       $conn = new mysqli($servername,$username, $password, $database);
      //check connection
       if ($conn->connect_error)
	   { 
           echo "conct err";
           die("Connection failed: " . $con->connect_error);
	   }
		session_start();
		$username = $_SESSION['username1'];
		//echo ($username);

		$stmt1 = $conn->prepare ("select UserID from userlogin where username=?");
		$stmt1->bind_param("s",$username);
		$stmt1->execute();
		$stmt1->bind_result($Uid);
			while($res=$stmt1->fetch())
			{
				$uid=$Uid;
			}

//
		error_reporting(E_ALL);

//echo ("post variables are".$_POST['updatedlat']);
if(isset($_POST['updatedlat'])&&isset($_POST['updatedlng'])&&isset($_POST['updatedblock'])&&isset($_POST['updatedzip']))
{
  if($_POST['updatedzip']=='undefined'||$_POST['updatedblock']=='undefined') 
  {
    echo 'Please select a place on earth, You are given one more chance!';
  }
  else{
	if($_POST['updatedzip']=='Brownsville') $zip = 3;
	if($_POST['updatedzip']=='Park Slope') $zip = 2;
	if($_POST['updatedzip']=='Crown Heights') $zip = 1;
  //echo $zip;
 //echo 'values are set for user'.$_SESSION['Username'].'<br>'; 
  //echo $_POST['updatedlat'].$_POST['updatedlng'];
  $lat=  floatval($_POST['updatedlat']);
  $lng =  floatval($_POST['updatedlng']);
  $blk = $_POST['updatedblock'];
 // echo $lat.$lng;
  if(isset($_POST['Apt']))
  {
	  $aptno = $_POST['Apt'];
  }
  if(isset($_POST['flat']))
  {
	  $flatno= $_POST['flat'];
  }
	$uname = $_SESSION['username1'];
	$stmt = $conn->prepare("update userprofile set Lat1 = (?) , Long1 = (?), BID= (?), HID = (?) , AptNo =(?),FlatNo =(?) where UserID= (?)");	
	$stmt->bind_param("ssiiiii",$lat,$lng,$blk,$zip,$aptno,$flatno,$uid);
	$value = "";
  if($stmt->execute()){ $value = true; //echo $value;
                      }
  if($value){ header('location: profile.php'); }
  else echo '<br>failure';
  }
}
//else echo 'values are not set';
else echo'<center>Please choose the location:</center><br><br>';

?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #googleMap {
        height: 80%;
        width: 50%;
        border: 1px solid black;
		left: 350px;
      }
.controls {
  margin-top: 10px;
  border: 1px solid transparent;
  border-radius: 2px 0 0 2px;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  height: 32px;
  outline: none;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
}

#pac-input {
  background-color: #fff;
  font-family: Roboto;
  font-size: 15px;
  font-weight: 300;
  margin-left: 12px;
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
  width: 300px;
}

#pac-input:focus {
  border-color: #4d90fe;
}

.pac-container {
  font-family: Roboto;
}
s
#type-selector {
  color: #fff;
  background-color: #4d90fe;
  padding: 5px 11px 0px 11px;
}

#type-selector label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}
      #target {
        width: 345px;
      }
</style>
    <center><title>Insert your Location</title></center>
	</head>
  <body>
     <form method="post" action="location.php" >
       <br>
    <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="googleMap"></div><center>
<!--    <div id="note">Area</div>--><br><br>
		House No. : <input type ="text" id="flat"  name="flat" required > <br><br>
		Apt No : <input type ="text" id ="apt" name ="Apt" required > <br><br>
		Lat: <input type = "text" id= "latgargi" name="updatedlat" required><br><br>
		Lng: <input type = "text" id= "lnggargi" name="updatedlng" required><br><br>
		Block: <input type="text" id="blockgargi" name= "updatedblock" required><br><br>
		Neighborhood: <input type="text" id="zipgargi" name="updatedzip" required><br><br>
	   

    <input type="submit"  value="Update Location">
	</center>
      </form>
      </body>
</html>
    <script>
      
      var marker,markerarray=[];
function initAutocomplete() {
//var userpos = new google.maps.LatLng(part1+','+part2);
 // var userpos = {lat: part1, lng: part2};
  var mapProp = {
    center:{lat:40.67985693941085 ,lng: -73.96991729736328},
  //  center:userpos,
    zoom:12,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById('googleMap'),mapProp);
 // map.setOptions({draggableCursor:'crosshair'});
  
  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });
  ////////////////////
  function placemark(location)
  {
   // console.log(location);
    //delete other markers
      console.clear();
if( google.maps.geometry.poly.containsLocation(location, myarea1)) 
{console.log('inside area1') ;
//document.getElementById('note').innerHTML="block 1";
document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
  document.getElementById('blockgargi').value=1;
document.getElementById('zipgargi').value='Crown Heights';}

  else  if( google.maps.geometry.poly.containsLocation(location, myarea2)) 
       { console.log('inside area2') ;
      //  document.getElementById('note').innerHTML="block 2";
       document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
        document.getElementById('blockgargi').value=2;
       document.getElementById('zipgargi').value='Crown Heights';}
	   
     else  if( google.maps.geometry.poly.containsLocation(location, myarea3)) 
     {console.log('inside area3') ;
      //document.getElementById('note').innerHTML="block 3";
     document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
      document.getElementById('blockgargi').value=3;
     document.getElementById('zipgargi').value='Crown Heights';}
	 
    else  if( google.maps.geometry.poly.containsLocation(location, myarea4)) 
    {console.log('inside area4') ;
     //document.getElementById('note').innerHTML="block 4";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=4;
    document.getElementById('zipgargi').value='Park Slope';}
	
    else  if( google.maps.geometry.poly.containsLocation(location, myarea5)) 
    {console.log('inside area5') ;
     //document.getElementById('note').innerHTML="block 5";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=5;
    document.getElementById('zipgargi').value='Park Slope';}
	
    else  if( google.maps.geometry.poly.containsLocation(location, myarea6)) 
    {console.log('inside area6') ;
     //document.getElementById('note').innerHTML="block 6";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=6;
    document.getElementById('zipgargi').value='Park Slope';}
	
	else  if( google.maps.geometry.poly.containsLocation(location, myarea7)) 
    {console.log('inside area7') ;
     //document.getElementById('note').innerHTML="block 7";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=7;
    document.getElementById('zipgargi').value='Brownsville';}
	
	else  if( google.maps.geometry.poly.containsLocation(location, myarea8)) 
    {console.log('inside area8') ;
     //document.getElementById('note').innerHTML="block 8";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=8;
    document.getElementById('zipgargi').value='Brownsville';}
	
	else  if( google.maps.geometry.poly.containsLocation(location, myarea9)) 
    {console.log('inside area9') ;
     //document.getElementById('note').innerHTML="block 9";
    document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
     document.getElementById('blockgargi').value=9;
    document.getElementById('zipgargi').value='Brownsville';}
    
    else {console.log('devudaaa') ;
    console.log(location.lat()+ "  "+ location.lng());
        //  document.getElementById('note').innerHTML="not in any block";
          document.getElementById('latgargi').value= location.lat();
        document.getElementById('lnggargi').value= location.lng();
           document.getElementById('blockgargi').value=undefined;
          document.getElementById('zipgargi').value=undefined;
         }
   // map.setCenter(location);
deletemarkers();
         marker=new google.maps.Marker({
  position:location,
  animation:google.maps.Animation.BOUNCE
  });

marker.setMap(map);
    google.maps.event.addListener(marker,'click',function(event) {
      var v = 'lat:   '+event.latLng.lat()+'   lng: '+ event.latLng.lng();
        var infowindow = new google.maps.InfoWindow({
    content: v
  });
   // alert('clicked on a marker');
    infowindow.open(map, marker);

  });
    markerarray.push(marker);
    
  }
  
  
  function deletemarkers()
  {
    for (i in markerarray)
      {
        markerarray[i].setMap(null);
      }
    markerarray.length = 0;
  }
var myrectangle1 = [
   
  {lat:40.668345, lng:-73.920047},
  {lat:40.676726, lng:-73.919296},
  {lat:40.677312, lng:-73.930433},
  {lat:40.663527, lng:-73.931656}
  
];
   var myrectangle2 = [
   
  {lat:40.663610, lng:-73.931697},
  {lat:40.664131, lng:-73.948090},
  {lat:40.678323, lng:-73.946975},
  {lat:40.677281, lng:-73.930495}
  
  ];
  var myrectangle3 = [
   
  {lat:40.678258, lng:-73.947060},
  {lat:40.664131, lng:-73.948305},
  {lat:40.663350, lng:-73.960965},
  {lat:40.681024, lng:-73.964441}
  ];
  
  
   
var myrectangle4 = [
   
  {lat:40.674507, lng:-73.970369},
  {lat:40.671284, lng:-73.971184},
  {lat:40.67611410960358, lng:-73.98371458053589},
  {lat:40.684628, lng:-73.977836}
  ];
  
    
var myrectangle5 = [
   
  {lat:40.671284, lng:-73.971098},
  {lat:40.665100, lng:-73.976162},
  {lat:40.670861, lng:-73.987921},
  {lat:40.67607342551644, lng:-73.98377895355225}
  ];
  
    var myrectangle6 = [
   
  {lat:40.665214, lng:-73.976409},
  {lat:40.658780441065176, lng:-73.98178339004517},
  {lat:40.665425, lng:-73.992615},
  {lat:40.670926, lng:-73.988146}
  ];
  
var myrectangle7 = [
   
  {lat:40.675402, lng:-73.903989},
  {lat:40.670910, lng:-73.902745},
  {lat:40.668404, lng:-73.920211},
  {lat:40.676736, lng:-73.919181}
  ];
  
  
var myrectangle8 = [
   
  {lat:40.670943, lng:-73.902788},
  {lat:40.663716, lng:-73.900857},
  {lat:40.660884, lng:-73.919782},
  {lat:40.668371,lng:-73.919997}
  ];
  
  
var myrectangle9 = [
   
  {lat:40.663651, lng:-73.900728},
  {lat:40.657107, lng:-73.899784},
  {lat:40.650400, lng:-73.908324},
  {lat:40.660721, lng:-73.919611}
  ];
  
  var myarea1 = new google.maps.Polygon({
    paths: myrectangle1,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.5
  });
  myarea1.setMap(map);
  
   var myarea2 = new google.maps.Polygon({
    paths: myrectangle2,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#000000',
    fillOpacity: 0.2
  });
  myarea2.setMap(map);

   var myarea3 = new google.maps.Polygon({
    paths: myrectangle3,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#0000FF',
    fillOpacity: 0.2
  });
  myarea3.setMap(map);
  
  var myarea4 = new google.maps.Polygon({
    paths: myrectangle4,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#0000FF',
    fillOpacity: 0.2
  });
  myarea4.setMap(map);
  
  var myarea5 = new google.maps.Polygon({
    paths: myrectangle5,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#000000',
    fillOpacity: 0.2
  });
  myarea5.setMap(map);
  
  var myarea6 = new google.maps.Polygon({
    paths: myrectangle6,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.5
  });
  myarea6.setMap(map);
  
  var myarea7 = new google.maps.Polygon({
    paths: myrectangle7,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.5
  });
  myarea7.setMap(map);
  
  var myarea8 = new google.maps.Polygon({
    paths: myrectangle8,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.5
  });
  myarea8.setMap(map);
  
  var myarea9 = new google.maps.Polygon({
    paths: myrectangle9,
    strokeColor: '#FF0000',
    strokeOpacity: 0.2,
    strokeWeight: 2,
    fillColor: '#000000',
    fillOpacity: 0.2
  });
  myarea9.setMap(map);
  
  
    google.maps.event.addListener(myarea1,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});

}); 
   google.maps.event.addListener(myarea1,"mouseout",function(){
 this.setOptions({fillColor: "#FF0000"});
}); 
  google.maps.event.addListener(myarea2,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});
}); 
   google.maps.event.addListener(myarea2,"mouseout",function(){
 this.setOptions({fillColor: "#000000"});
}); 
    google.maps.event.addListener(myarea3,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});

}); 
   google.maps.event.addListener(myarea3,"mouseout",function(){
 this.setOptions({fillColor: "#0000FF"});
}); 
      google.maps.event.addListener(myarea4,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});

}); 
   google.maps.event.addListener(myarea4,"mouseout",function(){
 this.setOptions({fillColor: "#0000FF"});
}); 
    google.maps.event.addListener(myarea5,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});

}); 
   google.maps.event.addListener(myarea5,"mouseout",function(){
 this.setOptions({fillColor: "#000000"});
}); 
   google.maps.event.addListener(myarea6,"mouseout",function(){
 this.setOptions({fillColor: "#FF0000"});
}); 
  google.maps.event.addListener(myarea6,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});
}); 
 
   google.maps.event.addListener(myarea7,"mouseout",function(){
 this.setOptions({fillColor: "#FF0000"});
}); 
  google.maps.event.addListener(myarea7,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});
});

 google.maps.event.addListener(myarea8,"mouseout",function(){
 this.setOptions({fillColor: "#FF0000"});
}); 
  google.maps.event.addListener(myarea8,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});
});

google.maps.event.addListener(myarea9,"mouseout",function(){
 this.setOptions({fillColor: "#FF0000"});
}); 
  google.maps.event.addListener(myarea9,"mouseover",function(){
 this.setOptions({fillColor: "#00FF00"});
});

   google.maps.event.addListener(myarea1,'click',function(event) {
    
    placemark(event.latLng);
     
   });
   google.maps.event.addListener(myarea2,'click',function(event) {
    
    placemark(event.latLng);
     
   });
  google.maps.event.addListener(myarea3,'click',function(event) {
  
    placemark(event.latLng);
     
   });
  google.maps.event.addListener(myarea4,'click',function(event) {
    
    placemark(event.latLng);
     
   });
  google.maps.event.addListener(myarea5,'click',function(event) {
     
    
    placemark(event.latLng);
     
   });
  google.maps.event.addListener(myarea6,'click',function(event) {
    
    placemark(event.latLng);
     
   });
   google.maps.event.addListener(myarea7,'click',function(event) {
    
    placemark(event.latLng);
     
   });
   
   google.maps.event.addListener(myarea8,'click',function(event) {
    
    placemark(event.latLng);
     
   });
   
   google.maps.event.addListener(myarea9,'click',function(event) {
    
    placemark(event.latLng);
     
   });
   
   google.maps.event.addListener(map,'click',function(event) {
    
    placemark(event.latLng);

  });
  ////////////////////////////
  var markers = [];
  // [START region_getplaces]
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
  // [END region_getplaces]
}
    </script>
    <script>
    var text = window.location.hash.substring(1);
      var part1 = text.substring(0,text.lastIndexOf('#'));
      var part2 =text.substring(text.lastIndexOf('#')+2);
      
      console.log(part1);
      console.log(part2);
    
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfd_t-yCoHmt8Tb3BobKI___rK_QW5Q9A&libraries=places&callback=initAutocomplete"
         async defer></script>
   
