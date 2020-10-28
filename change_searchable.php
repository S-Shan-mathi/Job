<?php
	include "logincheck.php";
	include_once "myconnect.php";

	if(!isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) || ($_REQUEST["sb_id"] < 1))
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Invalid Id, cannot proceed."));
		die();
	}
	$sb_id=$_REQUEST["sb_id"];
	$chk_q=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_approved='yes' and sb_id=$sb_id"));
	if($chk_q<=0)
	{
		header("Location:"."gen_confirm.php?errmsg=".urlencode("Invalid Id, cannot continue"));
		die();
	}

	$sbq1_job="select * from sbjbs_resumes where sb_id=$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	$sbrow1_job=mysql_fetch_array(mysql_query($sbq1_job));
	if(!$sbrow1_job)	
	{	
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Unauthorised Access, denied."));
		die();
	}
	if($sbrow1_job["sb_search_enable"]=='yes')
	{
		$sb_conf='no';
		$msg='not searchable';
	}
	else
	{
		$sb_conf='yes';
		$msg='seachable';
	}

	$sbq1_job="update sbjbs_resumes set sb_search_enable='no' where sb_id<>$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	mysql_query($sbq1_job);
	$sbq_job="update sbjbs_resumes set sb_search_enable='$sb_conf' where sb_id=$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	mysql_query($sbq_job);
	if(mysql_affected_rows() == 1)
	{
		header("Location: resumes.php?errmsg=".urlencode("Resume status has been changed to $msg."));
		die();
	}
	else
	{
		header("Location: resumes.php?errmsg=".urlencode("Unable to make resume $msg, please try again."));
		die();
	}
?>