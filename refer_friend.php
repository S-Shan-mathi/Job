<?php
include_once "myconnect.php";
include_once "date_time_format.php";
//die();
if( !isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) ||  ($_REQUEST["sb_id"] < 1) )
{
	header("Location:"."gen_confirm.php?errmsg=".urlencode("Invalid Id, cannot continue"));
	die();
}

$sb_id=$_REQUEST["sb_id"];

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
		if (!get_magic_quotes_gpc()) 
		{
			$sbyname=str_replace('$', '\$',addslashes($_REQUEST["sbyname"]));
			$sbyemail=str_replace('$', '\$',addslashes($_REQUEST["sbyemail"]));
			$sbfname=str_replace('$', '\$',addslashes($_REQUEST["sbfname"]));
			$sbfemail=str_replace('$', '\$',addslashes($_REQUEST["sbfemail"]));
		}
		else
		{
			$sbyname=str_replace('$', '\$',$_REQUEST["sbyname"]);
			$sbyemail=str_replace('$', '\$',$_REQUEST["sbyemail"]);
			$sbfname=str_replace('$', '\$',$_REQUEST["sbfname"]);
			$sbfemail=str_replace('$', '\$',$_REQUEST["sbfemail"]);
		}

		if ( strlen(trim($sbyname)) == 0 )
		{
			//echo $sbname." kya hai";die();
			$errs[$errcnt]="Your Name field must be provided";
    		$errcnt++;
		}
		elseif(preg_match ("/[<>&]/", $sbyname))
		{
			$errs[$errcnt]="Your Name field must not have any special character i.e. & < >";
    		$errcnt++;
		}
		
		if ( strlen(trim($sbyemail)) == 0 )
		{
			$errs[$errcnt]="Your Email Address field must be provided";
    		$errcnt++;
		}

		if ( strlen(trim($sbfname)) == 0 )
		{
			$errs[$errcnt]="Friend's Name must be provided";
    		$errcnt++;
		}
		elseif(preg_match ("/[<>&]/", $sbfname))
		{
			$errs[$errcnt]="Friend's Name must not have any special character i.e. & < >";
    		$errcnt++;
		}
		
		if ( strlen(trim($sbfemail)) == 0 )
		{
			$errs[$errcnt]="Friend's Email Address must be provided";
    		$errcnt++;
		}
		elseif( $sbyemail==$sbfemail )
		{
			$errs[$errcnt]="Friend's Email Address must be different from Your Email Address";
    		$errcnt++;
		}
	
	if($errcnt==0)
	{
//	mysql_query("INSERT INTO sbjbs_feedback (sb_fname,sb_lname,sb_email,sb_url,sb_title,sb_comments) VALUES('$fname','$lname','$email','$url','$title','$comments')");
	
//	if(mysql_affected_rows()>0)
//	{
	//--------------
				//SENDING MAIL TO MEMBER////////////////////////
	$sbq_job="select * from sbjbs_jobs where sb_id=$sb_id";
	$sbrow_job=mysql_fetch_array(mysql_query($sbq_job));
	$sbjob_title=$sbrow_job["sb_title"];
		
	$row_con=mysql_fetch_array(mysql_query("select * from sbjbs_config where sb_id=1"));
	$sb_null_char=$row_con["sb_null_char"];
	$sbsignup_url=$row_con["sb_site_root"]."/addmember.php";
	$login_url=$row_con["sb_site_root"]."/signin.php";
	$sbjob_url=$row_con["sb_site_root"]."/view_job.php?sb_id=$sb_id";
	$ondate=sb_date(date(time()));

//Reads email to be sebt
$sql = "SELECT * FROM sbjbs_mails where sb_mailid=9";
//echo $sql; 
$rs_query=mysql_query($sql);

if ( $rs=mysql_fetch_array($rs_query)  )
  {
			 $from =$rs["sb_fromid"];
			 $to = $sbfemail;
			 $subject =$rs["sb_subject"];
		     $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

			 $body=str_replace("%email%", $sb_null_char,str_replace("%password%",$sb_null_char,str_replace("%lname%", $sb_null_char,str_replace("%fname%", $sb_null_char,str_replace("%username%",$sb_null_char, $rs["sb_mail"]) )))); 
				
			$body=str_replace("%signup_url%",$sbsignup_url,str_replace("%login_url%",$login_url,$body));

		 $body=str_replace("%message_text%",$sb_null_char,str_replace("%message_title%",$sb_null_char,str_replace("%sender_username%",$sb_null_char,str_replace("%message_date%",$ondate,$body))));	
 
 		$body=str_replace("%visitor_name%",$sbyname,str_replace("%friend_name%",$sbfname,$body));
		
		$body=str_replace("%job_id%",$sb_id,str_replace("%job_title%",$sbjob_title,str_replace("%job_url%",$sbjob_url,$body)));
		
		if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//$body=str_replace("\n","<br>",$body);
		}
 
// 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
//	 echo "<pre>$body</pre>";
//	 die();
	if( $rs["sb_status"]=='yes')
	 	mail($to,$subject,$body,$header);
  }

	header("Location:"."gen_confirm.php?errmsg=".urlencode("An email has been sent to your friend."));
	die();
