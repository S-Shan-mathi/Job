<?php
include_once "myconnect.php";
include_once "session.php";
include_once "styles.php";
include_once "left_panel.php";
include_once "date_time_format.php";
include_once "send_alerts.php";

//include_once "check_msg_function.php";
//=============================================================
function loc_order($pid)
{
	$rs_query=mysql_query("select * from sbjbs_locations where sb_pid=$pid order by sb_loc_name");
	while($rst=mysql_fetch_array($rs_query))
	{
	  $cat_path="";
	  $cat_path.=$rst["sb_loc_name"];
 	  $par=mysql_query("select * from sbjbs_locations where sb_id=".$rst["sb_pid"]);
	  while($parent=mysql_fetch_array($par))
	  {
		$cat_path=$parent["sb_loc_name"]."-".$cat_path;
		$par=mysql_query("select * from sbjbs_locations where sb_id=".$parent["sb_pid"]);
	  }
      echo "<option value='".$rst["sb_id"]."' >$cat_path</option>";
	  $child=mysql_fetch_array(mysql_query("select * from sbjbs_locations where 
	  sb_pid=".$rst["sb_id"]));
	  if($child)
	  {
		loc_order($child["sb_pid"]);
	  }
	}
}
//=============================================================


///////////////////////////////////////////////////////////////////////////////
///      THE CODE OF THIS SCRIPT HAS BEEN DEVELOPED BY SOFTBIZ SOLUTIONS  /////
///      AND IS MEANT TO BE USED ON THIS SITE ONLY AND IS NOT FOR REUSE,  /////
///      RESALE OR REDISTRIBUTION.                                        ///// 
///      IF YOU NOTICE ANY VIOLATION OF ABOVE PLEASE REPORT AT:           /////
///      admin@softbizscripts.com                                         /////
///      http://www.softbizscripts.com                                    /////
///      http://www.softbizsolutions.com                                  /////  
///////////////////////////////////////////////////////////////////////////////
/*$mail_q=mysql_query("select * from sbjbs_mails");
while($mail=mysql_fetch_array($mail_q))
{
mysql_query("update sbjbs_mails set sb_mail='".str_replace("<","%",str_replace(">","%",$mail["sb_mail"]))."' where sb_id=".$mail["sb_id"]);
}*/
//======================code here is equivalent to chron file code=============================
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config "));

if(date("Ymd",time())>$config["last_sent"])
{
//echo "working";
send_mail_alerts();
mysql_query("update sbjbs_config set last_sent=".date("Ymd",time())." where 1"); 
}

// LOAD style number from the config file

define("HELP_LINK","Tips");
//-------------------default icons
$sbico_apply_user='admin/sbjbs_default_icons/sbico_apply_user.gif';
$sbico_apply_new='admin/sbjbs_default_icons/sbico_apply_new.gif';
$sbico_refer_friend='admin/sbjbs_default_icons/sbico_refer_friend.gif';
$sbico_view_profile='admin/sbjbs_default_icons/sbico_view_profile.gif';
///////--------end default icons
$icons=mysql_fetch_array(mysql_query("select * from sbjbs_icons where sb_id=".$config["sb_icon_list"]));
//----read icons here from database
if($icons)
{
	$sbico_apply_user='admin/sbjbs_icons/'.$icons["sb_apply_now"];
	$sbico_apply_new='admin/sbjbs_icons/'.$icons["sb_join_now"];
	$sbico_refer_friend='admin/sbjbs_icons/'.$icons["sb_refer_friend"];
	$sbico_view_profile='admin/sbjbs_icons/'.$icons["sb_profile"];
}
//----end read  icons here from database
$keyword="";
$title_str="";
$site_keywords="";

if(isset($_REQUEST["keyword"])&&($_REQUEST["keyword"]<>""))
{
$keyword=$_REQUEST["keyword"];
$title_str=$keyword." : ";
$site_keywords=$keyword.",";
}

	if(preg_match("/\/resumes.php/",$_SERVER['PHP_SELF']))
	{
	$title_str.="Resumes : ";
	$site_keywords.="Resumes,";
	}
	elseif(preg_match("/\/search_result.php/",$_SERVER['PHP_SELF']))
	{
	$title_str.="Jobs : ";
	$site_keywords.="Jobs,";
	}
	elseif(preg_match("/\/profiles.php/",$_SERVER['PHP_SELF']))
	{
	$title_str.="Companies : ";
	$site_keywords.="Companies,";
	}


if(isset($_REQUEST["sb_id"])&&($_REQUEST["sb_id"]<>"")&&($_REQUEST["sb_id"]<>0))
{
	$id=$_REQUEST["sb_id"];
	if(preg_match("/\/view_job.php/",$_SERVER['PHP_SELF']))
	{
	$job=mysql_fetch_array(mysql_query("select * from sbjbs_jobs where sb_id=$id")); 
	$title_str.=$job["sb_title"]." : Jobs : ";
	$site_keywords.=$job["sb_title"].",Jobs,";
	}
}
if(isset($_REQUEST["resume_id"])&&($_REQUEST["resume_id"]<>"")&&($_REQUEST["resume_id"]<>0))
{
	$id=$_REQUEST["resume_id"];
	if(preg_match("/\/view_resume.php/",$_SERVER['PHP_SELF']))
	{
	$offer=mysql_fetch_array(mysql_query("select * from sbjbs_resumes where sb_id=$id")); 
	$title_str.=$offer["sb_target_job"]." : Resume : ";
	$site_keywords.=$offer["sb_target_job"].",";
	}
}
if(isset($_REQUEST["id"])&&($_REQUEST["id"]<>"")&&($_REQUEST["id"]<>0))
{
	$id=$_REQUEST["id"];
	if(preg_match("/\/view_profile.php/",$_SERVER['PHP_SELF']))
	{
	$offer=mysql_fetch_array(mysql_query("select * from sbjbs_companies where sb_id=$id")); 
	$title_str.=$offer["sb_name"]." : Companies : ";
	$site_keywords.=$offer["sb_name"].",";
	}
}

if(isset($_REQUEST["cid"])&&($_REQUEST["cid"]<>"")&&($_REQUEST["cid"]<>0))
{
	$cid=$_REQUEST["cid"];
	$catpath="";
	$catpath2="";
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $cid );
	while ($rs=mysql_fetch_array($cat_query))
    {
    $catpath .=$rs["sb_cat_name"]." : "; 
  	$catpath2 .=$rs["sb_cat_name"].",";
	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $rs["sb_pid"] );
	}
$title_str.=$catpath;
$site_keywords.=$catpath2;
}
$title_str.=$config["sb_site_name"];
$site_keywords.=$config["sb_site_keywords"];

