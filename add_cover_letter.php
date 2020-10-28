<?
include_once "logincheck.php";
include_once("myconnect.php");

if(!isset($_REQUEST["sb_id"]))
{
	$sbq_cov="select * from sbjbs_cover_letters where sb_seeker_id=".$_SESSION["sbjbs_userid"];
	//die($sbq_cov);
	$sbrs_cov=mysql_query($sbq_cov);
	$sbtotal_letters=mysql_num_rows($sbrs_cov);
	
	$sbq_con="select * from sbjbs_config where sb_id=1";
	$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
	if( ($sbrow_con["sb_letter_cnt"] > 0) && ($sbtotal_letters >= $sbrow_con["sb_letter_cnt"]) )
	{
		header("Location: gen_confirm_mem.php?errmsg=".urlencode("You already have maximum number of cover letters allowed."));
		die();
	}
}

$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		$headline_len=$config["sb_title_len"];
		$objective_len=$config["sb_cover_letter_len"];
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
		$errs[$errcnt]="Title must be provided.";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Tile can not have any special character (e.g. & ; < >).";
   		$errcnt++;
	}
	elseif(strlen(trim($sb_title))>$headline_len )
	{
		$errs[$errcnt]="Title must be less than $headline_len characters.";
   		$errcnt++;
	}
	else
	{
		$cov_letter=mysql_fetch_array(mysql_query("select * from sbjbs_cover_letters where 
		sb_title='$sb_title' and sb_seeker_id=".$_SESSION["sbjbs_userid"]." and sb_id<>".$_POST["sb_id"]));
		if($cov_letter)
		{
			$errs[$errcnt]="Cover Letter with this title has already been saved.";
			$errcnt++;
		}
	}
	if ( strlen(trim($sb_objective)) == 0 )
	{
		$errs[$errcnt]="Letter Contents must be provided.";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["objective"]))
	{
		$errs[$errcnt]="Letter Contents can not have any special character (e.g. & ; < >).";
   		$errcnt++;
	}
	elseif(strlen(trim($sb_objective))>$objective_len )
	{
		$errs[$errcnt]="Letter Contents must be less than $objective_len characters.";
   		$errcnt++;
	}

	if($errcnt==0)
	{
		$approved="yes";
		if($config["sb_letter_approval"]=="admin")
		{ $approved="no";}
		
		$id=$_REQUEST["sb_id"];
		if($id==0)//insert new record
		{
		if($approved=="no")
		{ $approved="new";}
		
		mysql_query("insert into sbjbs_cover_letters (sb_title,sb_contents,sb_seeker_id,sb_approved) 
		values('$sb_title','$sb_objective',".$_SESSION["sbjbs_userid"].",'$approved')");
		if(mysql_affected_rows()>0)
		{
			header("Location: gen_confirm_mem.php?errmsg=".urlencode("Your cover letter has been added."));
			die();
		}
		else
		{
			header("Location: gen_confirm_mem.php?err=add_cover_letter&errmsg=".urlencode("Sorry, no updations carried out."));
			die();
		}
	}// end new record
	else
	{
		$chk_app=mysql_fetch_array(mysql_query("select * from sbjbs_cover_letters where sb_id=$id"));
		if($chk_app["sb_approved"]<>"yes")
		{ $approved=$chk_app["sb_approved"];}
		
		mysql_query("update sbjbs_cover_letters set 
		sb_title='$sb_title',sb_contents='$sb_objective',sb_approved='$approved' where sb_id=$id");
		if(mysql_affected_rows()>0)
		{
			header("Location: gen_confirm_mem.php?errmsg=".urlencode("Your cover letter has been saved."));
			die();
		}
		else
		{
			header("Location: gen_confirm_mem.php?err=add_cover_letter&id=$id&errmsg=".urlencode("Sorry, no updations carried out."));
			die();
		}
	}//end updation
 }			//end if-errcnt==0
}			//end if count-post


function main()
{
global $errs, $errcnt;
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		$headline_len=$config["sb_title_len"];
		$objective_len=$config["sb_cover_letter_len"];
$id=0;
$sb_title="";
$sb_objective="";
if(isset($_REQUEST["sb_id"])&&($_REQUEST["sb_id"]<>"")&&($_REQUEST["sb_id"]<>0))
{
$id=$_REQUEST["sb_id"];
$letter=mysql_fetch_array(mysql_query("select * from sbjbs_cover_letters where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]));
if ( $letter )
{
$sb_title=$letter["sb_title"];
$sb_objective=$letter["sb_contents"];
}
else
{
echo "<p>&nbsp;</p><p>&nbsp;</p><br><br><br><div align='center'><font class='normal'>Cover Letter Not Found. Click <a href='index.php' >here</a> to continue</font></div><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>";
return;
}
}// end if letter id
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
       	   alert('Please Specify Title!');
		   form.title.focus();
	   return false;
	   }
	if(form.title.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Title (e.g. &  < >)");
			form.title.focus();
			return(false);
		}
	if(form.title.value.length><?php echo $headline_len;?>)
		{
			alert("Title must not be greater than <?php echo $headline_len;?> characters.");
			form.title.focus();
			return(false);
		}
	if ( form.objective.value == "" ) {
       	   alert('Please Specify Letter Contents!');
		   form.objective.focus();
	   return false;
	   }
	if(form.objective.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Letter Contents(e.g. &  < >)");
			form.objective.focus();
			return(false);
		}
	if(form.objective.value.length><?php echo $objective_len;?>)
		{
			alert(" Letter Contents must not be greater than <?php echo $objective_len;?> characters.");
			form.objective.focus();
			return(false);
		}
	return true;
  }
// -->
</SCRIPT>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" onSubmit="return validate(this);">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr class="titlestyle"> 
            <td>&nbsp;
              <?php if($id==0) echo "Add Cover Letter"; else echo "Edit Cover Letter";?>
            </td>
          </tr>
    <tr>
      <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
          <tr valign="top"> 
            <td width="30%" align="right" class="innertablestyle"><font class="normal"><strong>Title</strong></font><br> 
            </td>
            <td width="6"><font class="red">*</font></td>
            <td width="70%"> <input name="title" type="text" id="title"  value="<?php echo $sb_title; ?>" size="50" maxlength="70"> 
              <font class="normal"><strong> 
              <input name="sb_id" type="hidden" id="sb_id" value="<? echo $id;?>">
              </strong></font> <br> <font class="smalltext"> not more than <?php echo $headline_len;?> 
              characters.</font></td>
          </tr>
          <tr valign="top"> 
            <td width="30%" align="right" class="innertablestyle"><font class="normal"><strong>Letter 
              Contents<br>
              <a href="javascript:help_popup('cover_letter');" class="help_style"><?php echo HELP_LINK;?></a></strong></font> 
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
        </table></td>
    </tr>
  </table>
  </form>
<?
}
include_once("template.php");

?>
