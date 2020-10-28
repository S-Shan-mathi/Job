<?php
include_once "myconnect.php";
function main()
{
?>
<table width="80%" border="0" align="center" cellpadding="1" cellspacing="0">
  <tr> 
    <td></td>
  </tr>
  <tr height="20"> 
    <td ></td>
  </tr><?php
  if(isset($_REQUEST["errmsg"])&&($_REQUEST["errmsg"]<>""))
  {
  ?><tr>
    <td valign="top"> 
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="msgstyle">
        <tr align="left"> 
          <td colspan="2"><strong><?php echo $_REQUEST["errmsg"];?></strong></td>
        </tr>
        <?php
		if(isset($_REQUEST["err"])&&($_REQUEST["err"]<>""))
		{
		 $return_path=$_REQUEST["err"].".php";
		 if(isset($_REQUEST["id"])&&($_REQUEST["id"]<>""))
		 { $return_path.="?sb_id=".$_REQUEST["id"];} 
		
        ?>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left"> Click <a href="<?php echo $return_path;?>">here</a> 
            to try again.</td>
        </tr>
        <?php
		}
        ?>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left"> Click <a href="editmember.php">here</a> to edit your 
            personal information. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="changepassword.php">here</a> to change 
            password. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="subscribe.php">here</a> to <?php
		  $sub=mysql_num_rows(mysql_query("select * from sbjbs_newsletter,sbjbs_seekers
		  where sbjbs_newsletter.sb_email=sbjbs_seekers.sb_email_addr and 
		  sbjbs_seekers.sb_id=".$_SESSION["sbjbs_userid"]));
		  //echo $sub;
		  if($sub>0)
		  {  echo "unsubscribe";}
		  else
		  { echo "subscribe";}
		  
		  ?> 
            Newsletter. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="logout.php">here</a> to logout. </td>
        </tr>
      </table>
      
    </td>
  </tr><?php
  }
  ?><tr> 
    <td> <div align="center"> </div></td>
  </tr>
</table>
  
<? 
}// end of main()
include "template.php";
?>