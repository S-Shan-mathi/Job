<?php
//include_once("logincheck.php");
include_once("session.php");
include_once("myconnect.php");
include_once "styles.php";

$errcnt=0;
$showform=true;
/*if( count($_REQUEST) <> 0)
{
	foreach($_REQUEST as $i => $j)
		echo "Name: $i, Value= $j<br>";
	
}*/
//echo "count -------->".count($_POST)."<br> Insert------>".$_POST["insert"];
if( (count($_POST) > 0) && (isset($_POST["insert_confirm"])) )// && ($_REQUEST["Submit"]=='Save Now') ) )
{	//main 
	//echo "hi";
		$sb_title=$_REQUEST["title"];
		$sb_keyword=$_REQUEST["keyword"];
		$sb_search_method=$_REQUEST["search_method"];
		$sb_cid_list=$_REQUEST["cid_list"];
		$sb_loc_id=$_REQUEST["loc_id"];
		$sb_work_exp=$_REQUEST["work_exp"];
		$sb_view=$_REQUEST["view"];
	
	if(strlen(trim($sb_title)) == 0)
	{
		$errs[$errcnt]="Search Title must be provided";
		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["title"]))
	{
		$errs[$errcnt]="Search Title can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	else
	{
		if(!get_magic_quotes_gpc())
			$sb_title_chk=str_replace('$','\$',addslashes($_REQUEST["title"]));
		else
			$sb_title_chk=str_replace('$','\$',$_REQUEST["title"]);
		$sbq_sea_chk="select * from sbjbs_search_results where sb_title='$sb_title_chk' and sb_uid=".$_SESSION["sbjbs_userid"];
//		echo $sbq_sea_chk;
		$sbrs_sea_chk=mysql_query($sbq_sea_chk);
		if(mysql_num_rows($sbrs_sea_chk) > 0)
		{
			$errs[$errcnt]="Search Title already exists";
			$errcnt++;
		}
	}

	if($errcnt == 0)
	{
		if(!get_magic_quotes_gpc())
		{
			$sb_title=str_replace('$','\$',addslashes($_REQUEST["title"]));
			$sb_keyword=str_replace('$','\$',addslashes($_REQUEST["keyword"]));
			$sb_cid_list=str_replace('$','\$',addslashes($_REQUEST["cid_list"]));
			$sb_loc_id=str_replace('$','\$',addslashes($_REQUEST["loc_id"]));
			$sb_view=str_replace('$','\$',addslashes($_REQUEST["view"]));
		}
		else
		{
			$sb_title=str_replace('$','\$',$_REQUEST["title"]);
			$sb_keyword=str_replace('$','\$',$_REQUEST["keyword"]);
			$sb_cid_list=str_replace('$','\$',$_REQUEST["cid_list"]);
			$sb_loc_id=str_replace('$','\$',$_REQUEST["loc_id"]);
			$sb_view=str_replace('$','\$',$_REQUEST["view"]);
		}
		
		$sb_search_method=(int)$_REQUEST["search_method"];
		$sb_work_exp=$_REQUEST["work_exp"];
		$sb_uid=$_SESSION["sbjbs_userid"];
		
		$sbq_sea="insert into sbjbs_search_results (sb_uid, sb_title, sb_keyword, sb_search_method, sb_cid_list, sb_loc_id, sb_work_exp, sb_view) values ($sb_uid, '$sb_title', '$sb_keyword', $sb_search_method, '$sb_cid_list', '$sb_loc_id', '$sb_work_exp', '$sb_view')";
	//	echo $sbq_sea;
		mysql_query($sbq_sea);
		if(mysql_affected_rows() == 1)
		{
			$msg1="Search result has been saved";
		}
		else
		{
			$msg1="Unable to save search result, please try again";
		}
		?><strong><font class="red"><?php echo $msg1; ?></font></strong><?php
		$showform=false;
	}	//end if errcnt ==0
}	//end if count(post)
else
{
	if (!isset($_SESSION["sbjbs_userid"]) )
	{	//unauthorised access	?>	
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="seperatorstyle">
  <tr> 
    <td height="25" class="titlestyle">&nbsp;Error</td>
  </tr>
  <tr> 
    <td> <table width="100%" border="0" cellspacing="0" cellpadding="5" class="innertablestyle">
        <tr> 
          <td><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td><font class="red" size="2"><strong>You must be logged to perform this operation</strong></font></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table><?php
		die();
	}	//end if session[userid]

		$sb_keyword=$_REQUEST["keyword"];
		$sb_search_method=$_REQUEST["search_method"];
		$sb_cid_list=$_REQUEST["cid_list"];
		$sb_loc_id=$_REQUEST["loc_id"];
		$sb_work_exp=$_REQUEST["work_exp"];
		$sb_view=$_REQUEST["view"];

	$sb_title='';
}

