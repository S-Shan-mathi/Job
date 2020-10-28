<?php
	include_once('logincheck.php');
	include_once('myconnect.php');
	
	$strinlist="0";
	
	foreach($_POST as $key => $value)
	{
		if(stristr($key,"checkbox"))
				$strinlist.=",".$value;
	}
	$strquery=" and sb_id in ($strinlist) ";

		$query_msg_del="delete from sbjbs_search_results where sb_uid=".$_SESSION["sbjbs_userid"]." $strquery";
	//	die($query_msg_del);
		$rs_del=mysql_query($query_msg_del);
		$msgdeleted=mysql_affected_rows();
	if($msgdeleted > 0)
	{	
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("$msgdeleted search result(s) removed."));
		die();
	}
	else
	{
		header("Location: gen_confirm_mem.php?err=manage_search&errmsg=".urlencode("Unable to remove search results"));
		die();
	}
?>