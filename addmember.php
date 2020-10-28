<?
include_once("myconnect.php");

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
	$sb_dob_day=(int)$_REQUEST['sb_dob_day'];
	$sb_dob_month=(int)$_REQUEST['sb_dob_month'];
	$sb_dob_year=(int)$_REQUEST['sb_dob_year'];

		if(!get_magic_quotes_gpc())
		{
			$username=str_replace("$","\$",addslashes($_REQUEST["username"]));
			$sb_title=str_replace("$","\$",addslashes($_REQUEST["sb_title"]));
			$password=str_replace("$","\$",addslashes($_REQUEST["password"]));
			$firstname=str_replace("$","\$",addslashes($_REQUEST["firstname"]));
			$lastname=str_replace("$","\$",addslashes($_REQUEST["lastname"]));
			$email=str_replace("$","\$",addslashes($_REQUEST["email"]));
			$street=str_replace("$","\$",addslashes($_REQUEST["street"]));
			$city=str_replace("$","\$",addslashes($_REQUEST["city"]));
			$state=str_replace("$","\$",addslashes($_REQUEST["state"]));
			$other_state=str_replace("$","\$",addslashes($_REQUEST["other_state"]));
			$phone=str_replace("$","\$",addslashes($_REQUEST["phone"]));
			$phone1=str_replace("$","\$",addslashes($_REQUEST["phone1"]));
			$phone2=str_replace("$","\$",addslashes($_REQUEST["phone2"]));
			$mobile=str_replace("$","\$",addslashes($_REQUEST["mobile"]));
			$zip_code=str_replace("$","\$",addslashes($_REQUEST["zip_code"]));
		}
		else
		{
			$username=str_replace("$","\$",$_REQUEST["username"]);
			$sb_title=str_replace("$","\$",$_REQUEST["sb_title"]);
			$password=str_replace("$","\$",$_REQUEST["password"]);
			$firstname=str_replace("$","\$",$_REQUEST["firstname"]);
			$lastname=str_replace("$","\$",$_REQUEST["lastname"]);
			$email=str_replace("$","\$",$_REQUEST["email"]);
			$street=str_replace("$","\$",$_REQUEST["street"]);
			$city=str_replace("$","\$",$_REQUEST["city"]);
			$state=str_replace("$","\$",$_REQUEST["state"]);
			$other_state=str_replace("$","\$",$_REQUEST["other_state"]);
			$phone=str_replace("$","\$",$_REQUEST["phone"]);
			$phone1=str_replace("$","\$",$_REQUEST["phone1"]);
			$phone2=str_replace("$","\$",$_REQUEST["phone2"]);
			$mobile=str_replace("$","\$",$_REQUEST["mobile"]);
			$zip_code=str_replace("$","\$",$_REQUEST["zip_code"]);
	}
	if($state=="")
	{ $state=$other_state; }
	
	$phone_no="";
	if(strlen(trim($phone))<>0)
	{$phone_no.=$phone;}
	$phone_no.="-";
	if(strlen(trim($phone1))<>0)
	{$phone_no.=$phone1;}
	$phone_no.="-";
	if(strlen(trim($phone2))<>0)
	{$phone_no.=$phone2;}

	if ( strlen(trim($username)) == 0 )
	{
		$errs[$errcnt]="Username must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[^a-zA-Z0-9_]/", $_REQUEST["username"]))
	{
		$errs[$errcnt]="Username can contain only Alpha-Numeric and Underscore character";
   		$errcnt++;
	}
	elseif(mysql_num_rows(mysql_query("select * from sbjbs_seekers where sb_username='$username' or sb_email_addr='$email'"))!= 0)
	{
			$errs[$errcnt]="Some Member with same Username or Email Address already exists";
    		$errcnt++;
	}

	if ( !isset( $_REQUEST["password"] ) || (strlen(trim($_REQUEST["password"])) == 0) )
	{
		$errs[$errcnt]="Password must be provided";
   		$errcnt++;
	}
	elseif( strcmp($_REQUEST["password"],$_REQUEST["pwd2"]) != 0)
	{
		$errs[$errcnt]="Retyped Password does not match the Password";
   		$errcnt++;
	}

	if ( strlen(trim($firstname)) == 0 )
	{
		$errs[$errcnt]="Firstname must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["firstname"]))
	{
		$errs[$errcnt]="Firstname can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($lastname)) == 0 )
	{
		$errs[$errcnt]="Lastname must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["lastname"]))
	{
		$errs[$errcnt]="Lastname can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(!checkdate($sb_dob_month, $sb_dob_day, $sb_dob_year))	
	{
		$errs[$errcnt]="Date of Birth must be a valid date";
   		$errcnt++;
	}
	elseif( ($sb_dob_year< date("Y",time())-100 ) || ($sb_dob_year>=date("Y",time())) )
	{
		$errs[$errcnt]="Date of Birth has suspecious year value";
   		$errcnt++;
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

	if ( strlen(trim($street)) == 0 )
	{
		$errs[$errcnt]="Street must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["street"]))
	{
		$errs[$errcnt]="Street can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($city)) == 0 )
	{
		$errs[$errcnt]="City must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["city"]))
	{
		$errs[$errcnt]="City can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($state)) == 0 )
	{
		$errs[$errcnt]="State must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["state"]))
	{
		$errs[$errcnt]="State can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($zip_code)) == 0 )
	{
		$errs[$errcnt]="Zip/Postal Code must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["zip_code"]))
	{
		$errs[$errcnt]="Zip/Postal Code can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( $_REQUEST["country"]== 0 )
	{
		$errs[$errcnt]="Country must be choosen";
   		$errcnt++;
	}
	
	if(preg_match ("/[;<>&]/", $phone_no))
	{
		$errs[$errcnt]="Phone No. can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(preg_match ("/[;<>&]/", $mobile))
	{
		$errs[$errcnt]="Mobile can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	if($errcnt==0)
	{
//		$sb_dob=date("YmdHis", mktime (0,0,0,$sb_dob_month,$sb_dob_day,$sb_dob_year));
//	echo mktime (0,0,0,$sb_dob_month,$sb_dob_day,$sb_dob_year).'<br>';
//	echo "$sb_dob_month,$sb_dob_day,$sb_dob_year";
	
		if($sb_dob_month<10)
			$sb_dob_month="0".$sb_dob_month;
		if($sb_dob_day<10)
			$sb_dob_day="0".$sb_dob_day;
		$sb_dob=$sb_dob_year.$sb_dob_month.$sb_dob_day."000000";

 	$suspended="no";
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	
	if($config["sb_mem_approval"]=="admin")
	{$suspended="yes";}

	
	$query_insert="Insert into `sbjbs_seekers` 
	( sb_username ,sb_password ,sb_last_login, sb_signup_on ,sb_suspended, sb_title, sb_firstname , sb_lastname , sb_dob, sb_email_addr , sb_addr1 , sb_city , sb_state , sb_zip , sb_country , sb_telephone, sb_mobile)
VALUES 	( '$username' ,'$password',0,'".date("YmdHis",time())."','$suspended', '$sb_title', '$firstname' , '$lastname', '$sb_dob', '$email','$street','$city','$state','$zip_code' ,".$_REQUEST["country"].",'$phone_no', '$mobile')";
//echo $query_insert;
//die();
		$rs_insert=mysql_query($query_insert);
		if(mysql_affected_rows()>0)
		{
		if(isset($_REQUEST["subscribe"])&&($_REQUEST["subscribe"]=="yes"))
		{
		  $check_prev=mysql_fetch_array(mysql_query("select * from sbjbs_newsletter where sb_email='$email'"));
		  if(!$check_prev)
		  {
		  mysql_query("insert into sbjbs_newsletter (sb_email) values ('$email')");
		  }
		}
		mysql_query ("delete from sbjbs_signups where sb_email='$email'");
	if($config["sb_mem_approval"]=="auto")
	{
		//sending welcome mail
		$sbrow_con=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		$sb_null_char=$sbrow_con["sb_null_char"];
		$sb_site_root=$sbrow_con["sb_site_root"];
					
		$rs0= mysql_fetch_array(mysql_query("SELECT * FROM sbjbs_seekers WHERE sb_email_addr='$email'"));
		if($rs0)
		{
		//Reads email to be sebt
		$sql = "SELECT * FROM sbjbs_mails where sb_mailid=1" ;
		$rs_query=mysql_query($sql);
		$login_url=$sb_site_root."/signin.php";
		
		if ( $rs=mysql_fetch_array($rs_query)  )// if mail
		{
		 if($rs["sb_status"]=="yes")	
		  {
					 $from =$rs["sb_fromid"];
					 $to = $rs0["sb_email_addr"];
					 $subject =$rs["sb_subject"];
							
	  	$body=str_replace("%title%", $rs0["sb_title"],str_replace("%email%", $rs0["sb_email_addr"],str_replace("%password%",$rs0["sb_password"],str_replace("%lname%", $rs0["sb_lastname"],str_replace("%fname%", $rs0["sb_firstname"],str_replace("%username%", $rs0["sb_username"], $rs["sb_mail"]) ))))); 
				
		$body=str_replace("%signup_url%",$sb_null_char,str_replace("%login_url%",$login_url,$body));
				
	  	$header="From:" . $from . "\r\n" ."Reply-To:". $from  ;
	 	if(isset($rs["sb_html_format"])&&($rs["sb_html_format"]=="yes"))
		{
			$header .= "\r\nMIME-Version: 1.0";
			$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
//			$body=str_replace("\n","<br>",$body);
		}

 // 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
// 	echo "<pre>$body</pre>";
//	die();
			if( $rs["sb_status"]=='yes')
				 mail($to,$subject,$body,$header);
		  }// end if status is on
		  }// end if mail 
		}
	}	//if approval==auto		
		//mail ends here
		header ("Location: signin.php?errmsg=".urlencode("You are successfully registered with us"));
		die();
		}
		else
		{
			header("Location: gen_confirm.php?errmsg=".urlencode("Some Error Occurred, Please try again."));
			die();
		}
	}			//end if-errcnt==0
}			//end if count-post


function main()
{
global $errs, $errcnt;

$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));

$invalidaccess="No";
if( !isset($_REQUEST["rnum"]) || !isset($_REQUEST["email"] ) )
{
$invalidaccess="Yes";
}
else
{
	if(!get_magic_quotes_gpc())
	{
		$email=str_replace("$","\$",addslashes($_REQUEST["email"]));
		$rnum=str_replace("$","\$",addslashes($_REQUEST["rnum"]));
	}
	else
	{
		$email=str_replace("$","\$",$_REQUEST["email"]);
		$rnum=str_replace("$","\$",$_REQUEST["rnum"]);
	}
	$rs0_query=mysql_query ("select * from sbjbs_signups where sb_email='$email' and sb_rnum='$rnum' ");
	if (!($rs0=mysql_fetch_array($rs0_query)))
	{
	$invalidaccess="Yes";
	}
}
if (($invalidaccess=="Yes")&&($config["sb_signup_verification"]=="yes"))
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr>
    <td><strong>&nbsp;Invalid 
      Access</strong></td>
  </tr>
</table>
<?
return;
}

$showform="";
$sb_title='';
$username="";
$password="";
$firstname="";
$lastname="";

$email="";
if(isset($_REQUEST["email"]))
{ $email=$_REQUEST["email"]; }

$street="";
$city="";
$state="";
$country="";
$zip_code="";
$phone="";
$phone1="";
$phone2="";
$fax="";
$fax1="";
$fax2="";
$mobile="";
$subscribe="";
$other_state="";
$sb_dob_day='';
$sb_dob_month='';
$sb_dob_year='';

//IF SOME FORM WAS POSTED DO VALIDATION
if ( count($_POST)>0 )
{
$username=$_REQUEST["username"];
$sb_title=$_REQUEST["sb_title"];
$firstname=$_REQUEST["firstname"];
$lastname=$_REQUEST["lastname"];
$email=$_REQUEST["email"];
$street=$_REQUEST["street"];
$city=$_REQUEST["city"];
$state=$_REQUEST["state"];
$country=$_REQUEST["country"];
$zip_code=$_REQUEST["zip_code"];
$phone=$_REQUEST["phone"];
$phone1=$_REQUEST["phone1"];
$phone2=$_REQUEST["phone2"];
$mobile=$_REQUEST["mobile"];
if(isset($_REQUEST["subscribe"]))
$subscribe=$_REQUEST["subscribe"];
$other_state=$_REQUEST["other_state"];
$sb_dob_day=$_REQUEST['sb_dob_day'];
$sb_dob_month=$_REQUEST['sb_dob_month'];
$sb_dob_year=$_REQUEST['sb_dob_year'];
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
</table>
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



  function validate(form) 
  {
  //var day_var=
//  var date_var= new Date(form.sb_dob_year.value,(form.sb_dob_month.value-1),form.sb_dob_day.value,0,0,0);
 // alert(date_var.toString());
  
	if ( (form.username.value == "")) {
       	   alert('Please Specify Username!');
		   form.username.focus();
	   return false;
	   }
	if((form.username.value.match(/[^a-zA-Z0-9_]/)))
		{
			alert("Username can contain only alphanumeric and underscore character");
			form.username.focus();
			return(false);
		}
	   
        if(form.password.value == "")
		{
	   	   alert('Please Specify Password.');
           form.password.focus();
		   return false; 
        }
		if (form.password.value != form.pwd2.value)
		{
			alert('Passwords do not match.');
			form.pwd2.value="";
			form.password.focus();
			form.password.select();
			return false;
		}
		
	if ( form.firstname.value == "" ) {
       	   alert('Please Specify Firstname!');
		   form.firstname.focus();
	   return false;
	   }
	if(form.firstname.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Firstname (e.g. &  < >)");
			form.firstname.focus();
			return(false);
		}
	if ( form.lastname.value == "" ) {
       	   alert('Please Specify Lastname!');
		   form.lastname.focus();
	   return false;
	   }
	if(form.lastname.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Lastname (e.g. &  < >)");
			form.lastname.focus();
			return(false);
		}

	 	if ( isNaN(form.sb_dob_year.value) || form.sb_dob_year.value == "" || form.sb_dob_year.value <= 0 ) // || form.sb_dob_year.value<1900 || form.sb_dob_year.value>2000 ) 
		{
       	   	alert('Please Specify valid Year in Date of Birth!');
		   	form.sb_dob_year.focus();
	   		return false;
	   	}

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

	if ( form.street.value == "" ) {
       	   alert('Please Specify Street!');
		   form.street.focus();
	   return false;
	   }
	if(form.street.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Street (e.g. &  < >)");
			form.street.focus();
			return(false);
		}

	if ( form.city.value == "" ) {
       	   alert('Please Specify City!');
		   form.city.focus();
	   return false;
	   }
		if(form.city.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from City(e.g. &  < >)");
			form.city.focus();
			return(false);
		}
	if ( (form.state.selectedIndex == 0 ) && (form.other_state.value == "") ) {
       	   alert('Please Specify State!');
		   form.state.focus();
	   return false;
	   }
		if(form.other_state.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from State(e.g. &  < >)");
			form.other_state.focus();
			return(false);
		}
		if ( form.zip_code.value == "" ) {
       	   alert('Please Specify Zip/Postal Code!');
		   form.zip_code.focus();
	   return false;
	   }
	if(form.zip_code.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Zip/Postal Code (e.g. &  < >)");
			form.zip_code.focus();
			return(false);
		}
if ( form.country.selectedIndex == 0 ) {
       	   alert('Please Choose a Country!');
		   form.country.focus();
	   return false;
	   }
	return true;
  }
// -->
</SCRIPT>

<form name="form1" method="post" action="addmember.php" onSubmit="return validate(this);">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td>&nbsp;Signup Process</td>
          </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong>Username</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"> <input name="username" type="text"  value="<?php echo $username  ; ?>" size="30" maxlength="30"> 
              <br> <font class="smalltext">can not be changed Later</font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Password</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="password" type="password"  value="<?php echo $password; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Retype 
              Password </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="pwd2" type="password" id="pwd2"  value="<?php echo $password; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Title</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Verdana, Arial" size=2> 
              <select name=sb_title id="sb_title">
                <option value="Mr." <?php echo ($sb_title=='Mr.')?'selected':''; ?>>Mr.</option>
                <option value="Ms." <?php echo ($sb_title=='Ms.')?'selected':''; ?>>Ms.</option>
                <option value="Mrs." <?php echo ($sb_title=='Mrs.')?'selected':''; ?>>Mrs.</option>
                <!--OPTION value="Dr." <?php echo ($sb_title=='Dr.')?'selected':''; ?>>Dr.</OPTION-->
              </select>
              </font><font face="Arial, Helvetica, sans-serif" size="2">&nbsp; 
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Firstname</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="firstname" type="text"  value="<?php echo $firstname; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Lastname</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="lastname" type="text"  value="<?php echo $lastname  ; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Date 
              of Birth</strong></font></td>
            <td><font class="red">*</font></td>
            <td><table border="0" align="left" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="100"><font class="smalltext">Month</font></td>
                  <td width="50"><font class="smalltext">Day</font></td>
                  <td><font class="smalltext">Year</font></td>
                </tr>
                <tr> 
                  <td><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <select name="sb_dob_month">
                      <option value="1" <?php echo($sb_dob_month==1)?'selected':''; ?>>January</option>
                      <option value="2" <?php echo($sb_dob_month==2)?'selected':''; ?>>February</option>
                      <option               value="3" <?php echo($sb_dob_month==3)?'selected':''; ?>>March</option>
                      <option               value="4" <?php echo($sb_dob_month==4)?'selected':''; ?>>April</option>
                      <option               value="5" <?php echo($sb_dob_month==5)?'selected':''; ?>>May</option>
                      <option               value="6" <?php echo($sb_dob_month==6)?'selected':''; ?>>June</option>
                      <option               value="7" <?php echo($sb_dob_month==7)?'selected':''; ?>>July</option>
                      <option               value="8" <?php echo($sb_dob_month==8)?'selected':''; ?>>August</option>
                      <option               value="9" <?php echo($sb_dob_month==9)?'selected':''; ?>>September</option>
                      <option               value="10" <?php echo($sb_dob_month==10)?'selected':''; ?>>October</option>
                      <option               value="11" <?php echo($sb_dob_month==11)?'selected':''; ?>>November</option>
                      <option               value="12" <?php echo($sb_dob_month==12)?'selected':''; ?>>December</option>
                    </select>
                    </font></td>
                  <td><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <select name="sb_dob_day">
                      <?php 	for($sbi=1;$sbi<=31;$sbi++)
				{
					echo '<option value="'.$sbi.'" ';
					echo ($sb_dob_day==$sbi)?'selected':'';
					echo '>'.$sbi.'</option>';
				}?>
                    </select>
                    </font></td>
                  <td><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input type="text" name="sb_dob_year" value="<?php echo $sb_dob_year; ?>" size="8" 
                              maxlength="4">
                    </font></td>
                </tr>
              </table>
              <font face="Arial, Helvetica, sans-serif" size="2">&nbsp;</font></td>
          </tr>
          <?php
	if($config["sb_signup_verification"]=="no")
	{
    ?>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Email</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="email" type="text"  value="<?php echo $email; ?>" size="30" maxlength="40">
              </font></td>
          </tr>
          <?php
	}
	else
	{
	 ?>
          <input name="email" type="hidden"  value="<?php echo $email; ?>" size="30" maxlength="40">
          <input name="rnum" type="hidden"  value="<?php echo $_REQUEST["rnum"]; ?>" >
          <?
	}
    ?>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Street</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text"  size="30" maxlength="30" name="street" value="<?php echo $street; ?>" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>City</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text"  size="30" maxlength="30" name="city"  value="<?php echo $city; ?>" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>State</strong></font></td>
            <td><font class="red">*</font></td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td><font class='normal'>US</font></td>
                  <td> <select name="state" >
                      <option value="" selected >Select State</option>
                      <? 
						  $state1=mysql_query("select * from sbjbs_us_states order by sb_state");
						  while($rst= mysql_fetch_array($state1))
						  {
						  ?>
                      <option value=<? echo $rst["sb_state"];?> <? if($rst["sb_state"]==$state) {echo " selected ";}?>><? echo $rst["sb_state"];?></option>
                      <?
							   } // wend
							   ?>
                    </select> </td>
                </tr>
                <tr> 
                  <td><font class='normal'>Non US</font></td>
                  <td><input name="other_state" type="text" id="other_state" value="<? echo $other_state;?>"></td>
                </tr>
              </table></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Zip/Postal 
              Code </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="zip_code" type="text"  id="zip_code"  value="<?php echo $zip_code; ?>" size="30" maxlength="30" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Country</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"><strong><font color="#004566"> 
              <select name="country" >
                <option selected value="">Select Country</option>
                <?
$rs_t_query=mysql_query ("select * from sbjbs_country order by country");
while ($rs_t=mysql_fetch_array($rs_t_query))    
{
?>
                <option  value="<? echo $rs_t["id"]  ?>"
		  <?php
				if ($country== $rs_t["id"] ) 
				{
				echo "  selected ";
				}
				?>
		  
		  ><? echo $rs_t["country"]  ; ?></option>
                <?
		  }
		  		  ?>
              </select>
              </font></strong></font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Phone</strong></font></td>
            <td>&nbsp;</td>
            <td><table border="0" align="left" cellpadding="0" cellspacing="0">
                <tr> 
                  <td><font class="smalltext">Country Code </font></td>
                  <td><font class="smalltext">Area Code </font></td>
                  <td><font class="smalltext">Phone Number</font></td>
                </tr>
                <tr> 
                  <td width="90"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone" type="text"  id="phone"  value="<?php echo $phone; ?>" size="5" maxlength="5" >
                    </font></td>
                  <td width="77"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone1" type="text"  id="phone1"   value="<?php echo $phone1; ?>" size="8" maxlength="8" >
                    </font></td>
                  <td width="148"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone2" type="text"  id="phone2"   value="<?php echo $phone2; ?>" size="20" maxlength="20" >
                    </font></td>
                </tr>
              </table>
              <font face="Arial, Helvetica, sans-serif" size="2">&nbsp; </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Mobile</strong></font></td>
            <td>&nbsp;</td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input type="text" name="mobile"  size="30" maxlength="30"   value="<?php echo $mobile; ?>" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><font class='normal'> 
              <input name="subscribe" type="checkbox" id="subscribe" value="yes" <?php 
								if($subscribe == "yes")
									echo "checked"; ?>>
              Subscribe Newsletter</font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="submit"  type="submit" value="Signup"></td>
          </tr>
        </table></td>
    </tr>
  </table>
  </form>
<?
}
include_once("template.php");

?>