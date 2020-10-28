<?php
include_once "logincheck.php";
include_once"myconnect.php";

$mem=mysql_fetch_array(mysql_query("select * from sbjbs_seekers where sb_id=".$_SESSION["sbjbs_userid"]));

		if(!get_magic_quotes_gpc())
			$sbemail_id=str_replace("$","\$",addslashes($mem["sb_email_addr"]));
		else
			$sbemail_id=str_replace("$","\$",$mem["sb_email_addr"]);

	$sbquery_mem="select * from sbjbs_newsletter where sb_email='$sbemail_id'";
	$sbrs_mem=mysql_query($sbquery_mem);

	if(mysql_num_rows($sbrs_mem) > 0)
	{
	$sbquery_mem="delete from sbjbs_newsletter where sb_email='$sbemail_id'";
	$sbrs_mem=mysql_query($sbquery_mem);
	$msg_part=" removed from ";
	}
	else
	{
	$sbquery_mem="insert into sbjbs_newsletter (sb_email) values ('$sbemail_id')";
	$sbrs_mem=mysql_query($sbquery_mem);
	$msg_part=" added to ";
	}

if(mysql_affected_rows() >0)
{
	header("Location: personal_confirm_mem.php?errmsg=".urlencode("Your Email address has been".$msg_part."the Subscribers List"));
	die();
}
else
{
	header("Location: personal_confirm_mem.php?errmsg=".urlencode("Some error occurred, Please try again"));
	die();
}
?>