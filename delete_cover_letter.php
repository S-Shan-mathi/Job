<?php
	include "logincheck.php";
	include_once "myconnect.php";

	if(!isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) || ($_REQUEST["sb_id"] < 1))
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Invalid Id, cannot proceed."));
		die();
	}
	$sb_id=$_REQUEST["sb_id"];
	$sbq_job="delete from sbjbs_cover_letters where sb_id=$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	mysql_query($sbq_job);
	if(mysql_affected_rows() == 1)
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Cover letter has been removed."));
		die();
	}
	else
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Unable to remove cover letter, please try again."));
		die();
	}
?>