?><html>
<head>
<title><? echo $title_str;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="keywords" content="<?php echo $site_keywords;?>"> 
<script language="JavaScript" type="text/JavaScript">
<!--
function sb_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body bgcolor="<?php echo $softbiz_page_bg; ?>" leftmargin="1" topmargin="1" rightmargin="1">
<script language="JavaScript">
function help_popup(form,context)
{
var file="help_popup.php?form=" + form+"&context="+context;
window.open(file,"Help","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=550,height=200,location=no,directories=no,scrollbars=yes");
}
</script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr align="left"> 
    <td   valign="top" ><div align="center"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td><h1 style="margin:0 0 20px 20px;">FINDSJOB.COM</h1></td>
            <td align="center"> 
              <?php
			
	mt_srand( (double)microtime() * 1000000);

	$rs_t_query=mysql_query("select * from sbjbs_ads where approved= 'yes' and credits > displays");
//die($rs_t_query);
	$cnt= mysql_num_rows($rs_t_query);

	$rs_t_query1=mysql_query("select * from sbjbs_affiliate_banner where sbaff_active= 'yes'");
//die($rs_t_query);
	$cnt2 = mysql_num_rows($rs_t_query1);
	$sbdowhat=0;		//stands for do nothing
	if ( ($cnt == 0) && ($cnt2 > 0) )
	{		//no banner but affiliate exists
		$sbdowhat=1;	/// stands for "affiliate";
	}
	elseif( ($cnt > 0) && ($cnt2 == 0) )
	{		//no affiliate but banner exists
		$sbdowhat=2;		//	stands for "banner";
	}
	elseif( ($cnt > 0) && ($cnt2 > 0) )
	{		//
		$sbrandon=mt_rand(1,2);
		if($sbrandon == 1)
			$sbdowhat=1;
		else
			$sbdowhat=2;
	}
	if($sbdowhat == 1)
	{
		$rnum= mt_rand(1,$cnt2);
		for ($i=0;$i<$rnum;$i++)
			$rs_t=mysql_fetch_array($rs_t_query1);
		echo $rs_t["sbaff_text"];
	}
	elseif($sbdowhat == 2)
	{
		$rnum= mt_rand(1,$cnt);
		for ($i=0;$i<$rnum;$i++)
			$rs_t=mysql_fetch_array($rs_t_query);
		$id=$rs_t["id"];
		$sbtitle=$rs_t["sbtitle"];
		$url=$rs_t["url"];
		$bannerurl=$rs_t["bannerurl"];
		echo "<a href='$url' target=\"_blank\"><img src='$bannerurl' width=468 height=60 border=0 alt=\"$sbtitle\"></a>";
		mysql_query("update sbjbs_ads set displays=displays+1 where id=$id");
	}
			?>
            </td>
          </tr>
        </table>
      </div></td>
  </tr>
  <tr> 
    <td width="100%" height="100%" align="center"   valign="top" > <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <form action="index.php" method="get">
        </form>
        <tr> 
          <td height="25" align="center" valign="bottom"> <table width="90%" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr align="center"> 
                <td height="25"  class="<?php 
				if(!preg_match("/\/index.php/",$_SERVER['SCRIPT_NAME']))
				{ echo "inactivetab";} 
				else
				{ echo "activetab"; }
				?>
				"> 
                  <?php 
				  
				if(!preg_match("/\/index.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="index.php" >Home</a> 
                  <?php
				} 
				else
				{
				echo "HOME";
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td height="25"  class="<?php 
				if(!preg_match("/\/advance_search.php/",$_SERVER['SCRIPT_NAME']))
				{ echo "inactivetab";} 
				else
				{ echo "activetab"; }
				?>
				"> 
                  <?php 
				if(!preg_match("/\/advance_search.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="advance_search.php" >Search Jobs</a> 
                  <?php
				} 
				else
				{
				echo "SEARCH JOBS";
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td height="25"  class="<?php 
				if(!preg_match("/\/search_companies.php/",$_SERVER['SCRIPT_NAME']))
				{ echo "inactivetab";} 
				else
				{ echo "activetab"; }
				?>
				"> 
                  <?php 
				if(!preg_match("/\/search_companies.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="search_companies.php" >Browse Companies</a> 
                  <?php
				} 
				else
				{
				echo "BROWSE COMPANIES";
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td class="<?php                 	
				if(isset($_SESSION["sbjbs_userid"]))
				{	  
					if(!preg_match("/\/userhome.php/",$_SERVER['SCRIPT_NAME']))
					{ echo "inactivetab"; } 
					else
					{ echo "activetab";	}
				}
				else
				{
					if(!preg_match("/\/signup.php/",$_SERVER['SCRIPT_NAME'])&&!preg_match("/\/addmember.php/",$_SERVER['SCRIPT_NAME']))
					{ echo "inactivetab"; } 
					else
					{ echo "activetab";	}

				}
				?>
				"> 
                  <?php 
			if(isset($_SESSION["sbjbs_userid"]))
			{	  
				if(!preg_match("/\/userhome.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="userhome.php" >Member Home</a> 
                  <?php
				} 
				else
				{
				echo "MEMBER HOME";
				}
			}
			else
			{
			if(!preg_match("/\/signup.php/",$_SERVER['SCRIPT_NAME'])&&!preg_match("/\/addmember.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="signup.php" >Register</a> 
                  <?php
				} 
				else
				{
				echo "REGISTER";
				}			
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td class="<?php                 	
				if(isset($_SESSION["sbjbs_userid"]))
				{	  
					if(!preg_match("/\/logout.php/",$_SERVER['SCRIPT_NAME']))
					{ echo "inactivetab"; } 
					else
					{ echo "activetab";	}
				}
				else
				{
					if(!preg_match("/\/signin.php/",$_SERVER['SCRIPT_NAME']))
					{ echo "inactivetab"; } 
					else
					{ echo "activetab";	}

				}
				?>
				"> 
                  <?php 
			if(isset($_SESSION["sbjbs_userid"]))
			{	  
				if(!preg_match("/\/logout.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="logout.php" >Logout</a> 
                  <?php
				} 
				else
				{
				echo "LOGOUT";
				}
			}
			else
			{
			if(!preg_match("/\/signin.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="signin.php" >Login</a> 
                  <?php
				} 
				else
				{
				echo "LOGIN";
				}			
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td width="80"  class="<?php 
				if(!preg_match("/\/employer/",$_SERVER['SCRIPT_NAME']))
				{ echo "inactivetab";} 
				else
				{ echo "activetab"; }
				?>
				"> 
                  <?php 
				if(!preg_match("/\/employer/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="employer/emp_home.php" >Employers</a> 
                  <?php
				} 
				else
				{
				echo "EMPLOYERS";
				}
				?>
                </td>
                <td width="7"  >&nbsp;</td>
                <td  class="<?php 
				if(!preg_match("/\/contactus.php/",$_SERVER['SCRIPT_NAME']))
				{ echo "inactivetab";} 
				else
				{ echo "activetab"; }
				?>
				" width="80"> 
                  <?php 
				if(!preg_match("/\/contactus.php/",$_SERVER['SCRIPT_NAME']))
				{
				?>
                  <a href="contactus.php" >Contact Us</a> 
                  <?php
				} 
				else
				{
				echo "CONTACT US";
				}
				?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td align="center" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr> 
                <td align="center" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr height=20> 
                      <td colspan="3" align="right" class="sidetitle" ><table width="100%" border="0" cellspacing="0" cellpadding="0" class="sidetitle" >
                          <tr> 
                            <td>&nbsp;<? echo sb_date(time());?></td>
                            <td align="right"> 
                              <?
					   
					  if(isset($_SESSION["sbjbs_userid"])) {
					  echo "Logged in as <b>" . $_SESSION["sbjbs_username"] . "</b>" ;
			?>
                              &nbsp;| &nbsp;&nbsp;<a href="logout.php" class="titlelink">LOGOUT</a> 
                              <?
					  }
					  else
					  {
					  ?>
                              <font class="smalltext">Welcome <strong>Guest</strong>, 
                              Please <a href="signin.php" class="titlelink">LOGIN</a> 
                              to your account or <a href="signup.php"class="titlelink">SIGNUP</a> 
                              with us for career opportunities</font> 
                              <?
					  }
					  ?>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr class="mainbgcolor" > 
                      <td align="center" valign="middle"> <font face="verdana, arial" size="1" class='red'>&nbsp; 
                        <?
					if ( isset($_REQUEST["msg"])&&$_REQUEST['msg']<>"")
					{
					print($_REQUEST['msg']); 
					}
					//end if
					?>
                        </font> </td>
                    </tr>
                    <tr valign="top" width=100%> 
                      <td class="mainbgcolor" > <div align="center"> 
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr valign="top"> 
                              <td width="150" align="left"> 
                                <? left();?>
                              </td>
                              <td align="center"> 
                                <?php main();?>
                              </td>
                            </tr>
                            <tr valign="top"> 
                              <td align="left">&nbsp;</td>
                              <td align="center">&nbsp;</td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <!-- <tr height=2> 
                      <td colspan="3"  class="seperatorstyle" ></td>
                    </tr>-->
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center"   valign="top" > <table align="center" width="100%" cellpadding="2" cellspacing="0" class="bottomstyle">
        <tr> 
          <td align="center">&nbsp;<a href="index.php" class="bottomlink">HOME</a> 
            | <a href="search_result.php" class="bottomlink">ALL JOBS</a> | <a href="search_companies.php"  class="bottomlink">BROWSE 
            COMPANIES</a> | <a href="employer/emp_home.php" class="bottomlink">EMPLOYERS</a> 
            | 
            <?php
	if(!isset($_SESSION["sbjbs_userid"]))
	{?>
            <a href="signin.php" class="bottomlink">LOGIN</a> | 
            <?php
	}?>
            <a href="contactus.php" class="bottomlink">CONTACT US</a> <br> <a href="terms.php" target="_blank" class="bottomlink">TERMS 
            OF USE</a> | <a href="privacy.php" target="_blank" class="bottomlink">PRIVACY 
            POLICY</a> | <a href="legal.php" target="_blank" class="bottomlink">LEGAL 
            POLICY</a> </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td   valign="top" ><div align="center"><font color="#003366" size="3" face="Verdana, Arial, Helvetica, sans-serif">
        <? //echo $config["sb_html_footer"];?>
        </font></div></td>
  </tr>
  <tr> 
    <td   valign="top" ><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="1"></font></font></div></td>
  </tr>
</table>
</body>
</html>