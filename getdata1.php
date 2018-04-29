<?php header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/Facebook/autoload.php';
use Facebook\FacebookRequest;
date_default_timezone_set('UTC');

$fb = new \Facebook\Facebook([
  'app_id' => '692913920870592',
  'app_secret' => 'f4246eff7b35666a21939433c1784974',
  'default_graph_version' => 'v2.8',
  ]);

$access_token="EAAJ2M5gdCMABAPlhndDRGQmRIVQHvEfzl89A9e5h8IOFOtQZAUMFOQOcEhekjA14CI2WA2afQxXm8bZAjFeTYK6nHUUe12y0dZAtNYh63GNkalDi4M3u9p8SSloAeVBxbn1FZAOqUnPmLb6zygINj0UQJRlwDNoZD";
if (($_SERVER["REQUEST_METHOD"] == "POST") || ($_SERVER["REQUEST_METHOD"] == "GET")){
    
    if(isset($_POST["Name"]) || isset($_GET["Name"]) ){
        
        $Name= trim($_GET["Name"]);
        $lat = trim($_GET["lat"]);
        $lng=trim($_GET["lng"]);
        
        try{  
            
            $response1 = $fb->get('search?q='.$Name.'&type=user&fields=id,name,picture.width(700).height(700)', $access_token);
            $response1=($response1->getDecodedBody());
                //$json= json_encode($response1);
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

        try{
                $response2 = $fb->get('search?q='.$Name.'&type=page&fields=id,name,picture.width(700).height(700)', $access_token);
                $response2=($response2->getDecodedBody());
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        try{
                $response3 = $fb->get('search?q='.$Name.'&type=event&fields=id,name,picture.width(700).height(700)', $access_token);
                $response3=($response3->getDecodedBody());
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        try{    
            if($lat!=NULL && $lng!=NULL){
                $response4 = $fb->get('search?q='.$Name.'&type=place'.'&amp;'.'center='.$lat.','.$lng.'&fields=id,name,picture.width(700).height(700)', $access_token);
                $response4=($response4->getDecodedBody());
            }
            else{
                $response4 = $fb->get('search?q='.$Name.'&type=place&fields=id,name,picture.width(700).height(700)', $access_token);
                $response4=($response4->getDecodedBody());   
            } 
        }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        
        try{
                $response5 = $fb->get('search?q='.$Name.'&type=group&fields=id,name,picture.width(700).height(700)', $access_token);
                $response5=($response5->getDecodedBody());
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        $json = array("user123"=>json_encode($response1),"page123"=>json_encode($response2),"event123"=>json_encode($response3),"place123"=>json_encode($response4),"group123"=>json_encode($response5));
        $json_en = json_encode($json);
        echo $json_en;
        
    }
    if(isset($_GET["id"])){
        
         $id=$_GET["id"];
         
       try{
                $response = $fb->get($id.'?fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name, picture}},posts.limit(5)', $access_token);
                $json=json_decode($response->getGraphNode());
                $json1=json_encode($json);
                echo $json1;
            }catch(Facebook\Exceptions\FacebookResponseException $e){
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            }catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }  
     
    }
      
    
}

?>
