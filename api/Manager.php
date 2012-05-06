<?php
require_once('DB.php');

class Manager {
	var $google_url 	= 'https://www.google.com/search?q=';
	var $bing_url		= 'http://www.bing.com/search?q=';
	var $yahoo_url 		= 'search.yahoo.com/search?p=';
	var $ajax_return = '';
	var $DB;
	var $search_string;
	var $id;
	var $total_times 	= array();
	
	function __construct() {
		$this->DB = new MysqlDB('localhost', 'root' , 'root', 'work203');
	}
	
	public function _liveSsearches($query = null) {		
		if($query == null) {
			$this->search_string = $_POST['searchfield'];
		} else {
			$this->search_string = $query;
		}
		
		$this->DB->insert('search', array('search_string' => $this->search_string));
		$this->id = $this->DB->getLastInsertedId();

		$this->ajax_return = array(
			'google' 	=> $this->googleSearch($this->search_string),
			'bing'		=> $this->bingSearch($this->search_string),
			'yahoo'		=> $this->yahooSearch($this->search_string),
			'total'		=> ''
		);
		$this->calculateTotal($this->ajax_return);
		return $this->ajax_return;
	}
	
	private function calculateTotal($search_results) {
		
		foreach($search_results['google'] as $gskey => $gsvalue) {
			$gvalue = 10 - $gskey;
			$bvalue = 0;
			$yvalue = 0;
			foreach($search_results['bing'] as $bskey => $bsvalue) {
				if($gsvalue == $bsvalue) {
					$bvalue = 10 - $bskey;
				}
			}
			foreach($search_results['yahoo'] as $yskey => $ysvalue) {
				if($gsvalue == $ysvalue) {
					$yvalue = 10 - $yskey;
				}
			}		
			$this->times($gsvalue, ($gvalue + $bvalue + $yvalue));		
		}
		
		foreach($search_results['yahoo'] as $gskey => $gsvalue) {
			$gvalue = 10 - $gskey;
			$bvalue = 0;
			$yvalue = 0;
			foreach($search_results['google'] as $bskey => $bsvalue) {
				if($gsvalue == $bsvalue) {
					$bvalue = 10 - $bskey;
				}
			}
			foreach($search_results['bing'] as $yskey => $ysvalue) {
				if($gsvalue == $ysvalue) {
					$yvalue = 10 - $yskey;
				}
			}		
			$this->times($gsvalue, ($gvalue + $bvalue + $yvalue));		
		}
		
		foreach($search_results['bing'] as $gskey => $gsvalue) {
			$gvalue = 10 - $gskey;
			$bvalue = 0;
			$yvalue = 0;
			foreach($search_results['yahoo'] as $bskey => $bsvalue) {
				if($gsvalue == $bsvalue) {
					$bvalue = 10 - $bskey;
				}
			}
			foreach($search_results['google'] as $yskey => $ysvalue) {
				if($gsvalue == $ysvalue) {
					$yvalue = 10 - $yskey;
				}
			}		
			$this->times($gsvalue, ($gvalue + $bvalue + $yvalue));		
		}
		
		$this->saveAndSetData();
	}

	private function times($string, $times) {
		if(!array_key_exists($string, $this->total_times)) {
			$this->total_times[$string] = $times; 	
		}
	}
	
	private function saveAndSetData() {
		foreach($this->total_times as $url => $time) {
			$data = array(
				'search_id'		=> $this->id,
				'search_result'	=> $url,
				'times'			=> $time
			);
			if($time > -1) {
				$this->DB->insert('results', $data);	
				$this->DB->toNull();
			}
		}
		
		$results = $this->DB->query("SELECT search_result, times FROM results WHERE search_id = $this->id ORDER BY times DESC");
		foreach($results as $url => $time) {
			$this->ajax_return['total'][$url] = $time;
		}
	}
	
	private function googleSearch($query) {
		return $this->googleData($this->getData($this->google_url.$query));
		
	}
	
	private function bingSearch($query) {
		return $this->bingData($this->getData($this->bing_url.$query));
	}
	
	private function yahooSearch($query) {
		return $this->yahooData($this->getData($this->yahoo_url.$query));
	}

	public function _getSearches() {
		return array('getsearch' => 'true');	
	}

	private function googleData($file) {
		$return_links = array();
    	preg_match_all('%<cite[^>]*>(.*?)</cite>%', $file, $patterns, PREG_SET_ORDER);
		
		foreach($patterns as $pattern) {
			$url = strip_tags($pattern[1]);
			$return_links[] = rtrim($url, "/");
		}
		$size = sizeof($return_links);
		$overlap = $size - 11;
		if($overlap >= 1) {
			for($i = 0; $i < $overlap; $i++) {
				array_pop($return_links);
			}
		}
    	return $return_links;
	}
	
	private function bingData($file) {
		$return_links = array();
    	preg_match_all('%<cite[^>]*>(.*?)</cite>%', $file, $patterns, PREG_SET_ORDER);
    	foreach($patterns as $pattern) {
    		$return_links[] = strip_tags($pattern[0]);
    	}
		$size = sizeof($return_links);
		$overlap = $size - 11;
		if($overlap >= 1) {
			for($i = 0; $i < $overlap; $i++) {
				array_pop($return_links);
			}
		}
		return $return_links; 
	}
	
	private function yahooData($file) {
		$return_links = array();
    	preg_match_all('%<span[^>]+url[^>]*>(.*?)</span>%', $file, $patterns, PREG_SET_ORDER);
		foreach($patterns as $pattern) {
			$return_links[] = strip_tags($pattern[0]);
		}
		$size = sizeof($return_links);
		$overlap = $size - 11;
		if($overlap >= 1) {
			for($i = 0; $i < $overlap; $i++) {
				array_pop($return_links);
			}
		}
		return $return_links;
	}

	private function getData($url) {
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
