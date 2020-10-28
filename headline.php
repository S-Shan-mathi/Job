<?
include_once "logincheck.php";
include_once("myconnect.php");

$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$resume_cnt=$config["sb_resume_cnt"];
$total_resume=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if(($total_resume>=$resume_cnt)&&(!isset($_REQUEST["resume_id"])||($_REQUEST["resume_id"]=="")))
{
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("You have already posted the maximum number of resumes allowed."));
die();
}

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		$headline_len=$config["sb_title_len"];
$objective_len=$config["sb_resume_desc_len"];
//		ob_start();
		if(!get_magic_quotes_gpc())
		{
			$sb_title=str_replace("$","\$",addslashes($_REQUEST["title"]));
			$sb_objective=str_replace("$","\$",addslashes($_REQUEST["objective"]));
		}
		else
		{
			$sb_title=str_replace("$","\$",$_REQUEST["title"]);
			$sb_objective=str_replace("$","\$",$_REQUEST["objective"]);
		}

	if ( strlen(trim($sb_title)) == 0 )
	{
		$errs[$errcnt]="Headline must be provided.";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Headline can not have any special character (e.g. & ; < >).";
   		$errcnt++;
	}
	elseif(strlen(trim($sb_title))>$headline_len )
	{
		$errs[$errcnt]="Headline must be less than $headline_len characters.";
   		$errcnt++;
	}
	if ( strlen(trim($sb_objective)) == 0 )
	{
		$errs[$errcnt]="Career Objective must be provided.";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["objective"]))
	{
		$errs[$errcnt]="Career Objective can not have any special character (e.g. & ; < >).";
   		$errcnt++;
	}
	elseif(strlen(trim($sb_objective))>$objective_len )
	{
		$errs[$errcnt]="Career Objective must be less than $objective_len characters.";
   		$errcnt++;
	}

	if($errcnt==0)
	{
		$id=$_REQUEST["resume_id"];
		if($id==0)//insert new record
		{
		mysql_query("insert into sbjbs_resumes 
		(sb_title,sb_objective,sb_seeker_id,sb_posted_on,sb_approved ) 
		values
		('$sb_title','$sb_objective',".$_SESSION["sbjbs_userid"].",'".date("YmdHis",time())."','incomplete')
		");
		if(mysql_affected_rows()>0)
		{
			$rs=mysql_fetch_array(mysql_query("select max(sb_id) from sbjbs_resumes"));
			$id=$rs[0];
			header("Location: contact_info.php?resume_id=$id");
			//&errmsg=".urlencode("Your career objective and headline has been saved."));
			die();
		}
		else
		{
			header("Location: gen_confirm_mem.php?err=headline&errmsg=".urlencode("Some error occured."));
			die();
		}
	}// end new record
	else
	{
	
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id"));
	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}

		mysql_query("update sbjbs_resumes set 
		sb_title='$sb_title',sb_objective='$sb_objective',sb_approved='$approved' where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
//		if(mysql_affected_rows()>0)
	//	{
		header("Location: contact_info.php?resume_id=$id");
		//&errmsg=".urlencode("Your career objective and headline has been saved."));
			die();
/*		}
		else
		{
			header("Location: headline.php?resume_id=$id&errmsg=".urlencode("Sorry, no updations carried out."));
			die();
		}*/
	}//end updation
 }			//end if-errcnt==0
}			//end if count-post


function main()
{
global $errs, $errcnt;
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$headline_len=$config["sb_title_len"];
$objective_len=$config["sb_resume_desc_len"];
$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}

