<?php
function send_mail_alerts()
{
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));

$mem_q=mysql_query("select * from sbjbs_seekers where sb_suspended<>'yes'");
while($mem=mysql_fetch_array($mem_q))
{
$group_q=mysql_query("select * from sbjbs_mailalert_set where sb_uid=".$mem["sb_id"]);
$job_str="";
$job_cnt=0;

while($group=mysql_fetch_array($group_q))
{
$cid_list=$group["sb_cid"];
$loc_id=$group["sb_loc_id"];

	$job_sql="select sbjbs_jobs.sb_id,sb_title from sbjbs_jobs,sbjbs_job_cats,sbjbs_job_locs 
	where TO_DAYS(NOW())-TO_DAYS(sb_posted_on)<=1 and sb_approved='yes' and
	sbjbs_jobs.sb_id=sbjbs_job_cats.sb_job_id and sbjbs_jobs.sb_id=sbjbs_job_locs.sb_job_id";
	
	if($cid_list<>-1)
	{
	$job_sql.=" and sb_cid in ($cid_list)";
	}
	if($loc_id<>-1)
	{
	$job_sql.=" and sb_lid in ($loc_id)";
	}
	$job_sql.=" group by sbjbs_jobs.sb_id";
	$job_q=mysql_query($job_sql);
	$cnt=0;
	while($job=mysql_fetch_array($job_q))
	{
		$cnt++;
		$job_cnt++;
		$path=$config["sb_site_root"]."/"."view_job.php?sb_id=".$job["sb_id"];
		$job_str.=$job_cnt." <a href='".$path."'>".$job["sb_title"]."</a><br><br>";
		
	}//end jobs
}// end groups
	if($job_cnt>0)
	{
	//sending mail to seeker
		$sb_null_char=$config["sb_null_char"];
		$email=$mem["sb_email_addr"];
		$fname=$mem["sb_firstname"];
		$lname=$mem["sb_lastname"];
		$title=$mem["sb_title"];
		$sql = "SELECT * FROM sbjbs_mails where sb_mailid=13";
		$rs_query=mysql_query($sql);

		if ( $rs=mysql_fetch_array($rs_query)  )
  		{
			 $from =$rs["sb_fromid"];
			 $to = $email;
			 $subject =$rs["sb_subject"];
		     $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

			 $body=str_replace("%title%",$title,str_replace("%email%", $sb_null_char,
			 str_replace("%password%",$sb_null_char, str_replace("%lname%", $lname,
			 str_replace("%fname%", $fname, str_replace("%username%",$sb_null_char, $rs["sb_mail"])))))); 
				
			 $body=str_replace("%signup_url%",$sb_null_char,str_replace("%login_url%",$sb_null_char,$body));

			 $body=str_replace("%message_text%",$sb_null_char,str_replace("%message_title%",$sb_null_char,
			 str_replace("%sender_username%",$sb_null_char,str_replace("%message_date%",$sb_null_char,
			 $body))));	
 
 			 $body=str_replace("%visitor_name%",$sb_null_char,$body);
			 $body=str_replace("%jobcnt%",$job_cnt,str_replace("%jobstr%",$job_str,$body));
		
	 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//$body=str_replace("\n","<br>",$body);
		}
 
 	//echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
	 //echo "<pre>$body</pre>";
	// die();
	if( $rs["sb_status"]=='yes')
	 	mail($to,$subject,$body,$header);
  	}
	}
}// end members 
}//end function
?>