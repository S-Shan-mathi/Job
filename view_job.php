<?
include "session.php";
include_once "myconnect.php";
include_once "date_time_format.php";

if(!isset($_REQUEST["sb_id"]) || !is_numeric($_REQUEST["sb_id"]) || ($_REQUEST["sb_id"] < 1))
{
	header("Location: gen_confirm.php?errmsg=".urlencode("Invalid access, denied"));
	die();
}

/////-----------
	$suspended_list="-1";
	$mem_q=mysql_query("select * from sbjbs_employers where sb_suspended='yes'");
	while($mem=mysql_fetch_array($mem_q))
	{ $suspended_list.=",".$mem["sb_id"];}
	
	$disapproved_list="-1";
	$comp_q=mysql_query("select * from sbjbs_companies where 
	(sb_approved='no' OR sb_uid in ($suspended_list))");
	
	while($comp=mysql_fetch_array($comp_q))
	{ $disapproved_list.=",".$comp["sb_id"];}
	

///////---------	


	$sbq_job="select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_jobs where sb_approved='yes' and sb_id=".$_REQUEST["sb_id"]." and sb_company_id not in ($disapproved_list)";
	$sbrs_job=mysql_query($sbq_job);
	$sbrow_job=mysql_fetch_array($sbrs_job);
if(!$sbrow_job)
{
	header("Location: gen_confirm.php?errmsg=".urlencode("Invalid access, denied"));
	die();
}


function main()
{
///////just for icons
	global $sbico_apply_user, $sbico_apply_new, $sbico_refer_friend, $sbico_view_profile, $sbrow_job ;
/////////////////////
	$sbq_con="select * from sbjbs_config where sb_id=1";
	$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
	$sb_null_char=$sbrow_con["sb_null_char"];
//	$sb_icon_scheme=$sbrow_con["sb_icon_list"];
	
?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Job Requirements</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr valign="top"> 
                            <td width="150" class="subtitle">Title</td>
                            <td class="innertablestyle"><font class="normal"><?php echo $sbrow_job["sb_title"]; ?></font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Role</td>
                            <td class="innertablestyle"><font class="normal"><strong><?php echo $sbrow_job["sb_role"]; ?></strong></font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Category</td>
                            <td class="innertablestyle"> <font class="smalltext"> 
                              <?php 
			$sbq_cat_jobs="select * from sbjbs_job_cats where sb_job_id=".$sbrow_job["sb_id"];
			$sbrs_cat_jobs=mysql_query($sbq_cat_jobs);
			$sb_cat_list="-1";
			while( $sbrow_cat_jobs=mysql_fetch_array($sbrs_cat_jobs) )
				$sb_cat_list.=",".$sbrow_cat_jobs["sb_cid"];
							  
					  $cat_q=mysql_query("select * from sbjbs_categories where 
					  sb_id in (".$sb_cat_list.")");
						while($cat=mysql_fetch_array($cat_q))
						{
						$cat_path='<a href="search_result.php?cid='.$cat["sb_id"].'">'.$cat["sb_cat_name"].'</a>';
						$par_q=mysql_query("select * from sbjbs_categories where sb_id=".$cat["sb_pid"]);
						  while($par=mysql_fetch_array($par_q))
						  {
						   $cat_path='<a href="search_result.php?cid='.$par["sb_id"].'">'.$par["sb_cat_name"].'</a>'." - ".$cat_path;
						   $par_q=mysql_query("select * from sbjbs_categories where sb_id=".$par["sb_pid"]);
						  }
						echo $cat_path."<br>";
						}
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Location</td>
                            <td class="innertablestyle"><font class="smalltext"> 
                              <?php 
			$sbq_cat_jobs="select * from sbjbs_job_locs where sb_job_id=".$sbrow_job["sb_id"];
			$sbrs_cat_jobs=mysql_query($sbq_cat_jobs);
			$sb_loc_list="-1";
			while( $sbrow_cat_jobs=mysql_fetch_array($sbrs_cat_jobs) )
				$sb_loc_list.=",".$sbrow_cat_jobs["sb_lid"];

					  $loc_q=mysql_query("select * from sbjbs_locations where 
					  sb_id in (".$sb_loc_list.")");
						while($loc=mysql_fetch_array($loc_q))
						{
						$loc_path='<a href="search_result.php?loc_id='.$loc["sb_id"].'">'.$loc["sb_loc_name"].'</a>';
						$par_q=mysql_query("select * from sbjbs_locations where sb_id=".$loc["sb_pid"]);
						  while($par=mysql_fetch_array($par_q))
						  {
						   $loc_path='<a href="search_result.php?loc_id='.$par["sb_id"].'">'.$par["sb_loc_name"].'</a>'." - ".$loc_path;
						   $par_q=mysql_query("select * from sbjbs_locations where sb_id=".$par["sb_pid"]);
						  }
							echo $loc_path."<br>";
						}
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Company</td>
                            <td class="innertablestyle"><font class="normal"><strong> 
                              <? 
							$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies 
							where sb_id=".$sbrow_job["sb_company_id"]));
							
							echo $comp["sb_name"];?>
                              </strong></font>,&nbsp;&nbsp;<font class="smalltext">Vacancies 
                              <font class='red'>[ 
                              <?php
							echo ($sbrow_job["sb_vacancies"])?$sbrow_job["sb_vacancies"]:$sb_null_char; ?>
                              ]</font> (Posted on <?php echo sb_date($sbrow_job["sbposted"]); ?>)</font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Experience</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 
    	switch($sbrow_job["sb_experience"])
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
                            <td class="subtitle">Educational Qualification</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 	
					$sbq_cur="select * from sbjbs_degrees where sb_id=".$sbrow_job["sb_education"];
					//echo $sbq_cur;
					$sbrow_cur=mysql_fetch_array(mysql_query($sbq_cur));
					if($sbrow_cur)
						echo $sbrow_cur['sb_degree'];
					else
						echo 'No preference';	?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Skills</td>
                            <td class="innertablestyle"> <font class="normal"> 
                              <?php 
			$sbq_cat_jobs="select * from sbjbs_job_skls where sb_job_id=".$sbrow_job["sb_id"];
			$sbrs_cat_jobs=mysql_query($sbq_cat_jobs);
			$sb_loc_list="-1";
			while( $sbrow_cat_jobs=mysql_fetch_array($sbrs_cat_jobs) )
				$sb_loc_list.=",".$sbrow_cat_jobs["sb_sid"];
					  
					  $cat_q=mysql_query("select * from sbjbs_skills where 
					  sb_id in (".$sb_loc_list.")");
						while($cat=mysql_fetch_array($cat_q))
						{
						$cat_path= $cat["sb_name"];
//						$par_q=mysql_query("select * from sbjbs_categories where sb_id=".$cat["sb_pid"]);
//						  while($par=mysql_fetch_array($par_q))
//						  {
//						   $cat_path=$par["sb_cat_name"]." - ".$cat_path;
//						   $par_q=mysql_query("select * from sbjbs_categories where sb_id=".$par["sb_pid"]);
//						  }
						echo $cat_path."<br>";
						}
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Salary</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php if( ($sbrow_job["sb_min_salary"] > 0) || ($sbrow_job["sb_max_salary"] > 0) )
	  		{	
				$sbq_cur='select * from sbjbs_currencies where sbcur_id='.$sbrow_job["sb_currency"];
			 	$sbrow_cur=mysql_fetch_array(mysql_query($sbq_cur));	
				echo ($sbrow_job["sb_min_salary"]>0)?"Minimum - ".number_format($sbrow_job["sb_min_salary"],2):'';
				echo ( ($sbrow_job["sb_min_salary"]>0) && ($sbrow_job["sb_max_salary"]>0) )?', ':'';
				echo ($sbrow_job["sb_max_salary"]>0)?"Maximum - ".number_format($sbrow_job["sb_max_salary"],2).' ':' ';
				echo $sbrow_cur["sbcur_name"].' ';
				switch($sbrow_job["sb_salary_type"])
				{
                	case 0: echo $sb_null_char; break;
					case 1: echo "Per Year"; break;
					case 2: echo "Per Month"; break;
					case 3: echo "Per Week"; break;
					case 4: echo "Fortnightly"; break;
					case 5: echo "Per Hour"; break;
				}	//end switch
				
			}	//end if sbrow_job["sb_min_salary"]
			else
				echo $sb_null_char;	?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Career Level</td>
                            <td class="innertablestyle"><font class="normal"><?php 
							switch($sbrow_job["sb_career_level"])
							{ 
							 case 1: echo "Student"; break;
							 case 2: echo "Student (High School)"; break;
							 case 3: echo "Entry Level (less than 2 years experience)"; break;
							 case 4: echo "Mid Career (2+ years of experience)"; break;
							 case 5: echo "Management (Manager/Director of Staff)"; break;
							 case 6: echo "Executive (SVP, EVP, VP)"; break;
							 case 7: echo "Senior Executive (President, CEO)"; break;
							}
							?></font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Job Type</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 
			if(($sbrow_job["sb_job_type"]&1)==1) echo "Permanent<br>";
			if(($sbrow_job["sb_job_type"]&2)==2) echo "Intern / Work Experience<br>";
			if(($sbrow_job["sb_job_type"]&4)==4) echo "Temporary/Contract/Project";	?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Job Status</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 
			if(($sbrow_job["sb_job_status"]&1)==1) echo "Full Time<br>";
			if(($sbrow_job["sb_job_status"]&2)==2) echo "Part Time";?>
                              </font></td>
                          </tr>
                          <tr valign="top" class="innertablestyle"> 
                            <td colspan="2" valign="bottom"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr valign="bottom"> 
                                  <td class="innertablestyle"><a href="apply_now.php?sb_id=<?php echo $sbrow_job["sb_id"]; ?>" title="Existing users apply now" ><img src="<?php echo $sbico_apply_user; ?>" border="0"></a></td>
                                  <td class="innertablestyle"><a href="signup.php" title="New users signup now" ><img src="<?php echo $sbico_apply_new; ?>" border="0"></a></td>
                                  <td class="innertablestyle"><a href="refer_friend.php?sb_id=<?php echo $sbrow_job["sb_id"];?>" title="Refer to a friend" ><img src="<?php echo $sbico_refer_friend; ?>" border="0"></a></td>
	<?php 	$sbq_com="select * from sbjbs_companies where sb_uid=".$sbrow_job["sb_uid"]." and sb_show_profile='yes'";
		  	$sbrow_com=mysql_fetch_array(mysql_query($sbq_com));
		  	if($sbrow_com)
		  	{	?><td class="innertablestyle"><a href="view_profile.php?id=<?php echo $sbrow_com["sb_id"]; ?>" title="View company profile" ><img src="<?php echo $sbico_view_profile; ?>" border="0"></a></td>
                                  <?php 					}		//end if sb_show_profile==yes?>
                                </tr>
                              </table></td>
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
        <tr>
          <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Description</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr valign="top"> 
                            <td class="innertablestyle"><font class="normal"><?php 
							echo str_replace("\n","<br>",$sbrow_job["sb_description"]); ?></font></td>
                          </tr>
                          <tr valign="top" class="innertablestyle"> 
                            <td valign="bottom"> <table width="100%" border="0" cellspacing="1" cellpadding="0">
                                <tr valign="bottom">
                                  <td class="innertablestyle"><a href="apply_now.php?sb_id=<?php echo $sbrow_job["sb_id"]; ?>" title="Existing users apply now" ><img src="<?php echo $sbico_apply_user; ?>" border="0"></a></td>
                                  <td class="innertablestyle"><a href="signup.php" title="New users signup now" ><img src="<?php echo $sbico_apply_new; ?>" border="0"></a></td>
                                  <td class="innertablestyle"><a href="refer_friend.php?sb_id=<?php echo $sbrow_job["sb_id"];?>" title="Refer to a friend" ><img src="<?php echo $sbico_refer_friend; ?>" border="0"></a></td>
                                  <?php 	if($sbrow_com)
		  	{	?><td class="innertablestyle"><a href="view_profile?id=<?php echo $sbrow_com["sb_id"]; ?>" title="View company profile" ><img src="<?php echo $sbico_view_profile; ?>" border="0"></a></td>
                                  <?php 					}		//end if sb_show_profile==yes?>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
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