<?php
include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));

	$sbq_resume='select *,UNIX_TIMESTAMP(sb_applied_on) as sb_applied from sbjbs_applications where sb_seeker_id='.$_SESSION["sbjbs_userid"].' order by sb_id';
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
                      <td height="25" class="titlestyle">&nbsp;My Applied Jobs</td>
                    </tr><?php 	if($num_jobs>0)	
								{?>
                    <tr> 
                      <td valign="top">&nbsp; </td>
                    </tr>
                    <tr> 
                      <td valign="top">
<form name="form2" method="post" action="delete_resume.php">
                          <?php 	
							$cnt=0;
							while( $sbrow_resume=mysql_fetch_array($sbq_resume) )
							{
							$cnt++;
							?>
                          <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1"
							 class="<? if($cnt%2<>0){echo "innertablestyle"; }else{echo "alternatecolor";}?>">
                            <tr> 
                              <td width="65" ><font class="smalltext">Job&nbsp;<?php echo $cnt;//$sbrow_resume["sb_id"];?></font></td>
                              <td valign="top"><font class="normal"><strong> 
                                <?php 
	$sbq_job='select * from sbjbs_jobs where sb_id='.$sbrow_resume["sb_job_id"];
	$sbrow_job=mysql_fetch_array(mysql_query($sbq_job));
    echo $sbrow_job["sb_title"]; 
                               ?>
                                </strong></font></td>
                            </tr>
                            <tr> 
                              <td ><font class="smalltext">&nbsp;</font></td>
                              <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td><font class="smalltext">Applied On: 
                                      <?php	echo sb_date($sbrow_resume["sb_applied"]); ?>
                                      </font></td>
                                    <td width="250" align="right">&nbsp;</td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr> 
                              <td >&nbsp;</td>
                              <td valign="top"><font class="smalltext">&nbsp;[&nbsp;<a href="view_job.php?sb_id=<?php echo $sbrow_resume["sb_job_id"];?>" class="small_link" title="View job details">View 
                                Job</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="view_resume.php?resume_id=<?php echo $sbrow_resume["sb_resume_id"];?>" class="small_link" title="View sent resume">View 
                                Resume</a>&nbsp;]
                                <?php if($sbrow_resume["sb_cover_id"] > 0) {	?>
                                &nbsp;&nbsp;[&nbsp;<a href="view_cover_letter.php?sb_id=<?php echo $sbrow_resume["sb_cover_id"];?>" class="small_link" title="View sent cover letter">View 
                                Cover Letter</a>&nbsp;]
                                <?php } //end if ?>
                                </font></td>
                            </tr>
                          </table>
                          <?
}
?>
                        </form></td>
                    </tr><?php 
								}
								else	
								{	?>
                    <tr>
                      <td valign="top">&nbsp;You have not applied for any job</td>
                    </tr><?php 	}	//end else ?>
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