/*	}
	else
	{
	header("Location:"."gen_confirm.php?err=linktous&errmsg=".urlencode("Sorry, some error occurred and  unable to send comments/feedback to Administrator."));
	die();
	}
 */
  }//if no errs
}//if posted

function main()
{
global $errs, $errcnt, $sb_id;

$sql = "SELECT * FROM sbjbs_mails where sb_mailid=9";
//echo $sql; 
$sbrow_mail=mysql_fetch_array(mysql_query($sql));

 
$sbyname="";
$sbyemail="";
$sbfname="";
$sbfemail="";

if(count($_POST)>0)
{
$sbyname=$_POST["sbyname"];
$sbyemail=$_POST["sbyemail"];
$sbfname=$_POST["sbfname"];
$sbfemail=$_POST["sbfemail"];
}
if  (count($_POST)>0)
{

if ( $errcnt<>0 )
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr> 
    <td colspan="2"><strong>&nbsp;Your request cannot be processed due to following 
      reasons</strong></td>
  </tr>
  <tr height="10"> 
    <td colspan="2"></td>
  </tr>
  <?

for ($i=0;$i<$errcnt;$i++)
{
?>
  <tr valign="top"> 
    <td width="6%">&nbsp;<?php echo $i+1;?></td>
    <td width="94%"><?php echo  $errs[$i]; ?></td>
  </tr>
  <?
}
?>
</table><br>
<?

}

}

?>
<SCRIPT language=javascript> 
//<!--
function emailCheck (emailStr) {
var emailPat=/^(.+)@(.+)$/
var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"
var validChars="\[^\\s" + specialChars + "\]"
var quotedUser="(\"[^\"]*\")"
var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/
var atom=validChars + '+'
var word="(" + atom + "|" + quotedUser + ")"
var userPat=new RegExp("^" + word + "(\\." + word + ")*$")
var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")
var matchArray=emailStr.match(emailPat)
if (matchArray==null) {
	alert("Email address seems incorrect (check @ and .'s)")
	return false
}
var user=matchArray[1]
var domain=matchArray[2]
if (user.match(userPat)==null) {
    alert("The username doesn't seem to be valid.")
    return false
}
var IPArray=domain.match(ipDomainPat)
if (IPArray!=null) {
    // this is an IP address
	  for (var i=1;i<=4;i++) {
	    if (IPArray[i]>255) {
	        alert("Destination IP address is invalid!")
		return false
	    }
    }
    return true
}
var domainArray=domain.match(domainPat)
if (domainArray==null) {
	alert("The domain name doesn't seem to be valid.")
    return false
}
var atomPat=new RegExp(atom,"g")
var domArr=domain.match(atomPat)
var len=domArr.length
if (domArr[domArr.length-1].length<2 || 
		domArr[domArr.length-1].length>4) {
	   alert("The address must end in a valid domain, or two letter country.")
   return false
}
if (len<2) {
   var errStr="This address is missing a hostname!"
   alert(errStr)
   return false
}
return true;
}

