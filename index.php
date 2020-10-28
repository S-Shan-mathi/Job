<?php 
function main()
{
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$policies=mysql_fetch_array(mysql_query("select * from sbjbs_policies"));
$cid=0;

	$suspended_list="-1";
	$mem_q=mysql_query("select * from sbjbs_employers where sb_suspended='yes'");
	while($mem=mysql_fetch_array($mem_q))
	{ $suspended_list.=",".$mem["sb_id"];}
	
	$disapproved_list="-1";
	$comp_q=mysql_query("select * from sbjbs_companies where 
	(sb_approved='no' OR sb_uid in ($suspended_list))");
	while($comp=mysql_fetch_array($comp_q))
	{ $disapproved_list.=",".$comp["sb_id"];}

?>
<table width="100%" border="0" cellspacing="10" cellpadding="2" class="maintablestyle">
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
        <tr> 
          <form name="form1" method="post" action="search_result.php">
            <td align="center" valign="middle" class="innertablestyle"><br> <input name="keyword" type="text" size="40"> 
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
      </table></td>
  </tr>
  <tr>
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="onepxtable">
        <tr> 
            <td align="justify" class="innertablestyle"><?php
			echo $policies["sb_welcome_msg"];
            ?></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <tr> 
          <td><font class="normal"><b>Browse Jobs</b></font></td>
        </tr>
        <tr > 
          <td class="onepxtable"><table width="100%" border="0" cellspacing="1" cellpadding="2" class="innertablestyle">
              <?
  	$cats_sql="select * from sbjbs_categories where sb_pid=$cid ";
	if($config["sb_cat_listing"]=="alpha")
	{
	$cats_sql.=" order by sb_cat_name";
	}
	else
	{
	$cats_sql.="  order by sb_order_index";
	}

	$cats_query=mysql_query($cats_sql);
	$cnt=1;
	while($cats=mysql_fetch_array($cats_query))
	{

		$rst_query=mysql_query("Select * from sbjbs_categories where sb_pid=".$cats["sb_id"] );
		$clist=$cats["sb_id"];
		while ( $rst=mysql_fetch_array($rst_query) )
		{
 				$clist.="," . $rst["sb_id"];
				$thislist="-1," . $rst["sb_id"];
				while ( $rst=mysql_fetch_array($rst_query) )
				{ 
					$clist.="," . $rst["sb_id"];
					$thislist.="," . $rst["sb_id"];
				//echo $rst["sbcat_id"];
				}
	   	$rst_query=mysql_query("Select * from sbjbs_categories where sb_pid in (" . $thislist . ")" );
		}
		
	$sbcat_str= " and  sb_cid IN (" .$clist . ")" ;
	
	$sbq_job_cat="select * from sbjbs_job_cats, sbjbs_jobs where sb_approved='yes' and sbjbs_jobs.sb_id=sbjbs_job_cats.sb_job_id and sb_company_id not in ($disapproved_list) $sbcat_str";
	
	$sbtotal=mysql_num_rows(mysql_query($sbq_job_cat));

//die();
	if($cnt%3==1)
	{
  ?>
              <tr> 
                <td width="20">&nbsp;</td>
                <?
  }
  ?>
                <td align="left"><font class='normal'> <a href="browsecats.php?cid=<? echo $cats["sb_id"];?>"> 
                  <? echo $cats["sb_cat_name"]; ?></a>(<? echo $sbtotal; ?>)</font></td>
                <?
  if($cnt%3==0)
  {
  ?>
              </tr>
              <?
  }
  	$cnt++;
}
?>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" class="onepxtable">
        <tr class="titlestyle"> 
          <td width="40%">&nbsp;Featured Jobs</td>
          <td width="40%">&nbsp;Company</td>
          <td>&nbsp;Experience</td>
          <!--td>&nbsp;</td-->
        </tr>
        <?php
        $ff_jobs_q=mysql_query("select * from sbjbs_jobs where sb_approved='yes' and 
		sb_front_featured='yes' and sb_company_id not in ($disapproved_list)");
		  $num_rows=mysql_num_rows($ff_jobs_q);
		  $max_allowed=$config["sb_featured_cnt"];
			unset($number);
			$number[0]=-1;
			if($num_rows>$max_allowed)
			{
				for($i=0;$i<$max_allowed;$i++)
				{
					$unique=0;
					while($unique==0)
					{
						$j=rand(0,$num_rows-1);
						for($k=0;$k<count($number);$k++)
						{
							//echo $j;
							if($number[$k]==$j)
							break;
						}
						if($k>(count($number)-1))
						{
							$unique=1;
						}
					}
					$number[$i]=$j;
							
				}
			}// end if num > no_allowed
					/*for($k=0;$k<count($number);$k++)
							{
								echo $number[$k]." ";
							}*/

		$row=0;
		$cnt=0;
		$ff_jobs=mysql_fetch_array($ff_jobs_q);
		while (($ff_jobs)&&($cnt<$max_allowed))
		{
			$display=0;
			if($num_rows>$max_allowed)
			{
				for($k=0;$k<count($number);$k++)
				{
					if($number[$k]==$row)
					{
						$display=1;
					}
				}
			}
			else
			{
				$display=1;
			}
			if($display==1)
			{
			$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
			where sb_id=".$ff_jobs["sb_company_id"]));
						
						$rec_class="innertablestyle";
						if($cnt%2==0)
						{ $rec_class="alternatecolor"; }
						if($ff_jobs["sb_highlight"]=="yes")
						{ 
						$rec_class="highlighted";
						if($cnt%2==0)
						{ $rec_class="highlighted1"; }
						}
						
		?>
        <tr class="<?php echo $rec_class;?>" height="25"> 
          <td>&nbsp;<font class="normal"><a href="view_job.php?sb_id=<?php echo $ff_jobs["sb_id"];?>"> 
            <?php
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "<b>";}
		   echo $ff_jobs["sb_title"];
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "</b>";}
		   ?>
            </a> </font></td>
          <td>&nbsp;<font class="normal"> 
            <?php
			if($comp["sb_show_profile"]=="yes")
			{ echo "<a href='view_profile.php?id=".$comp["sb_id"]."'>";} 
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "<b>";}
		  echo $comp["sb_name"];
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "</b>";}
			if($comp["sb_show_profile"]=="yes")
			{ echo "</a>";} 
		  ?>
            </font></td>
          <td>&nbsp;<font class="normal"> 
            <?php 
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "<b>";}
    	switch($ff_jobs["sb_experience"])
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
		
		   if($ff_jobs["sb_bold"]=="yes")
		   { echo "</b>";}
		?>
            </font></td>
          <!--td>&nbsp;</td-->
        </tr>
        <?php
			$cnt++;
			}//if display
		$ff_jobs=mysql_fetch_array($ff_jobs_q);
			$row++;
		}// end while
      ?>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" class="onepxtable">
        <tr class="titlestyle"> 
          <td width="40%">&nbsp;Latest Jobs</td>
          <td width="40%">&nbsp;Company</td>
          <td>&nbsp;Experience</td>
          <!--td>&nbsp;</td-->
        </tr>
        <?php
        $jobs_q=mysql_query("select * from sbjbs_jobs where sb_approved='yes' and sb_company_id not in ($disapproved_list) order by sb_id desc");
	    $num_rows=mysql_num_rows($ff_jobs_q);
		$cnt=0;
		while (($jobs=mysql_fetch_array($jobs_q))&&($cnt<$max_allowed))
		{
			$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
			where sb_id=".$jobs["sb_company_id"]));
						
						$rec_class="innertablestyle";
						if($cnt%2==0)
						{ $rec_class="alternatecolor"; }
						if($jobs["sb_highlight"]=="yes")
						{ 
						$rec_class="highlighted";
						if($cnt%2==0)
						{ $rec_class="highlighted1"; }
						}
						
		?>
        <tr class="<?php echo $rec_class;?>" height="25"> 
          <td>&nbsp;<font class="normal"><a href="view_job.php?sb_id=<?php echo $jobs["sb_id"];?>"> 
            <?php
		   if($jobs["sb_bold"]=="yes")
		   { echo "<b>";}
		   echo $jobs["sb_title"];
		   if($jobs["sb_bold"]=="yes")
		   { echo "</b>";}
		   ?>
            </a> </font></td>
          <td>&nbsp;<font class="normal"> 
            <?php
			if($comp["sb_show_profile"]=="yes")
			{ echo "<a href='view_profile.php?id=".$comp["sb_id"]."'>";} 
		   if($jobs["sb_bold"]=="yes")
		   { echo "<b>";}
		  echo $comp["sb_name"];
		   if($jobs["sb_bold"]=="yes")
		   { echo "</b>";}
			if($comp["sb_show_profile"]=="yes")
			{ echo "</a>";} 
		  ?>
            </font></td>
          <td>&nbsp;<font class="normal"> 
            <?php 
		   if($jobs["sb_bold"]=="yes")
		   { echo "<b>";}
    	switch($jobs["sb_experience"])
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
		
		   if($jobs["sb_bold"]=="yes")
		   { echo "</b>";}
		?>
            </font></td>
          <!--td>&nbsp;</td-->
        </tr>
        <?php
			$cnt++;
		}// end while
      ?>
      </table></td>
  </tr>
</table>
<?php
}
include_once'template.php';
?>