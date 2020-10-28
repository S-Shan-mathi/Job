<?php
include "logincheck.php";
include_once "myconnect.php";
//include_once "date_time_format.php";

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));

	$sbq_resume='select * from sbjbs_cover_letters where sb_seeker_id='.$_SESSION["sbjbs_userid"].' order by sb_id desc';
//	echo $sbq_off;
	$sbq_resume=mysql_query($sbq_resume);
	$num_jobs=mysql_num_rows($sbq_resume);
	?>
	<script language="JavaScript">
//<!--
function select_all()
{
  for (var i=0;i<document.form2.elements.length;i++)
  {
    var e =document. form2.elements[i];
    if ((e.name != 'check_all') && (e.type=='checkbox'))
    {
       e.checked = document.form2.check_all.checked;
    }
  }

}


//-->
</script>
	
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;My Cover Letters</td>
                    </tr>
                    <tr> 
                      <td valign="top"><font class='normal'>Posted - <strong><font class='red'><?php echo $num_jobs; ?></font></strong> 
                        <?php if($config["sb_letter_cnt"]>0) 	{?>
                        Maximum Allowed - <strong><font class='red'><?php echo $config["sb_letter_cnt"]; ?></font></strong> 
                        <?php 			}		//end if?>
                        </font> </td>
                    </tr>
                    <tr>
                      <td valign="top">&nbsp;</td>
                    </tr>
                    <tr> 
                      <td valign="top"><form name="form2" method="post" action="delete_resume.php">
                          <?php 	
							$cnt=0;
							while( $sbrow_resume=mysql_fetch_array($sbq_resume) )
							{
							$cnt++;
							?>
                          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1"
							 class="<? if($cnt%2<>0){echo "innertablestyle"; }else{echo "alternatecolor";}?>">
                            <tr> 
                              <td width="65" ><font class="smalltext">Letter &nbsp;<?php 
							  echo $cnt; //$sbrow_resume["sb_id"];
							  ?></font></td>
                              <td valign="top"><font class="normal"><strong> 
                                <?php 
                                  echo $sbrow_resume["sb_title"]; 
                                ?>
                                </strong></font></td>
                            </tr>
                            <tr> 
                              <td ><font class="smalltext">&nbsp;</font></td>
                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td><font class="smalltext"> 
                                      <?php 
							  if($sbrow_resume["sb_approved"]=="yes")
							  { echo "<em>Approved&nbsp;&nbsp;</em>"; }
							  elseif($sbrow_resume["sb_approved"]=="no")
							  { echo "<em>Waiting Approval&nbsp;&nbsp;</em>"; }
							  else
							  { echo "<em>New Waiting Approval&nbsp;&nbsp;</em>"; }
							  
						//	  echo "&nbsp;Posted on: ".sb_date($sbrow_resume["sbposted"]);
							  ?>
                                      </font></td>
                                    <td width="250" align="right">&nbsp;</td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td ><font class="smalltext"><strong>Options</strong></font> 
                              </td>
                              <td valign="top"><font class="smalltext">&nbsp;[&nbsp;<a href="view_cover_letter.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" title="View cover letter">View</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="add_cover_letter.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" title="Update cover letter">Edit</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="copy_cover_letter.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" title="Copy cover letter">Duplicate</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="delete_cover_letter.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" onClick="return confirm('Do you really want to remove the cover letter ?');" title="Remove cover letter">Delete</a>&nbsp;]</font></td>
                            </tr>
                          </table>
                          <?
}
?>
                        </form></td>
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
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
  <?
}// end main
include "template.php";
?>