<?php
	include "logincheck.php";
	include_once "myconnect.php";

//////------------checking for letter count
	$sbq_cov="select * from sbjbs_cover_letters where sb_seeker_id=".$_SESSION["sbjbs_userid"];
	//die($sbq_cov);
	$sbrs_cov=mysql_query($sbq_cov);
	$sbtotal_letters=mysql_num_rows($sbrs_cov);
	
	$sbq_con="select * from sbjbs_config where sb_id=1";
	$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
	if( ($sbrow_con["sb_letter_cnt"] > 0) && ($sbtotal_letters >= $sbrow_con["sb_letter_cnt"]) )
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("You already have maximum number of cover letters allowed."));
		die();
	}
//////------------end checking for letter count
	
	if(!isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) || ($_REQUEST["sb_id"] < 1))
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Invalid Id, cannot proceed."));
		die();
	}
	$sb_id=$_REQUEST["sb_id"];

	$sbq_job="select * from sbjbs_cover_letters where sb_id=$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	$sbrow_job=mysql_fetch_array(mysql_query($sbq_job));
	if(!$sbrow_job)
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Unauthorised access, denied."));
		die();
	}
	$sb_title=$sbrow_job["sb_title"];
	$sb_contents=$sbrow_job["sb_contents"];
	
	if(!get_magic_quotes_gpc())
	{
		$sb_title=str_replace("$","\$",addslashes($sb_title));
		$sb_contents=str_replace("$","\$",addslashes($sb_contents));
	}
	else
	{
		$sb_title=str_replace("$","\$",$sb_title);
		$sb_contents=str_replace("$","\$",$sb_contents);
	}
		
	$sbq_cov="insert into sbjbs_cover_letters (sb_title, sb_contents, sb_seeker_id,sb_approved) values( '$sb_title', '$sb_contents',".$_SESSION["sbjbs_userid"].",'".$sbrow_job["sb_approved"]."')";
//	die($sbq_cov);
	mysql_query($sbq_cov);
	if(mysql_affected_rows() == 1)
	{
		$sbq1_cov="select max(sb_id) as sb_max_id from sbjbs_cover_letters where sb_seeker_id=".$_SESSION["sbjbs_userid"];
		$sbrow1_cov=mysql_fetch_array(mysql_query($sbq1_cov));
		$sb_max_id=$sbrow1_cov["sb_max_id"];
		header("Location: add_cover_letter.php?sb_id=$sb_max_id&msg=".urlencode("Cover letter has been copied."));
		die();
	}
	else
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Unable to copy cover letter, please try again."));
		die();
	}
?>