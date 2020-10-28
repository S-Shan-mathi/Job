<?php
include_once "styles.php";

if(!isset($_SESSION["sbjbs_userid"]) )
	die();
?><html>
<head>
<Title>Image Uploader</Title>
<script language="JavaScript">
function checkFile(form1)
{
	if (form1.userfile.value == "")
	{
		alert("Please choose a file to upload");
		form1.userfile.focus();
		return (false);
	}
	if ( !form1.userfile.value.match(/(\.jpg|\.png|\.gif|\.bmp|\.jpeg)$/i) )
	{
		alert("Please upload .gif/.jpg/.jpeg/.bmp/.png files only");
		form1.userfile.focus();
		return (false);
	}
	return(true);
}
function submit_frm(frm)
{
	if(checkFile(frm))
	{
		frm.action="doupload.php?box=<?php echo $_REQUEST["box"]; ?>";
		frm.submit();
	}
}

</script>


</head>
<body bgcolor="<?php echo $softbiz_page_bg; ?>" >
<FORM ENCTYPE="multipart/form-data" METHOD=post ID=form1 NAME=form1 onSubmit="javscript:return checkFile(form1);">
  <table cellpadding="0" cellspacing="0" class="onepxtable">
    <tr>
      <td class="titlestyle"><b>&nbsp;Upload Image</b></TD>
    </TR>
    <tr class="innertablestyle"> 
      <td> <b><font size="3" color="#FFFFFF"><u></u></font></b> <table>
          <tr> 
            <td valign="top"><font class="normal">1.</font></td>
            <td><font class="normal">To add an image, click the 'Browse' button 
              &amp; select the file, or type the path to the file in the 'Text-box' 
              below.</font></td>
          </tr>
          <tr> 
            <td valign="top"><font class="normal">2.</font></td>
            <td><font class="normal">Then click 'Upload' button to complete the 
              process.</font></td>
          </tr>
          <tr> 
            <td valign="top"><font class="normal">3.</font></td>
            <td valign="top"><font class="normal"><font class="red">NOTE:</font> 
              The file transfer can take from a few seconds to a few minutes depending 
              on the size of the file. Please have patience while the file is 
              being uploaded.</font></td>
          </tr>
          <tr> 
            <td valign="top"><font class="normal">4.</font></td>
            <td><font class="normal"><font class="red">NOTE:</font> The file will 
              be renamed if the file with the same name is already present.</font></td>
          </tr>
        </table></TD>
    </TR>
    <TR class="innertablestyle">
      <TD><STRONG><font class="normal">&nbsp;&nbsp;Hit the [Browse] button to 
        find the file on your computer.</font></STRONG><BR></TD>
    </TR>
    <TR class="innertablestyle">
      <TD><font class="normal"><strong>&nbsp;&nbsp;Image</strong></font> 
        <INPUT NAME=userfile SIZE=30 TYPE=file   MaxFileSize="1000000"> 
        <input type="hidden" name="MAX_FILE_SIZE" value="1000000"> </TD>
    </TR>
    <TR class="innertablestyle">
      <TD>&nbsp;</TD>
    </TR>
    <TR class="innertablestyle">
      <TD>&nbsp; 
        <input type="button" value="Upload" name="uploadfile" onClick="javascript:submit_frm(this.form);"></TD>
    </TR>
    <TR class="innertablestyle"> 
      <TD><table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr> 
            <td><font  class='normal'><font  class='red'>NOTE</font>: Please have 
              patience, you will not receive any notification until the file is 
              completely transferred.</font></td>
          </tr>
        </table>
        </TD>
    </TR>
  </table>

</FORM>

   
</body>

</html>