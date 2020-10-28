<?
include_once("myconnect.php");
if (  !isset($_REQUEST["email"]) ||($_REQUEST["email"]=="") )
{
 header("Location: ". "gen_confirm.php?err=lostpassword&errmsg=".urlencode("Please provide your email id to retrieve your password.") );
 die();
}
$null_char="-";
/////////////getting Site Root
$site_root=mysql_fetch_array(mysql_query("select sb_site_root from sbjbs_config"));
			
			if (!get_magic_quotes_gpc()) {
			$email=str_replace('$', '\$',addslashes($_REQUEST["email"]));
			}
			else
			{
			$email=str_replace('$', '\$',$_REQUEST["email"]);
			}
		$rs0= mysql_fetch_array(mysql_query("SELECT * FROM sbjbs_seekers WHERE sb_email_addr='$email'"));
		if($rs0)
		{
		//Reads email to be sebt
		$sql = "SELECT * FROM sbjbs_mails where sb_mailid=4" ;
		$rs_query=mysql_query($sql);
		$login_url=$site_root[0]."/signin.php";
		
		if ( $rs=mysql_fetch_array($rs_query)  )// if mail
		{
		 if($rs["sb_status"]=="yes")	
		  {
					 $from =$rs["sb_fromid"];
					 $to = $rs0["sb_email_addr"];
					 $subject =$rs["sb_subject"];
							
			 $body=str_replace("%lname%",$rs0["sb_lastname"],str_replace("%title%",$rs0["sb_title"],str_replace("%username%",$rs0["sb_username"],str_replace("%fname%",$rs0["sb_firstname"],str_replace("%email%",$rs0["sb_email_addr"],str_replace("%password%",$rs0["sb_password"],str_replace("%login_url%",$login_url,$rs["sb_mail"]))))))) ;
			  
			  $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;
			 if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
			{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//$body=str_replace("\n","<br>",$body);
			}

// 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
// 	echo "<pre>$body</pre>";
			// die();
			 	mail($to,$subject,$body,$header);
		  }// end if status is on
		  }// end if mail 
	 header("Location: ". "gen_confirm.php?errmsg=".urlencode( "Your password has been e-mailed") );
   	die();
  }

//////////////////////////////////////////////////////////
else
{
 header("Location: ". "gen_confirm.php?errmsg=" . urlencode("No Member found with such email id.") );
 die();
}
?>