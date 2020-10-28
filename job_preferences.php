<?
include_once "logincheck.php";
include_once("myconnect.php");

$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$sb_cat_listing=$config["sb_cat_listing"];

/*$resume_cnt=$config["sb_resume_cnt"];
$total_resume=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if($total_resume>=$resume_cnt)
{
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("You have already posted the maximum number of resumes allowed."));
die();
}
*/
$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
//		ob_start();

		if(!get_magic_quotes_gpc())
		{
			$target_job=str_replace("$","\$",addslashes($_REQUEST["target_job"]));
			$alt_job=str_replace("$","\$",addslashes($_REQUEST["alt_job"]));
			$salary=str_replace("$","\$",addslashes($_REQUEST["salary"]));			
		}
		else
		{
			$target_job=str_replace("$","\$",$_REQUEST["target_job"]);
			$alt_job=str_replace("$","\$",$_REQUEST["alt_job"]);
			$salary=str_replace("$","\$",$_REQUEST["salary"]);
		}

			$job_type=0;
			if(isset($_REQUEST["permanent"]))
			{ $job_type=$job_type|$_REQUEST["permanent"];}
			if(isset($_REQUEST["intern"]))
			{ $job_type=$job_type|$_REQUEST["intern"];}
			if(isset($_REQUEST["contract"]))
			{ $job_type=$job_type|$_REQUEST["contract"];}
			
			$job_status=0;
			if(isset($_REQUEST["fulltime"]))
			{ $job_status=$job_status|$_REQUEST["fulltime"];}
			if(isset($_REQUEST["parttime"]))
			{ $job_status=$job_status|$_REQUEST["parttime"];}
			
			$relocate=(int)$_REQUEST["relocate"];
			$salary_currency=(int)$_REQUEST["salary_currency"];
			$target_comp_size=(int)$_REQUEST["company_size"];
			$salary_type=(int)$_REQUEST["salary_type"];
			$will_to_travel=(int)$_REQUEST["willtotravel"];
			$cid_list=str_replace(";",",",$_REQUEST["cid_list"]);
	$cat=explode(",",$cid_list);
			$loc_id_list=str_replace(";",",",$_REQUEST["loc_id"]);			
	$location=explode(",",$loc_id_list);

	if ( strlen(trim($target_job)) == 0 )
	{
		$errs[$errcnt]="Target Job must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["target_job"]))
	{
		$errs[$errcnt]="Target Job can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( strlen(trim($alt_job)) == 0 )
	{
		$errs[$errcnt]="Alternate Job must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["alt_job"]))
	{
		$errs[$errcnt]="Alternate Job can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( $job_type == 0 )
	{
		$errs[$errcnt]="Desired Job Type must be provided";
   		$errcnt++;
	}
	if ( $job_status == 0 )
	{
		$errs[$errcnt]="Desired Status must be provided";
   		$errcnt++;
	}
	if ( ($salary < 0)||(!is_numeric($salary)))
	{
		$errs[$errcnt]="Desired Salary must be an numeric positive value";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["salary"]))
	{
		$errs[$errcnt]="Desired Salary can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	
	if ( ($_REQUEST["salary_currency"]== "")&&($salary>0) )
	{
		$errs[$errcnt]="Salary Currency must be choosen";
   		$errcnt++;
	}
	
	if ( ($_REQUEST["salary_type"]== "")&&($salary>0) )
	{
		$errs[$errcnt]="Salary Type must be choosen";
   		$errcnt++;
	}
	if ( $cid_list== "" )
	{
		$errs[$errcnt]="Atleast one Company Category must be choosen";
   		$errcnt++;
	}
	if ( $loc_id_list== "" )
	{
		$errs[$errcnt]="Atleast one Target Location must be choosen";
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

	$query_update="update `sbjbs_resumes` set 
	sb_target_job='$target_job' ,
	sb_alt_job='$alt_job' , 
	sb_job_type='$job_type' , 
	sb_job_status='$job_status' , 
	sb_salary=$salary , 
	sb_salary_type=$salary_type , 
	sb_salary_currency='$salary_currency', 
	sb_target_comp_size='$target_comp_size', 
	sb_will_to_travel=$will_to_travel, 
	sb_relocate='$relocate',
	sb_approved='$approved'
	where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	$rs_update=mysql_query($query_update);
	
	mysql_query("delete from sbjbs_resume_cats where sb_resume_id=$id");
	foreach($cat as $sb_value)
	{
		$resume_cat_q="select * from sbjbs_resume_cats where sb_resume_id=$id and sb_cid=$sb_value";
		if( mysql_num_rows(mysql_query($resume_cat_q)) > 0 )
		continue;		//skips if record already exists
		$resume_cat_i="insert into sbjbs_resume_cats (sb_resume_id, sb_cid) values ($id, $sb_value)";
		mysql_query($resume_cat_i);
	}

	mysql_query("delete from sbjbs_resume_locs where sb_resume_id=$id");
	foreach($location as $sb_value)
	{
		$resume_loc_q="select * from sbjbs_resume_locs where sb_resume_id=$id and sb_loc_id=$sb_value";
		if( mysql_num_rows(mysql_query($resume_loc_q)) > 0 )
		continue;		//skips if record already exists
		$resume_loc_i="insert into sbjbs_resume_locs (sb_resume_id, sb_loc_id) values ($id, $sb_value)";
		mysql_query($resume_loc_i);
	}
	header("Location: experience.php?resume_id=$id");
	die();

/*	if(mysql_affected_rows()>0)
	{
		header("Location: experience.php?resume_id=$id&errmsg=".urlencode("Your job preferences have been saved."));
		die();
	}
	else
	{
		header("Location: job_preferences.php?resume_id=$id&errmsg=".urlencode("Sorry, no updations carried out."));
		die();
	}*/
 }			//end if-errcnt==0
}			//end if count-post


function main()
{
	global $errs, $errcnt, $sb_cat_listing;

	$id=0;
	if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
	{
		$id=$_REQUEST["resume_id"];
	}

$resume=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
if ( $resume )
{
$target_job=$resume["sb_target_job"];
$alt_job=$resume["sb_alt_job"];
$job_type=$resume["sb_job_type"];
$job_status=$resume["sb_job_status"];
$salary=$resume["sb_salary"];
$salary_type=$resume["sb_salary_type"];
$salary_currency=$resume["sb_salary_currency"];

$target_comp_size=$resume["sb_target_comp_size"];
$relocate=$resume["sb_relocate"];
$will_to_travel=$resume["sb_will_to_travel"];
$join_time=$resume["sb_availability_time"];


	$resume_cat_q="select * from sbjbs_resume_cats where sb_resume_id=".$resume["sb_id"];
	$resume_cat_q=mysql_query($resume_cat_q);

$cat_list="";
$cid_list="";
while($resume_cat=mysql_fetch_array($resume_cat_q))
{
//	$cat_id=$rs["cat".$i];
	$rs_t=mysql_query("Select * from sbjbs_categories  where sb_id =".$resume_cat["sb_cid"]);
	if ($rs_t=mysql_fetch_array($rs_t))
	{
		  $cat_path=$rs_t["sb_cat_name"];
 		  $par=mysql_query("select * from sbjbs_categories where sb_id=".$rs_t["sb_pid"]);
		  while($parent=mysql_fetch_array($par))
		  {
			$cat_path=$parent["sb_cat_name"]."-".$cat_path;
			$par=mysql_query("select * from sbjbs_categories where sb_id=".$parent["sb_pid"]);
			}
		if($cat_list=="")
		{

		$cat_list=$cat_path;
		$cid_list=$rs_t["sb_id"];
		}
		else
		{
		$cat_list.=";".$cat_path;
		$cid_list.=";".$rs_t["sb_id"];
		}
	}
}
	$resume_loc_q="select * from sbjbs_resume_locs where sb_resume_id=".$resume["sb_id"];
	$resume_loc_q=mysql_query($resume_loc_q);
$loc_list="";
$loc_id_list="";
while($resume_loc=mysql_fetch_array($resume_loc_q))
{
//	$cat_id=$rs["cat".$i];
	$rs_t=mysql_query("Select * from sbjbs_locations  where sb_id =".$resume_loc["sb_loc_id"]);
	if ($rs_t=mysql_fetch_array($rs_t))
	{
		  $loc_path=$rs_t["sb_loc_name"];
 		  $par=mysql_query("select * from sbjbs_locations where sb_id=".$rs_t["sb_pid"]);
		  while($parent=mysql_fetch_array($par))
		  {
			$loc_path=$parent["sb_loc_name"]."-".$loc_path;
			$par=mysql_query("select * from sbjbs_locations where sb_id=".$parent["sb_pid"]);
			}
		if($loc_list=="")
		{

		$loc_list=$loc_path;
		$loc_id_list=$rs_t["sb_id"];
		}
		else
		{
		$loc_list.=";".$loc_path;
		$loc_id_list.=";".$rs_t["sb_id"];
		}
	}
}
}
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>resume Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
if  (count($_POST)>0)
{
			$target_job=$_POST["target_job"];
			$alt_job=$_POST["alt_job"];
		
			$job_type=0;
			if(isset($_REQUEST["permanent"]))
			{ $job_type=$job_type|$_REQUEST["permanent"];}
			if(isset($_REQUEST["intern"]))
			{ $job_type=$job_type|$_REQUEST["intern"];}
			if(isset($_REQUEST["contract"]))
			{ $job_type=$job_type|$_REQUEST["contract"];}
			
			$job_status=0;
			if(isset($_REQUEST["fulltime"]))
			{ $job_status=$job_status|$_REQUEST["fulltime"];}
			if(isset($_REQUEST["parttime"]))
			{ $job_status=$job_status|$_REQUEST["parttime"];}
			
			$target_comp_size=$_REQUEST["company_size"];
			$will_to_travel=$_REQUEST["willtotravel"];
			$cat_list=$_POST["category"];
			$cid_list=$_REQUEST["cid_list"];
			$loc_id_list=$_REQUEST["loc_id"];
			$loc_list=$_REQUEST["location"];			
		
			$salary=$_POST["salary"];
			$salary_type=$_POST["salary_type"];
			$salary_currency=$_POST["salary_currency"];
	
			$relocate=$_POST["relocate"];
	
	

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
					function add_category()
					{
					if(document.form123.cats.value!=0)
					{
					var id=document.form123.cats.selectedIndex;
					var cid_list=form123.cid_list.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form123.cats.value)
						{ posted="yes";}
						cnt++;
					}
					if(posted=="yes")
					{
						alert('This company is already in the list');
						return false;
					}
					if(document.form123.category.value=="")
					{
					document.form123.cid_list.value=document.form123.cats.value;
					document.form123.category.value=document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					else
					{
					document.form123.cid_list.value=document.form123.cid_list.value+";"+document.form123.cats.value;
					document.form123.category.value=document.form123.category.value+";"+document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Category to add!');
					}
					}
					
					function remove_category()
					{
					var s1=window.document.form123.category.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form123.cats.selectedIndex;
					var s3=document.form123.cats.options[id].text;
					
					var id_list=document.form123.cid_list.value;
					var id_split=id_list.split(";");
					var rem_id=document.form123.cats.value;
										
					window.document.form123.category.value="";
					window.document.form123.cid_list.value="";
					
					while(i<s2.length)
					{
					//alert('Checking '+s2[i]+' nnn  with'+s3+' mm');
						if(s3==s2[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.category.value=="")
							{
							document.form123.category.value=s2[i];
							}
							else
							{
							document.form123.category.value=document.form123.category.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.cid_list.value=="")
							{
							document.form123.cid_list.value=id_split[i];
							}
							else
							{
							document.form123.cid_list.value=document.form123.cid_list.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form123.related.value="";
					//window.document.form123.rel_id.value="";
					}

					function add_location()
					{
					if(document.form123.locs.value!=0)
					{
					var id=document.form123.locs.selectedIndex;
					var cid_list=form123.loc_id.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form123.locs.value)
						{ posted="yes";}
						cnt++;
					}
					if(posted=="yes")
					{
						alert('This location is already in the list');
						return false;
					}
					if(document.form123.location.value=="")
					{
					document.form123.loc_id.value=document.form123.locs.value;
					document.form123.location.value=document.form123.locs.options[id].text;
					document.form123.location.focus();
					document.form123.locs.selectedIndex=0;
					}
					else
					{
					document.form123.loc_id.value=document.form123.loc_id.value+";"+document.form123.locs.value;
					document.form123.location.value=document.form123.location.value+";"+document.form123.locs.options[id].text;
					document.form123.location.focus();
					document.form123.locs.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Location to add!');
					}
					}
					
					function remove_location()
					{
					var s1=window.document.form123.location.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form123.locs.selectedIndex;
					var s3=document.form123.locs.options[id].text;
					
					var id_list=document.form123.loc_id.value;
					var id_split=id_list.split(";");
					var rem_id=document.form123.locs.value;
										
					window.document.form123.location.value="";
					window.document.form123.loc_id.value="";
					
					while(i<s2.length)
					{
					//alert('Checking '+s2[i]+' nnn  with'+s3+' mm');
						if(s3==s2[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.location.value=="")
							{
							document.form123.location.value=s2[i];
							}
							else
							{
							document.form123.location.value=document.form123.location.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.loc_id.value=="")
							{
							document.form123.loc_id.value=id_split[i];
							}
							else
							{
							document.form123.loc_id.value=document.form123.loc_id.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form123.related.value="";
					//window.document.form123.rel_id.value="";
					}

  function validate(form) 
  {
		
	if ( form.target_job.value == "" ) {
       	   alert('Please Specify Target Job Title!');
		   form.target_job.focus();
	   return false;
	   }
	if(form.target_job.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Target Job Title (e.g. &  < >)");
			form.target_job.focus();
			return(false);
		}
	if ( form.alt_job.value == "" ) {
       	   alert('Please Specify Alternate Job Title!');
		   form.alt_job.focus();
	   return false;
	   }
	if(form.alt_job.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Alternate Job Title (e.g. &  < >)");
			form.alt_job.focus();
			return(false);
		}

	if(form.permanent.checked==false && form.intern.checked==false && form.contract.checked==false ) 
	{
       	   alert('Please Specify Atleast One Job Type!');
		   form.permanent.focus();
		   return false;
	   }
	   
	if ( form.fulltime.checked==false && form.parttime.checked==false ) 
	{
       	   alert('Please Specify Atleast One Desired Status!');
		   form.fulltime.focus();
		   return false;
	   }

	if (isNaN(form.salary.value)||(form.salary.value<0)) 
	{
       	   	alert('Please Specify a Positive Numeric Value for Desired Salary!');
		   	form.salary.focus();
	   		return false;
	}
	if(form.salary.value>0)
	{   
	if ( form.salary_currency.selectedIndex == 0 ) {
       	   alert('Please Choose Currency for Desired Salary!');
		   form.salary_currency.focus();
	   return false;
	   }
	if ( form.salary_type.selectedIndex == 0 ) {
       	   alert('Please Choose Desired Salary Type!');
		   form.salary_type.focus();
	   return false;
	   }
	 }  
	   
		if ( form.cid_list.value == "" ) {
       	   alert('Please Choose Company Category!');
		   form.cats.focus();
	   return false;
	   }
		if ( form.loc_id.value == "" ) {
       	   alert('Please Choose Target Location!');
		   form.locs.focus();
	   return false;
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
          <td align="right"><font class="normal">
            <?php
		 if($id==0) {?>
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
        <tr class='seperatorstyle'> 
          <td align="right">&nbsp; <font class="normal"><strong> 
            Job Preferences 
            </strong></font></td>
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
            <a href="finish.php?resume_id=<?php echo $id;?>">Finishing 
            Up</a> 
            <?php }
		  ?>
            </font></td>
        </tr>
      </table></td>
    <td align="left"> 
      <form name="form123" method="post" action="<? echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td>&nbsp;Job Preferences</td>
          </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong> 
              <input name="resume_id" type="hidden" id="resume_id" value="<? echo $id;?>">
              Target Job Title<br>
              <a href="javascript: help_popup('resume_builder','jobtitle');" class="help_style"><?php echo HELP_LINK;?></a> 
              </strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="target_job" type="text" id="target_job"  value="<?php echo $target_job; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Alternate 
              Job Title</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font face="Arial, Helvetica, sans-serif" size="2"> 
              <input name="alt_job" type="text" id="alt_job"  value="<?php echo $alt_job  ; ?>" size="30" maxlength="30">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Desired 
              Job Type</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <INPUT type=checkbox value=1 name=permanent <?php 
			  if(($job_type&1)==1) echo "checked";?>>
              Permanent <br>
              <input type=checkbox value=2 name=intern <?php 
			  if(($job_type&2)==2) echo "checked";?>>
              Intern / Work Experience<br>
              <INPUT type=checkbox value=4 name=contract <?php 
			  if(($job_type&4)==4) echo "checked";?>>
              Temporary/Contract/Project</font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Desired 
              Status </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <INPUT type=checkbox value=1 name=fulltime  <?php 
			  if(($job_status&1)==1) echo "checked";?>>
              Full Time &nbsp; 
              <INPUT type=checkbox value=2 name=parttime <?php 
			  if(($job_status&2)==2) echo "checked";?>>
              Part Time </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Desired 
              Salary<br>
              <a href="javascript: help_popup('resume_builder','salary');" class="help_style"><?php echo HELP_LINK;?></a> 
              </strong></font></td>
            <td>&nbsp;</td>
            <td><INPUT name="salary" value="<? echo $salary;?>" size=15 maxLength=9> 
              &nbsp;
              <select name="salary_currency">
                <option value="">-</option>
                <?php
					$cur_q=mysql_query("select * from sbjbs_currencies order by sbcur_name");	
					while($cur=mysql_fetch_array($cur_q))
					{
				  ?>
                <option value="<?php echo $cur["sbcur_id"];?>" <?php 
			  if($salary_currency==$cur["sbcur_id"]) echo "selected";?>>
                <?php 
				  echo $cur["sbcur_name"];?>
                </option>
                <?php 
				  	}
				  ?>
              </select> &nbsp;
              <SELECT name="salary_type">
                <option value="">-</option>
                <OPTION value=1 <?php 
			  if($salary_type==1) echo "selected";?>>Per Year</OPTION>
                <OPTION value=2 <?php 
			  if($salary_type==2) echo "selected";?>>Per Month</OPTION>
                <OPTION value=3 <?php 
			  if($salary_type==3) echo "selected";?>>Per Week</OPTION>
                <OPTION value=4 <?php 
			  if($salary_type==4) echo "selected";?>>Fortnightly</OPTION>
                <OPTION value=5 <?php 
			  if($salary_type==5) echo "selected";?>>Per Hour</OPTION>
              </SELECT></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Target 
              Company Size</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext">
              <SELECT name="company_size">
                <OPTION value=1  <?php 
			  if($target_comp_size==1) echo "selected";?>>No Preference</OPTION>
                <OPTION value=2 <?php 
			  if($target_comp_size==2) echo "selected";?>>Small Scale(1 - 99)</OPTION>
                <OPTION value=3 <?php 
			  if($target_comp_size==3) echo "selected";?>>Medium Scale(100 - 999)</OPTION>
                <OPTION value=4 <?php 
			  if($target_comp_size==4) echo "selected";?>>Large Scale(1000+)</OPTION>
              </SELECT>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Target 
              Company<br>
              <a href="javascript: help_popup('resume_builder','choose_category');" class="help_style"><?php echo HELP_LINK;?></a> 
              </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <textarea name="category" cols="37" rows="5" readonly="readonly" id="category"><? echo $cat_list;?></textarea>
              <br>
              <select name="cats" id="select2" >
                <option value="0">Choose a category</option>
                <?
	$sbcat_arr=array();
	$sbord_arr=array();
	$rs_query=mysql_query("select * from sbjbs_categories order by sb_pid");
	while($rst=mysql_fetch_array($rs_query))
	{
		$cat_path="";
		$child=mysql_fetch_array(mysql_query("select * from sbjbs_categories where sb_pid=".$rst["sb_id"]));
		if($child)
			continue;
		$cat_path.=$rst["sb_cat_name"];
		$par=mysql_query("select * from sbjbs_categories where sb_id=".$rst["sb_pid"]);
		while($parent=mysql_fetch_array($par))
		{
			$cat_path=$parent["sb_cat_name"]."-".$cat_path;
			$par=mysql_query("select * from sbjbs_categories where sb_id=".$parent["sb_pid"]);
		}
		$sbcat_arr[$rst["sb_id"]]=$cat_path;
		$sbord_arr[$rst["sb_id"]]=$rst["sb_order_index"];
	}
	if($sb_cat_listing=='admin')
	{
		asort($sbord_arr);
		foreach($sbord_arr as $sbkey => $sbval)
		{
			echo '<option value="'.$sbkey.'"';
		//	echo($sbkey==$cid)?'selected':'';
			echo ' >'.$sbcat_arr[$sbkey].'</option>';
		}
	}
	else
	{
		asort($sbcat_arr);
		foreach($sbcat_arr as $sbkey => $sbval)
		{
			echo '<option value="'.$sbkey.'"';
		//	echo($sbkey==$cid)?'selected':'';
			echo ' >'.$sbval.'</option>';
		}
	}					  
									  ?>
              </select>
              <input name="cid_list" type="hidden" id="cid_list" value="<? echo $cid_list;?>">
              <input name="add" type="button" id="add" value="Add" onClick="add_category()">
              <input name="Remove" type="button" id="Remove" value="Remove" onClick="remove_category()">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Target 
              Location </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="smalltext"> 
              <textarea name="location" cols="37" rows="5" readonly="readonly" id="location"><? echo $loc_list;?></textarea>
              <br>
              <select name="locs" id="locs" >
                <option value="0">Choose a location</option>
                <?
				  $rs_query=mysql_query("select * from sbjbs_locations order by sb_pid");
				  
				  while($rst=mysql_fetch_array($rs_query))
				  {
				  $cat_path="";
		/*$child=mysql_fetch_array(mysql_query("select * from sbjbs_locations where sb_pid=".$rst["sb_id"]));
									  if($child)
									  {
									  continue;
									  }*/
									  $cat_path.=$rst["sb_loc_name"];
		 $par=mysql_query("select * from sbjbs_locations where sb_id=".$rst["sb_pid"]);
		 				while($parent=mysql_fetch_array($par))
		 				{
						$cat_path=$parent["sb_loc_name"]."-".$cat_path;
						$par=mysql_query("select * from sbjbs_locations where sb_id=".$parent["sb_pid"]);
						}
                                      ?>
                <option value="<? echo $rst["sb_id"];?>" ><? echo $cat_path;?></option>
                <?
									  }
									  ?>
              </select>
              <input name="loc_id" type="hidden" id="loc_id" value="<? echo $loc_id_list;?>">
              <input name="add2" type="button" id="add2" value="Add" onClick="add_location()">
              <input name="Remove2" type="button" id="Remove2" value="Remove" onClick="remove_location()">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Willing 
              to Relocate</strong></font></td>
            <td>&nbsp;</td>
            <td><font class="smalltext"> 
              <INPUT type=radio value=0 name="relocate" checked>
              No &nbsp;
              <INPUT type=radio value=1 name="relocate" <?php if($relocate==1) echo " checked";?>>
              Yes </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Will 
              to Travel</strong></font></td>
            <td>&nbsp;</td>
            <td> <SELECT id="willtotravel" name="willtotravel">
                <OPTION value=1 <?php if($will_to_travel==1) echo " selected";?>>Never</OPTION>
                <OPTION value=2 <?php if($will_to_travel==2) echo " selected";?>>Up 
                to 25% travel</OPTION>
                <OPTION value=3 <?php if($will_to_travel==3) echo " selected";?>>Up 
                to 50% travel</OPTION>
                <OPTION value=4 <?php if($will_to_travel==4) echo " selected";?>>Up 
                to 75% travel</OPTION>
                <OPTION value=5 <?php if($will_to_travel==5) echo " selected";?>>Up 
                to 100%</OPTION>
              </SELECT></td>
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