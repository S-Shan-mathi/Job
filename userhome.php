<?
include_once "logincheck.php";

include_once "session.php";

function main()
{
$id=0;
if(isset($_SESSION["sbjbs_userid"]))
{
$id=$_SESSION["sbjbs_userid"];
}
if(isset($_REQUEST["tmp"]))
{
$id=$_REQUEST["tmp"];
}
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
//$config["null_char"]="-";
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0" dwcopytype="CopyTableCell">
  <tr> 
    <td valign="top">&nbsp;</td>
  </tr>
  <tr align="center"> 
    <td align="right" valign="top"> <table width="95%" border="0" cellspacing="0" cellpadding="0" class="maintablestyle" align="center">
        <tr> 
          <td width="100%" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr> 
                <td height="25" class="titlestyle"><div align="left">&nbsp;User 
                    Statistics</div></td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
                    <tr> 
                      <td width="44%" height="25" class="innertablestyle">
<div align="right"><strong><font class='normal'>&nbsp;Total 
                          Number of Resumes Posted</font></strong></div></td>
                      <td width="56%"><font class='normal'><strong>
                        <?php 
				  $sbq_off="select * from sbjbs_resumes where sb_seeker_id=".$_SESSION["sbjbs_userid"];
				  echo mysql_num_rows(mysql_query($sbq_off)); 
				?>
                        </strong></font></td>
                    </tr>
                    <tr> 
                      <td width="44%" height="25" class="innertablestyle">
<div align="right"><strong><font class='normal'>&nbsp;Total 
                          Number of Cover Letters Posted</font></strong></div></td>
                      <td><font class='normal'><strong>
                        <?php 
				$sbq_off="select * from sbjbs_cover_letters where sb_seeker_id=".$_SESSION["sbjbs_userid"];
				echo mysql_num_rows(mysql_query($sbq_off)); ?>
                        </strong></font></td>
                    </tr>
                    <tr> 
                      <td width="44%" height="25" class="innertablestyle">
<div align="right"><strong><font class='normal'>&nbsp;Total 
                          Number of Applications Posted</font></strong></div></td>
                      <td><font class='normal'><strong>
                        <?php 
				$sbq_off="select * from sbjbs_applications where sb_seeker_id=".$_SESSION["sbjbs_userid"];
				echo mysql_num_rows(mysql_query($sbq_off)); ?>
                        </strong></font></td>
                    </tr>
                  </table></td>
              </tr>
            </table> 
            
          </td>
        </tr>
      </table>
      <br> </td>
  </tr>
</table>
<?
}// end main
include_once "template.php";
?>