<?
include_once("myconnect.php");
include_once "session.php";
if(isset($_SESSION["sbjbs_userid"])&&($_SESSION["sbjbs_userid"]<>""))
{
header("Location:"."gen_confirm.php?errmsg=".urlencode('You are already logged in as '.$_SESSION["sbjbs_username"]));
die();
}

$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
if($config["sb_signup_verification"]=="no")
{
header("Location:"."addmember.php");
die();
}

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
		if(!get_magic_quotes_gpc())
		{
			$email=str_replace("$","\$",addslashes($_REQUEST["email"]));
		}
		else
		{
			$email=str_replace("$","\$",$_REQUEST["email"]);
		}
	
		if ( !isset( $_REQUEST["email"] ) || (strlen(trim($_REQUEST["email"] )) == 0) )
		{
			$errs[$errcnt]="Email Address must be provided";
			$errcnt++;
		}
		elseif(preg_match ("/[;<>&]/", $_REQUEST["email"]))
		{
			$errs[$errcnt]="Email can not have any special character (e.g. & ; < >)";
			$errcnt++;
		}
		else
		{
			$mem_query=mysql_query ("select * from sbjbs_seekers where sb_email_addr='$email'");
			if ($mem=mysql_fetch_array($mem_query))
			{
				$errs[$errcnt]="Some member has already registered with this email id.";
				$errcnt++;
			
			}
		}
		if($errcnt==0)
		{
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config "));
		
		mysql_query ("delete from sbjbs_signups where sb_email='$email'");

		$rnum =  mt_rand(1,1000000000);
		$insert_str="Insert into `sbjbs_signups` ( sb_email ,sb_rnum,sb_onstamp) 
		VALUES ( '$email','$rnum','" . date("YmdHis",time()) . "')";
		mysql_query($insert_str);
		if(mysql_affected_rows()>0)
		{
		//==================send confirmation mail================
		$sb_null_char=$config["sb_null_char"];
		$signup_url=$config["sb_site_root"]."/addmember.php?rnum=$rnum&email=".$_REQUEST["email"];
		$sql = "SELECT * FROM sbjbs_mails where sb_id=10" ;
		$rs_query=mysql_query($sql);
		if ( $rs=mysql_fetch_array($rs_query)  )
		  	{
					 $from =$rs["sb_fromid"];
		
					 $to = $_REQUEST["email"];
		
					 $subject =$rs["sb_subject"];
		
					 $header="From:" . $from . "\r\n" ."Reply-To:". $from  ;

					 $body=str_replace("%email%", $_REQUEST["email"],str_replace("%password%",$sb_null_char,str_replace("%lname%", $sb_null_char,str_replace("%fname%", $sb_null_char,str_replace("%username%", $sb_null_char, $rs["sb_mail"]) )))); 
					$body=str_replace("%signup_url%",$signup_url,str_replace("%login_url%",$sb_null_char,$body));

				 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
					{
						$header .= "\r\nMIME-Version: 1.0";
						$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
						//$body=str_replace("\n","<br>",$body);
					}
						
 	//echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
 	//echo "<pre>$body</pre>";
	//die();
	
				if( $rs["sb_status"]=='yes')
	 				mail($to,$subject,$body,$header);

			}		
		//========================================================
			//	echo "<br>Just comment this before packing<br>".$signup_url."<br>";

			header ("Location:"."gen_confirm.php?errmsg=".urlencode("Please check your email and follow the link provided in your email to complete the registration process, Thank You."));
			die();
		}// if inserted
		else
		{
		header("Location:"."gen_confirm.php?err=signup&errmsg=".urlencode("Sorry, some error occurred and  unable to complete the signup process."));
		die();
		}
		}// if no errors

}// if form posted


function main ()
{
global $errs, $errcnt;
$showform="";

if  (count($_POST)>0)
{

if ( $errcnt<>0 )
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr>
    <td colspan="2"><strong>&nbsp;Your signup request cannot be processed due 
      to following reasons</strong></td>
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
</table>

<?

}

}

if ($showform<>"No")
{
?>
<script language="JavaScript">
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
	function validate(form)
	{
    if(!form.email.value.match(/[a-zA-Z\.\@\d\_]/)) 
		{
           alert('Invalid e-mail address.');
           form.email.focus();
		   return false;
        }
		
	if (!emailCheck (form.email.value) )
		{
			form.email.focus();
			return (false);
		}

	return true;
	}
</script>
<form name="form1" method="post" action="signup.php" onSubmit="return validate(this);">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td >&nbsp;Signup Process</td>
          </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong>Email 
              ID</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="email" type="text" class=select size="30" maxlength="40">
              <br>
              </font><font class='smalltext'>Your older verification code will 
              be disabled once you request for newer verification code.</font><font face="Arial, Helvetica, sans-serif" size="2">&nbsp; 
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="submit"  type="submit" value="Submit"></td>
          </tr>
        </table></td>
    </tr>
  </table>
  </form>
<?
} //If showform = No? ends here

}
include_once("template.php");

 ?>