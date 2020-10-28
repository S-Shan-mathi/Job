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
          <td colspan="2"><strong><?php echo $_REQUEST["errmsg"];?></strong></td>
        </tr>
        <?php
		if(isset($_REQUEST["err"])&&($_REQUEST["err"]<>""))
		{
		 $return_path=$_REQUEST["err"].".php"; 
		
        ?>
        <tr> 
          <td width="3%" align="center">&nbsp;</td>
          <td width="97%" align="left"> Click <a href="<?php echo $return_path;?>">here</a> 
            to try again.</td>
        </tr>
        <?php
		} 
		?>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left"> Click <a href="search_result.php">here</a> to view all jobs. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="search_companies.php">here</a> to browse by companies. 
          </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="advance_search.php">here</a> to search for jobs. </td>
        </tr>
        <?php
		if(isset($_SESSION["sbjbs_userid"]))
		{
        ?><tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="userhome.php">here</a> to go to member 
            area. </td>
        </tr>
        <tr> 
          <td align="center">&nbsp;</td>
          <td align="left">Click <a href="logout.php">here</a> to logout. </td>
        </tr>
        <?
		}
      ?>
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