<?
//include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$recperpage=$config["sb_recperpage"];
	$sb_null_char=$config["sb_null_char"];
	$strpass="";
	$keyword="";
	$catpath="";
	$searchkeyword="";
	$cid_list="";
	$loc_id_list="";
	$view="desc";
	
	$suspended_list="-1";
	$mem_q=mysql_query("select * from sbjbs_employers where sb_suspended='yes'");
	while($mem=mysql_fetch_array($mem_q))
	{ $suspended_list.=",".$mem["sb_id"];}
	
	$disapproved_list="-1";
	$comp_q=mysql_query("select * from sbjbs_companies where 
	(sb_approved='no' OR sb_uid in ($suspended_list))");
	
	while($comp=mysql_fetch_array($comp_q))
	{ $disapproved_list.=",".$comp["sb_id"];}
	if(isset($_REQUEST["keyword"])&&($_REQUEST["keyword"]<>""))
	{
		$keyword=$_REQUEST["keyword"];
		$strpass.="&keyword=".$_REQUEST["keyword"];
		if (!get_magic_quotes_gpc()) 
		{
		$searchkeyword=str_replace('$', '\$',addslashes($_REQUEST["keyword"]));
		}
		else
		{
		$searchkeyword=str_replace('$', '\$',$_REQUEST["keyword"]);
		}
		$searchkeyword=trim($searchkeyword);
	}
	if(isset($_REQUEST["cid_list"])&&($_REQUEST["cid_list"]<>""))
	{
	$strpass.="&cid_list=".$_REQUEST["cid_list"];
	$cid_list=str_replace(";",",",$_REQUEST["cid_list"]);
	//$cid_list=explode(";",$_REQUEST["cid_list"]);
	}
	$cid=0;
	if(isset($_REQUEST["cid"])&&($_REQUEST["cid"]<>""))
	{
	$strpass.="&cid=".$_REQUEST["cid"];
			$cid=$_REQUEST["cid"];
			$c_list=$_REQUEST["cid"];
			$child_cat=mysql_query("select * from sbjbs_categories where sb_pid=".$_REQUEST["cid"]);
				
				while ( $child=mysql_fetch_array($child_cat) )
				{
					$c_list.="," . $child["sb_id"];
					while ( $child=mysql_fetch_array($child_cat) )
					{ 
						$c_list.="," . $child["sb_id"];
					}
						
				$child_cat=mysql_query("Select * from sbjbs_categories where 
				sb_pid IN (" . $c_list . ") and sb_id not in ( ". $c_list . ")") ;
				}
		$cid_list=$c_list;
		}
	
	//=================================catpath code
	$catname="";
	$category=0;
  	
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $cid );
	if ($cat=mysql_fetch_array($cat_query))
	{
	$catname=$cat["sb_cat_name"];
	$category=$cat["sb_id"];
	}

	$catpath="";
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $cid );
	while ($rs=mysql_fetch_array($cat_query))
    {
    $catpath =" > <a href=\"browsecats.php?cid=" . $rs["sb_id"] . "\">" .$rs["sb_cat_name"]."</a>".$catpath; 
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $rs["sb_pid"] );
	 
	}
