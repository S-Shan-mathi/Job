<?
include_once "myconnect.php";
include_once "date_time_format.php";

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
			if (!get_magic_quotes_gpc()) {
			$fname=str_replace('$', '\$',addslashes($_REQUEST["fname"]));
			$lname=str_replace('$', '\$',addslashes($_REQUEST["lname"]));
			$email=str_replace('$', '\$',addslashes($_REQUEST["email"]));
			$url=str_replace('$', '\$',addslashes($_REQUEST["url"]));
			$title=str_replace('$', '\$',addslashes($_REQUEST["title"]));
			$comments=str_replace('$', '\$',addslashes($_REQUEST["comments"]));
			}
			else
			{
			$fname=str_replace('$', '\$',$_REQUEST["fname"]);
			$lname=str_replace('$', '\$',$_REQUEST["lname"]);
			$email=str_replace('$', '\$',$_REQUEST["email"]);
			$url=str_replace('$', '\$',$_REQUEST["url"]);
			$title=str_replace('$', '\$',$_REQUEST["title"]);
			$comments=str_replace('$', '\$',$_REQUEST["comments"]);
			}

	if ( strlen(trim($fname)) == 0 )
	{
		$errs[$errcnt]="First Name must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["fname"]))
	{
		$errs[$errcnt]="First Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($lname)) == 0 )
	{
		$errs[$errcnt]="Last Name must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["lname"]))
	{
		$errs[$errcnt]="Last Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($email)) == 0 )
	{
		$errs[$errcnt]="Email must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["email"]))
	{
		$errs[$errcnt]="Email can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if(preg_match ("/[;<>&]/", $_REQUEST["url"]))
	{
		$errs[$errcnt]="URL can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($title)) == 0 )
	{
		$errs[$errcnt]="Title must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Title can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($comments)) == 0 )
	{
		$errs[$errcnt]="Comments/Feedback must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["comments"]))
	{
		$errs[$errcnt]="Comments/Feedback can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if($errcnt==0)
	{
	mysql_query("INSERT INTO sbjbs_feedback 
	(sb_fname,sb_lname,sb_email,sb_url,sb_title,sb_comments) 
	VALUES('$fname','$lname','$email','$url','$title','$comments')");
	
	if(mysql_affected_rows()>0)
	{
	//--------------
				//SENDING MAIL TO MEMBER////////////////////////
	$row_con=mysql_fetch_array(mysql_query("select * from sbjbs_config where sb_id=1"));
	$sb_null_char=$row_con["sb_null_char"];
	$login_url=$row_con["sb_site_root"]."/signin.php";
	$ondate=sb_date(date(time()));

//Reads email to be sebt
$sql = "SELECT * FROM sbjbs_mails where sb_mailid=7";
//echo $sql; 
$rs_query=mysql_query($sql);

if ( $rs=mysql_fetch_array($rs_query)  )
  {
			 $from =$rs["sb_fromid"];
			 $to = $email;
			 $subject =$rs["sb_subject"];
		     $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

			 $body=str_replace("%email%", $sb_null_char,str_replace("%password%",$sb_null_char,str_replace("%lname%", $sb_null_char,str_replace("%fname%", $sb_null_char,str_replace("%username%",$sb_null_char, $rs["sb_mail"]) )))); 
				
			$body=str_replace("%signup_url%",$sb_null_char,str_replace("%login_url%",$login_url,$body));

		 $body=str_replace("%message_text%",$comments,str_replace("%message_title%",$title,str_replace("%sender_username%",$sb_null_char,str_replace("%message_date%",$ondate,$body))));	
 
 		$body=str_replace("%visitor_name%",$fname.' '.$lname,$body);
		
	 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//$body=str_replace("\n","<br>",$body);
		}
 
// 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
//	 echo "<pre>$body</pre>";
	// die();
	if( $rs["sb_status"]=='yes')
	 	mail($to,$subject,$body,$header);
  }

//////////////////////////////////////////////////////////
/////          Sending mail to admin

//$rs0=mysql_fetch_array(mysql_query("select * from sbrrs_config where sbcfg_id=1"));
//$login_url=$site_root[0]."/signinform.php";

//Reads email to be sebt
$sql = "SELECT * FROM sbjbs_mails where sb_mailid=8";
$rs_query=mysql_query($sql);

if ( $rs=mysql_fetch_array($rs_query) )
  {
			 $from =$rs["sb_fromid"];
			 $to = $row_con["sb_admin_email"];
			 $subject =$rs["sb_subject"];
		     $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

			 $body=str_replace("%email%", $sb_null_char,str_replace("%password%",$sb_null_char,str_replace("%lname%", $sb_null_char,str_replace("%fname%", $sb_null_char,str_replace("%username%",$sb_null_char, $rs["sb_mail"]) )))); 
				
			$body=str_replace("%signup_url%",$sb_null_char,str_replace("%login_url%",$login_url,$body));

		 $body=str_replace("%message_text%",$comments,str_replace("%message_title%",$title,str_replace("%sender_username%",$sb_null_char,str_replace("%message_date%",$ondate,$body))));	
 
 		$body=str_replace("%visitor_name%",$fname.' '.$lname,$body);
		
	 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//$body=str_replace("\n","<br>",$body);
		}
	
//	 echo "---$from---$to----$subject-----$header----";
//	 echo "<pre>$body</pre>";
//	 die();
	
	if( $rs["sb_status"]=='yes')
	 	mail($to,$subject,$body,$header);
  }
			
	
/////////////////////////////////////			
//die();
	//////----------
	header("Location:"."gen_confirm.php?errmsg=".urlencode("Your message has been Forwarded to the Administrator we will very shortly get back to you."));
	die();
	}
	else
	{
	header("Location:"."gen_confirm.php?err=linktous&errmsg=".urlencode("Sorry, some error occurred and  unable to send comments/feedback to Administrator."));
	die();
	}
  }//if no errs
}//if posted

function main()
{
global $errs, $errcnt;
$fname="";
$lname="";
$email="";
$url="";
$title="";
$comments="";
if(count($_POST)>0)
{
$fname=$_POST["fname"];
$lname=$_POST["lname"];
$email=$_POST["email"];
$url=$_POST["url"];
$title=$_POST["title"];
$comments=$_POST["comments"];
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


  function Validate(form) {
	if ( form.fname.value == "" ) {
		alert('Please Specify First Name!');
		form.fname.focus();
		return false;
	   }
	if(form.fname.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from First Name (e.g. &  < >)");
			form.fname.focus();
			return(false);
		}
	   
	if ( form.lname.value == "" ) {
       	   alert('Please Specify Last Name!');
			form.lname.focus();
	   return false;
	   }
	if(form.lname.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Last Name (e.g. &  < >)");
			form.lname.focus();
			return(false);
		}

        if(!form.email.value.match(/[a-zA-Z\.\@\d\_]/)) {
           alert('Invalid e-mail address.');
  				form.email.focus();
         return false;
           }
		   if (!emailCheck (form.email.value) )
			{
				form.email.focus();
				return (false);
			}
		if(form.email.value.match(/[&<>]+/))
			{
				alert("Please remove Invalid characters from Email (e.g. &  < >)");
				form.email.focus();
				return(false);
			}

		if(form.url.value.match(/[&<>]+/))
			{
				alert("Please remove Invalid characters from URL (e.g. &  < >)");
				form.url.focus();
				return(false);
			}
	   
	   if (form.title.value == "") {
	   alert('Please Specify Title.');
				form.title.focus();
	   return false;
	   }
		if(form.title.value.match(/[&<>]+/))
			{
				alert("Please remove Invalid characters from Title (e.g. &  < >)");
				form.title.focus();
				return(false);
			}
	   
	   
	if (form.comments.value == "") {
	   alert('Please Specify Comments/Feedback.');
	   form.comments.focus();
	   return false;
	   }
   		if(form.comments.value.match(/[&<>]+/))
		{
				alert("Please remove Invalid characters from Comments/Feedback (e.g. &  < >)");
				form.comments.focus();
				return(false);
		}
	   return true;
  }
// -->

</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr> 
            <td height="25" align="left" class="titlestyle">&nbsp;Give 
              Your Suggestions</td>
          </tr>
  <tr> 
    <td valign="top"><form name="form" onSubmit="return Validate(this);" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table width="100%" border="0" cellspacing="5" cellpadding="2">
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>First 
              Name </b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input type="text" name="fname" SIZE="35" value="<?php echo $fname;?>">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Last 
              Name</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input type="text" name="lname" SIZE="35" value="<?php echo $lname;?>">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Email</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input type="text" name="email" SIZE="35" value="<?php echo $email;?>">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>URL</b></font></td>
            <TD align=right valign="top"><font class='red'>&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input name="url" type="text" value="<?php echo $url;?>" SIZE="35">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Title</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input type="text" name="title" SIZE="35" value="<?php echo $title;?>">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td width="40%" height="25" align="right" class='innertablestyle'><font class='normal'>&nbsp;<b>Comment 
              / Feedback</b></font></td>
            <TD align=right><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <textarea name="comments" cols="35" rows="5" ><?php echo $comments;?></textarea>
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" class='innertablestyle'><font class='normal'>&nbsp;</font></td>
            <td><font class='normal'>&nbsp;</font></td>
            <td width="60%"><font class='normal'> 
              <INPUT type=submit value=Done name=submit>
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