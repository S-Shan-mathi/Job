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
	$id=$_POST["resume_id"];
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id"));
	$approved=$resume["sb_approved"];
	
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	if($approved=="incomplete")
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
		else
		{ $approved="yes";}
	}
	
	if(isset($_POST["search"]))
	{ 
	mysql_query("update sbjbs_resumes set sb_search_enable='no' 
	where sb_seeker_id=".$_SESSION["sbjbs_userid"]);
	mysql_query("update sbjbs_resumes set sb_search_enable='yes',sb_approved='$approved',
	 sb_posted_on='".date("YmdHis",time())."' where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
	 if($approved=="yes")
	 {
	 $msg="Your resume has been saved as searchable.";
	 }
	 else
	 {
	 $msg="Your resume has been saved as searchable and sent for Admin approval";
	 }
	}//end make it searchable
	elseif(isset($_POST["complete"]))
	{
	 mysql_query("update sbjbs_resumes set sb_search_enable='no',sb_approved='$approved' ,
	 sb_posted_on='".date("YmdHis",time())."' where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
	//}//end make it searchable
	 if($approved=="yes")
	 {
	 $msg="Your resume has been marked as completed.";
	 }
	 else
	 {
	 $msg="Your resume has been sent for Admin approval";
	 }


	}// end mark complete
	else
	{
	 
	 if($resume["sb_approved"]=="no")
	 { $approved="no";}
	 else
	 { $approved="incomplete";}
	 
	 mysql_query("update sbjbs_resumes set sb_search_enable='no',sb_approved='$approved' 
	 where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);

	 if($approved=="incomplete")
	 {
	 $msg="Your resume has been marked as incomplete.";
	 }
	 else
	 {
	 $msg="Disapproved resumes can not be marked as incomplete.";
	 }

	}//end incomplete
	if(mysql_affected_rows()>0)
	{
	
	header("Location:"."gen_confirm_mem.php?errmsg=".urlencode($msg));
	die();
	}
	else
	{
	header("Location:"."finish.php?resume_id=$id&errmsg=".urlencode("Some error occurred, Please try again."));
	die();
	}
}			//end if count-post


function main()
{
global $errs, $errcnt;
$headline_len=70;
$objective_len=2000;
$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}

$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
if ( $resume )
{
$complete="no";

if(($resume["sb_title"]<>"")&&($resume["sb_firstname"]<>"")&&($resume["sb_target_job"]<>""))
{ $complete="yes";}
$hide=$resume["sb_hide_info"];

}
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}

if  (count($_POST)>0)
{

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
		
	if(form.objective.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Additional Info(e.g. &  < >)");
			form.objective.focus();
			return(false);
		}
	if(form.objective.value.length><?php echo $objective_len;?>)
		{
			alert("Additional Info must not be greater than <?php echo $headline_len;?> characters.");
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
             <?php if($id==0) {?>Additional Info<?php }
		  else
		  {?>
            <a href="additional_info.php?resume_id=<?php echo $id;?>">Additional Info</a>
            <?php }
		  ?>

            </font></td>
        </tr>
        <tr class="seperatorstyle"> 
          <td align="right">&nbsp; <font class="normal">
           
            <strong>Finishing Up</strong>
            </font></td>
        </tr>
      </table>
    </td>
    <td align="left"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
              <tr class="titlestyle"> 
                <td>&nbsp;Finishing Up</td></tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
                <?php
			if($complete<>"no")
			{
          ?>
              <tr valign="top"> 
                <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><font class="normal"><strong>Congratulations, Your Resume 
                        and Searchable information has been saved. Now you can 
                        Apply online through our site. </strong></font><br> </td>
                    </tr>
                    <tr> 
                      <td><font class="normal"> 
                        <ul>
                          <li>Currently your resume is 
                            <?php 
					if($hide=="yes") { echo "in confidential";}
					else { echo "not in confidential";}
					?>
                            state. Click <a href="javascript: help_popup('resume_builder','hide_info'); ">here</a> 
                            to know more about confidential state.</li>
                          <li>Click the following button to make your resume searchable. 
                            It will mark all other resume non searchable.</li>
                        </ul>
                        </font></td>
                    </tr>
                    <tr class="innertablestyle"> 
                      <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <td align="center"> <font class="normal"><strong> 
                          <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
                          </strong></font> <input name="search"  type="submit" value="Mark It Searchable"></td>
                      </form>
                    </tr>
                    <tr> 
                      <td height="25"><font class="normal"> 
                        <ul>
                          <li>Click following button to mark your resume as completed 
                            but not searchable.</li>
                        </ul>
                        </font></td>
                    </tr>
                    <tr class="innertablestyle"> 
                      <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <td align="center"> <font class="normal"><strong> 
                          <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
                          </strong></font> <input name="complete"  type="submit" value="Mark It Complete"></td>
                      </form>
                    </tr>
                    <tr> 
                      <td height="25"><font class="normal"> 
                        <ul>
                          <li>Click following button to mark your resume as incomplete.</li>
                        </ul>
                        </font></td>
                    </tr>
                    <tr class="innertablestyle"> 
                      <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <td align="center"> <font class="normal"><strong> 
                          <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
                          </strong></font> <input name="incomplete"  type="submit" value="Mark It Incomplete"></td>
                      </form>
                    </tr>
                  </table></td>
              </tr>
              <?php
		  }
		  else
		  {
          ?>
              <tr valign="top"> 
                <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr> 
                      <td><font class="normal"><strong>You have not entered all 
                        the mandatory information.</strong></font><br> </td>
                    </tr>
                    <tr> 
                      <td height="25"><font class="normal"> 
                        <ul>
                          <?php
					if($resume["sb_title"]=="")
					{ echo "<li>Click <a href='headline.php?resume_id=$id'>here</a> to 
					enter your headline and career objective</li>";}
 					if($resume["sb_firstname"]=="")
					{ echo "<li>Click <a href='contact_info.php?resume_id=$id'>here</a> to 
					enter your Contact Info</li>";}
  					if($resume["sb_target_job"]=="")
					{ echo "<li>Click <a href='job_preferences.php?resume_id=$id'>here</a> to 
					enter your Job Preferences</li>";}
                  
                    ?>
                          <li>Click following button to mark your resume as incomplete 
                            and exit.</li>
                        </ul>
                        </font></td>
                    </tr>
                    <tr class="innertablestyle"> 
                      <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <td align="center"> <font class="normal"><strong> 
                          <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
                          </strong></font> <input name="incomplete"  type="submit" value="Mark It Incomplete"></td>
                      </form>
                    </tr>
                  </table></td>
              </tr>
              <?
		  }// not completed
		  ?>
              <tr valign="top"> 
                <td align="right">&nbsp;</td>
              </tr>
              <tr valign="top"> 
                <td align="right"><table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="helpstyle">
                    <tr align="left"> 
                      <td width="97%" align="left"><strong>The followings are 
                        the mandatory steps of resume wizard, please be sure you 
                        have entered the required information.</strong></td>
                    </tr>
                    <tr align="left"> 
                      <td><ul >
                          <li><a href="headline.php?resume_id=<?php echo $id;?>">Career 
                            Objective</a></li>
                          <li><a href="contact_info.php?resume_id=<?php echo $id;?>">Contact 
                            Info</a></li>
                          <li><a href="job_preferences.php?resume_id=<?php echo $id;?>">Job 
                            Preferences</a></li>
                        </ul></td>
                    </tr>
                  </table></td>
              </tr>
              <tr valign="top"> 
                <td align="right">&nbsp;</td>
              </tr>
            </table></td>
		  </tr>
		</table>
    </td>
  </tr>
</table>
<?

}
include_once("template1.php");
?>