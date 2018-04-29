window.fbAsyncInit = function() {
FB.init({
appId      : '692913920870592',
xfbml      : true,
version    : 'v2.8'
});
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
    

//Initialize the Angular JS App with Bootstrap UI and Animate modules 

var app = angular.module('myApp', ['ui.bootstrap','ngAnimate']);

// Execute the Controller actions with the scope- to intialize variables, map functions to ng-model, ng-click 
// Pass $http to the function to make Ajax calls to RESTful API to fetch the required data in the form of JSON 
app.controller('myCtrl', function($scope,$http) {
    

$scope.access_token="EAAJ2M5gdCMABAPlhndDRGQmRIVQHvEfzl89A9e5h8IOFOtQZAUMFOQOcEhekjA14CI2WA2afQxXm8bZAjFeTYK6nHUUe12y0dZAtNYh63GNkalDi4M3u9p8SSloAeVBxbn1FZAOqUnPmLb6zygINj0UQJRlwDNoZD";    
$scope.post_fb = function(event){
    
    var name= event.name;
    var b=$('.active').attr('id');
    if(b=="fav_clear"){
        var pic=event.picture;
    }
    else{
        var pic=event.picture.data.url;
    }
    FB.ui(
    {
        method: 'feed',
        name:name,
        //description:description,
        caption:"FB SEARCH FROM USC CSCI571",
        picture: pic,
        //link: 'http://facebook.com/',
    }, 
    function(response)
    {
        if(response && response.post_id)
        {
            alert("Posted Successfully");
        }
        else 
        {
            alert("Not Posted");
        }
    }
    ); 
}
    
    $scope.lat = "";
    $scope.long = "";
    
    angular.element(document).ready(function(){
        navigator.geolocation.getCurrentPosition(success, error, options);
      var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
      };
      function success(pos) {
            $scope.crd = pos.coords;
            $scope.lat =$scope.crd.latitude ;
            $scope.long =$scope.crd.longitude;
      };

      function error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
      };        
    });
    //Initialize the table rows
    $scope.rows=[];
    if(localStorage.length == 0){
        $scope.hide_fav = false;
    }
    else{
        $scope.hide_fav = true;
        for(var i=0, len=localStorage.length; i<len; i++){
            var key=localStorage.key(i);
            var value=localStorage[key];
            $scope.rows.push(JSON.parse(value));
        }
    }

    $scope.tabs=false;   
    //Variable to keep track of Progress bar visibility when Search button is pressed
    $scope.user_pro = false;
    //Variable to keep track of ng-show visiblity for progress bar in pages tab
    $scope.page_pro_det = false;
    // Variable to keep track of main page of Users,Pages,Groups,Events etc AND details page of each type
    $scope.myCheck = true;
    $scope.myCheck_2 = false;

    //$scope.myvalue = false;
    $scope.detail=true;
    
    
//    $scope.post_fb = function(event){
//        
//    }
  $scope.clear=function(){
      $scope.tabs=false;
      $scope.search_name = "";
//      $('[href=#users]').tab('show');
//      var b="#"+$('.active').attr('id');
//      console.log(b)
//      $(b).removeClass("active");
//      $('#user').addClass("active");
//  var c="#"+$('.tab-pane .active').attr('id');
//      console.log("c:"+c);
      $('.nav-tab a[href="#users"]').tab('show');  
  }
    $scope.search_data = function() {

        
//        console.log($scope.crd.latitude+" "+$scope.crd.longitude);
        $scope.tabs=false;
        $scope.user_pro = true; // Set the visibility of progress bar
        
        $scope.keyword = $scope.search_name;
        var req = {
            method: 'GET',
            url: 'http://sample-env-1.6vd9zq2qsg.us-west-2.elasticbeanstalk.com/',
            params: { Name: $scope.search_name,
                     lat:$scope.lat,
                     lng:$scope.long
                     
                    }
        }
        
        $http(req).then(function successCallback(response){
            //Hide the visibility of progress bar when the data is loaded
            $scope.user_pro = false; 
            console.log(response);
            $scope.tabs=true;
//            console.log(JSON.PARSEresponse);
            //Map the JSON response to the respective typ
            if(JSON.parse(response.data.user123).data.length!=0){
              $scope.user = JSON.parse(response.data.user123).data;
              $scope.user_paging=JSON.parse(response.data.user123).paging;
              $scope.users_paging_next = decodeURIComponent($scope.user_paging.next);
              $scope.np_prev1=false;
              $scope.np_next1=true;
            }
            else{
            $scope.user = JSON.parse(response.data.user123).data;
            console.log("user page");
            $scope.np_prev1=false;
            $scope.np_next1=false;
            }
            if(JSON.parse(response.data.page123).data.length !=0){
            $scope.pages = JSON.parse(response.data.page123).data;
            $scope.pages_paging=JSON.parse(response.data.page123).paging;
            $scope.pages_paging_next = decodeURIComponent($scope.pages_paging.next);
            $scope.np_prev2=false;
            $scope.np_next2=true;
            }
            else{
            console.log("user page");
            $scope.np_prev2=false;
            $scope.np_next2=false;    
            }
            if( JSON.parse(response.data.event123).data.length!=0)
            {$scope.events = JSON.parse(response.data.event123).data;
             $scope.events_paging=JSON.parse(response.data.event123).paging;
            $scope.events_paging_next = decodeURIComponent($scope.events_paging.next);
             $scope.np_prev3=false;
            $scope.np_next3=true;
            
            }
            else{
            $scope.np_prev3=false;
            $scope.np_next3=false; 
            
            $scope.events = JSON.parse(response.data.event123).data;
               
            }
            
            if(JSON.parse(response.data.place123).data.length!=0)
            {$scope.places = JSON.parse(response.data.place123).data;
             $scope.places_paging=JSON.parse(response.data.place123).paging;
             $scope.places_paging_next = decodeURIComponent($scope.places_paging.next);
             $scope.np_prev4=false;
             $scope.np_next4=true;
            }
            else{
            console.log("user page");
            $scope.np_prev4=false;
            $scope.np_next4=false;    
            }
            if(JSON.parse(response.data.group123).data.length!=0){
            $scope.groups = JSON.parse(response.data.group123).data; 
             $scope.groups_paging=JSON.parse(response.data.group123).paging;
            $scope.groups_paging_next = decodeURIComponent($scope.groups_paging.next);
            $scope.np_prev5=false;
             $scope.np_next5=true;
            }
            else{
            console.log("user page");
            $scope.np_prev5=false;
            $scope.np_next5=false;   
            }
            
//            console.log($scope.user.length);
//            console.log(decodeURIComponent($scope.user_paging.next));
            //console.log(decodeURIComponent($scope.user_paging.previous));

        },
        function errorCallback(response){
            $scope.user_pro = true;
        });
    }
    


    $scope.next_prev = function(page_url) {
        $scope.type1=$(".active").attr('id');
        console.log("In Next Prev"+page_url);
        
        
        var req = {
            method: 'GET',
            url: page_url,
            //params: { Name: $scope.search_name }
        }
        
        $http(req).then(function successCallback(response){
            //Hide the visibility of progress bar when the data is loaded
        if($scope.type1=="user")  {
            $scope.np_prev1=true;
            $scope.np_next1=true;
            $scope.user = response.data.data;
            if(response.data.paging.next != undefined){
            $scope.users_paging_next=decodeURIComponent(response.data.paging.next);
            }
            else{
             console.log("user next undefined");
            $scope.np_next1=false;     
            }
            if(response.data.paging.previous != undefined){
            $scope.users_paging_previous=decodeURIComponent(response.data.paging.previous);}
            else{
            $scope.np_prev1=false;    
            }
             console.log(response.data);}
        if($scope.type1=="page")  {
            $scope.np_prev2=true;
            $scope.np_next2=true;
            $scope.pages = response.data.data;
            if(response.data.paging.next != undefined){
            $scope.pages_paging_next=decodeURIComponent(response.data.paging.next);
            console.log($scope.pages_paging_next);
            }
            else{
            console.log("page next undefined");
            $scope.np_next2=false;     
            }
            if(response.data.paging.previous != undefined){
            $scope.pages_paging_previous=decodeURIComponent(response.data.paging.previous);}
            else{
            $scope.np_prev2=false;    
            }}
        if($scope.type1=="event")  {
            $scope.np_prev3=true;
            $scope.np_next3=true;
            $scope.events = response.data.data;
            if(response.data.paging.next != undefined){
            $scope.events_paging_next=decodeURIComponent(response.data.paging.next);}
            else{
             $scope.np_next3=false;    
            }
            if(response.data.paging.previous != undefined){
            $scope.events_paging_previous=decodeURIComponent(response.data.paging.previous);}
            else{
            $scope.np_prev3=false;    
            }}
        if($scope.type1=="place")  {
            $scope.np_prev4=true;
            $scope.np_next4=true;
            $scope.places = response.data.data;
            console.log("place:"+$scope.places.paging);
            console.log($scope.places_paging_next);
            if(response.data.paging.next != undefined){
            $scope.places_paging_next=decodeURIComponent(response.data.paging.next);
            }
            else{
              $scope.np_next4=false;   
            }
            if(response.data.paging.previous != undefined){
            $scope.places_paging_previous=decodeURIComponent(response.data.paging.previous);
//                console.log("place:"+response.data.paging.previous);
            }
            else{
            $scope.np_prev4=false;    
            }}
        if($scope.type1=="group")  {
            $scope.np_prev5=true;
            $scope.np_next5=true;
            $scope.groups = response.data.data;
            console.log(response.data.paging);
            if(response.data.paging.next != undefined){
            $scope.groups_paging_next=decodeURIComponent(response.data.paging.next);}
            else{
              console.log("hill");
              $scope.np_next5=false;  
            }
            if(response.data.paging.previous != undefined){
            $scope.groups_paging_previous=decodeURIComponent(response.data.paging.previous);}
            else{
            $scope.np_prev5=false;    
            }}
            //Map the JSON response to the respective type
        },
        function errorCallback(response){
        });
    }



    $scope.hide=function(){
        $scope.myCheck_2=false;
        $scope.myCheck = true;
    }

    //On click of Back button in the details page, hide the div2 and show the div1
    $scope.back_btn = function(event) {
      $scope.myCheck_2 = false;
      $scope.myCheck = true; 
    }

    //Actions to perform when the details button is clicked
    $scope.details = function(event,type){
        $scope.nopost=false;
         $scope.postavail=false;
         $scope.albumavail=false;
        $scope.noalbum=false;
        //Set the visibility of div1-main page to hide and div2-details page to show 
        $scope.myCheck = false;
        $scope.myCheck_2 = true;
        //Sets the visibility to show of Progress bar in the details page
        $scope.page_pro_det = true;

        // Id of the button clicked
        if(type == 'favorite'){
            $scope.current_id = event.id;
            $scope.type1 = event.type;
            $scope.data=event;
            console.log("Favorite Details Clicked:"+event.type);
        }
        else{
            $scope.current_id=event.id;
            $scope.type1 = type;
            $scope.data=event;
            console.log("event:"+$scope.type1+$scope.current_id);
        }
        

        // Set the config reqd for Ajax call 
        var req1 = {
            method: 'GET',
            url: 'http://sample-env-1.6vd9zq2qsg.us-west-2.elasticbeanstalk.com/',
            params: { 
                      id : $scope.current_id,
                    }
        }
        //Make the AJAX request 
        $http(req1).then(function successCallback(response){
            
            //console.log("In details call Success :"+event.target.id+$scope.type1);
            $scope.page_pro_det = false;
            //console.log("Type is: "+$scope.type1);
//            $scope.data=response.data;
             console.log("albums:"+response.id);
            console.log("details event is: "+response.data);
            if(response.data.albums !=undefined)
            {$scope.albums=response.data.albums;
            
             $scope.albumavail=true;
             $scope.noalbum=false;
            }
            else{
            console.log($scope.albums);
             $scope.albumavail=false;
             $scope.noalbum=true;   
            }
            if(response.data.posts !=undefined){
            $scope.posts=response.data.posts;
            $scope.postavail=true;
            $scope.nopost=false;
            }
            else{
             $scope.postavail=false;
            $scope.nopost=true;   
            }
            $scope.posts_name = response.data.name;
            $scope.posts_picture = response.data.picture.url;
            $scope.posts_id=response.data.id;
            $scope.detail=false;
            },
            function errorCallback(response){
                console.log("In Error Call");
            });
    }
    
    $scope.myfav_click = function(event,type){

        if(typeof(Storage) !== "undefined"){

            $scope.type1= type;

            //If already set remove from local storage and table rows 
            if(localStorage.getItem(event.id+$scope.type1) !== null){

                localStorage.removeItem(event.id+$scope.type1);
                
                if(localStorage.length == 0){
                    $scope.hide_fav = false;
                }

                //remove from the rows
                for(var i=0; i < $scope.rows.length; i++){
                    //console.log("row:"+$scope.rows[i].id+"   "+event.id);
                    if ($scope.rows[i].id === event.id){
                    //console.log(i);
                    $scope.rows.splice(i, 1);     
                    }
                }
            }
            else{
                $scope.hide_fav = true;
                //Add the current type id as key and create an object as value; Add to local Storage
                $scope.id=event.id;
                $scope.name=event.name;
                if(event.picture.data !== undefined){
                    $scope.picture=event.picture.data.url;    
                }
                else{
                    $scope.picture=event.picture.url;        
                }
                
                $scope.fav_obj = {
                    "id":$scope.id,
                    "Name":$scope.name,
                    "picture":$scope.picture,
                    "type":$scope.type1
                }
     
                // $scope.id+$scope.type1 - Makes the two types with same id as unique by appending the type information
                localStorage.setItem($scope.id+$scope.type1, JSON.stringify($scope.fav_obj));
                var id1=localStorage.getItem($scope.id+$scope.type1);
                var id2=JSON.parse(id1);
                //Add the object to the table rows
                $scope.rows.push(id2);
            }

        }
    }

    $scope.return_local_storage = function(id,type){
        
        var id = id+type;
        if (localStorage.getItem(id) == undefined){
            return true;
        }
        else{
            return false;
        }

    }
    
    $scope.handle_post=function(event){
        if(event.message != undefined){
            return event.message;
        }
        if (event.story != undefined){
            return event.story;
        }
    }

    $scope.fav_is_set = function(id){
        if (localStorage.getItem(id) == undefined){
            return false;
        }
        else{
            return true;
        }
    }
    $scope.hdpic=function(event){
        $scope.a="https://graph.facebook.com/v2.8/"+event+"/picture?access_token="+$scope.access_token;
        console.log($scope.a);
        return $scope.a;
    }
    $scope.delete1=function(event){        
        
        localStorage.removeItem(event.id+event.type);
        if(localStorage.length == 0){
        $scope.hide_fav = false;
        }
        
        var gly ="span#"+event.id+event.type;
        $(gly).removeClass("gly glyphicon-star");
        $(gly).addClass("glyphicon-star-empty");
        for(var i=0; i < $scope.rows.length; i++){
            console.log("row:"+$scope.rows[i].id+"   "+event.id);
            if ($scope.rows[i].id === event.id){
                //var index = $scope.rows.indexOf(item);
                console.log(i);
                $scope.rows.splice(i, 1);     
            }
        }
        for(var i in localStorage)
        {
        console.log(localStorage[i]);
        }

    }





});