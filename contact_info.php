<?
include_once "logincheck.php";
include_once("myconnect.php");

/*$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$resume_cnt=$config["sb_resume_cnt"];
$total_resume=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if($total_resume>=$resume_cnt)
{
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("You have already posted the maximum number of resumes allowed."));
die();
}*/

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
//		ob_start();
		if(!get_magic_quotes_gpc())
		{
			$firstname=str_replace("$","\$",addslashes($_REQUEST["firstname"]));
			$lastname=str_replace("$","\$",addslashes($_REQUEST["lastname"]));
			$street=str_replace("$","\$",addslashes($_REQUEST["street"]));
			$city=str_replace("$","\$",addslashes($_REQUEST["city"]));
			$state=str_replace("$","\$",addslashes($_REQUEST["state"]));
			$other_state=str_replace("$","\$",addslashes($_REQUEST["other_state"]));
			$phone=str_replace("$","\$",addslashes($_REQUEST["phone"]));
			$phone1=str_replace("$","\$",addslashes($_REQUEST["phone1"]));
			$phone2=str_replace("$","\$",addslashes($_REQUEST["phone2"]));
			$mobile=str_replace("$","\$",addslashes($_REQUEST["mobile"]));
			$zip_code=str_replace("$","\$",addslashes($_REQUEST["zip_code"]));
			$career_level=str_replace("$","\$",addslashes($_REQUEST["career_level"]));			
			$availability_time=str_replace("$","\$",addslashes($_REQUEST["availability_time"]));			
			$email=str_replace("$","\$",addslashes($_REQUEST["email"]));
			$work_exp=str_replace("$","\$",addslashes($_REQUEST["work_exp"]));		}
		else
		{
			$firstname=str_replace("$","\$",$_REQUEST["firstname"]);
			$lastname=str_replace("$","\$",$_REQUEST["lastname"]);
			$street=str_replace("$","\$",$_REQUEST["street"]);
			$city=str_replace("$","\$",$_REQUEST["city"]);
			$state=str_replace("$","\$",$_REQUEST["state"]);
			$other_state=str_replace("$","\$",$_REQUEST["other_state"]);
			$phone=str_replace("$","\$",$_REQUEST["phone"]);
			$phone1=str_replace("$","\$",$_REQUEST["phone1"]);
			$phone2=str_replace("$","\$",$_REQUEST["phone2"]);
			$mobile=str_replace("$","\$",$_REQUEST["mobile"]);
			$zip_code=str_replace("$","\$",$_REQUEST["zip_code"]);
			$career_level=str_replace("$","\$",$_REQUEST["career_level"]);
			$availability_time=str_replace("$","\$",$_REQUEST["availability_time"]);
			$email=str_replace("$","\$",$_REQUEST["email"]);
			$work_exp=str_replace("$","\$",$_REQUEST["work_exp"]);
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
	$id=$_REQUEST["resume_id"];
	$hide="no";
	if(isset($_REQUEST["hide"]))
	$hide=$_REQUEST["hide"];
	
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id"));
	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
		
	
	$query_update="update `sbjbs_resumes` set 
	sb_firstname='$firstname' ,
	sb_lastname='$lastname' , 
	sb_addr1='$street' , 
	sb_city='$city' , 
	sb_state='$state' , 
	sb_zip='$zip_code' , 
	sb_email='$email',
	sb_country=".$_REQUEST["country"]." , 
	sb_telephone='$phone_no', 
	sb_mobile='$mobile', 
	sb_career_level=".$_REQUEST["career_level"]." , 
	sb_availability_time='$availability_time', 
	sb_relevent_experience='$work_exp',
	sb_hide_info='$hide',
	sb_approved='$approved' 
	where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
//echo $query_update;
//die();
	$rs_update=mysql_query($query_update);
		header("Location: job_preferences.php?resume_id=$id");
		//&errmsg=".urlencode("Your personal profile has been updated."));
		die();

	/*if(mysql_affected_rows()>0)
	{
		header("Location: job_preferences.php?resume_id=$id");
		//&errmsg=".urlencode("Your personal profile has been updated."));
		die();
	}
	else
	{
		header("Location: contact_info.php?resume_id=$id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
	}*/
 }			//end if-errcnt==0
}			//end if count-post