$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if ( $resume )
{
$sb_title=$resume["sb_title"];
$sb_objective=$resume["sb_objective"];
}
else
{
$sb_title="";
$sb_objective="";
}
if  (count($_POST)>0)
{
$sb_title=$_POST["title"];
$sb_objective=$_POST["objective"];

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
		
	if ( form.title.value == "" ) {
       	   alert('Please Specify Headline!');
		   form.title.focus();
	   return false;
	   }
	if(form.title.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Headline (e.g. &  < >)");
			form.title.focus();
			return(false);
		}
	if(form.title.value.length><?php echo $headline_len;?>)
		{
			alert("Headline must not be greater than <?php echo $headline_len;?> characters.");
			form.title.focus();
			return(false);
		}
	if ( form.objective.value == "" ) {
       	   alert('Please Specify Objective!');
		   form.objective.focus();
	   return false;
	   }
	if(form.objective.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Objective (e.g. &  < >)");
			form.objective.focus();
			return(false);
		}
	if(form.objective.value.length><?php echo $objective_len;?>)
		{
			alert("Objective must not be greater than <?php echo $objective_len;?> characters.");
			form.objective.focus();
			return(false);
		}
	return true;
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
        <tr class='seperatorstyle'> 
          <td align="right"><font class="normal"><strong>Career Objective </strong></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp;<font class="normal"><?php if($id==0) {?>Contact Info<?php }
		  else
		  {
		?><a href="contact_info.php?resume_id=<?php echo $id;?>">Contact Info</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Job Preferences<?php }
		  else
		  {?><a href="job_preferences.php?resume_id=<?php echo $id;?>">Job Preferences</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Professional Experience<?php }
		  else
		  {?><a href="experience.php?resume_id=<?php echo $id;?>">Professional Experience</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>References<?php }
		  else
		  {?><a href="references.php?resume_id=<?php echo $id;?>">References</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Education<?php }
		  else
		  {?><a href="education.php?resume_id=<?php echo $id;?>">Education</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Affiliations<?php }
		  else
		  {?><a href="affiliations.php?resume_id=<?php echo $id;?>">Affiliations</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Languages<?php }
		  else
		  {?><a href="languages.php?resume_id=<?php echo $id;?>">Languages</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Skills<?php }
		  else
		  {?><a href="skills.php?resume_id=<?php echo $id;?>">Skills</a><?php }
		  ?></font></td>
        </tr>
        <tr>
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Additional Info<?php }
		  else
		  {?><a href="additional_info.php?resume_id=<?php echo $id;?>">Additional Info</a><?php }
		  ?></font></td>
        </tr>
        <tr> 
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?>Finishing Up<?php }
		  else
		  {?><a href="finish.php?resume_id=<?php echo $id;?>">Finishing Up</a><?php }
		  ?></font></td>
        </tr>
      </table>
    </td>
    <td align="left"> 
      <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td >&nbsp;Career Objective &amp; Headline</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="30%" align="right" class="innertablestyle"><font class="normal"><strong>Headline</strong></font><br>
              <a href="javascript: help_popup('resume_builder','headline');" class="help_style"><?php echo HELP_LINK;?></a> 
            </td>
            <td width="6"><font class="red">*</font></td>
            <td width="70%"> <input name="title" type="text" id="title"  value="<?php echo $sb_title; ?>" size="50" maxlength="70">
              <font class="normal"><strong>
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              </strong></font> <br> <font class="smalltext"> not more than <?php echo $headline_len;?> 
              characters</font></td>
          </tr>
          <tr valign="top"> 
            <td width="30%" align="right" class="innertablestyle"><font class="normal"><strong>Career 
              Objective<br>
              </strong></font><a href="javascript:help_popup('resume_builder','objective');" class="help_style"><?php echo HELP_LINK;?></a> 
              <font class="normal"><strong> </strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="70%"> 
              <textarea name="objective" cols="50" rows="5" id="objective"><?php echo $sb_objective;?></textarea><br>
              <font class="smalltext"> not more than <?php echo $objective_len;?> 
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
                  <td width="97%" align="center"><strong>Make Your Resume Better</strong></td>
                </tr>
                <tr align="left"> 
                  <td><ul >
                      <li>Provide a headline that emphasize your skills.</li>
                      <li>Summerise your goals, skills, achievements etc. in career 
                        objectives.</li>
                    </ul></td>
                </tr>
              </table>
              <br>
            </td>
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
