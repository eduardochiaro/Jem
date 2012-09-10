<?php

class Youtube{
	
	// will parse the youtube URL passed to it and return
    // an 11 character youtube ID                
   function getVideoID($url){
      // make sure url has http on it
      if(substr($url, 0, 4) != "http") {
         $url = "http://".$url;
      }
      
      // make sure it has the www on it
      if(substr($url, 7, 4) != "www.") {
        $url = str_replace('http://', 'http://www.', $url);
      }

      // extract the youtube ID from the url
      if(substr($url, 0, 31) == "http://www.youtube.com/watch?v=") {
         $id = substr($url, 31, 11);
      }
         
      return $id;      
   }

   // will accept a youtube video ID
   // returns title, description, thumbnail
   function getVideoDetails($id){
      // create an array to return
      $videoDetails = Array();
      
      // get the xml data from youtube
      $url = "http://gdata.youtube.com/feeds/api/videos/".$id;
      $xml = simplexml_load_file($url);
      
	  
      // load up the array
      $videoDetails['title'] = $xml->title[0];
      $videoDetails['description'] = $sxml->content[0];
      $videoDetails['thumbnail'] = "http://i.ytimg.com/vi/".$id."/2.jpg";

	return  $videoDetails;
   }
   
	function getThumb($video){
		$id = Youtube::getVideoID($video);
   		$media =  Youtube::getVideoDetails($id);
		return Utils::img($media["thumbnail"],$media["title"]);
	}
	function callVideo ($video,$w = 400, $h = 300){
		
		$id = Youtube::getVideoID($video);
		return '<object type="application/x-shockwave-flash" style="width:'.$w.'px; height:'.$h.'px;" data="http://www.youtube.com/v/'.$id .'"><param name="wmode" value="opaque" /><param name="movie" value="http://www.youtube.com/v/'.$id .'" /></object>'; 
	}	
}
