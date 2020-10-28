<?
include "session.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{

	$sbq_resume='select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_jobs 
	where sb_id='.$_REQUEST["sb_id"];

	$sbq_resume=mysql_query($sbq_resume);
	$num_resume=mysql_num_rows($sbq_resume);
	$resume=mysql_fetch_array($sbq_resume);
	$imgpmt=" width=80 height=100 ";
?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Job Info</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                          <tr valign="top"> 
                            <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php 
								  echo $resume["sb_title"];
								  ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php
                                       echo $resume["sb_role"];
								    ?>
                                    </font></td>
                                </tr>
                              </table></td>
                            <td width="50%"><?php 
							if(isset($_SESSION["sbjbs_userid"]))
							{	?>
                              <form name="form1" method="post" action="apply_now.php">
                                <input type="submit" name="Submit" value="Apply Now">
                                <input name="sb_id" type="hidden" id="sb_id" value="<?php echo $_REQUEST["sb_id"]; ?>">
                              </form><?php 
							}	//end if  ?></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td align="center"></td>
        </tr>
        <tr> 
          <td valign="top">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
  <?
}// end main
include "template.php";
?>