function Validate(form) 
{
	if ( form.sbyname.value == "" ) 
	{
		alert('Please specify Your Name!');
		form.sbyname.focus();
		return false;
	}
	if(form.sbyname.value.match(/[&<>]+/))
	{
			alert("Please remove special characters from Your Name i.e. &  < >");
			form.sbyname.focus();
			form.sbyname.select();
			return(false);
	}
	   
	if ( form.sbyemail.value == "" ) 
	{
		alert('Please specify Your Email Address!');
		form.sbyemail.focus();
		return false;
	}
	else if(!form.sbyemail.value.match(/[a-zA-Z\.\@\d\_]/)) 
	{
		alert('Invalid e-mail address.');
		form.sbyemail.focus();
		return false;
	}
	else if (!emailCheck (form.sbyemail.value) )
	{
		form.sbyemail.focus();
		return (false);
	}

	if ( form.sbfname.value == "" ) 
	{
		alert('Please specify Your Name!');
		form.sbfname.focus();
		return false;
	}
	else if(form.sbfname.value.match(/[&<>]+/))
	{
			alert("Please remove special characters from Your Name i.e. &  < >");
			form.sbfname.focus();
			form.sbfname.select();
			return(false);
	}
	   
	if ( form.sbfemail.value == "" ) 
	{
		alert("Please specify Friend's Email Address!");
		form.sbfemail.focus();
		return false;
	}
	else if(!form.sbfemail.value.match(/[a-zA-Z\.\@\d\_]/)) 
	{
		alert('Invalid e-mail address.');
		form.sbfemail.focus();
		return false;
	}
	else if (!emailCheck (form.sbfemail.value) )
	{
		form.sbfemail.focus();
		return (false);
	}

	if ( form.sbyemail.value == form.sbfemail.value ) 
	{
		alert("Please specify valid Friend's Email Address!");
		form.sbfemail.focus();
		form.sbfemail.select();
		return false;
	}

   	return true;
}
// -->

</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top"><form name="form" onSubmit="return Validate(this);" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table width="100%" border="0" cellspacing="5" cellpadding="2" class="onepxtable">
          <tr> 
            <td height="25" colspan="3" align="left" class="titlestyle">&nbsp;Refer 
              to a Friend</td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Your 
              Name </b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input name="sbyname" type="text" id="sbyname" value="<?php echo $sbyname;?>" SIZE="35">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Your 
              Email Address</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input name="sbyemail" type="text" id="sbyemail" value="<?php echo $sbyemail;?>" SIZE="35">
              </font></td>
          </tr>
          <tr>
            <td width="40%" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Friend's 
              Name </b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input name="sbfname" type="text" id="sbfname" value="<?php echo $sbfname;?>" SIZE="35">
              </font></td>
          </tr>
          <tr>
            <td width="40%" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Friend's 
              Email Address</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input name="sbfemail" type="text" id="sbfemail" value="<?php echo $sbfemail;?>" SIZE="35">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="40%" height="25" align="right" class='innertablestyle'><font class='normal'>&nbsp;<b>Mail 
              Contents</b></font></td>
            <TD align=right><font class='red'>&nbsp;</FONT></TD>
            <td width="60%"> <font class='smalltext'> 
              <textarea name="comments" cols="35" rows="5" readonly ><?php echo strip_tags($sbrow_mail['sb_mail']); ?></textarea>
              <br>
              (This text will be sent to your friend, it contains custom defined 
              tags which will be replaced by respective values. You cannot edit 
              this.) </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" class='innertablestyle'><font class='normal'>&nbsp;</font></td>
            <td><font class='normal'>&nbsp;</font></td>
            <td width="60%"><font class='normal'> 
              <INPUT type=submit value=Submit name=submit>
              <input name="sb_id" type="hidden" id="sb_id" value="<?php echo $sb_id; ?>">
              </font></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
 <?
}// end main
include "template.php";
?> 