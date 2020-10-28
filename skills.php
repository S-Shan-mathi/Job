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
			$level=str_replace("$","\$",addslashes($_REQUEST["level"]));
			$work_exp=str_replace("$","\$",addslashes($_REQUEST["work_exp"]));
		}
		else
		{
			$level=str_replace("$","\$",$_REQUEST["level"]);
			$work_exp=str_replace("$","\$",$_REQUEST["work_exp"]);
		}

	$skill=(int)$_REQUEST["skill"];
	$month=(int)$_REQUEST["month1"];
	$year=(int)$_REQUEST["year1"];
	if($month==13)
	{ $year=date("Y",time()); }	
	
	if ( strlen(trim($skill)) == 0 )
	{
		$errs[$errcnt]="Skill must be choosen";
   		$errcnt++;
	}

	if ( strlen(trim($level)) == 0 )
	{
		$errs[$errcnt]="Level must be choosen";
   		$errcnt++;
	}
	if ( strlen(trim($work_exp)) == 0 )
	{
		$errs[$errcnt]="Skill Experience must be choosen";
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
	$skill_id=$_REQUEST["skill_id"];
	if(isset($_REQUEST["delete"])&&($_REQUEST["delete"]<>""))
	{
		if($skill_id<>0)
		{
		mysql_query("delete from sbjbs_resume_skills where sb_id=$skill_id"); 
		}
		header("Location:"."skills.php?resume_id=$resume_id");
		die();
	}//end delete
	
	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	mysql_query("update sbjbs_resumes set sb_approved='$approved' where sb_id=$resume_id");
	
	if($skill_id==0)
	{
	$query_str="insert into sbjbs_resume_skills (sb_skill_id,sb_level,sb_resume_id,sb_last_month,sb_last_year,sb_experience) 
	values($skill,'$level',$resume_id,$month,$year,'$work_exp')";
	}
	else
	{	
	$query_str="update `sbjbs_resume_skills` set 
	sb_skill_id=$skill,
	sb_level='$level',
	sb_last_month=$month,
	sb_last_year=$year,
	sb_experience='$work_exp'
	where sb_id=$skill_id";
	}
//echo $query_str;
//die();
	$rs_update=mysql_query($query_str);
	
	if(mysql_affected_rows()>0)
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: additional_info.php?resume_id=$resume_id&errmsg=".urlencode("Your professional experience has been saved."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: skills.php?resume_id=$resume_id&new=yes");
		die();
		}
	}
	else
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: skills.php?resume_id=$resume_id");
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: skills.php?resume_id=$resume_id&new=yes");
		die();
		}

	}
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
	$skill="";
	$level="";
	$month1=date("m",time());
	$year1=date("Y",time());
	$work_exp="";
	$skill_id=0;
	$pg=0;
	if(isset($_REQUEST["pg"])&&($_REQUEST["pg"]<>""))
	{$pg=$_REQUEST["pg"];}
	$new="no";
	if(isset($_REQUEST["new"])&&($_REQUEST["new"]<>""))
	{$new=$_REQUEST["new"];}
	
	$sql="select * from sbjbs_resume_skills where sb_resume_id=$id ";
	/*if($exp_id<>0)
	{ $sql.=" and sb_id=$exp_id";}*/
	$sql.=" order by sb_id desc";
	$skill_q=mysql_query($sql);
	$num_records=mysql_num_rows($skill_q);
	if(($pg>$num_records)||($pg<0))
	{ $pg=0;}
	
	if(($num_records>0)&&($new=="no"))
	{
	for($i=0;$i<$pg-1;$i++)
	{ $skill_rs=mysql_fetch_array($skill_q); }
	$skill_rs=mysql_fetch_array($skill_q);
	$skill=$skill_rs["sb_skill_id"];
	$level=$skill_rs["sb_level"];
	$work_exp=$skill_rs["sb_experience"];
	$month1=$skill_rs["sb_last_month"];
	$year1=$skill_rs["sb_last_year"];
	$skill_id=$skill_rs["sb_id"];
	}
}//end if resume
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
		
	if ( form.skill.value == "" ) 
	{
       alert('Please Choose a Skill!');
	   form.skill.focus();
	   return false;
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
          <td align="right">&nbsp; <font class="normal"><?php if($id==0) {?> Languages<?php }
		  else
		  {?>
            <a href="languages.php?resume_id=<?php echo $id;?>">Languages</a> 
            <?php }
		  ?> 
            </font></td>
        </tr>
        <tr class='seperatorstyle' > 
          <td align="right">&nbsp; <font class="normal"> 
            <strong>
            Skills </strong>
            
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
            <td >&nbsp;Skills</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="skill_id" type="hidden" id="skill_id" value="<?php echo $skill_id;?>">
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Skill</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font class="smalltext"> 
              <select name="skill" id="skill" >
                <option value="" selected >Select Skill</option>
                <? 
						  $state1=mysql_query("select * from sbjbs_skills order by sb_name");
						  while($rst= mysql_fetch_array($state1))
						  {
						  ?>
                <option value="<? echo $rst["sb_id"];?>" <? if($rst["sb_id"]==$skill) {echo " selected ";}?>><? echo $rst["sb_name"];?></option>
                <?
						   } // wend
              ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Skill 
              Level </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <SELECT id="level" name="level">
                <OPTION value="Beginners" <? if($level=="Beginners") 
			{echo " selected ";}?>>Beginners</OPTION>
                <OPTION value="Intermediate" <? if($level=="Intermediate") 
			{echo " selected ";}?>>Intermediate</OPTION>
                <OPTION value="Advance" <? if($level=="Advance") 
			{echo " selected ";}?>>Advance</OPTION>
                <OPTION value="Expert" <? if($level=="Expert") 
			{echo " selected ";}?>>Expert</OPTION>
              </SELECT>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Last 
              Used</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <select name="month1" id="select">
                <option value=13 <?php if($month1==13) echo "selected";?>>Present</option>
                <option value=1 <?php if($month1==1) echo "selected";?>>January</option>
                <option value=2 <?php if($month1==2) echo "selected";?>>February</option>
                <option value=3 <?php if($month1==3) echo "selected";?>>March</option>
                <option value=4 <?php if($month1==4) echo "selected";?>>April</option>
                <option value=5 <?php if($month1==5) echo "selected";?>>May</option>
                <option value=6 <?php if($month1==6) echo "selected";?>>June</option>
                <option value=7 <?php if($month1==7) echo "selected";?>>July</option>
                <option value=8 <?php if($month1==8) echo "selected";?>>August</option>
                <option value=9 <?php if($month1==9) echo "selected";?>>September</option>
                <option value=10 <?php if($month1==10) echo "selected";?>>October</option>
                <option value=11 <?php if($month1==11) echo "selected";?>>November</option>
                <option value=12 <?php if($month1==12) echo "selected";?>>December</option>
              </select>
              &nbsp; 
              <select name="year1" id="year1">
                <?php
			  for($i=1919;$i<=(date("Y",time())+5);$i++)
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
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Skill 
              Experience</strong></font></td>
            <td><font class="red">*</font></td>
            <td> <SELECT name=work_exp id="work_exp">
                
				<OPTION value="Less than 1 Year" <?php 
				if($work_exp=="Less than 1 Year") echo " selected";?>>Less 
                than 1 Year</OPTION>
                
				<OPTION value="1 to 2 Years"<?php 
				if($work_exp=="1 to 2 Years") echo " selected";?>>1 
                to 2 Years</OPTION>
				
                <OPTION value="2 to 5 Years"<?php 
				if($work_exp=="2 to 5 Years") echo " selected";?>>2 
                to 5 Years</OPTION>
                
				<OPTION value="5 to 7 Years"<?php 
				if($work_exp=="5 to 7 Years") echo " selected";?>>5 
                to 7 Years</OPTION>
				
                <OPTION value="7 to 10 Years"<?php 
				if($work_exp=="7 to 10 Years") echo " selected";?>>7 
                to 10 Years</OPTION>
				
                <OPTION value="More than 10 Years"<?php 
				if($work_exp=="More than 10 Years") echo " selected";?>>More 
                than 10 Years</OPTION>
				
              </SELECT> </td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="next"  type="submit" value="Save"> &nbsp; <input type="submit" name="new" value="Save &amp; Add New">
              <?php
			  if( $skill_id>0)
			  {
			  ?>
              <input type="submit" name="delete" value="Delete" onClick="return confirm('This will delete the current record. Do you want to continue');"> 
              <?php 
			  }
			  ?>
            </td>
          </tr>
          <?php
		  if($num_records>1)
		  {
          ?>
          <tr align="left" valign="top"> 
            <td colspan="3"> &nbsp;<font class="normal"><strong>Saved Skills</strong>&nbsp; 
              <?php
			if($pg<>0)
			{ $noclick=$pg;}
			elseif(isset($_REQUEST["new"])&&($_REQUEST["new"]=="yes"))
			{ $noclick=0;}
			else
			{ $noclick=1;}
			$skill_q=mysql_query("select * from sbjbs_resume_skills where sb_resume_id=$id order by sb_id desc");
			for($i=1;$i<=$num_records;$i++)
			{ 
			$skill=mysql_fetch_array($skill_q);
			
			$rs=mysql_fetch_array(mysql_query("select * from sbjbs_skills 
			where sb_id=".$skill["sb_skill_id"]));
			
			$title=$rs["sb_name"]." [".$skill["sb_level"]."]";

			if($i<>$noclick)
			{ echo "<a href='".$_SERVER['PHP_SELF']."?resume_id=$id&pg=$i' title='$title'>";}
			  echo "<b>".$i."</b>";
			if($i<>$noclick)
			{ echo "</a>";}
			  echo "&nbsp;&nbsp;";
			}
			?>
              </font></td>
          </tr>
          <?
		  }
          ?>
          <tr valign="top"> 
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr valign="top"> 
            <td colspan="3" align="right"><table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="helpstyle">
                <tr align="left"> 
                  <td width="97%" align="center"><strong>Make Your Resume Better</strong></td>
                </tr>
                <tr align="left"> 
                  <td valign="top"><ul >
                      <li> Please fill in the above fields to describe your skill 
                        set. Provide the information one by one. These will appear 
                        on your resume in chronological order starting with the 
                        most recent.</li>
                      <li>You can choose <strong>Present</strong> in last used 
                        if you are currently doing that.</li>
                      <li>To enter additional skills, click <strong>Save &amp; 
                        Add New</strong>. It will save current skill details and 
                        start a new form.</li>
                      <li>To edit saved skill details click the relevant record 
                        number in <strong>Saved Skills</strong>, it will <strong> 
                        </strong>reload the saved record for editing.</li>
                      <li>To delete a saved skill details click the relevant record 
                        number in <strong>Saved Skills</strong>, then click the 
                        <strong>Delete</strong> button.<br>
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
