<?php
	//Local
	//define('FB_KEY','286082064767708');
	//define('FB_SECRET','cbe2cee113c46d8f143ad0eb71555dd2');
	
	//Production
	define('FB_KEY','201564959926338');
	define('FB_SECRET','79b67e9d2f254048e149b91ebed72aae');

	class Helper{
		var $facebook = NULL;
		
		function getFacebook(){
			//Generate Facebook instance if required
			if(is_null($this->facebook)){
				//Load in Facebook class if required
				if(!class_exists('myApiFacebook')){
					require_once('facebook/myApiConnectFacebook.php');
				}
				
				$this->facebook =  new myApiFacebook(array(   
					'appId'  => FB_KEY,
					'secret' => FB_SECRET,
					'cookie' => true, // enable optional cookie support
				));
			}
			return $this->facebook;	
		}
			
	}
?>