function main()
{
	global $errs, $errcnt, $sb_title, $showform, $sb_keyword, $sb_search_method, $sb_cid_list, $sb_loc_id, $sb_work_exp, $sb_view;
	$title_str="Save Search Results";
	?>
<html>
<head>
<title><? echo $title_str;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body leftmargin="1" topmargin="1" rightmargin="1"><br>
<script language="JavaScript">
window.focus();
</script>
<?php
	if  (count($_POST)>0)
	{
		if ( $errcnt <> 0 )
		{ 	?>
<table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
  <tr> 
    <td colspan="2"><strong>&nbsp;Your request cannot be processed due to following 
      reasons</strong></td>
  </tr>
  <tr height="10"> 
    <td colspan="2"></td>
  </tr>
  <?		for ($i=0;$i<$errcnt;$i++)
			{ 	?>
  <tr valign="top"> 
    <td width="6%">&nbsp;<?php echo $i+1;?></td>
    <td width="94%"><?php echo  $errs[$i]; ?></td>
  </tr>
  <?		}	//end for	?>
</table>
<?		}	//end if errcnt <> 0
	}		//end if count (POST)
	if($showform)
	{
?>
<script language="JavaScript">
function validate(frm)
{
	if(frm.title.value=="")
	{
		alert("Please specify search title");
		frm.title.focus();
		return false;
	}
	if(frm.title.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from search title (e.g. &  < >)");
			frm.title.focus();
			return(false);
		}
	return true;
}
</script>
<form name="form1" onSubmit="return validate(this);" action="save_search_popup.php" method="post">
<table width="95%" border="0" cellspacing="0" cellpadding="0" class="onepxtable">
    <tr> 
      <td  align="left" class="titlestyle">&nbsp;Save Search 
        Result </td>
    </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="5" cellpadding="2">
    <tr> 
      <td width="40%" height="25" align="right" valign="top" class='innertablestyle'><font class='normal'>&nbsp;<b>Search 
        Title</b></font></td>
      <TD align=right valign="top"><font class='red'>*&nbsp;</FONT></TD>
      <td width="60%"> <font class='normal'> 
        <input name="title" type="text" id="title" value="<?php echo $sb_title; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="40%" height="25" class='innertablestyle'><font class='normal'>&nbsp;</font></td>
      <td><font class='normal'>&nbsp;</font></td>
      <td width="60%"><font class='normal'> 
        <input name="work_exp" type="hidden" id="work_exp" value="<?php echo $sb_work_exp; ?>">
        <input name="view" type="hidden" id="view" value="<?php echo $sb_view; ?>">
        <input name="cid_list" type="hidden" id="cid_list" value="<?php echo $sb_cid_list; ?>">
        <input name="loc_id" type="hidden" id="loc_id" value="<?php echo $sb_loc_id; ?>">
        <input name="search_method" type="hidden" id="search_method" value="<? echo $sb_search_method; ?>">
        <input name="keyword" type="hidden" id="keyword" value="<?php echo $sb_keyword; ?>">
        <input name="insert_confirm" type="hidden" id="insert_confirm" value="yes">
        <input name="insert" type="submit" id="insert" value="Save Now">
        </font></td>
    </tr>
  </table></td>
  </tr>
</table>

  
</form>
<?php
	}	//end if showform
	?>
				  </body>
			  </html>
	<?php
}	//end main
main();
?>