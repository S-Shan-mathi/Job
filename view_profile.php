<?php
include_once "myconnect.php";
include_once "session.php";
include_once "date_time_format.php";


if(!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id']) || ($_REQUEST['id'] == 0) )
{
	header("Location: gen_confirm.php?errmsg=".urlencode("Invalid access, denied"));
	die();
}

$id=$_REQUEST['id'];

$sbstr='';
if( isset($_SESSION["sbjbs_emp_userid"]) )
	$sbstr="or (sb_uid=".$_SESSION["sbjbs_emp_userid"].")";

$sbq_off="select * from sbjbs_companies where sb_id=$id and ((sb_approved='yes' and sb_show_profile='yes')  $sbstr )";
//echo($sbq_off);
$sbrow_off=mysql_fetch_array(mysql_query($sbq_off));
if(!$sbrow_off)
{
	header("Location: gen_confirm.php?errmsg=".urlencode("No such profile exists or disabled by admin"));
	die();
}
///////////------updating view count
$sbqu_off="update sbjbs_companyprofiles set sb_viewed=sb_viewed+1 where sb_id=$id";
mysql_query($sbqu_off);

//////////---------
function main()
{
	global $sbrow_off, $id;
	
	$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sb_null_char=$config["sb_null_char"];
/*	$icons=mysql_fetch_array(mysql_query("select * from sbjbs_icons where sb_id=".$config["sb_icon_list"]));
	$send_inquiry_icon="admin/sbjbs_icons/".$icons["sb_send_inquiry"];
	$inquiry_basket_icon="admin/sbjbs_icons/".$icons["sb_inquiry_basket"];
	$contact_list_icon="admin/sbjbs_icons/".$icons["sb_contact_list"];
	$block_list_icon="admin/sbjbs_icons/".$icons["sb_block_list"];
	$add_fav_icon="admin/sbjbs_icons/".$icons["sb_add_fav"];
	$company_profile_icon="admin/sbjbs_icons/".$icons["sb_profile"];
*/
$cid=0;
$keyword="";

	if(isset($_REQUEST["cid"])&&($_REQUEST["cid"]<>""))
	{
	$cid=$_REQUEST["cid"];
	}

	if(isset($_REQUEST["keyword"])&&($_REQUEST["keyword"]<>""))
	{
		$keyword=$_REQUEST["keyword"];
	}


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
    $catpath =" > <a href=\"cat_sell.php?cid=" . $rs["sb_id"] . "\">" .$rs["sb_cat_name"]."</a>".$catpath; 
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id=" . $rs["sb_pid"] );
	 
	}

 $mem=mysql_fetch_array(mysql_query("select * from sbjbs_employers where sb_id=".$sbrow_off["sb_uid"]));


?> 
<script language="JavaScript">
function win(box)
{
str="addcontact_popup.php?"  + box;

window.open(str,"Allot","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=350,height=150,location=no,directories=no,scrollbars=yes");
return false;
}

function win1(box)
{
str="addblock_popup.php?"  + box;

window.open(str,"Allot","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=350,height=150,location=no,directories=no,scrollbars=yes");
return false;
}
</script> 
<table width="100%" border="0" cellspacing="10" cellpadding="2" class="maintablestyle">
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" >
        <tr> 
          <td> 
            <!--font class="normal">&nbsp;<a href="index.php">Home</a><? echo $catpath;?></font-->
          </td>
        </tr>
        <tr> 
          <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr> 
                <td valign="middle" class="titlestyle">&nbsp;&nbsp;<?php echo $sbrow_off["sb_name"]; ?> 
                  posted by <?php 
		  echo $mem["sb_username"];
 ?>
                  <!--font class="smalltext"><font class="red"><strong>[ <font class="smalltext"><font class="red"><strong> 
                  <?php  
//			$level_query=mysql_query("select * from sbjbs_levels where sb_levelid=" .$mem["sb_memtype"]);
 //           $level= mysql_fetch_array($level_query);
//			echo $level["sb_levelname"];
			?>
                  </strong></font></font>]</strong></font></font></strong -->
                </td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td><div align="center"> 
              <?php
		  if($sbrow_off["sb_logo"]<>"")
		  {
		  ?>
              <img src="uploadedimages/<?php echo $sbrow_off["sb_logo"]; ?>" border="0"> 
              <?php
		  }
		  ?>
            </div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1" class="onepxtable">
        <tr valign="top"> 
          <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr valign="top"> 
                <td width="50%" height="100%"> <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="1">
                    <tr valign="top" class="onepxtable"> 
                      <td width="40%" height="25" class="subtitle"> <div align="left">Industry</div></td>
                      <td width="60%" height="25" class="innertablestyle"><font class="normal">
                        <?php 
		  $sbq_pro_fo="select * from sbjbs_industries where sb_id=".$sbrow_off["sb_industry"];
		  $sbrow_pro_fo=mysql_fetch_array(mysql_query($sbq_pro_fo));
		  echo $sbrow_pro_fo["sb_name"];
		   ?>
                      </font> </td>
                    </tr>
                    <tr valign="top" class="onepxtable"> 
                      <td height="25" class="subtitle"> <div align="left">Bussiness 
                          Type</div></td>
                      <td height="25" class="innertablestyle" ><font class="normal"> 
                        <?php 
		  $sbq_pro_fo="select * from sbjbs_businesstypes where sb_id=".$sbrow_off["sb_type"];
		  $sbrow_pro_fo=mysql_fetch_array(mysql_query($sbq_pro_fo));
		  echo $sbrow_pro_fo["sb_businesstype"];
		?>
                        </font></td>
                    </tr>
                    <tr valign="top" class="onepxtable"> 
                      <td height="25" class="subtitle">Number of Employees</td>
                      <td height="25" class="innertablestyle" ><font class="normal"><?php echo $sbrow_off["sb_no_of_emps"]; ?></font></td>
                    </tr>
                  </table></td>
                <td height="100%"> <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="1">
                    <tr valign="top" class="onepxtable"> 
                      <td width="40%" height="25" class="subtitle"> <div align="left">Location</div></td>
                      <td width="60%" height="25" class="innertablestyle" ><font class='normal'><?php echo $sbrow_off["sb_location"];?>, 
                        <?php 
  		  $sbq_pro_fo="select * from sbjbs_country where id=".$sbrow_off["sb_country"];
		  $sbrow_pro_fo=mysql_fetch_array(mysql_query($sbq_pro_fo));
		  echo $sbrow_pro_fo["country"];	?>
                        </font></td>
                    </tr>
                    <tr valign="top" class="onepxtable"> 
                      <td height="25" class="subtitle"> <div align="left">Sales 
                          Turnover </div></td>
                      <td height="25" class="innertablestyle" ><font class="normal"> 
                        <?php 
				echo $sbrow_off["sb_sales"].' '.$sbrow_off["sb_multiplier"];
				$sbq_cur="select * from sbjbs_currencies where sbcur_id=".$sbrow_off["sb_currency"];
				$sbrow_cur=mysql_fetch_array(mysql_query($sbq_cur));
				echo ' '.$sbrow_cur["sbcur_name"];
		?>
                        </font></td>
                    </tr>
                    <tr valign="top" class="onepxtable"> 
                      <td height="25" class="subtitle"> <div align="left">Number 
                          of Offices</div></td>
                      <td height="25" class="innertablestyle" > <font class="normal"><?php echo $sbrow_off["sb_no_of_officies"]; ?> 
                        </font></td>
                    </tr>
                  </table></td>
              </tr>
              <tr valign="top"> 
                <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
                    <tr valign="top" class="onepxtable"> 
                      <td width="20%" height="25" class="subtitle">Website Address</td>
                      <td height="25" class="innertablestyle" ><font class="normal"> 
                        <?php
					   if($sbrow_off["sb_website"]<>"")
					   {echo $sbrow_off["sb_website"]; }
					   else
					   {echo $config["sb_null_char"];}
					   ?>
                        </font></td>
                    </tr>
                    <tr valign="top" class="onepxtable"> 
                      <td height="25" class="subtitle"> <div align="left">Categories</div></td>
                      <td height="25" class="innertablestyle"> 
                        <?php 
			$sbq_cat_jobs="select * from sbjbs_profile_cats where sb_company_id=".$sbrow_off["sb_id"];
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
                      </td>
                    </tr>
                  </table></td>
              </tr>
              <!--tr valign="top"> 
                <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="2" class="innertablestyle">
                    <tr valign="bottom"> 
                      <td align="center"><a href="contactuser.php?sb_type=4&sb_id=<?php echo $id; ?>"><img src="<?php echo $send_inquiry_icon;?>" border="0" alt="Send Inquires"></a></td>
                      <td align="center"> <a href="dummy.htm" onClick="return win('<?php echo "sb_type=1&sb_id=$id&username=".$mem["sb_username"]; ?>');"  ><img src="<?php echo $contact_list_icon;?>" border="0" alt="Add to Partner List"></a> 
                      </td>
                      <td align="center"><a href="dummy.htm" onClick="return win1('<?php echo "sb_type=1&sb_id=$id&username=".$mem["sb_username"]; ?>');"><img src="<?php echo $block_list_icon;?>" border="0" alt="Add to Block List"></a></td>
                      <td align="center"> <a href="add_favorites.php?sb_type=4&sb_id=<?php echo $id; ?>"><img src="<?php echo $add_fav_icon;?>" border="0" alt="Add to Favourites"></a></td>
                    </tr>
                  </table></td>
              </tr -->
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td valign="top">      
	<table width="90%" border="0" align="center" cellpadding="2" cellspacing="1" class="onepxtable">
        <tr> 
          <td class="titlestyle">&nbsp;Company Profile</td>
        </tr>
        <tr> 
          <td height="25" valign="top" class="innertablestyle"> <font class="normal"><?php echo $sbrow_off["sb_profile"]; ?></font></td>
        </tr>
      </table></td>
  </tr>
</table>
<?
}// end main
include "template.php";
?>
