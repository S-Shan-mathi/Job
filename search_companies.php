<?php
include_once "myconnect.php";
function main()
{
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$recperpage=$config["sb_recperpage"];
$alpha_pass="A";
$strpass="";

if(isset($_REQUEST["alpha"])&&($_REQUEST["alpha"]<>""))
{
$strpass.="&alpha=".$_REQUEST["alpha"];
$alpha_pass=$_REQUEST["alpha"];
}
	$suspended_list="-1";
	$mem_q=mysql_query("select * from sbjbs_employers where sb_suspended='yes'");
	while($mem=mysql_fetch_array($mem_q))
	{ $suspended_list.=",".$mem["sb_id"];}
	

$sql="select * from sbjbs_companies where sb_approved='yes' and sb_uid not in ($suspended_list)";
$sql.=" and (sb_name like '$alpha_pass%')";
$sql.=" order by sb_name";
//echo $sql;
$comp_q=mysql_query($sql);
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center">&nbsp;&nbsp;<?
		  $alpha=65;
		  while($alpha<91)
		  {
		  if(chr($alpha)<>$alpha_pass)
		  {
		  ?><a href="search_companies.php?alpha=<? printf("%c",$alpha);?>"><? printf("%c",$alpha);?></a>&nbsp;&nbsp;<?
		  }
		  else
		  {
		  ?><font class="red"><strong><? printf("%c",$alpha);?></strong></font>&nbsp;&nbsp;<?
		  }
		  $alpha++;
		  }
		  ?></td>
              </tr>
            </table>
            
          </td>
        </tr>
        <tr> 
          <td valign="top"> <div align="center"> 
              <table width="90%" border="0" cellpadding="1" cellspacing="0">
                <tr> 
                  <td align="center" valign="top"> 
                    <table width="100%" border="0" cellspacing="2" cellpadding="0" class="onepxtable">
                      <tr align="center" class="titlestyle"> 
                        <td width="70%" height="25" align="left">&nbsp;Company 
                        </td>
                        <td align="left">&nbsp;Positions [Vacancies]</td>
                      </tr>
                      <?
					  $cnt=0;
                      while($comp=mysql_fetch_array($comp_q))
					  {
					  ?>
                      <tr align="center"> 
                        <td align="left" class="<? if($cnt%2<>0){echo "innertablestyle"; }else{echo "alternatecolor";}?>"><font class="smalltext"> 
                          &nbsp; 
                          <? 
						  if($comp["sb_show_profile"]=="yes")
						  {
						  echo "<a href='view_profile.php?id=".$comp["sb_id"]."' title='View company profile'>".$comp["sb_name"]."</a>";
						  }
						  else
						  {
						   echo $comp["sb_name"];
						  }
					  ?>
                          </font></td>
                        <td align="left" class="<? if($cnt%2<>0){echo "innertablestyle"; }else{echo "alternatecolor";}?>"><font class="smalltext">&nbsp;<? 
						$jobs=mysql_fetch_array(mysql_query("select sum(sb_vacancies) as total, count(*) as jobs from sbjbs_jobs where sb_approved='yes' and  sb_company_id=".$comp["sb_id"]));
						if($jobs["jobs"]>0)
						{
							echo "<a href='search_result.php?company=".$comp["sb_id"]."' title=\"View all jobs of ".$comp["sb_name"]."\">".$jobs["jobs"]."</a>&nbsp;[";
							if($jobs["total"]<>"")
							{
							echo $jobs["total"];
							}
							else
							{
							echo $config["sb_null_char"];
							}
							echo "]&nbsp;";
						}
						else
						{
							echo $config["sb_null_char"];
						}	
						?></font></td>
                      </tr>
                      <?
        			  $cnt++;            
					}// end while
					if($cnt==0)
					{
					 ?><tr><td colspan="2" align="center" height="25">
					 <font class="normal">No companies found satisfying your search criteria</font>
					 </td></tr><?php
					}
					?>
                    </table>
                  </td>
                </tr>
                <tr> 
                  <td align="center">&nbsp; </td>
                </tr>
              </table>
            </div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top">&nbsp;</td>
  </tr>
</table>
<?
}// end main
include "template.php";
?>