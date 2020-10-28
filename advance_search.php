<?
include_once "myconnect.php";


function main()
{

	$sbrow_con=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
	$sb_cat_listing=$sbrow_con["sb_cat_listing"];

$work_exp="";
$cid_list="";
$cat_list="";
$loc_list="";
$loc_id_list="";

?>
<script language="JavaScript">
//<!--
					function add_category()
					{
					if(document.form1.cats.value!=0)
					{
					var id=document.form1.cats.selectedIndex;
///-------
					var cid_list=form1.cid_list.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form1.cats.value)
						{ posted="yes";}
						cnt++;
						}
						if(posted=="yes")
						{
						alert('This category is already in the list');
						return false;
					}

//----------
					if(document.form1.category.value=="")
					{
					document.form1.cid_list.value=document.form1.cats.value;
					document.form1.category.value=document.form1.cats.options[id].text;
					document.form1.category.focus();
					document.form1.cats.selectedIndex=0;
					}
					else
					{
					document.form1.cid_list.value=document.form1.cid_list.value+";"+document.form1.cats.value;
					document.form1.category.value=document.form1.category.value+";"+document.form1.cats.options[id].text;
					document.form1.category.focus();
					document.form1.cats.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Category to add!');
					}
					}
					
					function remove_category()
					{
					var s1=window.document.form1.category.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form1.cats.selectedIndex;
					var s3=document.form1.cats.options[id].text;
					
					var id_list=document.form1.cid_list.value;
					var id_split=id_list.split(";");
					var rem_id=document.form1.cats.value;
										
					window.document.form1.category.value="";
					window.document.form1.cid_list.value="";
					
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
							if(document.form1.category.value=="")
							{
							document.form1.category.value=s2[i];
							}
							else
							{
							document.form1.category.value=document.form1.category.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form1.cid_list.value=="")
							{
							document.form1.cid_list.value=id_split[i];
							}
							else
							{
							document.form1.cid_list.value=document.form1.cid_list.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form1.related.value="";
					//window.document.form1.rel_id.value="";
					}

					function add_location()
					{
					if(document.form1.locs.value!=0)
					{
					var id=document.form1.locs.selectedIndex;

///-------
					var cid_list=form1.loc_id.value.split(";");
					var cnt=0;
					var posted="no";
					while(cnt<cid_list.length)
					{
						if(cid_list[cnt]==document.form1.locs.value)
						{ posted="yes";}
						cnt++;
						}
						if(posted=="yes")
						{
						alert('This location is already in the list');
						return false;
					}

//----------

					if(document.form1.location.value=="")
					{
					document.form1.loc_id.value=document.form1.locs.value;
					document.form1.location.value=document.form1.locs.options[id].text;
					document.form1.location.focus();
					document.form1.locs.selectedIndex=0;
					}
					else
					{
					document.form1.loc_id.value=document.form1.loc_id.value+";"+document.form1.locs.value;
					document.form1.location.value=document.form1.location.value+";"+document.form1.locs.options[id].text;
					document.form1.location.focus();
					document.form1.locs.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Location to add!');
					}
					}
					function remove_location()
					{
					var s1=window.document.form1.location.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form1.locs.selectedIndex;
					var s3=document.form1.locs.options[id].text;
					
					var id_list=document.form1.loc_id.value;
					var id_split=id_list.split(";");
					var rem_id=document.form1.locs.value;
										
					window.document.form1.location.value="";
					window.document.form1.loc_id.value="";
					
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
							if(document.form1.location.value=="")
							{
							document.form1.location.value=s2[i];
							}
							else
							{
							document.form1.location.value=document.form1.location.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form1.loc_id.value=="")
							{
							document.form1.loc_id.value=id_split[i];
							}
							else
							{
							document.form1.loc_id.value=document.form1.loc_id.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form1.related.value="";
					//window.document.form1.rel_id.value="";
					}


//-->
</script>

<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr align="left" valign="top"> 
    <td>
<table width="90%%" border="0" align="center" cellpadding="0" cellspacing="0"  class="onepxtable">
                <tr align="left" valign="top"> 
                  
          <td class="titlestyle">&nbsp;Advanced Search</td>
                </tr>
        <tr > 
          <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="5">
              <form name="form1" method="post" action="search_result.php">
                <tr valign="top"> 
                  <td width="40%" align="right" class="innertablestyle"><font class='normal'><strong>Search 
                    Keywords</strong></font></td>
                  <td width="6" class="maintext">&nbsp;</td>
                  <td width="60%" class="maintext"> <font class='normal'> 
                    <input name="keyword" type="text" size="50">
                    </font></td>
                </tr>
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class='normal'><strong>Search 
                    Method </strong></font></td>
                  <td class="maintext">&nbsp;</td>
                  <td class="maintext"><font class='normal'> 
                    <input name="search_method" type="radio" value="1" checked>
                    An exact phrase match<br>
                    <input type="radio" name="search_method" value="2">
                    Matches on all words (AND)<br>
                    <input type="radio" name="search_method" value="3">
                    Matches on any words (OR)</font></td>
                </tr>
                <!--<tr valign="top"> 
                  <td width="40%" align="right" class="innertablestyle"><font class='normal'><strong>Search 
                    in </strong></font></td>
                  <td width="6" class="maintext">&nbsp;</td>
                  <td width="60%" class="maintext"> <p><font class='normal'>(Please choose at least one option of these)<br>
                      <input name="all_p" type="checkbox" value="yes" checked>
                      All<br>
                      <input name="article" type="checkbox" id="article" value="yes">
                      Article<br>
                      <input type="checkbox" name="int_comment" value="yes">
                      Initial Comment<br>
                      <input type="checkbox" name="all_comment" value="yes">
                      All Comments<br>
                      <input type="checkbox" name="member" value="yes">
                      Members Username/Name<br>
                      </font></p></td>
                </tr>-->
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class='normal'><strong>Categories</strong></font></td>
                  <td class="maintext">&nbsp;</td>
                  <td class="maintext"><font class="smalltext"> 
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
                  <td align="right" class="innertablestyle"><font class='normal'><strong>Locations</strong></font></td>
                  <td class="maintext">&nbsp;</td>
                  <td class="maintext"><font class="smalltext"> 
                    <textarea name="location" cols="37" rows="5" readonly="readonly" id="location"><? echo $loc_list;?></textarea>
                    <br>
                    <font class="smalltext"><select name="locs" id="locs" >
                <option value="0">Choose a location</option>
                <?
			$rs_query=mysql_query("select * from sbjbs_locations where sb_pid=0 order by  sb_default desc,sb_loc_name");
			  
			 while($rst=mysql_fetch_array($rs_query))
			  {
//				  $cat_path="";
		/*$child=mysql_fetch_array(mysql_query("select * from sbjbs_locations where sb_pid=".$rst["sb_id"]));
									  if($child)
									  {
									  continue;
									  }*///
									  $cat_path=$rst["sb_loc_name"];
/*		 $par=mysql_query("select * from sbjbs_locations where sb_id=".$rst["sb_pid"]);
		 				while($parent=mysql_fetch_array($par))
		 				{
						$cat_path=$parent["sb_loc_name"]."-".$cat_path;
						$par=mysql_query("select * from sbjbs_locations where sb_id=".$parent["sb_pid"]);
						}*/
                                      ?>
                <option value="<? echo $rst["sb_id"];?>" ><? echo $cat_path;?></option>
                <?
									  
									loc_order($rst["sb_id"]);
									}
									  ?>
              </select></font>
                    <input name="loc_id" type="hidden" id="loc_id" value="<? echo $loc_id_list;?>">
                    <input name="add2" type="button" id="add2" value="Add" onClick="add_location()">
                    <input name="Remove2" type="button" id="Remove2" value="Remove" onClick="remove_location()">
                    </font></td>
                </tr>
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class='normal'><strong>Experience 
                    Level </strong></font></td>
                  <td class="maintext">&nbsp;</td>
                  <td class="maintext"><SELECT name=work_exp id="work_exp">
                      <option value="" selected>- -</option>
                      <OPTION value=0>Fresher</OPTION>
                      <OPTION value=1>Less than 1 Year</OPTION>
                      <OPTION value=2>1 to 2 Years</OPTION>
                      <OPTION value=3>2 to 5 Years</OPTION>
                      <OPTION value=4>5 to 7 Years</OPTION>
                      <OPTION value=5>7 to 10 Years</OPTION>
                      <OPTION value=6>10 to 15 Years</OPTION>
                      <OPTION value=7>More than 15 Years</OPTION>
                    </SELECT></td>
                </tr>
                <tr valign="top"> 
                  <td align="right" class="innertablestyle"><font class='normal'><strong>View 
                    Search Results </strong></font></td>
                  <td class="maintext">&nbsp;</td>
                  <td class="maintext"><font class="smalltext"> 
                    <input name="view" type="radio" value="desc" checked>
                    Descriptive 
                    <input type="radio" name="view" value="brief" >
                    Brief</font></td>
                </tr>
                <tr valign="top"> 
                  <td width="40%" align="right" class="innertablestyle"><font class='normal'>&nbsp;</font></td>
                  <td width="6" class="maintext">&nbsp;</td>
                  <td width="60%" class="maintext"><font class='normal'> 
                    <input type="submit" name="Submit" value="Search">
                    <input name="show_save" type="hidden" id="show_save" value="yes">
                    </font></td>
                </tr>
              </form>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr align="left" valign="top"> 
    <td>&nbsp;</td>
  </tr>
</table>
<?
}// end main
include "template.php";
?>