//================================== LOC SEARCH [MULTI LOCATION CODE] ====================================
	if(isset($_REQUEST["loc_id"])&&($_REQUEST["loc_id"]<>""))
	{
		$strpass.="&loc_id=".$_REQUEST["loc_id"];
		$loc_id_list=str_replace(";",",",$_REQUEST["loc_id"]);
		$loc_q=mysql_query("select * from sbjbs_locations where sb_id in ($loc_id_list)");
		while($loc=mysql_fetch_array($loc_q))
		  {
		  	$clist=$loc["sb_id"];
			$child_loc=mysql_query("select * from sbjbs_locations where sb_pid=".$loc["sb_id"]);
				
				while ( $child=mysql_fetch_array($child_loc) )
				{
					$clist.="," . $child["sb_id"];
					while ( $child=mysql_fetch_array($child_loc) )
					{ 
						$clist.="," . $child["sb_id"];
					}
						
				$child_loc=mysql_query("Select * from sbjbs_locations where 
				sb_pid IN (" . $clist . ") and sb_id not in ( ". $clist . ")") ;
				}

		  }
		$loc_id_list.=",".$clist;
		//$loc_id_list=explode(",",$loc_id_list);
	}
	
	$sbq_job="select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_jobs where sb_approved='yes' and sb_company_id not in ($disapproved_list) ";
//===================================== keyword search ==================================================
	if($searchkeyword<>"")
	{
	if(isset($_REQUEST["search_method"])&&(($_REQUEST["search_method"]==2)||($_REQUEST["search_method"]==3)))
		{
			$log_operator="OR";
			if($_REQUEST["search_method"]==2)
			$log_operator="AND";
			
			$search_str="";
			$keyword_arr=explode(" ",$searchkeyword);
			foreach($keyword_arr as $key)
			{
				if($search_str=="")
				{
				$search_str="(sb_title like '%$key%' or sb_description like '%$key%' 
				or sb_role like '%$key%') ";
				}
				else
				{
				$search_str.=" $log_operator (sb_title like '%$key%' or sb_description like '%$key%' 
				or sb_role like '%$key%') ";
				}
			}
			$sbq_job.=" and ($search_str)";
		}// end if AND/ OR keywords	
		else
		{
		$sbq_job.=" and (sb_title like '%$searchkeyword%' or sb_description like '%$searchkeyword%' 
		or sb_role like '%$searchkeyword%') ";
		}
}	
	//============================================================
//=======================category search==========================
if((count($cid_list)>0)&&($cid_list<>""))
{
	$cat_str="-1";
	$job_cat_q=mysql_query("select * from sbjbs_job_cats where sb_cid in ($cid_list)");
	while($job_cat=mysql_fetch_array($job_cat_q))
	{
		$cat_str.=",".$job_cat["sb_job_id"];
	}	
	
	$sbq_job.=" and sb_id in ($cat_str)";
}
//================================================================
//=======================Location search==========================
if((count($loc_id_list)>0)&&($loc_id_list<>""))
{
	$loc_str="-1";
	$job_loc_q=mysql_query("select * from sbjbs_job_locs where sb_lid in ($loc_id_list)");
	while($job_loc=mysql_fetch_array($job_loc_q))
	{
		$loc_str.=",".$job_loc["sb_job_id"];
	}	
	
	$sbq_job.=" and sb_id in ($loc_str)";
}
//================================================================
//===================================== Experience Search =================================
if(isset($_REQUEST["work_exp"])&&($_REQUEST["work_exp"]<>""))
{
	$strpass.="&work_exp=".$_REQUEST["work_exp"];
	$sbq_job.=" and (sb_experience=".$_REQUEST["work_exp"]." or sb_experience=-1)";
}
//===================================Company Search===========================================
if(isset($_REQUEST["company"]) &&($_REQUEST["company"]<>""))
{
	$strpass.="&company=".$_REQUEST["company"];
	$sbq_job.=" and sb_company_id=".$_REQUEST["company"];
}
//=========================================================================================
	$sbq_job.=" order by sb_featured desc, sb_id desc";
	//echo $sbq_job;
	$sbq_job=mysql_query($sbq_job);
	$num_job=mysql_num_rows($sbq_job);
	$strpass1=$strpass;
	if(isset($_REQUEST["view"])&&($_REQUEST["view"]<>""))
	{
	$strpass.="&view=".$_REQUEST["view"];
	$view=$_REQUEST["view"];
	}

///////////////////////////////////PAGINATION /////////
	if(!isset($_REQUEST["pg"]))
	{
			$pg=1;
	}
	else 
	{
	$pg=$_REQUEST["pg"];
	}
	
