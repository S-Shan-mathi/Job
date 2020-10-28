<?
include_once "logincheck.php";
include_once("myconnect.php");


$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
//		ob_start();
		if(!get_magic_quotes_gpc())
		{
			$comp_name=str_replace("$","\$",addslashes($_REQUEST["comp_name"]));
			$ref_name=str_replace("$","\$",addslashes($_REQUEST["ref_name"]));
			$title=str_replace("$","\$",addslashes($_REQUEST["title"]));
			$email=str_replace("$","\$",addslashes($_REQUEST["email"]));
			$phone=str_replace("$","\$",addslashes($_REQUEST["phone"]));
			}
		else
		{
			$comp_name=str_replace("$","\$",$_REQUEST["comp_name"]);
			$ref_name=str_replace("$","\$",$_REQUEST["ref_name"]);
			$title=str_replace("$","\$",$_REQUEST["title"]);
			$email=str_replace("$","\$",$_REQUEST["email"]);
			$phone=str_replace("$","\$",$_REQUEST["phone"]);
	}

	
	if ( strlen(trim($ref_name)) == 0 )
	{
		$errs[$errcnt]="Name must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["ref_name"]))
	{
		$errs[$errcnt]="Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(preg_match ("/[;<>&]/", $_REQUEST["comp_name"]))
	{
		$errs[$errcnt]="Company Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Designation can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($phone)) == 0 )
	{
		$errs[$errcnt]="Phone must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $phone))
	{
		$errs[$errcnt]="Phone can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if(preg_match ("/[;<>&]/", $_REQUEST["email"]))
	{
		$errs[$errcnt]="Email can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	$relation=$_REQUEST["relation"];

	if($errcnt==0)
	{

	$resume_id=$_REQUEST["resume_id"];
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$resume_id  and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
	if(!$resume)
	{
	header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("Invalid Access, Denied."));
	die();
	}
	$ref_id=$_REQUEST["ref_id"];
	if(isset($_REQUEST["delete"])&&($_REQUEST["delete"]<>""))
	{
		if($ref_id<>0)
		{
		mysql_query("delete from sbjbs_references where sb_id=$ref_id"); 
		}
		header("Location:"."references.php?resume_id=$resume_id");
		die();
	}//end delete

	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	mysql_query("update sbjbs_resumes set sb_approved='$approved' where sb_id=$resume_id");

	if($ref_id==0)
	{
	$query_str="insert into sbjbs_references (sb_company,sb_name,sb_email,sb_designation,sb_phone,sb_relation,sb_resume_id) 
	values('$comp_name','$ref_name','$email','$title','$phone','$relation',$resume_id)";
	}
	else
	{	
	$query_str="update `sbjbs_references` set 
	sb_company='$comp_name' ,
	sb_name='$ref_name' , 
	sb_designation='$title' , 
	sb_email='$email' , 
	sb_phone='$phone' , 
	sb_relation='$relation'
	where sb_id=$ref_id";
	}
//echo $query_str;
//die();
	$rs_update=mysql_query($query_str);
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: education.php?resume_id=$resume_id");
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: references.php?resume_id=$resume_id&new=yes");
		die();
		}
	
/*	if(mysql_affected_rows()>0)
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: education.php?resume_id=$resume_id&errmsg=".urlencode("Your references have been saved."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: references.php?resume_id=$resume_id&new=yes");
		die();
		}
	}
	else
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: references.php?resume_id=$resume_id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: references.php?resume_id=$resume_id&new=yes");
		die();
		}

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

if ($resume)
{
	$comp_name="";
	$ref_name="";
	$title="";
	$email="";
	$relation="professional";
	$phone="";
	$ref_id=0;
	$pg=0;
	if(isset($_REQUEST["pg"])&&($_REQUEST["pg"]<>""))
	{$pg=$_REQUEST["pg"];}
	$new="no";
	if(isset($_REQUEST["new"])&&($_REQUEST["new"]<>""))
	{$new=$_REQUEST["new"];}
	
	$sql="select * from sbjbs_references where sb_resume_id=$id ";
	/*if($exp_id<>0)
	{ $sql.=" and sb_id=$exp_id";}*/
	$sql.=" order by sb_id desc";
	$ref_q=mysql_query($sql);
	$num_records=mysql_num_rows($ref_q);
	if(($pg>$num_records)||($pg<0))
	{ $pg=0;}
	
	if(($num_records>0)&&($new=="no"))
	{
	for($i=0;$i<$pg-1;$i++)
	{ $ref=mysql_fetch_array($ref_q); }
	$ref=mysql_fetch_array($ref_q);
	$comp_name=$ref["sb_company"];
	$ref_name=$ref["sb_name"];
	$title=$ref["sb_designation"];
	$email=$ref["sb_email"];
	$relation=$ref["sb_relation"];
	$phone=$ref["sb_phone"];
	$ref_id=$ref["sb_id"];
	}
}//end if resume
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
			$comp_name=$_REQUEST["comp_name"];
			$ref_name=$_REQUEST["ref_name"];
			$title=$_REQUEST["title"];
			$email=$_REQUEST["email"];
			$phone=$_REQUEST["phone"];
			$ref_id=$_POST["ref_id"];
			$pg=0;

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
		
	if ( form.ref_name.value == "" ) {
       	   alert('Please Specify Name!');
		   form.ref_name.focus();
	   return false;
	   }
	if(form.ref_name.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Name (e.g. &  < >)");
			form.ref_name.focus();
			return(false);
		}

	if(form.comp_name.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Company Name (e.g. &  < >)");
			form.comp_name.focus();
			return(false);
		}

	if(form.title.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Designation (e.g. &  < >)");
			form.title.focus();
			return(false);
		}

	if ( form.phone.value == "" ) {
       	   alert('Please Specify Phone!');
		   form.phone.focus();
	   return false;
	   }
		if(form.phone.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Phone(e.g. &  < >)");
			form.phone.focus();
			return(false);
		}
    if(form.email.value!="") 
	{
		if (!emailCheck (form.email.value) )
		{
			form.email.focus();
			return (false);
		}
	}

	return true;
  }
// -->
</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr valign="top"> 
    <td width="155"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td align="right"><font class='normal'><strong>Resume Components</strong></font></td>
        </tr>
        <tr> 
          <td align="right"><font class="normal">
            <?php if($id==0) {?>
            Career Objective
            <?php }
		  else
		  {?>
            <a href="headline.php?resume_id=<?php echo $id;?>">Career 
            Objective</a> 
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp;<font class="normal"> 
            <?php if($id==0) {?>
            Contact Info 
            <?php }
		  else
		  {?>
            <a href="contact_info.php?resume_id=<?php echo $id;?>">Contact Info</a> 
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"> 
            <?php if($id==0) {?>
            Job Preferences 
            <?php }
		  else
		  {?>
            <a href="job_preferences.php?resume_id=<?php echo $id;?>">Job 
            Preferences</a> 
            <?php }
		  ?>
            </font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Professional 
            Experience <?php }
		  else
		  {?>
            <a href="experience.php?resume_id=<?php echo $id;?>">Professional Experience</a> 
            <?php }
		  ?>
 </font></td>
        </tr>
        <tr class='seperatorstyle'> 
          <td align="right">&nbsp; <font class="normal"> 
             <strong>
            References</strong> 
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
            <a href="finish.php?resume_id=<?php echo $id;?>">Finishing 
            Up</a> 
            <?php }
		  ?>
            </font></td>
        </tr>
      </table></td>
    <td align="left"> 
      <form name="form1" method="post" action="<? echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td >&nbsp;References</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="ref_id" type="hidden" id="ref_id" value="<?php echo $ref_id;?>">
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Name</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="ref_name" type="text" id="ref_name"  value="<?php echo $ref_name; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Company 
              </strong></font></td>
            <td>&nbsp;</td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="comp_name" type="text" id="comp_name"  value="<?php echo $comp_name  ; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Designation 
              </strong></font></td>
            <td>&nbsp;</td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="title" type="text" id="title" value="<?php echo $title; ?>"  size="30" maxlength="30" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Phone 
              </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext">
              <input name="phone" type="text" id="title3" value="<?php echo $phone; ?>"  size="30" maxlength="30" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Email</strong></font></td>
            <td>&nbsp;</td>
            <td><font class="smalltext">
              <input name="email" type="text" id="email" value="<?php echo $email; ?>"  size="30" maxlength="30" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Relationship</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <input type="radio" name="relation" value="personal" <?php 
			  if($relation=="personal") echo "checked";?>>
              Personal&nbsp; 
              <input type="radio" name="relation" value="professional" <?php 
			  if($relation=="professional") echo "checked";?>>
              Professional </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="next"  type="submit" value="Save">&nbsp;
              <input type="submit" name="new" value="Save &amp; Add New">
              &nbsp;
              <?php
			  if( $ref_id>0)
			  {
			  ?>
              <input type="submit" name="delete" value="Delete" onClick="return confirm('This will delete the current record. Do you want to continue');">
              <?php 
			  }
			  ?>
            </td>
          </tr><?php
		  if($num_records>1)
		  {
          ?>
          <tr align="left" valign="top"> 
            <td colspan="3"> &nbsp;<font class="normal"><strong>Saved References</strong> 
              &nbsp; 
              <?php
			if($pg<>0)
			{ $noclick=$pg;}
			elseif(isset($_REQUEST["new"])&&($_REQUEST["new"]=="yes"))
			{ $noclick=0;}
			else
			{ $noclick=1;}
			$ref_q=mysql_query("select * from sbjbs_references where sb_resume_id=$id order by sb_id desc");
			for($i=1;$i<=$num_records;$i++)
			{ 
			$ref=mysql_fetch_array($ref_q);
			$title=$ref["sb_name"];
			$title.=($ref["sb_designation"]<>"")?" [".$ref["sb_designation"]."]":"";
			$title.=($ref["sb_company"]<>"")?"\n".$ref["sb_company"]:"";
		
			if($i<>$noclick)
			{ echo "<a href='".$_SERVER['PHP_SELF']."?resume_id=$id&pg=$i' title=\"$title\">";}
			  echo "<b>".$i."</b>";
			if($i<>$noclick)
			{ echo "</a>";}
			  echo "&nbsp;&nbsp;";
			}
			?>
              </font></td>
          </tr><?
		  }
         ?><tr valign="top"> 
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr valign="top"> 
            <td colspan="3" align="right"><table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="helpstyle">
                <tr align="left"> 
                  <td width="97%" align="center"><strong>Make Your Resume Better</strong></td>
                </tr>
                <tr align="left"> 
                  <td valign="top"> <ul >
                      <li> References must be informed before adding their details 
                        here.</li>
                      <li>In case of confidential resume your references will 
                        be hidden on your resume detail page.</li>
                      <li>To enter additional references, click <strong>Save &amp; 
                        Add New</strong>. It will save reference details you have 
                        just entered and start a new form.</li>
                      <li>To edit saved references click the relevant record number 
                        in <strong>Saved References</strong>, it will <strong> 
                        </strong>reload the saved record for editing.</li>
                      <li>To delete a saved references click the relevant record 
                        number in <strong>Saved References </strong>, then click 
                        the <strong>Delete</strong> button.<br>
                      </li>
                    </ul></td>
                </tr>
              </table></td>
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
