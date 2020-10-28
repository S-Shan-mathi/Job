<?
include_once("logincheck.php");
include_once("myconnect.php");

$to_delete="";
$items_removed=0;
if ( count($_POST)<>0 )
{


/////////////////////////// A REQUEST TO DELETE SOME CONTACTS ///////////////
if(isset($_REQUEST["delete"]))
{
	for($i=0;$i<=$_REQUEST["cnt"];$i++)
	{
	 
	if ( isset($_REQUEST["checkbox" . $i]) )  //Is the checkbox ticked
	{
	if ($to_delete!="") { $to_delete.="," ; }  //To insert comma??
	$to_delete.= $_REQUEST["checkbox" . $i];  //Add another item to delete
	$items_removed++;
	}
	
	} //End For
	
	
	if ($to_delete=="")
	{
		$msg1="Please choose a group";
	}
	else
	{
		$del_str="  Delete from sbjbs_mailalert_set where sb_id IN (" . $to_delete .")";
	//echo $del_str; 
	mysql_query($del_str);
	$msg1= $items_removed;
	$msg1.=($items_removed>1)?' Groups have':' Group has';
	$msg1.=" been removed";
	}
}		//edn if delete

//=============insert
if(isset($_REQUEST["insert"]))
{
	$sb_cid=str_replace(";",",",$_REQUEST["cid_list"]);
	$sb_loc_id=str_replace(";",",",$_REQUEST["loc_id"]);
	$sb_uid=$_SESSION["sbjbs_userid"];
	if($sb_cid==""){ $sb_cid=-1;}
	if($sb_loc_id==""){ $sb_loc_id=-1;}

		mysql_query("insert into sbjbs_mailalert_set (sb_uid,sb_cid,sb_loc_id) 
		values ($sb_uid,'$sb_cid','$sb_loc_id') ");
			if(mysql_affected_rows()>0)
			{
			$msg1="Requested group has been added to your alert list";
			}
			else
			{
			header("Location: gen_confirm_mem.php?err=manage_mail_alerts&errmsg=".urlencode("
			Sorry, some error occurred and unable to add group to your alert list"));
			die();
			}
}	//end if insert
header("Location: gen_confirm_mem.php?errmsg=".urlencode($msg1));
die();
/////////////////////////////
}



function main()
{
	global $to_delete, $items_removed;
	$cat_list="";
	$cid_list="";
	$loc_id_list="";
	$loc_list="";

	$sbrow_con=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sb_cat_listing=$sbrow_con["sb_cat_listing"];
	
?>	
<script language="JavaScript">
//<!--
				function add_category()
					{
					if(document.form123.cats.value!=0)
					{
					var id=document.form123.cats.selectedIndex;
					
					var cid_list=form123.cid_list.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form123.cats.value)
						{ posted="yes";}
						cnt++;
						}
						if(posted=="yes")
						{
						alert('This category is already in the list');
						return false;
					}

					if(document.form123.category.value=="")
					{
					document.form123.cid_list.value=document.form123.cats.value;
					document.form123.category.value=document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					else
					{
					document.form123.cid_list.value=document.form123.cid_list.value+";"+document.form123.cats.value;
					document.form123.category.value=document.form123.category.value+";"+document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Category to add!');
					}
					}
					
					function remove_category()
					{
					var s1=window.document.form123.category.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form123.cats.selectedIndex;
					var s3=document.form123.cats.options[id].text;
					
					var id_list=document.form123.cid_list.value;
					var id_split=id_list.split(";");
					var rem_id=document.form123.cats.value;
										
					window.document.form123.category.value="";
					window.document.form123.cid_list.value="";
					
					while(i<s2.length)
					{
					//alert('Checking '+s2[i]+' nnn  with'+s3+' mm');
						if(s3==s2[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.category.value=="")
							{
							document.form123.category.value=s2[i];
							}
							else
							{
							document.form123.category.value=document.form123.category.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.cid_list.value=="")
							{
							document.form123.cid_list.value=id_split[i];
							}
							else
							{
							document.form123.cid_list.value=document.form123.cid_list.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form123.related.value="";
					//window.document.form123.rel_id.value="";
					}


					function add_location()
					{
					if(document.form123.locs.value!=0)
					{
					var id=document.form123.locs.selectedIndex;
					
					var cid_list=form123.loc_id.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form123.locs.value)
						{ posted="yes";}
						cnt++;
					}
					if(posted=="yes")
					{
						alert('This location is already in the list');
						return false;
					}

					
					if(document.form123.location.value=="")
					{
					document.form123.loc_id.value=document.form123.locs.value;
					document.form123.location.value=document.form123.locs.options[id].text;
					document.form123.location.focus();
					document.form123.locs.selectedIndex=0;
					}
					else
					{
					document.form123.loc_id.value=document.form123.loc_id.value+";"+document.form123.locs.value;
					document.form123.location.value=document.form123.location.value+";"+document.form123.locs.options[id].text;
					document.form123.location.focus();
					document.form123.locs.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Location to add!');
					}
					}
					
					function remove_location()
					{
					var s1=window.document.form123.location.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form123.locs.selectedIndex;
					var s3=document.form123.locs.options[id].text;
					
					var id_list=document.form123.loc_id.value;
					var id_split=id_list.split(";");
					var rem_id=document.form123.locs.value;
										
					window.document.form123.location.value="";
					window.document.form123.loc_id.value="";
					
					while(i<s2.length)
					{
					//alert('Checking '+s2[i]+' nnn  with'+s3+' mm');
						if(s3==s2[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.location.value=="")
							{
							document.form123.location.value=s2[i];
							}
							else
							{
							document.form123.location.value=document.form123.location.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.loc_id.value=="")
							{
							document.form123.loc_id.value=id_split[i];
							}
							else
							{
							document.form123.loc_id.value=document.form123.loc_id.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form123.related.value="";
					//window.document.form123.rel_id.value="";
					}
//-->
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <form name="form123" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <td colspan="3" valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
          <tr> 
            <td  valign="top"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="titlestyle">&nbsp;Add New</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
                <tr align="left" valign="top"> 
                  <td colspan="3" class="innertablestyle"><font class="smalltext">Once 
                    you added a category- location group to your alerts then you 
                    will receive mail alerts upon posting of jobs in that group. 
                    <strong><br>
                    Leave blank if you want to choose all categories/locations</strong>.</font></td>
                </tr>
                <tr valign="top"> 
                  <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong><font class='normal'>Choose 
                    Category</font></strong></font></td>
                  <td width="6">&nbsp;</td>
                  <td><font class="smalltext"> 
                    <textarea name="category" cols="37" rows="5" readonly="readonly" id="category"><? echo $cat_list;?></textarea>
                    <br>
                    <select name="cats" id="select2" >
                      <option value="0">Choose a category</option>
                      <?
	$sbcat_arr=array();
	$sbord_arr=array();
	$rs_query=mysql_query("select * from sbjbs_categories order by sb_pid");
	while($rst=mysql_fetch_array($rs_query))
	{
		$cat_path="";
		$child=mysql_fetch_array(mysql_query("select * from sbjbs_categories where sb_pid=".$rst["sb_id"]));
		if($child)
			continue;
		$cat_path.=$rst["sb_cat_name"];
		$par=mysql_query("select * from sbjbs_categories where sb_id=".$rst["sb_pid"]);
		while($parent=mysql_fetch_array($par))
		{
			$cat_path=$parent["sb_cat_name"]."-".$cat_path;
			$par=mysql_query("select * from sbjbs_categories where sb_id=".$parent["sb_pid"]);
		}
		$sbcat_arr[$rst["sb_id"]]=$cat_path;
		$sbord_arr[$rst["sb_id"]]=$rst["sb_order_index"];
	}
	if($sb_cat_listing=='admin')
	{
		asort($sbord_arr);
		foreach($sbord_arr as $sbkey => $sbval)
		{
			echo '<option value="'.$sbkey.'"';
		//	echo($sbkey==$cid)?'selected':'';
			echo ' >'.$sbcat_arr[$sbkey].'</option>';
		}
	}
	else
	{
		asort($sbcat_arr);
		foreach($sbcat_arr as $sbkey => $sbval)
		{
			echo '<option value="'.$sbkey.'"';
		//	echo($sbkey==$cid)?'selected':'';
			echo ' >'.$sbval.'</option>';
		}
	}					  
									  ?>
                    </select>
                    <input name="cid_list" type="hidden" id="cid_list" value="<? echo $cid_list;?>">
                    <input name="add" type="button" id="add" value="Add" onClick="add_category()">
                    <input name="Remove" type="button" id="Remove" value="Remove" onClick="remove_category()">
                    </font></td>
                </tr>
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class="normal"><strong><font class='normal'>Choose 
                    Location</font></strong></font></td>
                  <td>&nbsp;</td>
                  <td><font class="smalltext"> 
                    <textarea name="location" cols="37" rows="5" readonly="readonly" id="location"><? echo $loc_list;?></textarea>
                    <br>
                    <select name="locs" id="locs" >
                      <option value="0">Choose a location</option>
                      <?
				  $rs_query=mysql_query("select * from sbjbs_locations order by sb_pid");
				  while($rst=mysql_fetch_array($rs_query))
				  {
				  $cat_path="";
		/*$child=mysql_fetch_array(mysql_query("select * from sbjbs_locations where sb_pid=".$rst["sb_id"]));
									  if($child)
									  {
									  continue;
									  }*/
									  $cat_path.=$rst["sb_loc_name"];
		 $par=mysql_query("select * from sbjbs_locations where sb_id=".$rst["sb_pid"]);
		 				while($parent=mysql_fetch_array($par))
		 				{
						$cat_path=$parent["sb_loc_name"]."-".$cat_path;
						$par=mysql_query("select * from sbjbs_locations where sb_id=".$parent["sb_pid"]);
						}
                                      ?>
                      <option value="<? echo $rst["sb_id"];?>" ><? echo $cat_path;?></option>
                      <?
									  }
									  ?>
                    </select>
                    <input name="loc_id" type="hidden" id="loc_id" value="<? echo $loc_id_list;?>">
                    <input name="add2" type="button" id="add2" value="Add" onClick="add_location()">
                    <input name="Remove2" type="button" id="Remove2" value="Remove" onClick="remove_location()">
                    </font></td>
                </tr>
                <tr valign="top"> 
                  <td align="right" class="innertablestyle">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td><input type="submit" name="insert" value="Add"></td>
                </tr>
              </table></td>
          </tr>
        </table> 
        </td>
    </form>
  </tr>
  <tr> 
    <td height="25" colspan="3" align="center" valign="middle">&nbsp;</td>
  </tr>
  <tr> 
    <td  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp; Mail Alerts Groups 
                      </td>
                    </tr>
                    <tr> 
                      <td valign="top"><font class='normal'>To remove a category 
                        just click the check box and click the remove button below.</font></td>
                    </tr>
                    <tr> 
                      <td valign="top"> 
					  <script language="JavaScript">
						//<!--
						function select_all()
						{
						  for (var i=0;i<document.form2.elements.length;i++)
						  {
							var e =document. form2.elements[i];
							if ((e.name != 'check_all') && (e.type=='checkbox'))
							{
							   e.checked = document.form2.check_all.checked;
							}
						  }
						
						}
						//-->
						</script> 
						<form name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
                            <tr class="subtitle"> 
                              <td width="10"> <div align="center"> 
                                  <input name="check_all" type="checkbox" id="check_all3" onClick="select_all();" value="yes">
                                </div></td>
                              <td colspan="3"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Select 
                                All </font></b></td>
                            </tr>
                            <?
//	  $sql="Select * from sbjbs_fav_cats where sbjbs_fav_cats.mid=sbjbs_members.sb_id and sbjbs_members.sb_id =" .$_SESSION["sbjbs_userid"] ;
	
		$sql="Select * from sbjbs_mailalert_set where sb_uid=".$_SESSION["sbjbs_userid"];
		$rs0_query=mysql_query($sql);

			$cnt=0;
			while ($rs0=mysql_fetch_array($rs0_query))
			{
			$cat_str="";
			$loc_str="";
			if($rs0["sb_cid"]<>"-1")
			{
				$cats_q=mysql_query("select * from sbjbs_categories where sb_id in (".$rs0["sb_cid"].")");
				while($cats=mysql_fetch_array($cats_q))
				{ 
				$cat_str=($cat_str=="")?$cats["sb_cat_name"]:$cat_str.",".$cats["sb_cat_name"];
				}
			}
			else
			{
				$cat_str="All Categories";
			}
			if($rs0["sb_loc_id"]<>"-1")
			{
				$locs_q=mysql_query("select * from sbjbs_locations where sb_id in (".$rs0["sb_loc_id"].")");
				while($locs=mysql_fetch_array($locs_q))
				{ 
				$loc_str=($loc_str=="")?$locs["sb_loc_name"]:$loc_str.",".$locs["sb_loc_name"];
				}
			}
			else
			{
				$loc_str="All Locations";
			}
			?>
                            <tr> 
                              <td width="10" valign="top">
<div align="center"> 
                                  <input type="checkbox" name="checkbox<?php echo $cnt;?>" value="<?php
			    echo $rs0["sb_id"];?>">
                                </div></td>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="50"><font class="smalltext"><strong>Categories:</strong></font></td>
                                    <td><font class="smalltext"> <strong>&nbsp;</strong><? echo $cat_str;?></font></td>
                                  </tr>
                                  <tr>
                                    <td width="50"><font class="smalltext"><strong>Locations:</strong></font></td>
                                    <td><font class="smalltext"> <strong>&nbsp;</strong><? echo $loc_str;?></font></td>
                                  </tr>
                                </table>
                                
                              </td>
                            </tr>
                            <?
			$cnt++;
			}
			?>
                            <tr > 
                              <td colspan="2" align="left"> <div align="left"> 
                                  <input name="cnt" type="hidden" id="cnt3" value="<?php echo $cnt; ?>">
                                  <input type="submit" name="delete" value="Remove">
                                </div></td>
                            </tr>
                          </table>
                        </form></td>
                    </tr>
                  </table></td>
      </tr>
     </table></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
     </tr>
     <tr> 
       <td valign="top"> <div align="center"></div></td>
     </tr>
  </table></td>
 </tr>
</table>
        <?
 }
include_once("template.php");
 
  ?> 