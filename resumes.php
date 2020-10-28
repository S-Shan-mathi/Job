<?
include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sb_resume_cnt=$config["sb_resume_cnt"];


	$sbq_resume='select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_resumes 
	where sb_seeker_id='.$_SESSION["sbjbs_userid"]." order by sb_id";
//	echo $sbq_off;
	$sbq_resume=mysql_query($sbq_resume);
	$num_resume=mysql_num_rows($sbq_resume);

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
                      <td height="25" class="titlestyle">&nbsp;My Resumes</td>
                    </tr>
                    <tr> 
                      <td><font class='normal'>Posted - <strong><font class='red'><?php echo $num_resume; ?></font></strong> 
                        Maximum Allowed - <strong><font class='red'><?php echo $sb_resume_cnt; ?></font></strong> 
                        </font></td>
                    </tr>
                    <tr> 
                      <td valign="top"><?php
					  if(isset($_REQUEST["errmsg"])&&($_REQUEST["errmsg"]<>""))
					  {
					  ?>
                        <table width="90%" border="0" align="center" cellpadding="5" cellspacing="0" class="errorstyle">
                          <tr align="left"> 
                            <td width="100%" align="center"><strong><?php echo $_REQUEST["errmsg"];?></strong></td>
                          </tr>
                        </table><?php
						}
						?>&nbsp;</td>
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
                              <td width="50" ><font class="smalltext">Resume <?php echo $cnt;?></font></td>
                              <td valign="top"><font class="normal"><strong><?php 
                                  echo $sbrow_resume["sb_title"]; 
                                ?></strong></font></td>
                            </tr>
                            <tr> 
                              <td ><font class="smalltext">&nbsp;</font></td>
                              <td valign="top"><font class="smalltext"> 
                                <?php 
							  if($sbrow_resume["sb_approved"]=="yes")
							  { echo "<em>Approved&nbsp;&nbsp;</em>"; }
							  elseif($sbrow_resume["sb_approved"]=="no")
							  { echo "<em>Disapproved&nbsp;&nbsp;</em>"; }
							  elseif($sbrow_resume["sb_approved"]=="incomplete")
							  { echo "<em>Incomplete&nbsp;&nbsp;</em>"; }
							  else
							  { echo "<em>Waiting Approval&nbsp;&nbsp;</em>"; }
							  
							  if($sbrow_resume["sb_hide_info"]=="yes")
							  { echo "<font class='red'><b>Confidential&nbsp;&nbsp;</b></font>";}
							  else
							  { echo "<font class='red'><b>Non Confidential&nbsp;&nbsp;</b></font>";}
							
							  if($sbrow_resume["sb_search_enable"]=="yes")
							  { echo "<em>Searchable&nbsp;&nbsp;</em>"; }
							  else
							  { echo "<em>Not Searchable&nbsp;&nbsp;</em>"; }

							  echo "&nbsp;Posted on: ".sb_date($sbrow_resume["sbposted"]);
							  ?></font></td>
                            </tr>
                            <tr>
                              <td >&nbsp;</td>
                              <td valign="top"><font class="smalltext">&nbsp;[&nbsp;<a href="view_resume.php?resume_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link">View</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="headline.php?resume_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link">Edit</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="copy_resume.php?resume_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link">Duplicate</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="delete_resume.php?resume_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" onClick="javascript: return confirm('Do you really want to delete the resume?');">Delete</a>&nbsp;]&nbsp;<?php
							  if($sbrow_resume["sb_approved"]=="yes")
							  { 
							  ?>&nbsp;[&nbsp;<a href="send_resume.php?resume_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link">Send</a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="change_searchable.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link" onClick="javascript: return confirm('<?php
if(	$sbrow_resume["sb_search_enable"]=='no')
{ echo "This will make resume searchable and all other resumes will be marked as not searchable. ";}
else
{ echo "This will mark resume as not searchable. ";}
?>\nDo you want to continue?');"><?php echo($sbrow_resume["sb_search_enable"]=='no')?'Make Searchable':'Make Not Searchable'; ?></a>&nbsp;]&nbsp;&nbsp;[&nbsp;<a href="change_confid.php?sb_id=<?php echo $sbrow_resume["sb_id"];?>" class="small_link"  onClick="javascript: return confirm('<?php
if(	$sbrow_resume["sb_hide_info"]=='no')
{ echo "This will mark your resume as confidential. ";}
else
{ echo "This will mark your resume as non confidential. ";}
?>\nDo you want to continue?');"><?php echo($sbrow_resume["sb_hide_info"]=='no')?'Make Confidential':'Make Non Confidential'; ?></a>&nbsp;]&nbsp;<?php
							  }
							  ?>&nbsp;</font></td>
                            </tr></table>
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