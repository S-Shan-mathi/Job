<?
//include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{

	$sbq_job="select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_jobs where sb_approved='yes' ";
	$sbq_job.=" order by sb_featured desc, sb_id desc";

	$sbq_job=mysql_query($sbq_job);
	$num_job=mysql_num_rows($sbq_job);
	
	$label=1;
	$featured_label=1;

?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;All jobs<font class="smalltext">&nbsp; 
                        </font></td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0"><?php 	while($job=mysql_fetch_array($sbq_job))
					  	{	?>
                          <tr valign="top"> 
                            <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                <tr> 
                                  <td>&nbsp;</td>
                                </tr>
                                <tr class="subtitle"> 
                                  <td> &nbsp; <a href="view_job.php?sb_id=<?php echo $job["sb_id"]; ?>"> 
                                    <strong>
                                    <?php 
								  echo $job["sb_title"];
								  ?>
                                    </strong></a> </td>
                                </tr>
                                <tr> 
                                  <td>&nbsp;</td>
                                </tr>
                                <tr> 
                                  <td>&nbsp;</td>
                                </tr>
                                <tr> 
                                  <td>&nbsp;</td>
                                </tr>
                              </table>
                               </td>
                          </tr><?php 
						}		//end while 
                        ?></table></td>
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