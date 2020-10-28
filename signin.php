<?php
include_once"myconnect.php";
include_once "session.php";
if(isset($_SESSION["sbjbs_userid"])&&($_SESSION["sbjbs_userid"]<>""))
{
header("Location:"."gen_confirm.php?errmsg=".urlencode('You are already logged in as '.$_SESSION["sbjbs_username"]));
die();
}

function main()
{
$username="";
$pwd="";
//---------comment these to disable autofill
$username="demo";
$sbq_mem="select * from sbjbs_seekers where sb_username='$username'";
$sbrow_mem=mysql_fetch_array(mysql_query($sbq_mem));
$pwd=$sbrow_mem["sb_password"];
//---------end comment these to disable autofill
?>
<table width="70%" border="0" align="center" cellpadding="1" cellspacing="0">
  <tr> 
    <td></td>
  </tr>
  <tr height="20"> 
    <td ></td>
  </tr><?php
  if(isset($_REQUEST["errmsg"])&&($_REQUEST["errmsg"]<>""))
  {
  ?><tr>
    <td><? //<table align="center" bgcolor="#FEFCFC" bordercolor="#FF0000" border="1" cellpadding="5" class="msgstyle">?>
        
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="msgstyle">
        <tr align="left"> 
          <td colspan="2"><strong><?php echo $_REQUEST["errmsg"];?></strong></td>
        </tr>
        <tr> 
          <td width="2%" align="center">&nbsp;</td>
          <td width="100%" align="left"> Click <a href="signup.php">here</a> to 
            signup or fill the following form to login. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="lostpassword.php">here</a> to retrieve 
            lost signup information. </td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="#" onClick="javascript:window.history.go(-1);">here</a> to go to 
            previous page. </td>
        </tr>
      </table>
      <br>
    </td>
  </tr><?php
  }
  ?><tr> 
    <td> <div align="center"> <table width="100%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
	<tr class="titlestyle"> 
       <td>&nbsp;Member Login </td>
    </tr>
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
          <FORM name=loginForm  action="login.php" method=post>
            <tr valign="top"> 
              <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong>
                <input type="hidden" id="id" name="id" value="<? if (isset($_REQUEST["id"])&& ($_REQUEST["id"]>=0) ) echo $_REQUEST["id"]; else echo 0; ?>">
                <input name="return_path" type="hidden" id="return_path" value="<? if (isset($_REQUEST["return_add"])) echo $_REQUEST["return_add"]; else echo ""; ?>">
                Username</strong></font></td>
              <td width="6"><font class="red">*</font></td>
              <td width="341" valign="center"><input name="username" type="text" ></td>
            </tr>
            <tr valign="top"> 
              <td align="right" class="innertablestyle"><font class="normal"><strong>Password</strong></font></td>
              <td><font class="red">*</font></td>
              <td valign="center"><input name="pwd" type="password" ></td>
            </tr>
           <!-- <tr valign="top"> 
              <td align="right" class="innertablestyle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input name="remember_user" type="checkbox" id="remember_user" value="yes"> 
                <font class="smalltext">Remember Username/Password</font></td>
            </tr>-->
            <tr valign="top"> 
              <td align="right" class="innertablestyle">&nbsp;</td>
              <td>&nbsp;</td>
              <td><input name="submit"  type="submit" value="Sign In"></td>
            </tr>
            <tr valign="top"> 
              <td align="leftt"><a  class="insidelink"  href="signup.php">Signup 
                Now</a><br> <a class="insidelink" href="lostpassword.php">Lost 
                Password</a></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </FORM>
        </table></td>
  </tr>
</table>

        
      </div></td>
  </tr>
</table>
  
<? 
}// end of main()
include "template.php";
?>