function main()
{
global $errs, $errcnt;
$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}

$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if ( $resume )
{
$firstname=$resume["sb_firstname"];
$lastname=$resume["sb_lastname"];
$street=$resume["sb_addr1"];
$city=$resume["sb_city"];
$state=$resume["sb_state"];
$country=$resume["sb_country"];
$zip_code=$resume["sb_zip"];
$email=$resume["sb_email"];

$phone_arr=explode("-",$resume["sb_telephone"]);

$phone=(isset($phone_arr[0]))?$phone_arr[0]:"";
$phone1=(isset($phone_arr[1]))?$phone_arr[1]:"";
$phone2=(isset($phone_arr[2]))?$phone_arr[2]:"";

$mobile=$resume["sb_mobile"];
$other_state=$resume["sb_state"];
$career=$resume["sb_career_level"];
$join_time=$resume["sb_availability_time"];
$work_exp=$resume["sb_relevent_experience"];
$hide=$resume["sb_hide_info"];
}
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
			$firstname=$_REQUEST["firstname"];
			$lastname=$_REQUEST["lastname"];
			$street=$_REQUEST["street"];
			$city=$_REQUEST["city"];
			$state=$_REQUEST["state"];
			$other_state=$_REQUEST["other_state"];
			$country=$_REQUEST["country"];
			$phone=$_REQUEST["phone"];
			$phone1=$_REQUEST["phone1"];
			$phone2=$_REQUEST["phone2"];
			$mobile=$_REQUEST["mobile"];
			$zip_code=$_REQUEST["zip_code"];
			$career_level=$_REQUEST["career_level"];
			$join_time=$_REQUEST["availability_time"];
			$email=$_REQUEST["email"];
			$work_exp=$_REQUEST["work_exp"];
			if(isset($_REQUEST["hide"]))
			$hide=$_REQUEST["hide"];

