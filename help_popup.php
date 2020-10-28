<?
include_once "styles.php";
$form="";
$context="";
if(isset($_REQUEST["form"]))
{ $form=$_REQUEST["form"]; }
$title_str="Help";

if(isset($_REQUEST["context"]))
{ $context=$_REQUEST["context"]; }

	if($form=="resume_builder")
	{
		switch($context)
		{
			case "headline": $msg="Resume Headline Help";
							 $help_contents="Please enter a headline that will truly reflect your
							  career, so that the employers get an idea about your capabilities.<br>
							  Examples: Results Oriented QA Specialist, Great Organiser ";
							  break;
									
			case "objective": $msg="Career Objective Help";
							  $help_contents="Please specify the goals you want to achieve in your life. Be realistic and true. You can summerize your skills, area of interests, specialisations, 
							  desires..";break;
							  
			case "hideinfo": $msg="Cofidential Settings Help";
							  $help_contents="Your Contact Info, Current Job Details, References will be kept hidden on your Resume details page. This is just to ensure that your secure information remains secret from the undesirable persons. To make your Resume confidential now check the checkbox available in the form otherwise you can also do the same later.";break;
							  
			case "jobtitle": $msg="Target Job Title Help";
							  $help_contents="Fill in your highly preferred job title like 
							  Sr. Software Engineer, Programmer, System Analyst etc. Be specific while writing this because it will act as a mirror of your capabilities.";break;
			case "salary": $msg="Desired Salary Help";
							  $help_contents="Specify here the salary you would expect to be given, don't write too much or too low otherwise the employer might not take it seriously eg.
							  10000 INR Fortnightly";break;
			case "choose_category": $msg="Choose Company Category Help";
							  $help_contents="Choose the category or company type suitable to your career  or educational background. Multiple categories can be selected. Select the category from dropdown list and click Add
							   to add to the selected list or click Remove to remove from the list.";
							   break;
			case "additional_info": $msg="Additional Info Help";
							 $help_contents="Please enter any additional information you want to 
							 specify. You can specify any thing you consider worth mentioning or you have not found appropriate space. You may include information regarding awards won, hobbies etc.. ";
							  break;
									
			case "hide_info": $msg="Make Confidential Help";
							  $help_contents="Your Contact Info, Current Job Details, References will be kept hidden on your Resume details page. This is just to ensure that your secure information remains secret from the undesirable persons. ";break;
							  
		}//end switch
	}//end if resume builder
	elseif($form=="cover_letter")
	{
	$msg="Cover Letter Help";
	$help_contents="To<br>
%DESIGNATION%<br>
%COMPANY ADDRESS%<br><br>
Respected Sir/Madam,<br>
Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here<br> 
Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here Letter Contents Here 
<br><br>
Thanking You,
<br>
With Regards,<br>
%your name%,<br>
%your address%,<br>
%your email%. <br>
<br>
Dated: %date here%";
	}
	else
	{
	}//default case of form
?>
<html>
<head>
<title><? echo $title_str;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="<?php echo $softbiz_page_bg; ?>" leftmargin="1" topmargin="1" rightmargin="1"><br>
<script language="JavaScript">
window.focus();
</script>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing="2" class="helpstyle">
  <tr align="left"> 
                  <td width="97%" align="center"><strong><?php echo $msg;?></strong></td>
                </tr>
                <tr align="left"> 
                  <td><?php echo $help_contents;?></td>
                </tr>
              </table><br>

			  </body>
			  </html>