<?php
/*
* Caches API calls to a local file which is updated on a
* given time interval.
*/
class API_cache {
  
  private
      $_update_interval // how often to update
    , $_cache_file // file to save results to
    , $_api_call // API call (URL)
    , $_time_diff
    , $_url_params; 

  public function __construct ($tw, $int=10, $cf, $params) {
    $this->_api_call = $tw;
    $this->_update_interval = $int ; // seconds to cache
    $this->_cache_file = $cf;
    $this->_time_diff = 280;
    $this->_url_params = $params;
  }

  /*
* Updates cache if last modified is greater than
* update interval and returns cache contents
*/
  public function get_api_cache () {
    if (!file_exists($this->_cache_file) ||
        time() - filemtime($this->_cache_file) > ($this->_update_interval + $this->_time_diff)) {
      $this->_update_cache();
    }
    $temp = time() - filemtime($this->_cache_file);
    return file_get_contents($this->_cache_file);
  }

  /*
* Http expires date
*/
  public function get_expires_datetime () {
    if (file_exists($this->_cache_file)) {
      return date (
        'D, d M Y H:i:s \G\M\T',
        filemtime($this->_cache_file) + ($this->_update_interval) + $this->_time_diff
      );
    }
  }

  /*
* Makes the api call and updates the cache
*/
  private function _update_cache () {
    // update from api if past interval time
    $fp = fopen($this->_cache_file, 'w+'); // open or create cache
    if ($fp) {
      if (1) {
      	$temp = $this->_api_call;
        $tweets = $this->curl_call($temp."?nocache=true",$this->_url_params);
        //$tweets = "";
        $access = date("Y/m/d H:i:s");
        fwrite($fp,$tweets);
        //flock($fp, LOCK_UN);
      }
      fclose($fp);
    }
  }
  
  private function curl_call($curlurl,$attachment=null){
  	$options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => false,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_CONNECTTIMEOUT => 10,      // timeout on connect
        CURLOPT_TIMEOUT        => 10,      // timeout on response
    );

    $ch      = curl_init( $curlurl );
    curl_setopt_array( $ch, $options );
  	if(isset($attachment) && is_array($attachment)){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
	}
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $content;
  }
  
}
