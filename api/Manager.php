<?php

class Manager {
	var $google_url 	= 'https://www.google.se/search?q=';
	var $bing_url		= 'http://www.bing.com/search?q=';
	var $yahoo_url 		= 'search.yahoo.com/search?p=';
	var $ajax_return = '';
	
	public function _liveSsearches($query = null) {
		if($query != null) {
			$this->ajax_return = array(
				'google' 	=> $this->googleSearch($query),
				'bing'		=> $this->bingSearch($query),
				'yahoo'		=> $this->yahooSearch($query) 
			);
		} else {
			$this->ajax_return = array(
				'google' 	=> $this->googleSearch($_POST['searchfield']),
				'bing'		=> $this->bingSearch($_POST['searchfield']),
				'yahoo'		=> $this->yahooSearch($_POST['searchfield'])
			);
		}
		$total = $this->calculateTotal($this->ajax_return);
		return $this->ajax_return;
	}
	
	private function calculateTotal($search_results) {
		
	}
	
	private function googleSearch($query) {
		return $this->googleData($this->get_data($this->google_url.$query));
		
	}
	
	private function bingSearch($query) {
		return $this->bingData($this->get_data($this->bing_url.$query));
	}
	
	private function yahooSearch($query) {
		return $this->yahooData($this->get_data($this->yahoo_url.$query));
	}

	public function _getSearches() {
		return array('getsearch' => 'true');	
	}
	
	public function _saveSearch() {
		
	}

	private function googleData($file) {
		$return_links = array();
    	preg_match_all('%<cite[^>]*>(.*?)</cite>%', $file, $patterns, PREG_SET_ORDER);
		
		foreach($patterns as $pattern) {
			$return_links[] = rtrim($pattern[1], "/");
		}
    	return $return_links; 
	}
	
	private function bingData($file) {
		$return_links = array();
    	preg_match_all('%<cite[^>]*>(.*?)</cite>%', $file, $patterns, PREG_SET_ORDER);
    	foreach($patterns as $pattern) {
    		$return_links[] = $pattern[0];
    	}
		return $return_links; 
	}
	
	private function yahooData($file) {
		$return_links = array();
    	preg_match_all('%<span[^>]+url[^>]*>(.*?)</span>%', $file, $patterns, PREG_SET_ORDER);
		foreach($patterns as $pattern) {
			$return_links[] = $pattern[0];
		}
		return $return_links;
	}

	private function get_data($url) {
	  $ch = curl_init();
	  $timeout = 5;
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data;
	}
}
