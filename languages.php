<?
include_once "logincheck.php";
include_once("myconnect.php");

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
//		ob_start();
		if(!get_magic_quotes_gpc())
		{
			$proficiency=str_replace("$","\$",addslashes($_REQUEST["proficiency"]));
			}
		else
		{
			$proficiency=str_replace("$","\$",$_REQUEST["proficiency"]);
	}

	$language=(int)$_REQUEST["language"];
	
	if ( strlen(trim($language)) == 0 )
	{
		$errs[$errcnt]="Language must be choosen";
   		$errcnt++;
	}

	if ( strlen(trim($proficiency)) == 0 )
	{
		$errs[$errcnt]="Proficiency Level must be choosen";
   		$errcnt++;
	}
	
	if($errcnt==0)
	{
	$resume_id=$_REQUEST["resume_id"];
	$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$resume_id  
	and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
	if(!$resume)
	{
	header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("Invalid Access, Denied."));
	die();
	}
	$lang_id=$_REQUEST["lang_id"];
	if(isset($_REQUEST["delete"])&&($_REQUEST["delete"]<>""))
	{
		if($lang_id<>0)
		{
		mysql_query("delete from sbjbs_resume_language where sb_id=$lang_id"); 
		}
		header("Location:"."languages.php?resume_id=$resume_id");
		die();
	}//end delete

	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	mysql_query("update sbjbs_resumes set sb_approved='$approved' where sb_id=$resume_id");
	
	if($lang_id==0)
	{
	$query_str="insert into sbjbs_resume_language (sb_language_id,sb_proficiency,sb_resume_id) 
	values($language,'$proficiency',$resume_id)";
	}
	else
	{	
	$query_str="update `sbjbs_resume_language` set 
	sb_language_id=$language,
	sb_proficiency='$proficiency'
	where sb_id=$lang_id";
	}
//echo $query_str;
//die();
	$rs_update=mysql_query($query_str);
	
	if(mysql_affected_rows()>0)
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: skills.php?resume_id=$resume_id&errmsg=".urlencode("Your professional experience has been saved."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: languages.php?resume_id=$resume_id&new=yes");
		die();
		}
	}
	else
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: languages.php?resume_id=$resume_id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: languages.php?resume_id=$resume_id&new=yes");
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
	$language="";
	$proficiency="";
	$lang_id=0;
	$pg=0;
	if(isset($_REQUEST["pg"])&&($_REQUEST["pg"]<>""))
	{$pg=$_REQUEST["pg"];}
	$new="no";
	if(isset($_REQUEST["new"])&&($_REQUEST["new"]<>""))
	{$new=$_REQUEST["new"];}
	
	$sql="select * from sbjbs_resume_language where sb_resume_id=$id ";
	/*if($exp_id<>0)
	{ $sql.=" and sb_id=$exp_id";}*/
	$sql.=" order by sb_id desc";
	$lang_q=mysql_query($sql);
	$num_records=mysql_num_rows($lang_q);
	if(($pg>$num_records)||($pg<0))
	{ $pg=0;}
	
	if(($num_records>0)&&($new=="no"))
	{
	for($i=0;$i<$pg-1;$i++)
	{ $lang=mysql_fetch_array($lang_q); }
	$lang=mysql_fetch_array($lang_q);
	$language=$lang["sb_language_id"];
	$proficiency=$lang["sb_proficiency"];
	$lang_id=$lang["sb_id"];
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
		
	if ( form.language.value == "" ) {
       	   alert('Please Choose a Language!');
		   form.language.focus();
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
            <?php 
		if($id==0) {?>
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
        <tr class='seperatorstyle' > 
          <td align="right">&nbsp; <font class="normal"> <strong>Languages</strong> 
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
            <td >&nbsp;Languages</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="lang_id" type="hidden" id="lang_id" value="<?php echo $lang_id;?>">
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Language</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font class="smalltext"> 
              <select name="language" >
                <option value="" selected >Select Language</option>
                <? 
						  $state1=mysql_query("select * from sbjbs_languages order by sb_name");
						  while($rst= mysql_fetch_array($state1))
						  {
						  ?>
                <option value="<? echo $rst["sb_id"];?>" <? if($rst["sb_id"]==$language) {echo " selected ";}?>><? echo $rst["sb_name"];?></option>
                <?
							   } // wend
							   ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Proficiency 
              </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <SELECT id="proficiency" name="proficiency">
                <OPTION value="Basic - Familiar" <? if($proficiency=="Basic - Familiar") 
			{echo " selected ";}?>>Basic - Familiar</OPTION>
                <OPTION value="Conversational - Limited" <? if($proficiency=="Conversational - Limited") 
			{echo " selected ";}?>>Conversational - Limited</OPTION>
                <OPTION value="Conversational" <? if($proficiency=="Conversational") 
			{echo " selected ";}?>>Conversational</OPTION>
                <OPTION value="Conversational - Advanced" <? 
			if($proficiency=="Conversational - Advanced") 
			{echo " selected ";}?>>Conversational - Advanced</OPTION>
                <OPTION value="Fluent - Wide Knowledge" <? if($proficiency=="Fluent - Wide Knowledge") 
			{echo " selected ";}?>>Fluent - Wide Knowledge</OPTION>
                <OPTION value="Fluent - Full Knowledge" <? if($proficiency=="Fluent - Full Knowledge") 
			{echo " selected ";}?>>Fluent - Full Knowledge</OPTION>
              </SELECT>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="next"  type="submit" value="Save"> &nbsp; <input type="submit" name="new" value="Save &amp; Add New">
              <?php
			  if( $lang_id>0)
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
            <td colspan="3"> &nbsp;<font class="normal"><strong>Saved Languages</strong> 
              &nbsp; 
              <?php
			if($pg<>0)
			{ $noclick=$pg;}
			elseif(isset($_REQUEST["new"])&&($_REQUEST["new"]=="yes"))
			{ $noclick=0;}
			else
			{ $noclick=1;}
			$lang_q=mysql_query("select * from sbjbs_resume_language where sb_resume_id=$id order by sb_id desc");
			for($i=1;$i<=$num_records;$i++)
			{ 
			$lang=mysql_fetch_array($lang_q);
			
			$rs=mysql_fetch_array(mysql_query("select * from sbjbs_languages 
			where sb_id=".$lang["sb_language_id"]));
			
			$title=$rs["sb_name"]." [".$lang["sb_proficiency"]."]";

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
                      <li>To enter languages, click <strong>Save &amp; Add New</strong>. 
                        It will save language details you have just entered and 
                        start a new form.</li>
                      <li>To edit saved language details click the relevant record 
                        number in <strong>Saved Languages</strong>, it will <strong> 
                        </strong>reload the saved record for editing.</li>
                      <li>To delete a saved language details click the relevant 
                        record number in <strong>Saved Languages,</strong> then 
                        click the <strong>Delete</strong> button.<br>
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
