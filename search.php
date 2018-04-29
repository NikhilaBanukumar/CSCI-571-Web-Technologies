<?php
require_once __DIR__ . '/Facebook/autoload.php';
use Facebook\FacebookRequest;
date_default_timezone_set('UTC');

$fb = new \Facebook\Facebook([
  'app_id' => '692913920870592',
  'app_secret' => 'f4246eff7b35666a21939433c1784974',
  'default_graph_version' => 'v2.8',
  ]);

$access_token="EAAJ2M5gdCMABAPlhndDRGQmRIVQHvEfzl89A9e5h8IOFOtQZAUMFOQOcEhekjA14CI2WA2afQxXm8bZAjFeTYK6nHUUe12y0dZAtNYh63GNkalDi4M3u9p8SSloAeVBxbn1FZAOqUnPmLb6zygINj0UQJRlwDNoZD";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if(isset($_POST["reset"])){
        $keyword= "";
        $type="";
        $location = "";
        $distance = "";
    }
    else{
        $keyword= trim($_POST["keyword"]);
        $type=$_POST["type"];
        if(isset($_POST["location"]) && isset($_POST["distance"])){
            $location = $_POST["location"];
            $location_encoded = rawurlencode($location);
           // echo $location;
            $distance = $_POST["distance"];          
        }
    }
        
}
    
else if($_SERVER["REQUEST_METHOD"] == "GET"){

    if(isset ($_GET["keyword"])){
        $keyword = rawurldecode($_GET["keyword"]);
    }
    if(isset ($_GET["type"])){
        $type = $_GET["type"];
    }
    if(isset ($_GET["location"])){
        $location = rawurldecode($_GET["location"]);
    }
    if(isset ($_GET["distance"])){
        $distance = rawurldecode($_GET["distance"]);
    }

}


?>

<html>
    <head>
<meta charset="UTF-8">
    </head>
        <style>
    td{
        width:700px;
    }
    #first{
        border: lightgrey 2px solid;
        background-color: white;
        width: 700px;
        height: 185px;
        line-height: 60%;
        padding-top: 20px; 
    }

    i{
	   font-size: 35px;
    }

    form{
        padding-top: 20px; 
    }
    
    .toggle_location{
        display: none;
    }
    
     table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    
}

.parentRow {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}


.subRow {
    display: none;
    background-color: #dddddd;
}
    .albums{
        border:1px solid black;
        width:700px;
        background-color: darkgrey;
        
    }
/*
input:required {
    box-shadow:none;
}
*/
</style>

<script type="text/javascript">
    var count=1;
    

    
	function reset_form(getForm)
	{
        var tags = getForm.getElementsByTagName('input');
        for(i=0;i<tags.length;i++) 
            {a=tags[i].type;
             switch (a){
                 case "text":tags[i].value = '';
                              break;
                     }
            }
        tags = getForm.getElementsByTagName('select');
        document.getElementById("keyword").style.boxShadow="none";
        tags[0].options[0].selected=true;
        document.getElementById("loc_dist").style.display = "none";
        var tbl = document.getElementById("tab");
        if(tbl) 
            tbl.parentNode.removeChild(tbl);
        
        
        
        
	}
    
	function report(opt){
        
        var select = document.getElementById("droplist");
        var val = select.options[select.selectedIndex].value;
        if(val=="place"){
            document.getElementById("loc_dist").style.display = "block";
        }
    }
    
    function toggleInner(e) { 
        //alert(e.parentNode.nodeName);
    
        var subRow = e.parentNode.parentNode.nextElementSibling;
        subRow.style.display = (subRow.style.display === 'none' || subRow.style.display == '') ? 'table-row' : 'none';
        
    }
    
     function toggleRow(e) {
         if(e.id == 'posts'){
             if(document.getElementById("albums")){
             document.getElementById("albums").parentNode.nextElementSibling.style.display="none";
             }
            
         }
         else{
             if(document.getElementById("posts")){
                    document.getElementById("posts").parentNode.nextElementSibling.style.display="none";      
             }
         }
            
         
        var subRow = e.parentNode.nextElementSibling;
        subRow.style.display = (subRow.style.display === 'none' || subRow.style.display == '') ? 'table-row' : 'none';
        
    }
    function swipe(e){
        url=e.src;
        var newwindow=window.open();
        newwindow.document.body.style.background="white";
        var node=document.createElement("img");
        node.setAttribute("src",url);
        node.setAttribute("width","400");
        node.setAttribute("height","400");
        node.setAttribute("alt","picture");
        newwindow.document.body.appendChild(node);
        
        
    }

