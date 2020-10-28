<?php

include_once "session.php";
include_once "myconnect.php";

if(isset($_SESSION["sbjbs_userid"])&&($_SESSION["sbjbs_userid"]<>""))
{
	header("Location: gen_confirm.php?errmsg=".urlencode('You are already logged in as '.$_SESSION["sbjbs_username"]));
	die();
}
function main()
{
?>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="msgstyle">
  <tr align="left"> 
    <td colspan="2"><strong>Welcome to the member's area<?php //echo $_SESSION["sbjbs_username"];?>, you have successfully logged-in.</strong></td>
  </tr>
  <?
	if(isset($_REQUEST["return_path"]) && ($_REQUEST["return_path"]<>""))
	{
		$return_path=$_REQUEST["return_path"]."?sb_id=".$_REQUEST["id"];
  ?>
  <tr> 
    <td width="2%" align="center">&nbsp;</td>
    <td width="100%" align="left"> Click <a href="<?php echo $return_path;?>">here</a> 
      <?php
		if(preg_match("/\/apply_now.php/",$_REQUEST["return_path"]))
		{ 	echo " to apply for the job.";}
		else
		{ 	echo " to continue";}
    ?>
    </td>
  </tr>
  <?
  }?>
  <tr> 
    <td align="center">&nbsp;</td>
    <td align="left">Click <a href="userhome.php">here</a> to go to member stats. 
    </td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
    <td align="left">Click <a href="logout.php">here</a> to logout. </td>
  </tr>
</table>
<?
}

if(!isset($_REQUEST['username']) || ($_REQUEST['username']=="") || !isset($_REQUEST['pwd']) || ($_REQUEST['pwd']=="") )
{
	header("Location: signin.php?errmsg=".urlencode("Please enter login information."));
	die();
}

if (!get_magic_quotes_gpc()) 
{
	$username=str_replace('$', '\$',addslashes($_REQUEST["username"]));
	$pwd=str_replace('$', '\$',addslashes($_REQUEST["pwd"]));
}
else
{
	$username=str_replace('$', '\$',$_REQUEST["username"]);
	$pwd=str_replace('$', '\$',$_REQUEST["pwd"]);
}
			

$sql = "SELECT * FROM sbjbs_seekers WHERE sb_username = '$username' AND sb_password = '$pwd'" ;

$rs_query=mysql_query($sql);
if ( $rs=mysql_fetch_array($rs_query)  )
{
	if($rs["sb_suspended"]=="no")
	{
		if($rs["sb_password"]===$_REQUEST['pwd'])
		{
			$_SESSION["sbjbs_username"]=$rs["sb_username"] ;
			$_SESSION["sbjbs_userid"]=$rs["sb_id"] ;
			
			mysql_query("update sbjbs_seekers set sb_last_login='".date("YmdHis",time())."' where sb_id=".$rs["sb_id"]);
			include_once "template.php";
			die();
		}
	}//not suspended
	else
	{
		header("Location: gen_confirm.php?errmsg=". urlencode("Your Account has been suspended by Admin.") );
		die();
	}
}

header("Location: signin.php?errmsg=". urlencode("Please enter correct login information.") );
die();
?>