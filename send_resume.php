<?php
include_once "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

if(!isset($_REQUEST["resume_id"]) || !is_numeric($_REQUEST["resume_id"]) || ($_REQUEST["resume_id"] < 1) )
{
	header("Location:"."gen_confirm.php?errmsg=".urlencode("Invalid Id, cannot continue"));
	die();
}
$sb_id=$_REQUEST["resume_id"];
$chk_q=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_approved='yes' and sb_id=$sb_id"));
if($chk_q<=0)
{
	header("Location:"."gen_confirm.php?errmsg=".urlencode("Invalid Id, cannot continue"));
	die();
}

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
			if (!get_magic_quotes_gpc()) {
			$email=str_replace('$', '\$',addslashes($_REQUEST["email"]));
			}
			else
			{
			$email=str_replace('$', '\$',$_REQUEST["email"]);
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
	

	if($errcnt==0)
	{
				//SENDING MAIL TO MEMBER////////////////////////
	$row_con=mysql_fetch_array(mysql_query("select * from sbjbs_config where sb_id=1"));
	$sb_null_char=$row_con["sb_null_char"];
	$resume_url=$row_con["sb_site_root"]."/view_resume.php?resume_id=$sb_id";
//	$ondate=sb_date(date(time()));
	$sbq_mem="select * from sbjbs_seekers where sb_id=".$_SESSION["sbjbs_userid"];
	$sbrow_mem=mysql_fetch_array(mysql_query($sbq_mem));

//Reads email to be sebt
$sql = "SELECT * FROM sbjbs_mails where sb_mailid=11";
//echo $sql; 
$rs_query=mysql_query($sql);

		if ( $rs=mysql_fetch_array($rs_query)  )
  		{
			 $from =$rs["sb_fromid"];
			 $to = $email;
			 $subject =$rs["sb_subject"];
		     $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

			 $body=str_replace("%email%", $sb_null_char,str_replace("%password%",$sb_null_char,str_replace("%lname%", $sbrow_mem["sb_lastname"],str_replace("%fname%", $sbrow_mem["sb_firstname"],str_replace("%username%",$sb_null_char,str_replace("%title%",$sbrow_mem["sb_title"],$rs["sb_mail"]) ))))); 
				
			$body=str_replace("%signup_url%",$sb_null_char,str_replace("%login_url%",$sb_null_char,str_replace("%resume_url%",$resume_url,$body)));

		 $body=str_replace("%message_text%",$sb_null_char,str_replace("%message_title%",$sb_null_char,str_replace("%sender_username%",$sb_null_char,str_replace("%message_date%",$sb_null_char,$body))));	
 
 		$body=str_replace("%visitor_name%",$sb_null_char,$body);
		
	 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
//			$body=str_replace("\n","<br>",$body);
		}
 
// 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
//	 echo "<pre>$body</pre>";
	// die();
		if( $rs["sb_status"]=='yes')
	 		mail($to,$subject,$body,$header);
  		}
		header("Location:"."gen_confirm.php?errmsg=".urlencode("Your resume has been forwarded to the desired email address."));
		die();
  	}//if no errs
}//if posted

function main()
{
global $errs, $errcnt, $sb_id ;
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

	   	return true;
  }
// -->

</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr> 
            <td height="25" align="left" class="titlestyle">&nbsp;Send 
              Resume </td>
          </tr>
  <tr> 
    <td valign="top">
        <table width="100%" border="0" cellspacing="5" cellpadding="2">
          <form name="form" onSubmit="return Validate(this);" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"><tr> 
            <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Email</b></font></td>
            <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
            <td width="60%"> <font class='normal'> 
              <input type="text" name="email" SIZE="35" value="<?php echo $email;?>">
              </font></td>
          </tr>
          <tr> 
            <td width="40%" height="25" class='innertablestyle'><font class='normal'>&nbsp;</font></td>
            <td><font class='normal'>&nbsp;</font></td>
            <td width="60%"><font class='normal'> 
              <INPUT type=submit value=Done name=submit>
              <input name="resume_id" type="hidden" id="resume_id" value="<?php echo $sb_id; ?>">
              </font></td>
          </tr>
        </form></table>
      </td>
  </tr>
</table>
 <?
}// end main
include "template.php";
?> 