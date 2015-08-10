<?php
/**
* writes rss to local file with some parsing
* @param url $url - what url to get
* @param String $result_file - where to write result
* @param String $keep_pattern - what pattern to keep "keeme|keepalsome"
* @param String $remove_pattern - what pattern to remove "removeme|removealsome"
* @param boolean $remove_default - if remove is the default action
*/
function writeRss($url, $result_file, $keep_pattern, $remove_pattern, $remove_default = true, $debug = false){

  $xml = simplexml_load_file($url);
  for($i = 0; $i < count($xml->channel->item); $i++){
    $title = $xml->channel->item[$i]->title;
    // keep these 
    if(preg_match('/('.$keep_pattern.')/', $title) > 0) {
      // keep
      if($debug) { print "KEEP:".$title."\n"; }
    } else if(preg_match('/('.$remove_pattern.')/', $title) > 0){
      if($debug) { print "REMOVE:".$title."\n"; }
      // remove these
      unset($xml->channel->item[$i]);
      $i--; // move the counter as the items are decreased
    } else if($remove_default){ // default action
      if($debug) { print "DEFAULT REMOVE:".$title."\n"; }
      unset($xml->channel->item[$i]);
      $i--;
    }
  }

  file_put_contents($result_file, $xml->asXML());
}


writeRss("http://www.hs.fi/rss/?osastot=ulkomaat", "temp_result.xml", "Syyria|kuoli", "poliiseja");
