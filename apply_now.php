<?php
include_once("logincheck.php");
include_once("myconnect.php");

$sb_first_time=false;		//used to select first radio button
$showform="";

	$sbq_res1="select * from sbjbs_resumes where sb_approved='yes' and sb_seeker_id=".$_SESSION["sbjbs_userid"];
	//echo $sbq_res;
	$sbrs_res1=mysql_query($sbq_res1);
	if(mysql_num_rows($sbrs_res1) < 1)
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("You must have atleast one approved resume to apply for a job."));
		die();
	}
	

	if(!isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) || ($_REQUEST["sb_id"] < 1))
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("Invalid Id, cannot proceed."));
		die();
	}
	$sb_id=$_REQUEST["sb_id"];
	
/////-----------
	$suspended_list="-1";
	$mem_q=mysql_query("select * from sbjbs_employers where sb_suspended='yes'");
	while($mem=mysql_fetch_array($mem_q))
	{ $suspended_list.=",".$mem["sb_id"];}
	
	$disapproved_list="-1";
	$comp_q=mysql_query("select * from sbjbs_companies where 
	(sb_approved='no' OR sb_uid in ($suspended_list))");
	
	while($comp=mysql_fetch_array($comp_q))
	{ $disapproved_list.=",".$comp["sb_id"];}
	

///////---------	

	$sbq_job="select * from sbjbs_jobs where sb_id=$sb_id and sb_approved='yes' and sb_company_id not in ($disapproved_list)";
	$sbrow_job=mysql_fetch_array(mysql_query($sbq_job));
	if(!$sbrow_job)
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("No such job exists or disabled by admin."));
		die();
	}
	$sb_title=$sbrow_job['sb_title'];
//-getting education
	$sb_education_str='';
	if($sbrow_job['sb_education']!=0)
	{
		$sbq_edu="select * from sbjbs_degrees where sb_id=".$sbrow_job['sb_education'];
		$sbrow_edu=mysql_fetch_array(mysql_query($sbq_edu));
		$sb_education_str=$sbrow_edu["sb_degree"];
	}
//--getting company info
	$sbq_com="select * from sbjbs_companies where sb_id=".$sbrow_job['sb_company_id'];
	$sbrow_com=mysql_fetch_array(mysql_query($sbq_com));
	$sb_company_str=$sbrow_com["sb_name"];
	
	$sbq_con="select * from sbjbs_country where id=".$sbrow_com['sb_country'];
	$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
	$sb_company_str.=", ".$sbrow_con["country"];

//--checking whether already submitted----------
	$sbq_app_chk="select * from `sbjbs_applications` where sb_job_id=$sb_id and sb_seeker_id=".$_SESSION["sbjbs_userid"];
//	echo $sbq_app_chk;
	$sbrs_app_chk=mysql_query($sbq_app_chk);
	if(mysql_num_rows($sbrs_app_chk) > 0)
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("You have already applied for the job."));
		die();
	}


///////----------------------------------------
$sbq_con="select * from sbjbs_config where sb_id=1";
$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));


