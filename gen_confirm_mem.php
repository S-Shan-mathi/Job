<?php
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
    <td>        
      <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="msgstyle">
        <tr align="left"> 
          <td width="102%" colspan="2"><strong><?php echo $_REQUEST["errmsg"];?></strong></td>
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
	
		?><tr> 
          <td align="center">&nbsp;</td>
          <td align="left"> Click <a href="resumes.php">here</a> to manage resumes. 
          </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="letters.php">here</a> to manage cover 
            letters. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="editmember.php">here</a> to edit personnel 
            profile. </td>
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