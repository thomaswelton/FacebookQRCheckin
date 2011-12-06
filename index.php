<?php
	session_start();
	
	//$my_url = 'http://checkinmanager.local:8888/';
	$my_url = "http://www.clicktag.co.uk/checkIn";
	
	$placeArray = array('place' => '92816507389', 'coordinates' => array('latitude' => '51.4846115', 'longitude' => '-0.17546'));
	
	require_once('helper.php');
	$helper = new Helper();
	
	$facebook = $helper->getFacebook();


	$code = array_key_exists('code',$_REQUEST) ? $_REQUEST["code"] : NULL;
	
	if(empty($code)) {
		 $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		 $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
		   . $facebook->getAppId() . "&redirect_uri=" . urlencode($my_url) . "&display=touch" . "&state=" . $_SESSION['state'] . "&scope=" . implode(',',array('publish_checkins'));
		   
		 echo("<script> top.location.href='" . $dialog_url . "'</script>");
		 die;
	}
	
	if($_REQUEST['state'] == $_SESSION['state']) {
		 $token_url = "https://graph.facebook.com/oauth/access_token?"
		   . "client_id=" . $facebook->getAppId() . "&redirect_uri=" . urlencode($my_url)
		   . "&client_secret=" . $facebook->getApiSecret() . "&code=" . $code;
		
		 $response = @file_get_contents($token_url);
		 $params = null;
		 parse_str($response, $params);
		 $access_token = $params['access_token'];
		 
		 
		if(!is_null($access_token)){
			//Show the publish checkin button
			try{
				$checkIn = $facebook->api('me/checkins','POST',array_merge($placeArray,array('access_token' => $access_token)));
				
				if(is_array($checkIn) && array_key_exists('id',$checkIn)){
					$body = "Thank you for checking in at ClickTag";	
				}else{
					$body = "There was a problem checkin you in";
					$body .= print_r($checkIn,true);	
				}
				
			}catch(Exception $e){
				$body = $e->getMessage();	
			}
		}else{
			$body = "Please log in";	 
		}
	}
	else {
		$body = "The state does not match. You may be a victim of CSRF.";
	}
	
	include('template.php');
?>