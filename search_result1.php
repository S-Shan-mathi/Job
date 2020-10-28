<?
//include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sbq_job="select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_jobs where sb_approved='yes' ";
	$sbq_job.=" order by sb_featured desc, sb_id desc";

	$sbq_job=mysql_query($sbq_job);
	$num_job=mysql_num_rows($sbq_job);
	
	$label=1;
	$featured_label=1;

?> 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top"> 
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        
                    <?php 
					$cnt=0;
						while($job=mysql_fetch_array($sbq_job))
					  	{
						$cnt++;	
						
						$rec_class="innertable";
						if($cnt%2==0)
						{ $rec_class="alternatecolor"; }
						if($job["sb_highlight"]=="yes")
						{ 
						$rec_class="highlighted";
						if($cnt%2==0)
						{ $rec_class="highlighted1"; }
						}
						
						
						if(($job["sb_featured"]=="yes") && ($featured_label==1))
						{
						?><tr > <td valign="top" class="onepxtable"> <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="innertablestyle">
						<tr> 
                <td height="25" class="titlestyle">&nbsp;Featured Jobs<font class="smalltext">&nbsp; 
                  </font></td>
              </tr>
              <tr> 
                <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <?
						$featured_label=0;
						}
						elseif(($label==1))
						{
								if($featured_label==0)
								{ ?>
                  </table></td>
              </tr></table></td></tr><tr><td>&nbsp;</td></tr><?	 }
								
						?><tr > <td valign="top" class="onepxtable"> <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="innertablestyle"><tr> 
                <td height="25" class="titlestyle">&nbsp;All Jobs<font class="smalltext">&nbsp; 
                  </font></td>
              </tr>
              <tr> 
                <td align="left" valign="top"> 
				<table width="100%" border="0" cellpadding="2" cellspacing="0"><?
						$label=0;
						}
							?>
                    <tr valign="top"> 
                      <td><table width="100%" border="0" cellspacing="2" cellpadding="2" class="<? echo $rec_class;?>">
                          
                          <tr > 
                            <td><a href="view_job.php?sb_id=<?php echo $job["sb_id"]; ?>"><?php 
								  echo $job["sb_title"];
								  ?></a> </td>
                          </tr>
                          <tr> 
                            <td><font class="normal"><? echo $job["sb_role"];?></font></td>
                          </tr>
                          <tr> 
                            <td><font class="normal"><? 
							$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
							where sb_id=".$job["sb_company_id"]));
							
							echo $comp["sb_name"];?></font>,&nbsp;&nbsp;<font class="smalltext"><?php
							echo "Vacancies <font class='red'>[ ";
							if($job["sb_vacancies"]>0)
							echo $job["sb_vacancies"];
							else
							echo $config["sb_null_char"];
							echo " ]</font>";
							
							 ?></font></td>
                          </tr>
                          <tr> 
                            <td></td>
                          </tr>
                        </table></td>
                    </tr>
                    <?php 
						}		//end while 
                        ?>
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
</table>
<?
}// end main
include "template.php";
?>
