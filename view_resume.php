<?
include "session.php";
include_once "myconnect.php";
include_once "date_time_format.php";


$sbspecial_str='';
if(isset($_SESSION["sbjbs_userid"]))
	$sbspecial_str=" or sb_seeker_id=".$_SESSION["sbjbs_userid"];

$sbq_resume="select * from sbjbs_resumes where sb_id=".$_REQUEST["resume_id"]." and (sb_approved='yes'  $sbspecial_str)";
$sb_resume=mysql_num_rows(mysql_query($sbq_resume));
if($sb_resume<=0)
{
header("Location:"."gen_confirm.php?errmsg=".urlencode("Invalid access, unable to continue."));
die();
}

function main()
{
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sbq_resume="select *,UNIX_TIMESTAMP(sb_posted_on) as sbposted from sbjbs_resumes 
	where sb_id=".$_REQUEST["resume_id"];

	$sbq_resume=mysql_query($sbq_resume);
	$num_resume=mysql_num_rows($sbq_resume);
	$resume=mysql_fetch_array($sbq_resume);
	$imgpmt=" width=80 height=100 ";
	$isowner="no";
	
	if(isset($_SESSION["sbjbs_userid"])&&($_SESSION["sbjbs_userid"]==$resume["sb_seeker_id"]))
	{
	$isowner="yes";
	}
	if(preg_match("/\/manage_applications.php/",$_SERVER['HTTP_REFERER']))
	{
	$isowner="yes";
	}
?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0"><?php
	  if(($resume["sb_hide_info"]<>"yes") || ($isowner=="yes"))
	  {
        ?><tr> 
          <td valign="top">
		  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Contact Info</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                          <tr valign="top"> 
                            <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php 
								  echo $resume["sb_firstname"]." ".$resume["sb_lastname"];
								  ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php
                                       echo $resume["sb_addr1"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php
                                     
								  echo $resume["sb_city"].", ".$resume["sb_state"]." - ".$resume["sb_zip"];
                                     ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp; 
                                    <?php 
                                    
								$country=mysql_fetch_array(mysql_query("select * from sbjbs_country 
								where id=".$resume["sb_country"]));
								
								echo $country["country"];
								  
                                    ?>
                                    </font></td>
                                </tr>
                              </table></td>
                            <td width="50%"> <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td><font class="smalltext">&nbsp;<b>Phone:&nbsp;</b> 
                                    <?php
                                       echo $resume["sb_telephone"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp;<b>Mobile:&nbsp;</b> 
                                    <?php
                                       echo $resume["sb_mobile"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp;</font></td>
                                </tr>
                                <tr> 
                                  <td><font class="smalltext">&nbsp;<b>Email:&nbsp;</b> 
                                    <?php
                                       echo $resume["sb_email"];
								    
                                    ?>
                                    </font></td>
                                </tr>
                              </table></td>
                            <td width="120"> 
                              <?php
							if($resume["sb_img_url"]<>"")
							{
							?>
                              <img src="uploadedimages/<?php echo $resume["sb_img_url"]; ?>" border=0 <?php echo $imgpmt;?>> 
                              <?php
							}
							?>
                            </td>
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
        </tr><?php
		}// if not hidden
        ?><tr> 
          <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Resume</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr valign="top"> 
                            <td width="150" class="subtitle">Headline</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 
								  echo $resume["sb_title"];
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Objective</td>
                            <td class="innertablestyle"><font class="normal"> 
                              <?php 
								  echo str_replace("\n","<br>",$resume["sb_objective"]);
								  ?>
                              </font></td>
                          </tr><?
						  $sql="select * from sbjbs_experience where sb_resume_id=".$resume["sb_id"];
						  if(($resume["sb_hide_info"]=="yes") && ($isowner=="no"))
						  {
						  $sql.=" and sb_end_month<13";
						  }
						  $sql.=" order by sb_end_year desc,sb_end_month desc";
						  $exp_q=mysql_query($sql);
						  $num=mysql_num_rows($exp_q);
						  if($num>0)
						  {
                          ?><tr valign="top"> 
                            <td class="subtitle">Experience</td>
                            <td class="innertablestyle"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							
							while($exp=mysql_fetch_array($exp_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
                                       echo $exp["sb_start_month"]."/".$exp["sb_start_year"];
									   echo " - ";
									   if($exp["sb_end_month"]<>13)
									   { echo $exp["sb_end_month"]."/".$exp["sb_end_year"];}
									   else
									   { echo "Present";}
								    ?>
                                    </font></td>
                                  <td width="50%"><font class="smalltext"> 
                                    <?php
                                       echo $exp["sb_company_name"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
                                       echo $exp["sb_location"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td colspan="3"><font class="smalltext"><b> 
                                    <?php
                                       echo $exp["sb_designation"];
								    ?>
                                    </b></font></td>
                                </tr>
                                <tr> 
                                  <td colspan="3"><font class="smalltext"> 
                                    <?php
                                       echo str_replace("\n","<br>",$exp["sb_work_desc"]);
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="3"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td>
                          </tr><?
						  }// if not any experience
						  $edu_q=mysql_query("select * from sbjbs_education 
						  where sb_resume_id=".$resume["sb_id"]." 
						  order by sb_end_year desc,sb_end_month desc");
						  $num=mysql_num_rows($edu_q);
						  if($num>0)	
						  {
                          ?><tr valign="top"> 
                            <td class="subtitle">Education</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							while($edu=mysql_fetch_array($edu_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
									   if($edu["sb_end_month"]<>13)
									   { echo $edu["sb_end_month"]."/".$edu["sb_end_year"];}
									   else
									   { echo "Currently Doing";}
									   
								    ?>
                                    </font></td>
                                  <td width="50%"><font class="smalltext"> 
                                    <?php
                                       echo $edu["sb_school"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
								$country=mysql_fetch_array(mysql_query("select * from sbjbs_country where 
								id=".$edu["sb_country"]));
                                
								echo $edu["sb_city"]." - ".$edu["sb_state"]." - ".$country["country"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td colspan="3"><font class="smalltext"><b> 
                                    <?php
                                       echo $edu["sb_degree"];
								    ?>
                                    </b></font></td>
                                </tr>
                                <tr> 
                                  <td colspan="3"><font class="smalltext"> 
                                    <?php
                                       echo str_replace("\n","<br>",$edu["sb_desc"]);
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="3"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td>
                          </tr><?
						  }// if not any education
						
						$aff_q=mysql_query("select * from sbjbs_affiliations 
						where sb_resume_id=".$resume["sb_id"]." 
						  order by sb_end_year desc,sb_end_month desc");
						  
						  $num=mysql_num_rows($aff_q);
						  if($num>0)	
						  {
                          ?><tr valign="top"> 
                            <td class="subtitle">Affiliations</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							while($aff=mysql_fetch_array($aff_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
                                       echo $aff["sb_start_month"]."/".$aff["sb_start_year"];
									   echo " - ";
									   if($aff["sb_end_month"]<>13)
									   { echo $aff["sb_end_month"]."/".$aff["sb_end_year"];}
									   else
									   { echo "Present";}
								    ?>
                                    </font></td>
                                  <td width="50%"><font class="smalltext"> 
                                    <?php
                                       echo $aff["sb_company"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
                                       echo $aff["sb_affiliation"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="3"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td>
                          </tr><?
						  }//end if not any affiliations
						$skill_q=mysql_query("select * from sbjbs_resume_skills 
						where sb_resume_id=".$resume["sb_id"]." 
						  order by sb_last_year desc,sb_last_month desc");
 						  $num=mysql_num_rows($skill_q);
						  if($num>0)	
						  {
                          ?><tr valign="top"> 
                            <td class="subtitle">Skills</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							while($skill=mysql_fetch_array($skill_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
                                       echo $skill["sb_last_month"]."/".$skill["sb_last_year"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
									$skill_desc=mysql_fetch_array(mysql_query("select * from sbjbs_skills 
									where sb_id=".$skill["sb_skill_id"]));
                                       echo $skill_desc["sb_name"];
								    ?>
                                    </font></td>
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
									
                                       echo $skill["sb_level"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
                                       echo $skill["sb_experience"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="4"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td>
                          </tr><?php
						  }// end if not any skill
						$lang_q=mysql_query("select * from sbjbs_resume_language
						where sb_resume_id=".$resume["sb_id"]);
  						  $num=mysql_num_rows($lang_q);
						  if($num>0)	
						  {

                          ?><tr valign="top"> 
                            <td class="subtitle">Languages</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							while($lang=mysql_fetch_array($lang_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
									$lang_desc=mysql_fetch_array(mysql_query("select * from sbjbs_languages 
									where sb_id=".$lang["sb_language_id"]));
                                       echo $lang_desc["sb_name"];
								    ?>
                                    </font></td>
                                  <td width="80%"><font class="smalltext"> 
                                    <?php
                                       echo $lang["sb_proficiency"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="2"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td>
                          </tr><?php
						  }// end if not any language

							$ref_q=mysql_query("select * from sbjbs_references
							where sb_resume_id=".$resume["sb_id"]);
						  $num=mysql_num_rows($ref_q);
						  if(($num>0) && (($resume["sb_hide_info"]<>"yes") || ($isowner=="yes")))
	
						  {	  
                          ?><tr valign="top"> 
                            <td class="subtitle">References</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <?php
							while($ref=mysql_fetch_array($ref_q))
							{							
							?>
                                <tr> 
                                  <td width="20%"><font class="smalltext"> 
                                    <?php
                                       echo $ref["sb_name"];
									   ?>
                                    </font></td>
                                  <td width="50%"><font class="smalltext"> 
                                    <?php
                                       echo $ref["sb_phone"];
								    ?>
                                    </font></td>
                                  <td width="30%"><font class="smalltext"> 
                                    <?php
                                       echo $ref["sb_relation"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr> 
                                  <td colspan="3"><font class="smalltext"> 
                                    <?php
                                       echo $ref["sb_designation"];
					 if(($ref["sb_designation"]<>"")&&($ref["sb_company"]<>""))
					  { echo ",&nbsp;";}
					 echo $ref["sb_company"];
					 if((($ref["sb_designation"]<>"")||($ref["sb_company"]<>""))&&($ref["sb_email"]<>""))
					  { echo ",&nbsp;";}
					echo $ref["sb_email"];
								    ?>
                                    </font></td>
                                </tr>
                                <tr height=10> 
                                  <td colspan="3"></td>
                                </tr>
                                <?php
							}
							?>
                              </table></td><?php
							  }//end if refernces and not hidden
							if($resume["sb_additional_info"]<>"")
							{  
                          ?></tr>
                          <tr valign="top"> 
                            <td class="subtitle">Additional Information</td>
                            <td class="innertablestyle"><font class="smalltext"> 
                              <?php
                                       echo str_replace("\n","<br>",$resume["sb_additional_info"]);
								    ?>
                              </font></td>
                          </tr><?php
						  }//end if additional info
                       ?></table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Summary</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"><table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr valign="top"> 
                            <td width="150" class="subtitle">Desired Salary</td>
                            <td class="innertablestyle"><font class="smalltext"> 
                              <?php 
							  if($resume["sb_salary"]>0)
							  {
							 $cur=mysql_fetch_array(mysql_query("Select * from sbjbs_currencies where sbcur_id=".$resume["sb_salary_currency"])); 
								  echo number_format($resume["sb_salary"],2)." ".$cur["sbcur_name"]." ";
								  switch($resume["sb_salary_type"])
								  {
								  case 1: echo "Per Year"; break;
								  case 2: echo "Per Month"; break;
								  case 3: echo "Per Week"; break;
								  case 4: echo "Fortnightly"; break;
								  case 5: echo "Per Hour"; break;
								  }
							 }
							 else
							 {
							 echo $config["sb_null_char"];
							 }	  
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Career Level</td>
                            <td class="innertablestyle"><font class="smalltext"> 
                              <?php 
								  switch($resume["sb_career_level"])
								  {
								  case 1: echo "Student (High School)"; break;
								  case 2: echo "Student"; break;
								  case 3: echo "Entry Level (less than 2 years of experience)"; break;
								  case 4: echo "Mid Career (2+ years of experience)"; break;
								  case 5: echo "Manager"; break;
								  case 6: echo "Senior Management (VP and equivalent)"; break;
								  case 7: echo "Top Management (CEO and equivalent)"; break;
								  }
								  ?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Total Experience</td>
                            <td class="innertablestyle"><font class="smalltext"> <?php
							switch($resume["sb_relevent_experience"])
							{
							 case 0: echo "Fresher"; break;
							 case 1: echo "Less than 1 Year"; break;
							 case 2: echo "1 to 2 Years"; break;
							 case 3: echo "2 to 5 Years"; break;
							 case 4: echo "5 to 7 Years"; break;
							 case 5: echo "7 to 10 Years"; break;
							 case 6: echo "10 to 15 Years"; break;
							 case 7: echo "More than 15 Years"; break;
							}
							?></font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Joining</td>
                            <td class="innertablestyle"><font class="smalltext"> 
                              <?php
							switch($resume["sb_availability_time"])
							{
							 case 1: echo "Immediately"; break;
							 case 2: echo "Within 2 weeks"; break;
							 case 3: echo "Within one month"; break;
							 case 4: echo "From 1 to 3 months"; break;
							 case 5: echo "More than 3 months"; break;
							 case 6: echo "Negotiable"; break;
							}
							?>
                              </font></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Target Job</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Target 
                                    Job Title</b></font></td>
                                  <td><font class="smalltext"><?php echo $resume["sb_target_job"];?></font></td>
                                </tr>
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Alternate 
                                    Job Title</b></font></td>
                                  <td><font class="smalltext"><?php echo $resume["sb_alt_job"];?></font></td>
                                </tr>
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Desired 
                                    Job Type</b></font></td>
                                  <td><font class="smalltext"> 
                                    <?php 
								  if(($resume["sb_job_type"]&1)==1)
								  { echo "Permanent<br>";}
								  if(($resume["sb_job_type"]&2)==2)
								  { echo "Intern / Work Experience<br>";}
								  if(($resume["sb_job_type"]&4)==4)
								  { echo "Temporary/Contract/Project";}
								  
								  ?>
                                    </font></td>
                                </tr>
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Work 
                                    Load</b></font></td>
                                  <td><font class="smalltext"><?php 
								  
								  if(($resume["sb_job_status"]&1)==1)
								  { echo "Full Time<br>";}
								  if(($resume["sb_job_status"]&2)==2)
								  { echo "Part Time";}
								  
								  ?></font></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Target Company</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Company 
                                    Size </b></font></td>
                                  <td><font class="smalltext"><?php 
								  switch($resume["sb_target_comp_size"])
								  {
								  	case 1: echo "No Preference"; break;
									case 2: echo "Small Scale(1 - 99)"; break;
									case 3: echo "Medium Scale(100 - 999)"; break;
									case 4: echo "Large Scale(1000+)"; break;
								  }?></font></td>
                                </tr>
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Category</b></font></td>
                                  <td><font class="smalltext"><?php 
		$resume_cat_q=mysql_query("select * from sbjbs_resume_cats where sb_resume_id=".$resume["sb_id"]);			  
		while($resume_cat=mysql_fetch_array($resume_cat_q))
		{
			$cat=mysql_fetch_array(mysql_query("select * from sbjbs_categories 
			where sb_id=".$resume_cat["sb_cid"]));
			
			$cat_path= $cat["sb_cat_name"];
			$par_q=mysql_query("select * from sbjbs_categories where sb_id=".$cat["sb_pid"]);
			while($par=mysql_fetch_array($par_q))
			 {
				   $cat_path=$par["sb_cat_name"]." - ".$cat_path;
				   $par_q=mysql_query("select * from sbjbs_categories where sb_id=".$par["sb_pid"]);
			  }
						echo $cat_path."<br>";

		}// end while resume cats
								  ?></font></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr valign="top"> 
                            <td class="subtitle">Target Locations</td>
                            <td class="innertablestyle"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Desired 
                                    Locations </b></font></td>
                                  <td><font class="smalltext"> 
                                    <?php 
		$resume_loc_q=mysql_query("select * from sbjbs_resume_locs where sb_resume_id=".$resume["sb_id"]);			  
		while($resume_loc=mysql_fetch_array($resume_loc_q))
		{
			$loc=mysql_fetch_array(mysql_query("select * from sbjbs_locations where sb_id=".$resume_loc["sb_loc_id"]));
			$loc_path= $loc["sb_loc_name"];
			$par_q=mysql_query("select * from sbjbs_locations where sb_id=".$loc["sb_pid"]);
    	    while($par=mysql_fetch_array($par_q))
			 {
			   $loc_path=$par["sb_loc_name"]." - ".$loc_path;
			   $par_q=mysql_query("select * from sbjbs_locations where sb_id=".$par["sb_pid"]);
			 }
		 	echo $loc_path."<br>";
		}// end while resume location
									
								  ?>
                                    </font></td>
                                </tr>
                                <tr valign="top"> 
                                  <td width="30%"><font class="smalltext"><b>&nbsp;Relocate</b></font></td>
                                  <td><font class="smalltext"><?php 
								  if($resume["sb_relocate"]==1)
								  {	echo "Yes";	}
								  else
								  { echo "No";}
								  ?>
                                    </font></td>
                                </tr>
                                <tr valign="top">
                                  <td> <font class="smalltext"><b>&nbsp;Willingness 
                                    to Travel</b></font></td>
                                  <td><font class="smalltext">
                                    <?php 
								  switch($resume["sb_will_to_travel"])
								  {
									case 1: echo "Never"; break;
									case 2: echo "Up to 25% travel"; break;
									case 3: echo "Up to 50% travel"; break;
									case 4: echo "Up to 75% travel"; break;
									case 5: echo "Up to 100%"; break;
								  }?>
                                    </font></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
  <?
}// end main
include "template1.php";
?>