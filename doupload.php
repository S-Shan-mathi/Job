<?php 
include_once "myconnect.php";
include_once "styles.php";

if(!isset($_SESSION["sbjbs_userid"]) )	//session FROM styles.php
	die();

$sbdontshowfinish=0;
function handleupload() 
{
	global $sbdontshowfinish;
	$config=mysql_fetch_array(mysql_query("select sb_image_size from sbjbs_config"));
	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) 
	{
		$realname = $_FILES['userfile']['name'];
	///////--------chking extension	
		if(!preg_match("/(\.jpg|\.png|\.gif|\.bmp|\.jpeg)$/i",$realname))
			die();
	///////--------end chking extension	
		if ($_FILES['userfile']['size']>($config["sb_image_size"]))
		{
			echo "Uploaded files must be less than ".($config["sb_image_size"]/1024)."k. Please close this window and try again";
			echo "<script language=\"JavaScript\">";
			echo "fname = ''";
			echo "</script>";
		}
		else
		{
			echo $realname . ", size: ". $_FILES['userfile']['size'] . " [ ";
			
			switch($_FILES['userfile']['error'])
			{ case 0: $mess = "Ok";
			  break;
			  case 1:
			  case 2: $mess = "Error : File size more than 512000 bytes";
			  break;
			  case 3: $mess = "Error : File partially uploaded";
			  break;
			  case 4: $mess = "Error : No File Uploaded";
			  break;
			}
			echo $mess . " ]  ";

			mt_srand((double)microtime()*1000000);
			$randvar =  mt_rand(1,10000000);
			settype($randvar,"string");
			$extension=explode(".",$realname);
			$newfilename = "uploadedimages/" . $randvar.".".$extension[count($extension)-1];
			$shortfname = $randvar.".".$extension[count($extension)-1];
			while ( file_exists($newfilename) != FALSE )
			{
				$randvar =  mt_rand(1,10000000);
				settype($randvar,"string");
				$newfilename = "uploadedimages/" . $randvar.".".$extension[count($extension)-1];
				$shortfname =  $randvar.".".$extension[count($extension)-1];
			}
			copy($_FILES['userfile']['tmp_name'], $newfilename);
			
			echo "<script language=\"JavaScript\">";
			echo "fname = '" . $shortfname . "'";
			echo "</script>";
		}// Else fr more than 60k
	} 
	else 
	{
		echo "<font class='normal'><div align=center><font class='red'>Error : File Not Uploaded. Check Size & Try Again.</font><br><a href=\" javascript: onclick=history.go(-1);\">Go Back</a></div></font>";
		$sbdontshowfinish = 1;
	}
}
?>

<html>
<head><title>Uploaded File Status</title>
<Script Language="JavaScript">
function closewin(fname)
{
window.opener.document.form123.<?php echo $_REQUEST["box"];?>.value=fname
window.close()
}
</script>
</head>

<body  bgcolor="<?php echo $softbiz_page_bg; ?>">

<table width="100%" cellpadding="0" cellspacing="0" class="innertablestyle">
  <tr>
    <td class="titlestyle"><strong>&nbsp;Image Uploader </strong> </td>
  </tr>
<tr>
    <td>
<hr>
</td></tr>
<tr>
    <td>&nbsp;<strong><font class="normal"><font class="red"> 
      <?php handleupload(); ?></font>
      </font></strong> </td>
  </tr>
<tr>
    <td>&nbsp; </td>
  </tr>
<tr>
    <td>
<hr>
</td></tr>
<tr>
    <td align=center> <strong> 
      <?php if($sbdontshowfinish!=1)	{?>
      <a href="javascript:onclick=closewin(fname)" class="insidelink">FINISH</a> 
      <?php }	//end if?>
      </strong> </td>
  </tr>
</table>
</body>
</html>