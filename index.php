<?php

require 'config.php';
require 'facebook.php';

//Create facebook application instance.
$facebook = new Facebook(array(
  'appId'  => $fb_app_id,
  'secret' => $fb_secret,
  'cookie' => true,
));

$friends = array();
$sent = false;
$userData = null;

//redirect to facebook page
if(isset($_GET['code'])){
  header("Location: " . $fb_app_url);
	exit;
}

$user = $facebook->getUser();
if ($user) {
	//get user data
	try {
		$userData = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		//do something about it
	}
	
	//get 5 random friends
	try {
		$friendsTmp = $facebook->api('/' . $userData['id'] . '/friends');
		shuffle($friendsTmp['data']);
		array_splice($friendsTmp['data'], 5);
		$friends = $friendsTmp['data'];
	} catch (FacebookApiException $e) {
		//do something about it
	}
	
	//post message to wall if it is sent trough form
	if(isset($_POST['mapp_message'])){
		try {
			$facebook->api('/me/feed', 'POST', array(
				'message' => $_POST['mapp_message']
			));
			$sent = true;
		} catch (FacebookApiException $e) {
			//do something about it
		}
	}
	
} else {
	$loginUrl = $facebook->getLoginUrl(array(
		'canvas' => 1,
		'fbconnect' => 0,
		'scope' => 'publish_stream',
	));
}

?>
<!DOCTYPE html 
	PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="et" lang="en">
	<head>
		<title>facebook-php-sdk example app</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body { font-family:Verdana,"Lucida Grande",Lucida,sans-serif; font-size: 12px}
		#apDiv1 {
	position:absolute;
	width:557px;
	height:115px;
	z-index:1;
	left: 165px;
	top: 473px;
}
        </style>
	</head>
<body>
		<h1> graph-API example app using php-sdk</h1>
		
			<?php if ($user){ ?>
				<?php if ($sent){ ?>
					<p><strong>Message sent!</strong></p>
				<?php } ?>
				<form method="post" action="">
					<p><input type="text" value="Your message here..." size="60" name="mapp_message" /></p>
					<p><input type="submit" value="Send message to the wall" name="sendit" /></p>
				</form>
				<p>
					<br /><br />
					5 of your randomly picked friends:<br /><br />
					<?php foreach($friends as $k => $i){ ?>
						<strong><?php echo $i['name']; ?></strong><br />
					<?php } ?>
				</p>
			<?php } else { ?>
				<p>
				<strong><a href="<?php echo $loginUrl; ?>" target="_top">Allow this app to interact with my profile</a></strong>
				<br /><br />
				This is just a simple app for testing/demonstrating some facebook graph API calls usinf php-sdk library. After allowing this application, 
				it can be used to post messages on your wall. Also it will list 5 of your randomly picked friends.
				</p>
			<?php } ?>
			<div id="apDiv1">
			  <form id="form1" method="post" action="">
		      </form>
			  <form id="form2" method="post" action="">
			    <p>
			      <label>
		          1</label>
		          <input type="radio" name="radio" id="1" value="1" />
		          <label>1</label>
                  <input type="radio" name="radio" id="12" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="13" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="14" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="15" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="16" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="17" value="1" />
                  <label>1</label>
                  <input type="radio" name="radio" id="18" value="1" />
                </p>
		      </form>
            </div>
			<p>
				
			</p>
			
	</body>
</html>
