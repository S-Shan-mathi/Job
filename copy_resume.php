<?php
include_once "logincheck.php";
include_once "myconnect.php";

$id=0;
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>""))
{
$id=$_REQUEST["resume_id"];
}
$resume_q=mysql_query("select * from sbjbs_resumes where sb_id=$id and sb_seeker_id=".$_SESSION["sbjbs_userid"]);
$num_resume=mysql_num_rows($resume_q);

if($num_resume<=0)
{ 
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("Invalid access, denied"));
die();
}

$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$resume_cnt=$config["sb_resume_cnt"];
$total_resume=mysql_num_rows(mysql_query("select * from sbjbs_resumes where sb_seeker_id=".$_SESSION["sbjbs_userid"]));

if($total_resume>=$resume_cnt)
{
header("Location:"."gen_confirm_mem.php?errmsg=".urlencode("You have already posted the maximum number of resumes allowed."));
die();
}
$resume=mysql_fetch_array($resume_q);
		if(!get_magic_quotes_gpc())
		{
			$sb_title=str_replace("$","\$",addslashes($resume["sb_title"]));
			$sb_objective=str_replace("$","\$",addslashes($resume["sb_objective"]));
			$firstname=str_replace("$","\$",addslashes($resume["sb_firstname"]));
			$lastname=str_replace("$","\$",addslashes($resume["sb_lastname"]));
			$street=str_replace("$","\$",addslashes($resume["sb_addr1"]));
			$city=str_replace("$","\$",addslashes($resume["sb_city"]));
			$state=str_replace("$","\$",addslashes($resume["sb_state"]));
			$phone=str_replace("$","\$",addslashes($resume["sb_telephone"]));
			$mobile=str_replace("$","\$",addslashes($resume["sb_mobile"]));
			$zip_code=str_replace("$","\$",addslashes($resume["sb_zip"]));
			$career_level=str_replace("$","\$",addslashes($resume["sb_career_level"]));			
			$availability_time=str_replace("$","\$",addslashes($resume["sb_availability_time"]));			
			$work_exp=str_replace("$","\$",addslashes($resume["sb_relevent_experience"]));
			$target_job=str_replace("$","\$",addslashes($resume["sb_target_job"]));
			$alt_job=str_replace("$","\$",addslashes($resume["sb_alt_job"]));
			$salary=str_replace("$","\$",addslashes($resume["sb_salary"]));			
			$sb_add_info=str_replace("$","\$",addslashes($resume["sb_additional_info"]));
			$email=str_replace("$","\$",addslashes($resume["sb_email"]));
		}
		else
		{
			$sb_title=str_replace("$","\$",$resume["sb_title"]);
			$sb_objective=str_replace("$","\$",$resume["sb_objective"]);
			$firstname=str_replace("$","\$",$resume["sb_firstname"]);
			$lastname=str_replace("$","\$",$resume["sb_lastname"]);
			$street=str_replace("$","\$",$resume["sb_addr1"]);
			$city=str_replace("$","\$",$resume["sb_city"]);
			$state=str_replace("$","\$",$resume["sb_state"]);
			$phone=str_replace("$","\$",$resume["sb_telephone"]);
			$mobile=str_replace("$","\$",$resume["sb_mobile"]);
			$zip_code=str_replace("$","\$",$resume["sb_zip"]);
			$career_level=str_replace("$","\$",$resume["sb_career_level"]);
			$availability_time=str_replace("$","\$",$resume["sb_availability_time"]);
			$work_exp=str_replace("$","\$",$resume["sb_relevent_experience"]);
			$target_job=str_replace("$","\$",$resume["sb_target_job"]);
			$alt_job=str_replace("$","\$",$resume["sb_alt_job"]);
			$salary=str_replace("$","\$",$resume["sb_salary"]);
			$sb_add_info=str_replace("$","\$",$resume["sb_additional_info"]);
			$email=str_replace("$","\$",$resume["sb_email"]);
	}
		
		 $job_type=$resume["sb_job_type"];
		 $job_status=$resume["sb_job_status"];
		 $relocate=$resume["sb_relocate"];
		 $salary_currency=$resume["sb_salary_currency"];
		 $target_comp_size=$resume["sb_target_comp_size"];
		 $salary_type=$resume["sb_salary_type"];
		 $will_to_travel=$resume["sb_will_to_travel"];
						
	mysql_query("insert into sbjbs_resumes (sb_title,sb_objective,sb_seeker_id) 
	values('$sb_title','$sb_objective',".$_SESSION["sbjbs_userid"].")");
	if(mysql_affected_rows()>0)
	{ 
	$rs=mysql_fetch_array(mysql_query("select max(sb_id) from sbjbs_resumes"));
	$id=$rs[0];
	$hide=$resume["sb_hide_info"];
	//update contact info	
	mysql_query("update `sbjbs_resumes` set sb_firstname='$firstname',sb_lastname='$lastname',
	sb_addr1='$street',sb_city='$city',sb_state='$state',sb_zip='$zip_code',
	sb_country=".$resume["sb_country"].",sb_telephone='$phone',sb_mobile='$mobile',sb_career_level=".$resume["sb_career_level"].",sb_availability_time='$availability_time',sb_relevent_experience='$work_exp',
	sb_hide_info='$hide',sb_email='$email' where sb_id=$id");
	
	//job preferances
	mysql_query("update `sbjbs_resumes` set sb_target_job='$target_job',sb_alt_job='$alt_job',
	sb_job_type='$job_type',sb_job_status='$job_status',sb_salary=$salary,sb_salary_type=$salary_type,
	sb_salary_currency='$salary_currency',sb_target_comp_size='$target_comp_size',
	sb_will_to_travel=$will_to_travel where sb_id=$id");
	
	//categories
	$resume_cats_q=mysql_query("select * from sbjbs_resume_cats where sb_resume_id=".$resume["sb_id"]);
	while($resume_cat=mysql_fetch_array($resume_cats_q))
	{
	mysql_query("insert into sbjbs_resume_cats (sb_cid,sb_resume_id) 
	values(".$resume_cat["sb_cid"].",$id)");
	}
	
	//locations
	$resume_locs_q=mysql_query("select * from sbjbs_resume_locs where sb_resume_id=".$resume["sb_id"]);
	while($resume_loc=mysql_fetch_array($resume_locs_q))
	{
	mysql_query("insert into sbjbs_resume_locs (sb_loc_id,sb_resume_id) 
	values(".$resume_loc["sb_loc_id"].",$id)");
	}
	//professional experience
	$exp_q=mysql_query("select * from sbjbs_experience where sb_resume_id=".$resume["sb_id"]);
	while($exp=mysql_fetch_array($exp_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$comp_name=str_replace("$","\$",addslashes($exp["sb_company_name"]));
			$comp_location=str_replace("$","\$",addslashes($exp["sb_location"]));
			$title=str_replace("$","\$",addslashes($exp["sb_designation"]));
			$work_desc=str_replace("$","\$",addslashes($exp["sb_work_desc"]));
		}
		else
		{
			$comp_name=str_replace("$","\$",$exp["sb_company_name"]);
			$comp_location=str_replace("$","\$",$exp["sb_location"]);
			$title=str_replace("$","\$",$exp["sb_designation"]);
			$work_desc=str_replace("$","\$",$exp["sb_work_desc"]);
		}

		$month=$exp["sb_start_month"];
		$year=$exp["sb_start_year"];
		$month1=$exp["sb_end_month"];
		$year1=$exp["sb_end_year"];

		mysql_query("insert into sbjbs_experience (sb_company_name,sb_location,sb_work_desc,sb_designation,
		sb_start_month,sb_start_year,sb_end_month,sb_end_year,sb_resume_id) values('$comp_name',
		'$comp_location','$work_desc','$title',$month,$year,$month1,$year1,$id)");
		
	}
	
	//references
	$ref_q=mysql_query("select * from sbjbs_references where sb_resume_id=".$resume["sb_id"]);
	while($ref=mysql_fetch_array($ref_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$comp_name=str_replace("$","\$",addslashes($ref["sb_company"]));
			$ref_name=str_replace("$","\$",addslashes($ref["sb_name"]));
			$title=str_replace("$","\$",addslashes($ref["sb_designation"]));
			$email=str_replace("$","\$",addslashes($ref["sb_email"]));
			$phone=str_replace("$","\$",addslashes($ref["sb_phone"]));
			}
		else
		{
			$comp_name=str_replace("$","\$",$ref["sb_company"]);
			$ref_name=str_replace("$","\$",$ref["sb_name"]);
			$title=str_replace("$","\$",$ref["sb_designation"]);
			$email=str_replace("$","\$",$ref["sb_email"]);
			$phone=str_replace("$","\$",$ref["sb_phone"]);
		}
			$relation=$ref["sb_relation"];

	mysql_query("insert into sbjbs_references (sb_company,sb_name,sb_email,sb_designation,sb_phone,
	sb_relation,sb_resume_id) values('$comp_name','$ref_name','$email','$title','$phone','$relation',$id)");
	}
	
	//education
	$edu_q=mysql_query("select * from sbjbs_education where sb_resume_id=".$resume["sb_id"]);
	while($edu=mysql_fetch_array($edu_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$school=str_replace("$","\$",addslashes($edu["sb_school"]));
			$city=str_replace("$","\$",addslashes($edu["sb_city"]));
			$state=str_replace("$","\$",addslashes($edu["sb_state"]));
			$degree=str_replace("$","\$",addslashes($edu["sb_degree"]));
			$edu_desc=str_replace("$","\$",addslashes($edu["sb_desc"]));
			}
		else
		{
			$school=str_replace("$","\$",$edu["sb_school"]);
			$city=str_replace("$","\$",$edu["sb_city"]);
			$state=str_replace("$","\$",$edu["sb_state"]);
			$degree=str_replace("$","\$",$edu["sb_degree"]);
			$edu_desc=str_replace("$","\$",addslashes($edu["sb_desc"]));
		}
		$month=$edu["sb_end_month"];
		$year=$edu["sb_end_year"];
		$country=$edu["sb_country"];
		
	mysql_query("insert into sbjbs_education (sb_school,sb_city,sb_state,sb_country,sb_end_month,
	sb_end_year,sb_degree,sb_desc,sb_resume_id) values('$school','$city','$state','$country','$month',
	'$year','$degree','$edu_desc',$id)");
	}	

	//affiliations
	$aff_q=mysql_query("select * from sbjbs_affiliations where sb_resume_id=".$resume["sb_id"]);
	while($aff=mysql_fetch_array($aff_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$comp_name=str_replace("$","\$",addslashes($aff["sb_company"]));
			$title=str_replace("$","\$",addslashes($aff["sb_affiliation"]));
		}
		else
		{
			$comp_name=str_replace("$","\$",$aff["sb_company"]);
			$title=str_replace("$","\$",$aff["sb_affiliation"]);
		}

	$month=$aff["sb_start_month"];
	$year=$aff["sb_start_year"];
	$month1=$aff["sb_end_month"];
	$year1=$aff["sb_end_year"];

	mysql_query("insert into sbjbs_affiliations (sb_company,sb_affiliation,sb_start_month,sb_start_year,
	sb_end_month,sb_end_year,sb_resume_id) values('$comp_name','$title',$month,$year,$month1,$year1,$id)");
	}

	//languages
	$lang_q=mysql_query("select * from sbjbs_resume_language where sb_resume_id=".$resume["sb_id"]);
	while($lang=mysql_fetch_array($lang_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$proficiency=str_replace("$","\$",addslashes($lang["sb_proficiency"]));
		}
		else
		{
			$proficiency=str_replace("$","\$",$lang["sb_proficiency"]);
		}

	$language=$lang["sb_language_id"];
	mysql_query("insert into sbjbs_resume_language (sb_language_id,sb_proficiency,sb_resume_id) 
	values($language,'$proficiency',$id)");
	}
	//skills
	$skills_q=mysql_query("select * from sbjbs_resume_skills where sb_resume_id=".$resume["sb_id"]);
	while($skills=mysql_fetch_array($skills_q))
	{
		if(!get_magic_quotes_gpc())
		{
			$level=str_replace("$","\$",addslashes($skills["sb_level"]));
			$work_exp=str_replace("$","\$",addslashes($skills["sb_experience"]));
		}
		else
		{
			$level=str_replace("$","\$",$skills["sb_level"]);
			$work_exp=str_replace("$","\$",$skills["sb_experience"]);
		}

	$skill=$skills["sb_skill_id"];
	$month=$skills["sb_last_month"];
	$year=$skills["sb_last_year"];
	
	mysql_query("insert into sbjbs_resume_skills (sb_skill_id,sb_level,sb_resume_id,sb_last_month,
	sb_last_year,sb_experience)	values($skill,'$level',$id,$month,$year,'$work_exp')");
	
	}
	
	//additional info
	mysql_query("update sbjbs_resumes set sb_additional_info='$sb_add_info',sb_img_url='".$resume["sb_img_url"]."' where sb_id=$id");
	
	//finish
	 mysql_query("update sbjbs_resumes set sb_search_enable='no',
	 sb_approved='".$resume["sb_approved"]."',sb_posted_on='".date("YmdHis",time())."' where sb_id=$id");

	header("Location:"."headline.php?resume_id=$id&errmsg=".urlencode("Successfully copied the resume."));
	die();

	}
	else
	{
	header("Location:"."genconfirm_mem.php?err=resumes&errmsg=".urlencode("Sorry, some error occurred,
	 unable to make copy of your resume."));
	die();
	}//unable to insert
?>