$errcnt=0;
if( isset($_POST["submit"]) && ($_POST["submit"]=='Apply') )		//IF SOME FORM WAS POSTED DO VALIDATION
{
///////getting into variables for returning back to counter any type of error after initializing////////
	$sb_resume='';
	$sb_cover_letter='';
	$sb_cover_letter_id='0';
	
	if(isset($_REQUEST["sb_resume"]))
		$sb_resume=$_REQUEST["sb_resume"];
	if(isset($_REQUEST["sb_cover_letter"]))
		$sb_cover_letter=$_REQUEST["sb_cover_letter"];
	if(isset($_REQUEST["sb_cover_letter_id"]))
		$sb_cover_letter_id=$_REQUEST["sb_cover_letter_id"];
	
/////////////error notifications////////////////////////
	if( !isset($_REQUEST["sb_resume"]) || !is_numeric($_REQUEST["sb_resume"]) )
	{
		$errs[$errcnt]="Resume must be selected";
   		$errcnt++;
	}
	
	if( isset($_REQUEST["sb_cover_letter"]) && ($_REQUEST["sb_cover_letter"] == 'old') )
	{
		if( isset($_REQUEST["sb_cover_letter_id"]) && ($_REQUEST["sb_cover_letter_id"]=="0") )
		{
			$errs[$errcnt]="Cover Letter must be selected";
			$errcnt++;
		}
	}	

	if($errcnt==0)
	{
		$sb_applied_on=date("YmdHis",time());
		$sb_seeker_id=$_SESSION["sbjbs_userid"];
		$sb_resume_id=(int)$sb_resume;
		$sb_cover_letter_id=(int)$sb_cover_letter_id;
				
		$sbqi_app="insert into `sbjbs_applications` (sb_job_id, sb_resume_id, sb_cover_id, sb_seeker_id, sb_applied_on) values ($sb_id, $sb_resume_id, $sb_cover_letter_id, $sb_seeker_id, $sb_applied_on)";
		$msg="Your resume has been sent";
//die($sbqi_app);
		mysql_query($sbqi_app);
		if(mysql_affected_rows()>0)
		{
			header("Location: gen_confirm_mem.php?errmsg=".urlencode($msg));
			die();
		}	//end if mysql_affected_rows() > 0 i.e.success
		else
		{
			header("Location: gen_confirm_mem.php?errmsg=".urlencode("Some error occurred, please try again."));
			die();
		}
	}			//end if-errcnt==0
}			//end if count-post
else
{		
	$sb_first_time=true;		//used to select first radio button
	$sb_resume='';
	$sb_cover_letter='no';
	$sb_cover_letter_id='';
}	

