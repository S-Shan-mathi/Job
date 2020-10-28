<?php
include_once "logincheck.php";
include_once "myconnect.php";

$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}
$resume_q=mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
$num_resume=mysql_num_rows($resume_q);
if($num_resume<=0)
{ 
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("Invalid access, denied"));
die();
}

mysql_query("delete from sbjbs_experience where sb_resume_id=$id");
mysql_query("delete from sbjbs_references where sb_resume_id=$id");
mysql_query("delete from sbjbs_education where sb_resume_id=$id");
mysql_query("delete from sbjbs_affiliations where sb_resume_id=$id");
mysql_query("delete from sbjbs_resume_skills where sb_resume_id=$id");
mysql_query("delete from sbjbs_resume_language where sb_resume_id=$id");
mysql_query("delete from sbjbs_resume_cats where sb_resume_id=$id");
mysql_query("delete from sbjbs_resume_locs where sb_resume_id=$id");
mysql_query("delete from sbjbs_applications where sb_resume_id=$id");

mysql_query("delete from sbjbs_resumes where sb_id=$id");

header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("The resume has been removed."));
die();
?>