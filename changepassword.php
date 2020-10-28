<?php 
include_once"logincheck.php";
include_once"myconnect.php";
$errcnt=0;

if(count($_POST) <> 0)
{
	if(!get_magic_quotes_gpc())
	{
		$sbcurrent_pwd=str_replace("$","\$",addslashes($_REQUEST["sbcurrent_pwd"]));
		$sbnew_pwd=str_replace("$","\$",addslashes($_REQUEST["sbnew_pwd"]));
		$sbre_pwd=str_replace("$","\$",addslashes($_REQUEST["sbre_pwd"]));
	}
	else
	{
		$sbcurrent_pwd=str_replace("$","\$",$_REQUEST["sbcurrent_pwd"]);
		$sbnew_pwd=str_replace("$","\$",$_REQUEST["sbnew_pwd"]);
		$sbre_pwd=str_replace("$","\$",$_REQUEST["sbre_pwd"]);
	}
	if(strlen(trim($_REQUEST["sbcurrent_pwd"])) == 0 )
	{
		$errs[$errcnt]="Please specify Current Password.";
   		$errcnt++;
	}
	else
	{
		$rs0=mysql_fetch_array(mysql_query("Select * from sbjbs_seekers 
		where sb_id=".$_SESSION["sbjbs_userid"]. " and sb_password='$sbcurrent_pwd'"));
		if (!($rs0["sb_password"] === $_REQUEST["sbcurrent_pwd"]))
		{
		$errs[$errcnt]="Password COULD NOT be changed because old password was incorrect.";
   		$errcnt++;
		}
	}
	if(strlen(trim($_REQUEST["sbnew_pwd"])) == 0 )
	{
		$errs[$errcnt]="Please specify New Password.";
   		$errcnt++;
	}
	if($sbnew_pwd<>$sbre_pwd)
	{
		$errs[$errcnt]="Retyped Password does not match to the New Password";
   		$errcnt++;
	}
	
	if($errcnt==0)
	{
			mysql_query("Update sbjbs_seekers SET sb_password='$sbnew_pwd' where sb_id=".$_SESSION["sbjbs_userid"]);
			if(mysql_affected_rows() == 1)
			{
				header("Location: personal_confirm_mem.php?errmsg=".urlencode("Password has been changed"));
				die();
			}
			else
			{
				header("Location: personal_confirm_mem.php?err=changepassword&errmsg=".urlencode("Sorry, some error occurred and unable to change the password."));
				die();
			}
			
	}
}

function main()
{
global $errs, $errcnt;

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
</table>
<?

}

}
?>
<script language="JavaScript">
//<!--
function validate(form)
{
	if(form.sbcurrent_pwd.value=="")
	{
	alert('Please specify current password');
	form.sbcurrent_pwd.focus();
	return false;
	}
	if(form.sbnew_pwd.value=="")
	{
	alert('Please specify new password');
	form.sbnew_pwd.focus();
	return false;
	}
	if(form.sbre_pwd.value!=form.sbnew_pwd.value)
	{
	alert("Retyped password can't match new password");
	form.sbre_pwd.value="";
	form.sbnew_pwd.select();
	form.sbnew_pwd.focus();
	return false;
	}
	return true;
}
//-->
</script>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onSubmit="return validate(this)" >
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
    <tr> 
      <td class="titlestyle">&nbsp;&nbsp;Change Password</td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
          <tr> 
            <td width="40%" height="25" class="innertablestyle"> <div align="right"><font class="normal"><strong> 
                Current Password</strong></font></div></td>
            <td width="6" valign="top"><font class="red">*</font></td>
            <td width="66%" height="25"> <input name="sbcurrent_pwd" type="password" class="box" id="currpassword2" value=""> 
            </td>
          </tr>
          <tr> 
            <td width="40%" height="25" class="innertablestyle"> <div align="right"><font class="normal"><strong>New 
                Password</strong></font></div></td>
            <td valign="top"><font class="red">*</font></td>
            <td height="25"> <input name="sbnew_pwd" type="password" class="box" id="newpassword2" ></td>
          </tr>
          <tr> 
            <td width="40%" height="25" class="innertablestyle"> <div align="right"><font class="normal"><strong>Retype 
                Password</strong></font></div></td>
            <td valign="top"><font class="red">*</font></td>
            <td height="25"> <input name="sbre_pwd" type="password" class="box" id="sbre_pwd"></td>
          </tr>
          <tr> 
            <td width="40%" height="25" class="innertablestyle">&nbsp;</td>
            <td width="6">&nbsp;</td>
            <td height="25"><input type="submit" name="Submit2" value="Change Password"></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form><?php
}		//end main
include_once"template.php";
?>