function main()
{
	global $sb_id, $errs, $errcnt, $sb_first_time, $sb_title, $sb_education_str, $sb_company_str, $sb_resume, $sb_cover_letter, 
$sb_cover_letter_id;

if  (count($_POST)>0)
{

if ( $errcnt<>0 )
{
?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr> 
    <td colspan="2"><strong>&nbsp;Your request cannot be processed due to following 
      reasons</strong></td>
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
</table>
<?

}

}
?>
<SCRIPT language=javascript> 
//<!--
function change_radio()
{
	form123.sb_cover_letter[1].checked=true;
}

function show_cover_letter(letter_link)
{
	if(form123.sb_cover_letter_id.selectedIndex==0)
	{
		alert('Please select a cover letter first');
		form123.sb_cover_letter_id.focus();
		return false;
	}
	var goto_file="view_cover_letter.php?sb_id="+form123.sb_cover_letter_id.value;
	window.open(goto_file,"preview");
	
}


function validate(frm) 
{
	if(frm.sb_cover_letter[1].checked==true)
	{
		
		if(frm.sb_cover_letter_id.selectedIndex==0)
		{
			alert('Please select an existing cover letter');
			frm.sb_cover_letter_id.focus();
			return false;
		}
	}
	return true;
}  
  
// -->
</SCRIPT>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form123" id="form123" onSu bmit="return validate(this);">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
    <tr class="titlestyle"> 
      <td>&nbsp;Apply for Job</td>
    </tr>
    <tr> 
      <td class="innertablestyle">&nbsp;</td>
    </tr>
    <tr valign="top"> 
      <td height="25" class="innertablestyle"> 
        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td><a href="view_job.php?sb_id=<?php echo $sb_id; ?>" >
              <?php 
	  		echo $sb_title;
			echo ($sb_education_str!='')?" ( $sb_education_str ), ":', ';
			echo $sb_company_str;
	   ?>
              </a></td>
          </tr>
        </table></td>
    </tr>
    <tr valign="top"> 
      <td height="25" class="innertablestyle"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="helpstyle">
                <tr> 
                  <td height="24" colspan="2" valign="top" class="subtitle"><font class="normal"><strong>&nbsp;&nbsp;Select 
                    Resume</strong></font><font class="red">*</font></td>
                </tr>
                <?php $sbq_res="select * from sbjbs_resumes where sb_approved='yes' and sb_seeker_id=".$_SESSION["sbjbs_userid"];
		  		//echo $sbq_res;
		  		$sbrs_res=mysql_query($sbq_res);
				if(mysql_num_rows($sbrs_res) > 0)
				{
					while($sbrow_res=mysql_fetch_array($sbrs_res))
					{	?>
                <tr> 
                  <td width="578" height="25" valign="top"><font class="normal"> &nbsp;&nbsp; 
                    &nbsp; 
                    <input type="radio" name="sb_resume" id="sb_resume<?php echo $sbrow_res["sb_id"]; ?>" value="<?php echo $sbrow_res["sb_id"]; ?>" <?php echo( ($sb_resume==$sbrow_res["sb_id"]) || ($sb_first_time) )?'checked':''; ?>>
                    <?php $sb_first_time=false;
			  		echo $sbrow_res["sb_title"]; ?>
                    </font></td>
                  <td width="58" valign="top"><a href="view_resume.php?resume_id=<?php echo $sbrow_res["sb_id"]; ?>" title="View Resume" target="_other">Preview</a>&nbsp;</td>
                </tr>
                <?php
		  			}		//end while row
		  		}		//end if($sbrs_res)
				else
				{?>
                <tr> 
                  <td height="25" colspan="2"><font class="normal">&nbsp;&nbsp;No 
                    resumes have been defined</font></td>
                </tr>
                <?php 
		  		}	//end else if($sbrs_res) i.e. rs not found 
			  ?></table></td>
          </tr>
        </table></td>
    </tr>
    <tr valign="top"> 
      <td height="25" class="innertablestyle"><font class="normal">&nbsp;</font> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="helpstyle">
                <tr> 
                  <td height="25" valign="top" class="subtitle"><strong>&nbsp;&nbsp;Cover 
                    Letter</strong></td>
                </tr>
                <tr> 
                  <td height="24" valign="top"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="sb_cover_letter" id="radio4" value="no" <?php echo ($sb_cover_letter=='no')?'checked':''; ?>> 
                    <font class="normal">Don't use cover letter.</font></td>
                </tr>
                <tr> 
                  <td height="25" valign="top"> &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="sb_cover_letter" id="radio5" value="old" <?php echo ($sb_cover_letter=='old')?'checked':''; ?>> 
                    <font class="normal">Use an existing cover letter.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <select name="sb_cover_letter_id" id="sb_cover_letter_id" onChange="change_radio();">
                      <option value="0">Select Cover Letter</option>
                      <?php $sbq_cov="select * from sbjbs_cover_letters where sb_approved='yes' and  sb_seeker_id=".$_SESSION["sbjbs_userid"];
					  $sbrs_cov=mysql_query($sbq_cov);
					  while($sbrow_cov=mysql_fetch_array($sbrs_cov)) 
					  {
					  	echo '<option value="'.$sbrow_cov['sb_id'].'"';
						echo ($sbrow_cov['sb_id']==$sb_cover_letter_id)?'selected':'';
						echo '>'.$sbrow_cov['sb_title'].'</option>';
					  }
					  ?>
                    </select>
                    <a href="#" title="View Cover letter" onClick="show_cover_letter(this);">Preview</a> 
                    </font></td>
                </tr>
                <tr>
                  <td height="8" valign="top"></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr valign="top"> 
      <td height="8" align="center" class="innertablestyle"></td>
    </tr>
    <tr valign="top"> 
      <td height="25" align="center" class="innertablestyle"> <input name="submit"  type="submit" value="Apply"> 
        <input name="sb_id" type="hidden" id="sb_id" value="<?php echo $sb_id; ?>"> 
        <input name="sb_first_time" type="hidden" id="sb_first_time" value="<?php echo $sb_first_time; ?>"> 
      </td>
    </tr>
    <tr valign="top">
      <td height="8" align="center" class="innertablestyle"></td>
    </tr>
  </table>
  </form>
<?php
}	//end main
include_once("template.php");
?>