if ( $errcnt<>0 )
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr> 
    <td colspan="2"><strong>&nbsp;Your Request cannot be processed due 
      to following Reasons</strong></td>
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


  function validate(form) 
  {
		
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
// -->
</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr valign="top"> 
    <td width="150"> <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td align="right"><font class='normal'><strong>Resume Components</strong></font></td>
        </tr>
        <tr> 
          <td align="right"><font class="normal"><?php if($id==0) {?>Career Objective<?php }
		  else
		  {?>
            <a href="headline.php?resume_id=<?php echo $id;?>">Career Objective</a>
            <?php }
		  ?> </font></td>
        </tr>
        <tr class='seperatorstyle'> 
          <td align="right">&nbsp;<font class="normal"><strong>
            
            Contact Info
           
            </strong></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Job Preferences
            <?php }
		  else
		  {?>
            <a href="job_preferences.php?resume_id=<?php echo $id;?>">Job Preferences</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Professional Experience
            <?php }
		  else
		  {?>
            <a href="experience.php?resume_id=<?php echo $id;?>">Professional 
            Experience</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            References
            <?php }
		  else
		  {?>
            <a href="references.php?resume_id=<?php echo $id;?>">References</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Education
            <?php }
		  else
		  {?>
            <a href="education.php?resume_id=<?php echo $id;?>">Education</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Affiliations
            <?php }
		  else
		  {?>
            <a href="affiliations.php?resume_id=<?php echo $id;?>">Affiliations</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Languages
            <?php }
		  else
		  {?>
            <a href="languages.php?resume_id=<?php echo $id;?>">Languages</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Skills
            <?php }
		  else
		  {?>
            <a href="skills.php?resume_id=<?php echo $id;?>">Skills</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Additional Info
            <?php }
		  else
		  {?>
            <a href="additional_info.php?resume_id=<?php echo $id;?>">Additional 
            Info</a>
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal">
            <?php if($id==0) {?>
            Finishing Up
            <?php }
		  else
		  {?>
            <a href="finish.php?resume_id=<?php echo $id;?>">Finishing Up</a>
            <?php }
		  ?>
            </font></td>
        </tr>
      </table></td>
    <td align="left"> 
      <form name="form1" method="post" action="<? echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td>&nbsp;Contact Info</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Firstname</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
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
            <td align="right" class="innertablestyle"><font class="normal"><strong>Email</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="email" type="text"  value="<?php echo $email; ?>" size="30" maxlength="40">
              </font></td>
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
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Total 
              Work Experience</strong></font></td>
            <td>&nbsp;</td>
            <td> <SELECT name=work_exp id="work_exp">
                <OPTION value=0<?php if($work_exp==0) echo " selected";?>>Fresher</OPTION>
                <OPTION value=1<?php if($work_exp==1) echo " selected";?>>Less 
                than 1 Year</OPTION>
                <OPTION value=2<?php if($work_exp==2) echo " selected";?>>1 
                to 2 Years</OPTION>
                <OPTION value=3<?php if($work_exp==3) echo " selected";?>>2 
                to 5 Years</OPTION>
                <OPTION value=4<?php if($work_exp==4) echo " selected";?>>5 
                to 7 Years</OPTION>
                <OPTION value=5<?php if($work_exp==5) echo " selected";?>>7 
                to 10 Years</OPTION>
                <OPTION value=6<?php if($work_exp==6) echo " selected";?>>10 
                to 15 Years</OPTION>
                <OPTION value=7<?php if($work_exp==7) echo " selected";?>>More 
                than 15 Years</OPTION>
              </SELECT> </td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Career 
              Level </strong></font></td>
            <td>&nbsp;</td>
            <td> <SELECT name="career_level" id="career_level">
                <!--OPTION value=""></OPTION-->
                <OPTION value=1<?php if($career==1) echo " selected";?>> 
                Student (High School)</OPTION>
                <OPTION value=2<?php if($career==2) echo " selected";?>> 
                Student</OPTION>
                <OPTION value=3<?php if($career==3) echo " selected";?>> 
                Entry Level (less than 2 years of experience)</OPTION>
                <OPTION value=4<?php if($career==4) echo " selected";?>> 
                Mid Career (2+ years of experience)</OPTION>
                <OPTION value=5<?php if($career==5) echo " selected";?>> 
                Manager</OPTION>
                <OPTION value=6<?php if($career==6) echo " selected";?>> 
                Senior Management (VP and equivalent)</OPTION>
                <OPTION value=7<?php if($career==7) echo " selected";?>> 
                Top Management (CEO and equivalent)</OPTION>
              </SELECT> </td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Joining 
              Period </strong></font></td>
            <td>&nbsp;</td>
            <td> <SELECT name="availability_time" id="availability_time">
                <!--OPTION value="" selected></OPTION-->
                <OPTION value=1<?php if($join_time==1) echo " selected";?>>Immediately</OPTION>
                <OPTION value=2<?php if($join_time==2) echo " selected";?>>Within 
                2 weeks</OPTION>
                <OPTION value=3<?php if($join_time==3) echo " selected";?>>Within 
                one month</OPTION>
                <OPTION value=4<?php if($join_time==4) echo " selected";?>>From 
                1 to 3 months</OPTION>
                <OPTION value=5<?php if($join_time==5) echo " selected";?>>More 
                than 3 months</OPTION>
                <OPTION value=6<?php if($join_time==6) echo " selected";?>>Negotiable</OPTION>
              </SELECT> </td>
          </tr>
          <tr valign="middle"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Hide 
              Contact Info</strong></font><br> <a href="javascript: help_popup('resume_builder','hideinfo');" class="help_style"><?php echo HELP_LINK;?></a><font class="normal"><strong><br>
              </strong></font></td>
            <td>&nbsp;</td>
            <td><font class="smalltext"> 
              <input name="hide" type="checkbox" id="hide" value="yes" <?php if($hide=="yes") {
			  echo " checked";  }?>>
              Hide My Personal Information</font> </td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="submit"  type="submit" value="Save"></td>
          </tr>
        </table></td>
  </tr>
</table>
  </form></td>
  </tr>
</table>
<?

}
include_once("template1.php");

?>
