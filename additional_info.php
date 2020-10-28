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
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$objective_len=$config["sb_resume_desc_len"];
//		ob_start();
		if(!get_magic_quotes_gpc())
		{
			$sb_objective=str_replace("$","\$",addslashes($_REQUEST["objective"]));
			$list1=str_replace("$","\$",addslashes($_REQUEST["list1"]));
		}
		else
		{
			$sb_objective=str_replace("$","\$",$_REQUEST["objective"]);
			$list1=str_replace("$","\$",$_REQUEST["list1"]);
		}

	if ( strlen(trim($sb_objective)) == 0 )
	{
		$errs[$errcnt]="Additional Info must be provided.";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["objective"]))
	{
		$errs[$errcnt]="Additional Info can not have any special character (e.g. & ; < >).";
   		$errcnt++;
	}
	elseif(strlen(trim($sb_objective))>$objective_len )
	{
		$errs[$errcnt]="Additional Info must be less than $objective_len characters.";
   		$errcnt++;
	}

	if($errcnt==0)
	{
		$id=$_REQUEST["resume_id"];

	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id"));
	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}

		mysql_query("update sbjbs_resumes set 
		sb_additional_info='$sb_objective',sb_img_url='$list1',sb_approved='$approved'  
		where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
		if(mysql_affected_rows()>0)
		{
			header("Location: finish.php?resume_id=$id&errmsg=".urlencode("Your Additional Info has been saved."));
			die();
		}
		else
		{
			header("Location: additional_info.php?resume_id=$id&errmsg=".urlencode("Sorry, no updations carried out."));
			die();
		}
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

if ( $resume )
{
$sb_objective=$resume["sb_additional_info"];
$photo=$resume["sb_img_url"];
}
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
$sb_objective=$_POST["objective"];
$photo=$_POST["list1"];

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
		
	if ( form.objective.value == "" ) {
       	   alert('Please Specify Additional Info!');
		   form.objective.focus();
	   return false;
	   }
	if(form.objective.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Additional Info(e.g. &  < >)");
			form.objective.focus();
			return(false);
		}
	if(form.objective.value.length><?php echo $objective_len;?>)
		{
			alert("Additional Info must not be greater than <?php echo $objective_len;?> characters.");
			form.objective.focus();
			return(false);
		}
	return true;
  }

function attachment(box)
{
	str="fileupload.php?box="  + box;
	sbwin=window.open(str,"Attachment","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=550,height=450,location=no,directories=no,scrollbars=yes");
	sbwin.focus();
}
function removeattachment(box)
{
window.document.form123.list1.value=""
}

// -->
</SCRIPT>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr valign="top"> 
    <td width="150">
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
            <a href="headline.php?resume_id=<?php echo $id;?>">Career Objective</a> 
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
            <a href="contact_info.php?resume_id=<?php echo $id;?>">Contact 
            Info</a>
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
            <?php 

			if($id==0) {?>
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
        <tr class="seperatorstyle"> 
          <td align="right">&nbsp; <font class="normal">
            <strong>Additional Info</strong>
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
      </table>
    </td>
    <td align="left"> 
      <form name="form123" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td>&nbsp;Additional Info</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class="normal"><strong>Photograph(100x80)</strong></font></td>
                  <td>&nbsp;</td>
                  <td><font size="2" face="Arial, Helvetica, sans-serif" color="#666666"> 
                    <input name = "list1" type = "text" id="list1" value="<?php echo $photo; ?>" size="20" readonly >
                    <input type=BUTTON name=btn_name2 value="Upload" onClick=attachment('list1')>
                    <input type=BUTTON name=buttonname2 value="Remove" onClick=removeattachment()>
                    </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;</font><font class="normal">&nbsp; 
                    </font></td>
                </tr>
                <tr valign="top"> 
                  <td width="30%" align="right" class="innertablestyle"><font class="normal"><strong> 
                    <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
                    Additional Information<br>
                    </strong></font><a href="javascript: help_popup('resume_builder','additional_info');" class="help_style"><?php echo HELP_LINK;?></a> 
                    <font class="normal"><strong> </strong></font></td>
                  <td width="6"><font class="red">*</font></td>
                  <td width="70%"> <textarea name="objective" cols="50" rows="5" id="objective"><?php echo $sb_objective;?></textarea> 
                    <br> <font class="smalltext"> not more than <?php echo $objective_len;?> 
                    characters</font></td>
                </tr>
                <tr valign="top"> 
                  <td width="30%" align="right" class="innertablestyle">&nbsp;</td>
                  <td width="6">&nbsp;</td>
                  <td width="70%"> <input name="submit"  type="submit" value="Save"></td>
                </tr>
                <tr valign="top"> 
                  <td colspan="3" align="right">&nbsp;</td>
                </tr>
                <tr valign="top"> 
                  <td colspan="3"><table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="helpstyle">
                      <tr align="left"> 
                        <td width="97%" align="center"><strong>Make Your Resume 
                          Better</strong></td>
                      </tr>
                      <tr align="left"> 
                        <td><ul >
                            <li>Please make sure that the size of your photograph 
                              is 100x80 px. otherwise it will look distorted.</li>
                          </ul></td>
                      </tr>
                    </table>
                    <br> </td>
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