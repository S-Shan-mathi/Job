<?php

include_once "session.php";  

if (!isset($_SESSION["sbjbs_userid"]))
{
	$errmsg="You must be logged to access this page.";
	$sb_id=0;
	$sb_type=0;
	
	if(isset($_REQUEST["sb_id"]))
		$sb_id=$_REQUEST["sb_id"];
	
	if(isset($_REQUEST["sb_type"]))
		$sb_type=$_REQUEST["sb_type"];
	
	if(preg_match("/\/apply_now.php/",$_SERVER['SCRIPT_NAME']))
		$errmsg="You must be logged-in to apply for a job.";
	elseif(preg_match("/\/add_favorites.php/",$_SERVER['SCRIPT_NAME']))
		$errmsg="You must be logged-in to add offers/profiles to your favorite list.";
	
	$return_add=$_SERVER['PHP_SELF'];
	
	header("Location: signin.php?id=$sb_id&return_add=$return_add&sb_type=$sb_type&errmsg=" .urlencode($errmsg) );
	die();
}
?>