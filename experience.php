<?
include_once "logincheck.php";
include_once("myconnect.php");

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
//		ob_start();
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		$objective_len=$config["sb_resume_desc_len"];
		if(!get_magic_quotes_gpc())
		{
			$comp_name=str_replace("$","\$",addslashes($_REQUEST["comp_name"]));
			$comp_location=str_replace("$","\$",addslashes($_REQUEST["comp_location"]));
			$title=str_replace("$","\$",addslashes($_REQUEST["title"]));
			$work_desc=str_replace("$","\$",addslashes($_REQUEST["work_desc"]));
			}
		else
		{
			$comp_name=str_replace("$","\$",$_REQUEST["comp_name"]);
			$comp_location=str_replace("$","\$",$_REQUEST["comp_location"]);
			$title=str_replace("$","\$",$_REQUEST["title"]);
			$work_desc=str_replace("$","\$",$_REQUEST["work_desc"]);
		}

	$month=(int)$_REQUEST["month"];
	$year=(int)$_REQUEST["year"];
	$month1=(int)$_REQUEST["month1"];
	$year1=(int)$_REQUEST["year1"];
	if($month1==13)
	{ $year1=date("Y",time()); }
	
	if ( strlen(trim($comp_name)) == 0 )
	{
		$errs[$errcnt]="Company Name must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["comp_name"]))
	{
		$errs[$errcnt]="Company Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($comp_location)) == 0 )
	{
		$errs[$errcnt]="Company Location must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["comp_location"]))
	{
		$errs[$errcnt]="Company Location can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($title)) == 0 )
	{
		$errs[$errcnt]="Designation must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Designation can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( strlen(trim($work_desc)) == 0 )
	{
		$errs[$errcnt]="Work Description must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["work_desc"]))
	{
		$errs[$errcnt]="Work Description can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	elseif(strlen(trim($work_desc))>$objective_len )
	{
		$errs[$errcnt]="Work Description must be less than $objective_len characters.";
   		$errcnt++;
	}
	

	if($errcnt==0)
	{

	$resume_id=$_REQUEST["resume_id"];
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$resume_id  and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
	if(!$resume)
	{
	header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("Invalid Access, Denied."));
	die();
	}
	$exp_id=$_REQUEST["exp_id"];
	if(isset($_REQUEST["delete"])&&($_REQUEST["delete"]<>""))
	{
		if($exp_id<>0)
		{
		mysql_query("delete from sbjbs_experience where sb_id=$exp_id"); 
		}
		header("Location:"."experience.php?resume_id=$resume_id");
		die();
	}//end delete
	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	mysql_query("update sbjbs_resumes set sb_approved='$approved' where sb_id=$resume_id");
	
	if($exp_id==0)
	{
	$query_str="insert into sbjbs_experience (sb_company_name,sb_location,sb_work_desc,sb_designation,sb_start_month,sb_start_year,sb_end_month,sb_end_year,sb_resume_id) 
	values('$comp_name','$comp_location','$work_desc','$title',$month,$year,$month1,$year1,$resume_id)";
	}
	else
	{	
	$query_str="update `sbjbs_experience` set 
	sb_company_name='$comp_name' ,
	sb_location='$comp_location' , 
	sb_designation='$title' , 
	sb_work_desc='$work_desc' , 
	sb_start_month=$month , 
	sb_start_year=$year, 
	sb_end_month=$month1 , 
	sb_end_year=$year1
	where sb_id=$exp_id";
	}
//echo $query_str;
//die();
	$rs_update=mysql_query($query_str);
	
	if(isset($_REQUEST["next"]))
	{
	header("Location: references.php?resume_id=$resume_id");
	die();
	}
	elseif(	isset($_REQUEST["new"]))	
	 {
		header("Location: experience.php?resume_id=$resume_id&new=yes");
		die();
	}
/*	if(mysql_affected_rows()>0)
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: references.php?resume_id=$resume_id&errmsg=".urlencode("Your professional experience has been saved."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: experience.php?resume_id=$resume_id&new=yes");
		die();
		}
	}
	else
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: experience.php?resume_id=$resume_id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: experience.php?resume_id=$resume_id&new=yes");
		die();
		}

	}*/
 }			//end if-errcnt==0
}			//end if count-post


function main()
{
global $errs, $errcnt;
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$objective_len=$config["sb_resume_desc_len"];
$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}

$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if ($resume)
{
	$comp_name="";
	$comp_location="";
	$title="";
	$month="";
	$year=date("Y",time());
	$month1="";
	$year1=date("Y",time());
	$work_desc="";
	$exp_id=0;
	$pg=0;
	if(isset($_REQUEST["pg"])&&($_REQUEST["pg"]<>""))
	{$pg=$_REQUEST["pg"];}
	$new="no";
	if(isset($_REQUEST["new"])&&($_REQUEST["new"]<>""))
	{$new=$_REQUEST["new"];}
	
	$sql="select * from sbjbs_experience where sb_resume_id=$id ";
	/*if($exp_id<>0)
	{ $sql.=" and sb_id=$exp_id";}*/
	$sql.=" order by sb_id desc";
	$exp_q=mysql_query($sql);
	$num_records=mysql_num_rows($exp_q);
	if(($pg>$num_records)||($pg<0))
	{ $pg=0;}
	
	if(($num_records>0)&&($new=="no"))
	{
	for($i=0;$i<$pg-1;$i++)
	{ $exp=mysql_fetch_array($exp_q); }
	$exp=mysql_fetch_array($exp_q);
	$comp_name=$exp["sb_company_name"];
	$comp_location=$exp["sb_location"];
	$title=$exp["sb_designation"];
	$month=$exp["sb_start_month"];
	$year=$exp["sb_start_year"];
	$month1=$exp["sb_end_month"];
	$year1=$exp["sb_end_year"];
	$work_desc=$exp["sb_work_desc"];
	$exp_id=$exp["sb_id"];
	}
}//end if resume
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
	$comp_name=$_POST["comp_name"];
	$comp_location=$_POST["comp_location"];
	$title=$_POST["title"];
	$month=$_POST["month"];
	$year=$_POST["year"];
	$month1=$_POST["month1"];
	$year1=$_POST["year1"];
	$work_desc=$_POST["work_desc"];
	$exp_id=$_POST["exp_id"];
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
  function validate(form) 
  {
		
	if ( form.comp_name.value == "" ) {
       	   alert('Please Specify Company Name!');
		   form.comp_name.focus();
	   return false;
	   }
	if(form.comp_name.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Company Name (e.g. &  < >)");
			form.comp_name.focus();
			return(false);
		}
	if ( form.comp_location.value == "" ) {
       	   alert('Please Specify Company Location!');
		   form.comp_location.focus();
	   return false;
	   }
	if(form.comp_location.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Company Location (e.g. &  < >)");
			form.comp_location.focus();
			return(false);
		}

	if ( form.title.value == "" ) {
       	   alert('Please Specify Designation!');
		   form.title.focus();
	   return false;
	   }
	if(form.title.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Designation (e.g. &  < >)");
			form.title.focus();
			return(false);
		}

	if ( form.work_desc.value == "" ) {
       	   alert('Please Specify Work Description!');
		   form.work_desc.focus();
	   return false;
	   }
		if(form.work_desc.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Work Description(e.g. &  < >)");
			form.work_desc.focus();
			return(false);
		}
	if(form.work_desc.value.length><?php echo $objective_len;?>)
		{
			alert("Work Description must not be greater than <?php echo $objective_len;?> characters.");
			form.work_desc.focus();
			return(false);
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
        <tr class='seperatorstyle'> 
          <td align="right">&nbsp; <font class="normal"> <strong>Professional 
            Experience</strong> </font></td>
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
            <td>&nbsp;Professional Experience</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="exp_id" type="hidden" id="exp_id" value="<?php echo $exp_id;?>">
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Company Name</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="comp_name" type="text" id="comp_name"  value="<?php echo $comp_name; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Company 
              Location </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="comp_location" type="text" id="comp_location"  value="<?php echo $comp_location  ; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Designation 
              </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="title" type="text" id="title" value="<?php echo $title; ?>"  size="30" maxlength="30" >
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Start 
              Date </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <SELECT name="month">
                <OPTION value=1 <?php if($month==1) echo "selected";?>>January</OPTION>
                <OPTION value=2 <?php if($month==2) echo "selected";?>>February</OPTION>
                <OPTION value=3 <?php if($month==3) echo "selected";?>>March</OPTION>
                <OPTION value=4 <?php if($month==4) echo "selected";?>>April</OPTION>
                <OPTION value=5 <?php if($month==5) echo "selected";?>>May</OPTION>
                <OPTION value=6 <?php if($month==6) echo "selected";?>>June</OPTION>
                <OPTION value=7 <?php if($month==7) echo "selected";?>>July</OPTION>
                <OPTION value=8 <?php if($month==8) echo "selected";?>>August</OPTION>
                <OPTION value=9 <?php if($month==9) echo "selected";?>>September</OPTION>
                <OPTION value=10 <?php if($month==10) echo "selected";?>>October</OPTION>
                <OPTION value=11 <?php if($month==11) echo "selected";?>>November</OPTION>
                <OPTION value=12 <?php if($month==12) echo "selected";?>>December</OPTION>
              </SELECT>
              &nbsp; 
              <select name="year" id="year">
                <?php
			  for($i=1919;$i<=(date("Y",time()));$i++)
			  {
			  ?>
                <option value="<?php echo $i;?>" <?php if($year==$i) echo "selected";?>>
                <?php 
			  echo $i;?>
                </option>
                <?php
			  }
              ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>End 
              Date </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <SELECT name="month1" id="month1">
                <OPTION value=13 <?php if($month1==13) echo "selected";?>>Present</OPTION>
                <OPTION value=1 <?php if($month1==1) echo "selected";?>>January</OPTION>
                <OPTION value=2 <?php if($month1==2) echo "selected";?>>February</OPTION>
                <OPTION value=3 <?php if($month1==3) echo "selected";?>>March</OPTION>
                <OPTION value=4 <?php if($month1==4) echo "selected";?>>April</OPTION>
                <OPTION value=5 <?php if($month1==5) echo "selected";?>>May</OPTION>
                <OPTION value=6 <?php if($month1==6) echo "selected";?>>June</OPTION>
                <OPTION value=7 <?php if($month1==7) echo "selected";?>>July</OPTION>
                <OPTION value=8 <?php if($month1==8) echo "selected";?>>August</OPTION>
                <OPTION value=9 <?php if($month1==9) echo "selected";?>>September</OPTION>
                <OPTION value=10 <?php if($month1==10) echo "selected";?>>October</OPTION>
                <OPTION value=11 <?php if($month1==11) echo "selected";?>>November</OPTION>
                <OPTION value=12 <?php if($month1==12) echo "selected";?>>December</OPTION>
              </SELECT>
              &nbsp; 
              <select name="year1" id="year1">
                <?php
			  for($i=1919;$i<=(date("Y",time()));$i++)
			  {
			  ?>
                <option value="<?php echo $i;?>" <?php if($year1==$i) echo "selected";?>> 
                <?php 
			  echo $i;?>
                </option>
                <?php
			  }
              ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Work 
              Description </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <textarea name="work_desc" cols="50" rows="5" id="work_desc"><?php echo $work_desc; ?></textarea>
              <font class="smalltext">not more than <?php echo $objective_len;?> 
              characters</font> </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="next"  type="submit" value="Save">&nbsp;
              <input type="submit" name="new" value="Save &amp; Add New">
              &nbsp;<?php
			  if( $exp_id>0)
			  {
			  ?><input type="submit" name="delete" value="Delete" onClick="return confirm('This will delete the current record. Do you want to continue');"><?php 
			  }
			  ?></td>
          </tr><?php
		  if($num_records>1)
		  {
          ?>
          <tr align="left" valign="top"> 
            <td colspan="3"> &nbsp;<font class="normal"><strong>Saved Experiences</strong> 
              &nbsp; 
              <?php
			if($pg<>0)
			{ $noclick=$pg;}
			elseif(isset($_REQUEST["new"])&&($_REQUEST["new"]=="yes"))
			{ $noclick=0;}
			else
			{ $noclick=1;}
			$exp_q=mysql_query("select * from sbjbs_experience where sb_resume_id=$id order by sb_id desc");
			for($i=1;$i<=$num_records;$i++)
			{ 
			$exp=mysql_fetch_array($exp_q);
			$title=$exp["sb_company_name"]." [".$exp["sb_designation"]."]\nFrom:\t".$exp["sb_start_month"].",".$exp["sb_start_year"]."\nTo:\t";
			$title.=($exp["sb_end_month"]==13)?"Present":$exp["sb_end_month"].",".$exp["sb_end_year"];
			if($i<>$noclick)
			{ echo "<a href='experience.php?resume_id=$id&pg=$i' title=\"$title\">";}
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
                      <li> Please fill in the above fields to describe your experience. 
                        Provide the information one by one. These will appear 
                        on your resume in chronological order starting with the 
                        most recent.</li>
                      <li>For confidential resume please choose <strong>Present</strong> 
                        in end date to hide the current job details and be sure 
                        not to include the information about current employer 
                        in the work description.</li>
                      <li>To enter additional professional work experience, click 
                        <strong>Save &amp; Add New</strong>. It will save the 
                        details you have just entered and start a new form.</li>
                      <li>To edit a saved experience details click the relevant 
                        record number in <strong>Saved Experiences </strong>, 
                        it will <strong> </strong>reload the saved record for 
                        editing.</li>
                      <li>To delete a saved experience details click the relevant 
                        record number in <strong>Saved Experiences </strong>, 
                        then click the <strong>Delete</strong> button.<br>
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