</script>

<body>
<center>
<div id=first style="background-color: #f2f3f4;" >
    <center>
        <i>Facebook Search</i>
        <hr style="width:650px">
    </center>

    <form method="post" id="form" action="/search.php" style="text-align:initial;">
        &nbsp;&nbsp;Keyword:
        <input type="text" id="keyword" name="keyword" value="<?php if (isset($keyword)) echo $keyword; ?>" required  oninvalid="this.setCustomValidity('This cant be left empty')"
    onchange="try{setCustomValidity('')}catch(e){}">
        <br/>
        <br/>
        &nbsp;&nbsp;Type:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="type" id="droplist" onchange="report(this)"><br/>
	       <option <?php if (isset($type) && $type=="user") echo 'selected' ?> value="user">users</option>
	       <option <?php if (isset($type) && $type=="page") echo 'selected' ?> value="page">pages</option>
	       <option <?php if (isset($type) && $type=="event") echo 'selected' ?> value="event">events</option>
	       <option <?php if (isset($type) && $type=="group") echo 'selected' ?> value="group">groups</option>
	       <option <?php if (isset($type) && $type=="place") echo 'selected' ?> value="place" >places</option></select>
        <br/>
        
        <p class ="toggle_location" <?php if(isset($type) and $type == "place") echo "style=\"display:block\""?> id = "loc_dist">
            &nbsp;&nbsp;Location:
            <input id="location" name="location" <?php if (isset($type) && $type=="place") echo 'value='."\"".$location."\"" ?> >
            &nbsp;&nbsp;Distance(Meters):
            <input id="distance" name = "distance" <?php if (isset($type) && $type=="place") echo 'value='.$distance ?>><br/>
        </p>
        <br/>
        <input type="submit" name="submit" value="Search" style="padding-left: 8px;margin-left: 45px;margin-left: 44px;">
        <input type="button" name="reset" value="Clear" onclick="reset_form(this.form)"> 
    </form>
