<?php 
include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";
if(isset($_REQUEST["sb_id"]) && is_numeric($_REQUEST["sb_id"]))
{
	$sb_id=$_REQUEST["sb_id"];
	$sbq_sea="select * from sbjbs_search_results where sb_id=$sb_id and sb_uid=".$_SESSION["sbjbs_userid"];
//	die ($sbq_sea);
	$sbrow_sea=mysql_fetch_array(mysql_query($sbq_sea));
	if($sbrow_sea)
	{
		$sbstr_pass="";
		if($sbrow_sea["sb_keyword"]<>'')
			$sbstr_pass.="&keyword=".$sbrow_sea["sb_keyword"];
		if( ($sbrow_sea["sb_search_method"] > 0) && ($sbrow_sea["sb_search_method"] < 4))
			$sbstr_pass.="&search_method=".$sbrow_sea["sb_search_method"];
		$sbstr_pass.="&cid_list=".$sbrow_sea["sb_cid_list"];
		$sbstr_pass.="&loc_id=".$sbrow_sea["sb_loc_id"];
		if($sbrow_sea["sb_work_exp"]<>"")
			$sbstr_pass.="&work_exp=".$sbrow_sea["sb_work_exp"];
		
		$sbstr_pass.="&view=".$sbrow_sea["sb_view"];

		header("Location: search_result.php?tmp=1$sbstr_pass");
		die();
	}	//end if $sbrow_sea exists
}
header("Location: index.php");
?>