$rcount=mysql_num_rows($sbq_job);
if ($rcount==0 )
{ 
	$pages=0;
}	
else
{
	$pages=floor($rcount / $recperpage);
	if  (($rcount%$recperpage) > 0 )
	{
		$pages=$pages+1;
	}
}
$jmpcnt=1;
while ( $jmpcnt<=($pg-1)*$recperpage  && $row = mysql_fetch_array($sbq_job) )
    {	
		$jmpcnt = $jmpcnt + 1;
	}

//////////////////////////////////////////////////////////////////////// 
	
	$label=1;
	$featured_label=1;

?> 
<script language="JavaScript">
function submit_form()
{
	document.save_search.action="save_search_popup.php";
	window.open("","win","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=530,height=220,location=no,directories=no,scrollbars=no");
	document.save_search.method="post";
	document.save_search.target="win";
	document.save_search.submit();
}
</script>
<table width="100%" border="0" cellspacing="10" cellpadding="2" class="maintablestyle">
<tr> 
    <td valign="top">
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
        <tr> 
          <form name="form1" method="post" action="search_result.php">
            <td align="center" valign="middle" class="innertablestyle"><br> 
            <input name="keyword" type="text" value="<? echo $keyword;?>" size="40"> 
              &nbsp;&nbsp; <select name="loc_id">
                <option value="" selected>Any Location</option>
                <?php
				$loc_query=mysql_query("select * from sbjbs_locations where sb_pid=0 order by sb_default desc,sb_loc_name");
				while($loc=mysql_fetch_array($loc_query))
				{
                ?>
                <option value="<?php echo $loc["sb_id"];?>"><?php echo $loc["sb_loc_name"];
				?></option>
                <?php 
				}
				?>
              </select> &nbsp;<font class='normal'> 
              <input name="show_save" type="hidden" id="show_save" value="yes">
              </font>&nbsp; <input type="submit" name="Submit" value="Search">
            &nbsp;<a href="advance_search.php">Advanced Search</a> <br> &nbsp; 
            </td>
          </form>
        </tr>
        <tr>
          <td height="25" valign="middle" class="alternatecolor">&nbsp;<a href="index.php">Home</a><?php echo $catpath;?></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
          <td >
    <table width="90%" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><font class="normal">Total <font class="red"><b><?php echo $rcount;?></b></font> Job(s) Found</font></td><?php 
		  if(isset($_SESSION["sbjbs_userid"]) && isset($_REQUEST["show_save"]) &&($rcount > 0))
		  {
          ?><form name="save_search"  id="save_search" method="post"><td> <div align="right"> 
              <input name="view" type="hidden" id="view" value="<?php echo (isset($_REQUEST["view"]))?$_REQUEST["view"]:''; ?>">
              <input name="cid_list" type="hidden" id="cid_list" value="<?php echo (isset($_REQUEST["cid_list"]))?$_REQUEST["cid_list"]:''; ?>">
              <input name="loc_id" type="hidden" id="loc_id" value="<?php echo (isset($_REQUEST["loc_id"]))?$_REQUEST["loc_id"]:''; ?>">
              <input name="work_exp" type="hidden" id="work_exp" value="<?php echo (isset($_REQUEST["work_exp"]))?$_REQUEST["work_exp"]:''; ?>">
              <input name="search_method" type="hidden" id="search_method" value="<?php echo (isset($_REQUEST["search_method"]))?$_REQUEST["search_method"]:'0'; ?>">
              <input name="keyword" type="hidden" id="keyword" value="<?php echo $keyword; ?>">
              <input type="button" name="Submit2" value="Save Search Result" onClick="submit_form()">
            </div></td></form><?
			}
        ?></tr>
      
    </table>
    </td>
			 </tr>
		<tr> 
    <td valign="top"> 
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <?php 
					$cnt=0;
						while(($job=mysql_fetch_array($sbq_job))&&($cnt<$recperpage))
					  	{
						$cnt++;	
						
						$rec_class="innertablestyle";
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
						?>
        <tr > 
          <td colspan="2" valign="top" class="onepxtable"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
              <tr> 
                <td height="25" class="titlestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="titlestyle">
                  <tr>
                    <td>&nbsp;Featured Jobs</td><?php
					if($view=="brief")
                    {
					?><td width="40%">&nbsp;Company</td>
                    <td width="20%">&nbsp;Experience</td><?php
					}
                  ?></tr>
                </table></td>
              </tr>
              <tr> 
                <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <?
						$featured_label=0;
						}
						if(($job["sb_featured"]=="no") &&($label==1))
						{
								if($featured_label==0)
								{ ?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td colspan="2">&nbsp;</td>
        </tr>
        <?	 }
								
						?>
        <tr > 
          <td colspan="2" valign="top" class="onepxtable"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
              <tr> 
                <td height="25" class="titlestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="titlestyle">
                  <tr> 
                    <td>&nbsp;All Jobs</td>
                    <?php
					if($view=="brief")
                    {
					?>
                    <td width="40%">&nbsp;Company</td>
                    <td width="20%">&nbsp;Experience</td>
                    <?php
					}
                  ?>
                  </tr>
                </table></td>
              </tr>
              <tr> 
                <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                    <?
						$label=0;
						}
							?>
                    <tr valign="top"> 
                      <td><?
						  if($view=="desc")
						  {
                          ?>
                      <table width="100%" border="0" cellspacing="1" cellpadding="1" class="<? echo $rec_class;?>">
                        <tr> 
                          <td><a href="view_job.php?sb_id=<?php echo $job["sb_id"]; ?>"> 
                            <?php 
							if($job["sb_bold"]=="yes")
							{ echo "<strong>";}
							 echo $job["sb_title"];
							if($job["sb_bold"]=="yes")
							{ echo "</strong>";}
							?>
                            </a></td>
                        </tr>
                        <tr> 
                          <td><table width="100%" border="0" cellspacing="2" cellpadding="0">
                              <tr valign="top"> 
                                <td ><font class="smalltext"><strong>Role</strong></font></td>
                                <td ><font class="normal"><? echo $job["sb_role"];?></font></td>
                              </tr>
                              <tr valign="top"> 
                                <td width="25%" ><font class="smalltext"><strong>Experience 
                                  Level</strong></font></td>
                                <td width="75%" ><font class="normal"> 
                                  <?php 
    	switch($job["sb_experience"])
		{
			case -1:	echo 'No preference'; break;
			case 0:		echo 'Fresher'; break;
			case 1:		echo 'Less than 1 Year'; break;
			case 2:		echo '1 to 2 Years'; break;
			case 3:		echo '2 to 5 Years'; break;
			case 4:		echo '5 to 7 Years'; break;
			case 5:		echo '7 to 10 Years'; break;
			case 6:		echo '10 to 15 Years'; break;
			case 7:		echo 'More than 15 Years'; break;
		}	//end switch ?>
                                  </font> </td>
                              </tr>
                              <tr valign="top"> 
                                <td width="25%" ><font class="smalltext"><strong>Educational 
                                  Qualification</strong></font></td>
                                <td ><font class="normal"> 
                                  <?php 	
					$sbq_cur="select * from sbjbs_degrees where sb_id=".$job["sb_education"];
					//echo $sbq_cur;
					$sbrow_cur=mysql_fetch_array(mysql_query($sbq_cur));
					if($sbrow_cur)
						echo $sbrow_cur['sb_degree'];
					else
						echo 'No preference';	?>
                                  </font></td>
                              </tr>
                              <tr valign="top"> 
                                <td width="25%" ><font class="smalltext"><strong>Salary</strong></font></td>
                                <td ><font class="normal"> 
                                  <?php if( ($job["sb_min_salary"] > 0) || ($job["sb_max_salary"] > 0) )
	  		{	
				$sbq_cur='select * from sbjbs_currencies where sbcur_id='.$job["sb_currency"];
			 	$sbrow_cur=mysql_fetch_array(mysql_query($sbq_cur));	
				echo ($job["sb_min_salary"]>0)?"Minimum - ".number_format($job["sb_min_salary"],2):'';
				echo ( ($job["sb_min_salary"]>0) && ($job["sb_max_salary"]>0) )?', ':'';
				echo ($job["sb_max_salary"]>0)?"Maximum - ".number_format($job["sb_max_salary"],2).' ':' ';
				echo $sbrow_cur["sbcur_name"].' ';
				switch($job["sb_salary_type"])
				{
                	case 0: echo $sb_null_char; break;
					case 1: echo "Per Year"; break;
					case 2: echo "Per Month"; break;
					case 3: echo "Per Week"; break;
					case 4: echo "Fortnightly"; break;
					case 5: echo "Per Hour"; break;
				}	//end switch
				
			}	//end if job["sb_min_salary"]
			else
				echo $sb_null_char;	?>
                                  </font></td>
                              </tr>
                              <tr valign="top"> 
                                <td ><font class="smalltext"><strong>Description</strong></font></td>
                                <td ><font class="normal"><font class="smalltext"> 
                                  <?php 
							
							if (strlen ($job["sb_description"])>150)
							{
							 echo substr($job["sb_description"], 0,  strrpos( substr($job["sb_description"], 0, 150),' ' ))." ...";  
							}
							else
							{
							 echo $job["sb_description"];  
							}
							?>
                                  </font></font></td>
                              </tr>
                              <tr valign="top"> 
                                <td ><font class="smalltext"><strong>Company</strong></font></td>
                                <td ><font class="normal"> 
                                  <? 
							$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
							where sb_id=".$job["sb_company_id"]));
							
							echo $comp["sb_name"];?>
                                  </font>,&nbsp;&nbsp;<font class="smalltext"> 
                                  <?php
							echo "Vacancies <font class='red'>[ ";
							if($job["sb_vacancies"]>0)
							echo $job["sb_vacancies"];
							else
							echo $config["sb_null_char"];
							echo " ]</font>";
							
							echo "&nbsp;&nbsp;Posted on:&nbsp;".sb_date($job["sbposted"]); 
							 ?>
                                  </font></td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr> 
                          <td></td>
                        </tr>
                      </table>
                      <?php
						  }//if descriptive view
						  else
						  {
                          ?>
                      <table width="100%" border="0" cellspacing="1" cellpadding="1" class="<? echo $rec_class;?>">
                        <tr> 
                          <td width="40%"><a href="view_job.php?sb_id=<?php echo $job["sb_id"]; ?>"> 
                            <?php 
							if($job["sb_bold"]=="yes")
							{ echo "<strong>";}
							 echo $job["sb_title"];
							if($job["sb_bold"]=="yes")
							{ echo "</strong>";}
							?>
                            </a></td>
                          <td width="40%"><font class="normal"> 
                            <? 
							$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
							where sb_id=".$job["sb_company_id"]));
							
							if($job["sb_bold"]=="yes")
							{ echo "<strong>";}
							echo $comp["sb_name"];
							
							if($job["sb_bold"]=="yes")
							{ echo "</strong>";}
							?>
                            </font></td>
                          <td><font class="normal"> 
                            <?php 
							if($job["sb_bold"]=="yes")
							{ echo "<strong>";}
    	switch($job["sb_experience"])
		{
			case -1:	echo 'No preference'; break;
			case 0:		echo 'Fresher'; break;
			case 1:		echo 'Less than 1 Year'; break;
			case 2:		echo '1 to 2 Years'; break;
			case 3:		echo '2 to 5 Years'; break;
			case 4:		echo '5 to 7 Years'; break;
			case 5:		echo '7 to 10 Years'; break;
			case 6:		echo '10 to 15 Years'; break;
			case 7:		echo 'More than 15 Years'; break;
		}	//end switch 
							if($job["sb_bold"]=="yes")
							{ echo "</strong>";}
							?>
                            </font></td>
                        </tr>
                        <tr> 
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </table>
                      <?php
						  }// end if brief view
						  ?>
                    </td>
                    </tr>
                    <?php 
						}		//end while 
						if($cnt==0)
						{?><tr> 
          					<td colspan="2" valign="top" class="onepxtable"> 
							<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
              <tr> 
                <td height="25" class="titlestyle">&nbsp;All Jobs<font class="smalltext">&nbsp; 
                  </font></td>
              </tr>
              <tr> 
                <td align="center" height="25">
				<font class='normal'>No jobs found satisfying your search criteria</font></td></tr><?php 
				}
                  ?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table>
      
      <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr ></tr>
        <?PHP
		if($pages>0)
		{
        ?>
        <tr valign="top"> 
          <td><font class="normal">
            <?php
		  	if($pages>1)
			{	
			echo "Page $pg of $pages<br>";	
			}
			?>
            </font></td>
          <td width="40%" align="right"><font class="normal"> 
            <?php 
		  if($view=="desc")
		  {
		  echo "<a href='".$_SERVER['PHP_SELF']."?view=brief&pg=$pg".$strpass1."'>Brief</a>";
		  echo "&nbsp;|&nbsp;Descriptive";
		  }
		  else
		  {
		  echo "Brief&nbsp;|&nbsp;";
		  echo "<a href='".$_SERVER['PHP_SELF']."?view=desc&pg=$pg".$strpass1."'>Descriptive</a>";
		  }
		  ?>
            </font></td>
        </tr>
        <tr valign="top"> 
          <td colspan="2"> <TABLE border=0 cellPadding=0 cellSpacing=0>
              <TBODY>
                <TR> 
                  <TD> <font class="normal"> 
                    <?
			if($pages>1)
			{	
			//echo "Page $pg of $pages<br>";	
			if ($pg!=1)
			{
			?>
                    <a  href="<? echo $_SERVER['PHP_SELF'];?>?pg=<?php echo ($pg-1).$strpass; ?>" > 
                    <?
			 }
			?>
                    Prev 
                    <?
			if ($pg!=1)
			{
			?>
                    </a> 
                    <?php
			} 
			?>
                    <B>&nbsp; 
                    <?
			if ($pages>1)
			{
				?>
                    </B> 
                    <?php
			if ($pg<=5)
			{
				$jmpcnt=1;
			}
			else
			{
			  $jmpcnt=$pg-5;
			}
			$cnt=0;

			while (  $jmpcnt<=$pages   && ($cnt<=5) )
   			{	
			$cnt++;
		   if ($jmpcnt!=$pg)
		   {
		   ?>
                    <a href="<? echo $_SERVER['PHP_SELF'];?>?pg=<?php echo "$jmpcnt$strpass"; ?>" > 
                    <?
			}
			else
			{
			echo "<b>";
			}
			echo $jmpcnt;
    	   if ($jmpcnt!=$pg)
		   {
		   ?>
                    </a> 
                    <?php
			}else{
			echo "</b>";
			}
			if ($jmpcnt<$pages)
            echo " &nbsp; ";
            ?>
                    <?php
		    $jmpcnt = $jmpcnt + 1;
    	    }
			?>
                    &nbsp;</font> <font class="normal"> 
                    <?
				}
				
			if ( $pg!=$pages && $pages<>0)
			{
			?>
                    <a   href="<? echo $_SERVER['PHP_SELF'];?>?pg=<?php echo ($pg+1); ?><?php echo "$strpass"; ?>" > 
                    <?
			 }
			?>
                    Next 
                    <? if ($pg!=$pages && $pages<>0)
			{
			?>
                    </a> 
                    <?
			 }
			}
			?>
                    </font> </TD>
                </TR>
              </TBODY>
            </TABLE></td>
        </tr>
        <?php
		}
      ?>
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