</div>
<br/>
<br/>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])){
        
        if($type==="user" or $type==="page"){
            try{
                $response = $fb->get('search?q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(700).height(700)', $access_token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            
            $str1="<div id=\"tab\"><table style=\"width:700px;background-color:#f2f3f4;\" border=\"5px\"><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
            
            $user = $response->getGraphEdge();
            if (!($user=="[]")) {
            $str_get="&keyword=".$keyword."&type=".$type;
            foreach ($user as $graphNode) {
                //Get the user_id, name and URL of the picture
                $id= $graphNode['id'];
                $name = $graphNode['name'];
                $url = $graphNode['picture']['url'];
                //Create the table row for each node returned 
                $str1.="<tr><td><img src=".$url." alt=\"HTML5 Icon\" style=\"width:40px;height:40px;\" onclick=\"swipe(this)\"></td><td>".$name."</td><td><a href=\"/search.php?user_id=".$id.$str_get."\" id=\"".$id."\">Details</a></td></tr>";
            }
            $str1.="</table></div>";
            }
            else{
              $str1="<div id=\"tab\"><p class=\"albums\">No Records have been found.</p></div>";  
            }
            //return the HTML created 
            echo $str1;
            }
    
        
        if($type==="event"){
            try{
                $response = $fb->get('search?q='.$keyword.'&type='.$type.'&fields=name,picture.width(700).height(700),place', $access_token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            
            $str1="<div id=\"tab\"><table style=\"width:700px;background-color:#f2f3f4;\" border=\"5px\"><tr><th>Profile Photo</th><th>Name</th><th>Place</th></tr>";
            $user = $response->getGraphEdge();
            if (!($user=="[]")){
    
            foreach ($user as $graphNode) {
                
                $id= $graphNode['id'];
                $name = $graphNode['name'];
//              $picture_id=$graphNode['picture']['id'];
                $url = $graphNode['picture']['url'];
                if(!isset($graphNode['place'])){
                    $place ="";
                }
                else{
                  $place=$graphNode['place']['name'];    
                }
                $str1.="<tr><td><img src=".$url." alt=\"HTML5 Icon\" style=\"width:40px;height:40px;\" onclick=\"swipe(this)\"></td><td>".$name."</td><td>".$place."</td></tr>";
            }
                
            
            
            $str1.="</table></div>";
            }
            else{
              $str1="<div id=\"tab\"><p class=\"albums\">No Records have been found.</p></div>";   
            }
            echo $str1;
        }
    if($type==="group"){
        try{
                $response = $fb->get('search?q='.$keyword.'&type='.$type.'&fields=id,name,picture.width(700).height(700)', $access_token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            
            $str1="<div id=\"tab\"><table style=\"width:700px;background-color:#f2f3f4;\" border=\"5px\"><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";

            $user = $response->getGraphEdge();
            if (!($user=="[]")){
            $str_get="&keyword=".$keyword."&type=".$type;
            
            foreach ($user as $graphNode) {
                //Get the user_id, name and URL of the picture
                $id= $graphNode['id'];
                $name = $graphNode['name'];
                $url = $graphNode['picture']['url'];
                //Create the table row for each node returned 
                $str1.="<tr><td><img src=".$url." alt=\"HTML5 Icon\" style=\"width:40px;height:40px;\" onclick=\"swipe(this)\"></td><td>".$name."</td><td><a href=\"/search.php?user_id=".$id.$str_get."\" id=\"".$id."\">Details</a></td></tr>";
            }
            $str1.="</table></div>";
            }
            else{
              $str1="<div id=\"tab\"><p class=\"albums\">No Records have been found.</p></div>";   
            }
        
            //return the HTML created 
            echo $str1;
    }
    if($type==='place' and $location=="" and $distance==""){
        $str_fb = "search?q=".$keyword."&type=".$type;
        $str_fb .= "&fields=name,id,picture.width(700).height(700)";
        try{
                $response = $fb->get($str_fb, $access_token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        $str1="<div id=\"tab\"><table style=\"width:700px;background-color:#f2f3f4;\" border=\"5px\"><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
//            print_r($response);
            $user = $response->getGraphEdge();
            
  //          echo $user;
           if (!($user=="[]")){
            $str_get="&keyword=".rawurlencode($keyword)."&type=".$type."&location=".rawurlencode($location)."&distance=".rawurlencode($distance);
            foreach ($user as $graphNode) {
                //Get the user_id, name and URL of the picture
                $id= $graphNode['id'];
                $name = $graphNode['name'];
                $url = $graphNode['picture']['url'];
                //Create the table row for each node returned 
                $str1.="<tr><td><img src=".$url." alt=\"HTML5 Icon\" style=\"width:40px;height:40px;\" onclick=\"swipe(this)\"></td><td>".$name."</td><td><a href=\"/search.php?user_id=".rawurlencode($id).$str_get."\" id=\"".$id."\">Details</a></td></tr>";
            }
            $str1.="</table></div>";
          
        
    }else{
              $str1="<div id=\"tab\"><p class=\"albums\">No Records have been found.</p></div>";   
            }
        echo $str1;
    }
    if($type==='place' and $location != ""){
       
        
        $str_fb = "search?q=".$keyword."&type=".$type."&amp;"."center=";
        
        if($location){    
        
        $str1="https://maps.googleapis.com/maps/api/geocode/json?address=".rawurlencode($location)."&key=AIzaSyCIDSMPeRYJoYec_F9h0laLBHmYw0IWSRI";
  
        $file=file_get_contents($str1);
        $json=json_decode($file);
        $lat=$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $lng=$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        $lat_1 = strval($lat);
        $lng_1 = strval($lng);
        $distance_1 = strval($distance);
        
//            echo var_dump($distance);
        $str_fb .= $lat_1.",".$lng_1."&amp;"."distance=".$distance;
        }
        
        $str_fb .= "&fields=name,id,picture.width(700).height(700)";
        
       //echo $str_fb;
        
        try{
                $response = $fb->get($str_fb, $access_token);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            
            $str1="<div id=\"tab\"><table style=\"width:700px;background-color:#f2f3f4;\" border=\"5px\"><tr><th>Profile Photo</th><th>Name</th><th>Details</th></tr>";
//            print_r($response);
            $user = $response->getGraphEdge();
            
  //          echo $user;
           if (!($user=="[]")){
            $str_get="&keyword=".rawurlencode($keyword)."&type=".$type."&location=".rawurlencode($location)."&distance=".rawurlencode($distance);
            foreach ($user as $graphNode) {
                //Get the user_id, name and URL of the picture
                $id= $graphNode['id'];
                $name = $graphNode['name'];
                $url = $graphNode['picture']['url'];
                //Create the table row for each node returned 
                $str1.="<tr><td><img src=".$url." alt=\"HTML5 Icon\" style=\"width:40px;height:40px;\" onclick=\"swipe(this)\"></td><td>".$name."</td><td><a href=\"/search.php?user_id=".rawurlencode($id).$str_get."\" id=\"".$id."\">Details</a></td></tr>";
            }
            $str1.="</table></div>";
           }
        else{
              $str1="<p class=\"albums\">No Records have been found.</p>";   
            }
         echo $str1;
        }
    
        else if ($type === "place" and $location == "" and $distance != ""){
         
            echo "<p id=\"tab\"class=\"albums\">Distance specified without location or address</p>";
        }
    }
                   
if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])){
    $id = $_GET["user_id"];
    $keyword = $_GET["keyword"];
    $type = $_GET["type"];
    

     try{
                $response = $fb->get($id.'?fields=albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)', $access_token);
                echo ($response->getGraphNode());
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
    
    
    $user11 = $response->getGraphNode();
    
    //echo var_dump(isset($user11['albums']));
    //echo var_dump(isset($user11['posts']));
       if(isset($user11['albums'])){
           $album_list= $user11['albums']; 
         //echo $album_list;
          
        $str2="<div id=\"tab\"><p class=\"albums\"><a href=\"#\" id=\"albums\" onclick=\"toggleRow(this); return false;\">Albums</a></p>";
        $str2 .="<table style=\"width:700px;background-color:#f2f3f4;border-collapse:collapse;display:none\">";
        foreach ($album_list as $album){
            

            $str2 = $str2."<tr><td style=\"border: 1px solid black;\"><a href=\"#\" onclick=\"toggleInner(this);return false;   \">".$album['name']."</a></td></tr>";
//            echo $album;
//            echo "<br/><br/>";
            if(isset($album['photos'])){
                    $picture_list= $album['photos'];     
                    $str2.="<tr style=\"display:none\" ><td style=\"border: 1px solid black; \">";
                    foreach ($picture_list as $picture){ 
                        $str2.="<img src=\"https://graph.facebook.com/v2.8/".$picture['id']."/picture?access_token=".$access_token."\" height=50px  width = 50px onclick=\"swipe(this)\" >";

                    }
            $str2.="</td></tr>";
            }
            else{
                $str2.="<tr style=\"display:none\"><td style=\"border: 1px solid black;\"> No photos to display</td></tr>";
            }
         }
        $str2.="</table>";  
           
       }
    else{
         $str2="<div id=\"tab\"><p class=\"albums\">No Albums have been found.</p><br/>";
        
    }
       
        
   if(isset($user11['posts'])){
        $post_list= $user11['posts'];
        
        
        $str2.="<p class=\"albums\"><a href=\"#\" id=\"posts\" onclick=\"toggleRow(this); return false;\">Posts</a></p>";
        $str2 .="<table style=\"width:700px;background-color:#f2f3f4;border-collapse:collapse;display:none\">";
        $flag = 0; 
       foreach ($post_list as $posts){
            if(isset($posts['message']))
                if (!$flag){
                    $flag = 1;
                $str2.="<th>Messages</th>";
                $str2.="<tr><td style=\"border: 1px solid black; \">".$posts['message']."</td></tr>";        
                }
            else{
                $str2.="<tr><td style=\"border: 1px solid black; \">".$posts['message']."</td></tr>";    
            }
                
        }
       if($flag == 0){
           $str2.="<tr><td style=\"border: 1px solid black; \">No messages to be displayed.</td></tr>"; 
           
       }
        $str2.="</table></div>";
   }
  else{
       $str2.="<div id=\"tab\"><p class=\"albums\">No posts have beeen found.</p></div>";
      
  }
  echo $str2;
    
    

}
?>
</center>
<noscript/>
</body>
</html>

 