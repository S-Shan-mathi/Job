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
			$school=str_replace("$","\$",addslashes($_REQUEST["school"]));
			$city=str_replace("$","\$",addslashes($_REQUEST["city"]));
			$state=str_replace("$","\$",addslashes($_REQUEST["state"]));
			$other_state=str_replace("$","\$",addslashes($_REQUEST["other_state"]));
			$degree=str_replace("$","\$",addslashes($_REQUEST["degree"]));
			$other_degree=str_replace("$","\$",addslashes($_REQUEST["other_degree"]));
			$edu_desc=str_replace("$","\$",addslashes($_REQUEST["edu_desc"]));
			}
		else
		{
			$school=str_replace("$","\$",$_REQUEST["school"]);
			$city=str_replace("$","\$",$_REQUEST["city"]);
			$state=str_replace("$","\$",$_REQUEST["state"]);
			$other_state=str_replace("$","\$",$_REQUEST["other_state"]);
			$degree=str_replace("$","\$",$_REQUEST["degree"]);
			$other_degree=str_replace("$","\$",$_REQUEST["other_degree"]);
			$edu_desc=str_replace("$","\$",$_REQUEST["edu_desc"]);
		}
		
	if($state=="")
	{ $state=$other_state; }
	if($degree=="")
	{ $degree=$other_degree; }

	$month=(int)$_REQUEST["month1"];
	$year=(int)$_REQUEST["year1"];
	if($month==13)
	{ $year=date("Y",time()); }
	
	$country=(int)$_REQUEST["country"];
	
	if ( strlen(trim($school)) == 0 )
	{
		$errs[$errcnt]="Institute must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["school"]))
	{
		$errs[$errcnt]="Institute can not have any special character (e.g. & ; < >)";
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
	
	
	if ( $_REQUEST["country"]== 0 )
	{
		$errs[$errcnt]="Country must be choosen";
   		$errcnt++;
	}
	
	if ( strlen(trim($degree)) == 0 )
	{
		$errs[$errcnt]="Degree must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $degree))
	{
		$errs[$errcnt]="Degree can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(preg_match ("/[;<>&]/", $edu_desc))
	{
		$errs[$errcnt]="Description can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	elseif(strlen(trim($edu_desc))>$objective_len )
	{
		$errs[$errcnt]="Description must be less than $objective_len characters.";
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
	$edu_id=$_REQUEST["edu_id"];
	if(isset($_REQUEST["delete"])&&($_REQUEST["delete"]<>""))
	{
		if($edu_id<>0)
		{
		mysql_query("delete from sbjbs_education where sb_id=$edu_id"); 
		}
		header("Location:"."education.php?resume_id=$resume_id");
		die();
	}//end delete

	$approved=$resume["sb_approved"];
	if(($approved=="yes"))
	{
		if($config["sb_resume_approval"]=="admin")
		{ $approved="updated";}
	}
	mysql_query("update sbjbs_resumes set sb_approved='$approved' where sb_id=$resume_id");

	if($edu_id==0)
	{
	$query_str="insert into sbjbs_education (sb_school,sb_city,sb_state,sb_country,sb_end_month,sb_end_year,sb_degree,sb_desc,sb_resume_id) 
	values('$school','$city','$state','$country','$month','$year','$degree','$edu_desc',$resume_id)";
	}
	else
	{	
	$query_str="update `sbjbs_education` set 
	sb_school='$school' ,
	sb_city='$city' , 
	sb_state='$state' , 
	sb_country='$country' , 
	sb_end_month='$month' , 
	sb_end_year='$year' , 
	sb_degree='$degree',
	sb_desc='$edu_desc'
	where sb_id=$edu_id";
	}
//echo $query_str;
//die();
	$rs_update=mysql_query($query_str);
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: affiliations.php?resume_id=$resume_id");
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: education.php?resume_id=$resume_id&new=yes");
		die();
		}
	
/*	if(mysql_affected_rows()>0)
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: affiliations.php?resume_id=$resume_id&errmsg=".urlencode("Your references have been saved."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: education.php?resume_id=$resume_id&new=yes");
		die();
		}
	}
	else
	{
		 if(isset($_REQUEST["next"]))
		 {
		header("Location: education.php?resume_id=$resume_id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
		}
		elseif(	isset($_REQUEST["new"]))	
		 {
		header("Location: education.php?resume_id=$resume_id&new=yes");
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
	$school="";
	$city="";
	$state="";
	$other_state="";
	$country=0;
	$degree="";
	$other_degree="";
	$month1=date("m",time());
	$year1=date("Y",time());
	$edu_desc="";
	$edu_id=0;
	$pg=0;
	if(isset($_REQUEST["pg"])&&($_REQUEST["pg"]<>""))
	{$pg=$_REQUEST["pg"];}
	$new="no";
	if(isset($_REQUEST["new"])&&($_REQUEST["new"]<>""))
	{$new=$_REQUEST["new"];}
	
	$sql="select * from sbjbs_education where sb_resume_id=$id ";
	/*if($exp_id<>0)
	{ $sql.=" and sb_id=$exp_id";}*/
	$sql.=" order by sb_id desc";
	$edu_q=mysql_query($sql);
	$num_records=mysql_num_rows($edu_q);
	if(($pg>$num_records)||($pg<0))
	{ $pg=0;}
	
	if(($num_records>0)&&($new=="no"))
	{
	for($i=0;$i<$pg-1;$i++)
	{ $edu=mysql_fetch_array($edu_q); }
	$edu=mysql_fetch_array($edu_q);
	$school=$edu["sb_school"];
	$city=$edu["sb_city"];
	$state=$edu["sb_state"];
	$other_state=$edu["sb_state"];
	$country=$edu["sb_country"];
	$degree=$edu["sb_degree"];
	$other_degree=$edu["sb_degree"];
	$month1=$edu["sb_end_month"];
	$year1=$edu["sb_end_year"];
	$edu_desc=$edu["sb_desc"];
	$edu_id=$edu["sb_id"];
	}
}//end if resume
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
			$school=$_REQUEST["school"];
			$city=$_REQUEST["city"];
			$state=$_REQUEST["state"];
			$other_state=$_REQUEST["other_state"];
			$degree=$_REQUEST["degree"];
			$other_degree=$_REQUEST["other_degree"];
			$edu_desc=$_REQUEST["edu_desc"];
			$month=$_REQUEST["month1"];
			$year=$_REQUEST["year1"];
			$country=$_REQUEST["country"];
			$edu_id=$_POST["edu_id"];
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
		
	if ( form.school.value == "" ) {
       	   alert('Please Specify Institute!');
		   form.school.focus();
	   return false;
	   }
	if(form.school.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Institute (e.g. &  < >)");
			form.school.focus();
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
if ( form.country.selectedIndex == 0 ) {
       	   alert('Please Choose a Country!');
		   form.country.focus();
	   return false;
	   }
	if ( (form.degree.selectedIndex == 0 ) && (form.other_degree.value == "") ) {
       	   alert('Please Specify Degree!');
		   form.degree.focus();
	   return false;
	   }
		if(form.other_degree.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Degree(e.g. &  < >)");
			form.other_degree.focus();
			return(false);
		}
	if(form.edu_desc.value.length><?php echo $objective_len;?>)
		{
			alert("Description must not be greater than <?php echo $objective_len;?> characters.");
			form.edu_desc.focus();
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
        <tr> 
          <td align="right">&nbsp; <font class="normal"> 
             <?php if($id==0) {?>
            References<?php }
		  else
		  {?>
            <a href="references.php?resume_id=<?php echo $id;?>">References</a> 
            <?php }
		  ?>
                       </font></td>
        </tr>
        <tr class='seperatorstyle'> 
          <td align="right">&nbsp; <font class="normal"> 
            <strong>
            Education 
            </strong> 
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
            <td >&nbsp;Education</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="edu_id" type="hidden" id="edu_id" value="<?php echo $edu_id;?>">
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Institute</strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="school" type="text" id="school"  value="<?php echo $school; ?>" size="30" maxlength="30">
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
                      <option value="<? echo $rst["sb_state"];?>" <? if($rst["sb_state"]==$state) {echo " selected ";}?>><? echo $rst["sb_state"];?></option>
                      <?
							   } // wend
							   ?>
                    </select> </td>
                </tr>
                <tr> 
                  <td><font class='normal'>Non US</font></td>
                  <td><input name="other_state" type="text" id="other_state" value="<?php echo $other_state;?>"></td>
                </tr>
              </table></td>
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
            <td align="right" class="innertablestyle"><font class="normal"><strong>Degree</strong></font></td>
            <td><font class="red">*</font></td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td colspan="2"> <select name="degree" >
                      <option value="" selected >Select Degree/Level Attained</option>
                      <? 
						  $degree_q=mysql_query("select * from sbjbs_degrees order by sb_degree");
						  while($rst= mysql_fetch_array($degree_q))
						  {
						  ?>
                      <option value="<? echo $rst["sb_degree"];?>" <? if($rst["sb_degree"]==$degree) {echo " selected ";}?>><? echo $rst["sb_degree"];?></option>
                      <?
							   } // wend
							   ?>
                    </select> </td>
                </tr>
                <tr> 
                  <td><font class='normal'>Others:</font></td>
                  <td width="100%">
<input name="other_degree" type="text" id="other_degree" value="<?php echo $other_degree;?>" size="45">
                  </td>
                </tr>
              </table>
              
            </td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Completion 
              Date </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <select name="month1" id="select">
                <!--	option value=13 <?php if($month1==13) echo "selected";?>>Present</option-->
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
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong> 
              Description </strong></font></td>
            <td>&nbsp;</td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <textarea name="edu_desc" cols="50" rows="5" id="edu_desc"><?php echo $edu_desc; ?></textarea>
              <font class="smalltext"> not more than <?php echo $objective_len;?> 
              characters</font> </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><input name="next"  type="submit" value="Save"> &nbsp; <input type="submit" name="new" value="Save &amp; Add New">
              <?php
			  if( $edu_id>0)
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
            <td colspan="3"> &nbsp;<font class="normal"><strong>Saved Education</strong> 
              &nbsp; 
              <?php
			if($pg<>0)
			{ $noclick=$pg;}
			elseif(isset($_REQUEST["new"])&&($_REQUEST["new"]=="yes"))
			{ $noclick=0;}
			else
			{ $noclick=1;}
			$edu_q=mysql_query("select * from sbjbs_education where sb_resume_id=$id order by sb_id desc");
			for($i=1;$i<=$num_records;$i++)
			{ 
			$edu=mysql_fetch_array($edu_q);
			$title=$edu["sb_degree"];
			$title.=" [".$edu["sb_end_month"].",".$edu["sb_end_year"]."]";
			$title.="\n".$edu["sb_school"]."\n".$edu["sb_city"];
		
			if($i<>$noclick)
			{ echo "<a href='".$_SERVER['PHP_SELF']."?resume_id=$id&pg=$i' title=\"$title\">";}
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
                  <td valign="top"> <ul >
                      <li> Please fill in the above fields to describe your educational 
                        qualifications. Provide the information one by one. These 
                        will appear on your resume in chronological order starting 
                        with the most recent.</li>
                      <!--li>You can choose <strong>Present</strong> in completion 
                        date if you are currently doing that.</li-->
                      <li>In Description please enter the information related 
                        to the education level like achievements,awards etc.</li>
                      <li>To enter additional education levels , click <strong>Save 
                        &amp; Add New</strong>. It will save education level you 
                        have just entered and start a new form.</li>
                      <li>To edit saved education level click the relevant record 
                        number in <strong>Saved Education</strong>, it will <strong> 
                        </strong>reload the saved record for editing.</li>
                      <li>To delete a saved education level click the relevant 
                        record number in <strong>Saved Education</strong>, then 
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
