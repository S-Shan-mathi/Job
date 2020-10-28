<?
function left()
{
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
/////---getting settings
$sbq_con="select * from sbjbs_config where sb_id=1";
$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
$premium_cnt=$config["sb_premium_cnt"]*2;
$imgpmt=" height=50 width=70 ";

$sbq_icon="select * from sbjbs_icons where sb_id=".$sbrow_con["sb_icon_list"];
$sbrow_icon=mysql_fetch_array(mysql_query($sbq_icon));
$sb_bar=$sbrow_icon["sb_bar"];
//$sb_more=$sbrow_icon["sb_more"];


$username="demo";

//---------comment these to disable autofill
$sbq_mem="select * from sbjbs_seekers where sb_username='$username'";
$sbrow_mem=mysql_fetch_array(mysql_query($sbq_mem));
$pwd=$sbrow_mem["sb_password"];
//---------end comment these to disable autofill
//---------comment these to disable autofill
$sbq_mem="select * from sbjbs_employers where sb_username='$username'";
$sbrow_mem=mysql_fetch_array(mysql_query($sbq_mem));
$pwd_emp=$sbrow_mem["sb_password"];
//---------end comment these to disable autofill



?>
<script language="JavaScript">
//<!--
function submit_form()
{
	if(document.frm123.option[0].checked==true)
	{
	document.frm123.action="login.php";
	}
	else
	{
	document.frm123.action="employer/login.php";
	}
	document.frm123.submit();
}
function initpwd()
{
	if(document.frm123.option[0].checked==true)
	{
	document.frm123.pwd.value="<?php echo $pwd;?>";
	}
	else
	{
	document.frm123.pwd.value="<?php echo $pwd_emp;?>";
	}
}
//-->
</script>
<table width="150" border="0" align="left" cellpadding="0" cellspacing="0" >
  <tr> 
    <td> 
      <? 
	  if(!isset($_SESSION["sbjbs_userid"])) 
			  {
			  
        ?>
      <table width="100%" border=0 align=center cellpadding=2 cellspacing=0 class="innertablestyle">
        <FORM name="frm123"   method=post>
          <tbody>
            <tr valign=center> 
              <td height="25" colspan="2"  class="sidetitle" ><strong><img src="admin/sbjbs_icons/<?php echo $sb_bar; ?>"  align="absmiddle" border="0">&nbsp;&nbsp;Member 
                Login</strong></td>
            </tr>
            <tr valign=center> 
              <td> <div align=right><font class="smalltext"><strong>Username</strong></font></div></td>
              <td width="97"><font color="#006699"> 
                <input  name="username" type="text" size="10" >
                <input type="hidden" id="sb_type" name="sb_type" value="<? if (isset($_REQUEST["sb_type"])&& ($_REQUEST["sb_type"]>=0) ) echo $_REQUEST["sb_type"]; else echo 0; ?>">
                <input type="hidden" id="id" name="id" value="<? if (isset($_REQUEST["id"])&& ($_REQUEST["id"]>=0) ) echo $_REQUEST["id"]; else echo 0; ?>">
                <input name="return_path" type="hidden" id="return_path" value="<? if (isset($_REQUEST["return_add"])) echo $_REQUEST["return_add"]; else echo ""; ?>">
                </font></td>
            </tr>
            <tr valign=center> 
              <td> <div align=right><font class="smalltext"><strong>Password</strong></font></div></td>
              <td> <input name="pwd"  type=password id="pwd"   size="10" > 
              </td>
            </tr>
            <tr align=right>
              <td><font class="smalltext"><strong>Login As</strong></font></td>
              <td align="left"><font class="smalltext"><input type="radio" name="option" value="JS" checked >
                Job Seekers </font></td>
            </tr>
            <tr align=right> 
              <td>&nbsp;</td>
              <td align="left"><font class="smalltext"> 
                <input type="radio" name="option" value="EMP" >
                Employers</font></td>
            </tr>
            <tr align=right> 
              <td 
        colspan=2> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="67%"><!--p><font color="#006699"><a  class="insidelink"  href="signup.php">Signup 
                        Now</a><br>
                        <a class="insidelink" href="lostpassword.php">Lost Password</a></font></p--></td>
                    <td width="33%"> <div align="left"> <font color="#006699"> 
                        <input type="submit" name="Submit" value="Sign In" onClick="submit_form();" >
                        </font></div></td>
                  </tr>
                </table></td>
            </tr>
          </tbody>
        </FORM>
      </table>
      <? }
		else
		{
		?>
      <table width="100%" border=0 align=center cellpadding=2 cellspacing=1 class="innertablestyle">
        <tbody>
          <tr valign=center> 
            <td height="25" colspan="2"  class="sidetitle" ><strong><img src="admin/sbjbs_icons/<?php echo $sb_bar; ?>"  align="absmiddle" border="0">&nbsp;&nbsp;Member 
              Options </strong></td>
          </tr>
          <tr valign=center > 
            <td width="2">&nbsp;</td>
            <td width="100%"> <div align="left"><font class="smalltext"><a href="editmember.php" class="sidelink">Edit 
                Your Profile </a></font></div></td>
          </tr>
          <tr valign=center> 
            <td width="2">&nbsp;</td>
            <td width="100%"><a href="changepassword.php" class="sidelink">Change 
              Password </a></td>
          </tr>
          <tr valign=center> 
            <td width="2">&nbsp;</td>
            <td width="100%"><a href="resumes.php" class="sidelink">My Resumes</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="headline.php" class="sidelink">Post Resume</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="letters.php" class="sidelink">My Cover Letters</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="add_cover_letter.php" class="sidelink">Add Cover Letter</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="my_jobs.php" class="sidelink">My Applied Jobs</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="manage_search.php" class="sidelink">My Searches</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="manage_mail_alerts.php" class="sidelink">Mail Alerts</a></td>
          </tr>
          <tr valign=center>
            <td>&nbsp;</td>
            <td><a href="subscribe.php" class="sidelink"><?php
		  $sub=mysql_num_rows(mysql_query("select * from sbjbs_newsletter,sbjbs_seekers
		  where sbjbs_newsletter.sb_email=sbjbs_seekers.sb_email_addr and 
		  sbjbs_seekers.sb_id=".$_SESSION["sbjbs_userid"]));
		  //echo $sub;
		  if($sub>0)
		  {  echo "Unsubscribe";}
		  else
		  { echo "Subscribe";}
		  
		  ?> Newsletter</a></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><a href="logout.php" class="sidelink">Logout</a></td>
          </tr>
        </tbody>
      </table>
      <?
		}
      ?>
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="100%" border=0 align=center cellpadding=2 cellspacing=1 class="innertablestyle">
        <tbody>
          <tr valign=center> 
            <td height="25" colspan="2"  class="sidetitle" ><strong><img src="admin/sbjbs_icons/<?php echo $sb_bar; ?>"  align="absmiddle" border="0">&nbsp;&nbsp;Premium 
              Employers </strong></td>
          </tr><?php
		  $prm_emp_q=mysql_query("select * from sbjbs_premium_gallery where sb_approved='yes' ");
		  $num_rows=mysql_num_rows($prm_emp_q);
		  $max_allowed=$premium_cnt;

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
		$prm_emp=mysql_fetch_array($prm_emp_q);
		while (($prm_emp)&&($cnt<$max_allowed))
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
			$comp=mysql_fetch_array(mysql_query("select * from sbjbs_companies where 
			sb_id=".$prm_emp["sb_profile_id"]." and sb_approved='yes' and sb_show_profile='yes' "));
			
			if($cnt%2==0)
			{	
			  ?><tr valign=center><?php
			   }
				?><td width="50%"><?php
				if($comp["sb_show_profile"]=="yes")
				{ echo "<a href='view_profile.php?id=".$comp["sb_id"]."'>";}
				?><img src="uploadedimages/<?php echo $prm_emp["sb_img_url"];?>" 
				<?php echo $imgpmt;?> alt="<? echo $comp["sb_name"];?>" border="0"><?php
				if($comp["sb_show_profile"]=="yes")
				{ echo "</a>";}
				?></td><?php
			if($cnt%2==1)
				{
			  ?></tr><?php
				}
			$cnt++;
			}//if display
			$prm_emp=mysql_fetch_array($prm_emp_q);
			$row++;
		}// end while
        ?></tbody>
      </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="100%" border=0 align=center cellpadding=2 cellspacing=0 class="innertablestyle">
        <tbody>
          <tr valign=center> 
            <td height="25" colspan="3"  class="sidetitle" ><strong><img src="admin/sbjbs_icons/<?php echo $sb_bar; ?>"  align="absmiddle" border="0">&nbsp;&nbsp;Site 
              Stats </strong></td>
          </tr>
          <tr valign=center> 
            <td width="5">&nbsp;</td>
            <td width="87"><font class="smalltext">Users Online</font></td>
            <td><font class="smalltext"> 
              <?php
	$sql="Select count(*) from sbjbs_online  "  ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	{
	echo $rs0[0];
	}
	else
	{
	echo "0";
	}	
		?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><font class="smalltext">Employers Online</font></td>
            <td><font class="smalltext"> 
              <?php
	$sql="Select count(*) from sbjbs_emp_online  "  ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	{
	echo $rs0[0];
	}
	else
	{
	echo "0";
	}	
		?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><font class="smalltext">Total Members</font></td>
            <td><font class="smalltext"> 
              <?php
	$sql="Select count(*) from sbjbs_seekers  "  ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	{
	echo $rs0[0];
	}
	else
	{
	echo "0";
	}	
		?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><font class="smalltext">Total Employers </font></td>
            <td><font class="smalltext"> 
              <?php
	$sql="Select count(*) from sbjbs_employers  "  ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	{
	echo $rs0[0];
	}
	else
	{
	echo "0";
	}	
		?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td>&nbsp;</td>
            <td><font class="smalltext">Jobs</font></td>
            <td><font class="smalltext"> 
              <?php
		$sql="Select count(*) from sbjbs_jobs where sb_approved='yes'" ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	echo $rs0[0];
	else
	echo "0";	
		?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td width="5">&nbsp;</td>
            <td width="87"><font class="smalltext"> Resumes</font></td>
            <td width="46"><font class="smalltext"> 
              <?php
      /*
		$sql="Select count(*) from sbjbs_resumes where sb_approved='yes' group by sb_seeker_id" ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	echo $rs0[0];
	else
	echo "0";	
		*/
				$num=mysql_fetch_array(mysql_query("select count(*) from sbjbs_resumes where 1"));
				if($num)
				{ echo $num[0]; }
				else
				{ echo "0";}
				?>
              </font></td>
          </tr>
          <tr valign=center> 
            <td width="5">&nbsp;</td>
            <td width="87"><font class="smalltext"> Companies</font></td>
            <td width="46"><font class="smalltext"> 
              <?php
		$sql="select count(*) from sbjbs_companies where sb_approved='yes' " ;
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	if($rs0)
	echo $rs0[0];
	else
	echo "0";	
		?>
              </font></td>
          </tr>
          <!-- <tr valign=center> 
            <td colspan="2"><div align="right"><a href="stats.php" ><img src="admin/sbjbs_icons/<?php echo $sb_more; ?>"   border="0" alt="More"></a></div></td>
          </tr>-->
        </tbody>
      </table></td>
  </tr>
  <tr> 
    <td height="100%">&nbsp;</td>
  </tr>
</table>
<?
}// end left
?>