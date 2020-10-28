<?php
include_once "myconnect.php";
	/* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /*
	/* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /*
	===									     ===
	===      THE CODE OF THIS SCRIPT HAS BEEN DEVELOPED BY SOFTBIZ SOLUTIONS     ===
	===      AND IS MEANT TO BE USED ON THIS SITE ONLY AND IS NOT FOR REUSE,     ===
	===      RESALE OR REDISTRIBUTION.                                           ===
	===      IF YOU NOTICE ANY VIOLATION OF ABOVE PLEASE REPORT AT:              ===
	===      admin@sof tbizscripts.com                                           ===
	===      http://www.softbizscri pts.com                                      ===
	===      http://www.softbizsolution s.com                                    ===
	===									     ===
	/* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /*
	/* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* /* */


$pwd="admin";
$sql = "SELECT * FROM sblnk_admin" ;
$rs_query=mysql_query($sql);
if ( $rs=mysql_fetch_array($rs_query)  )
{
$pwd=$rs["password"];
}
?>
<style type="text/css">
<!--
.maintablestyle {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: normal;
	text-transform: none;
	color: #990033;
	text-decoration: none;
	font-size: 12px;
	background-color: #f3faff;
}

.maintablestyle1 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: normal;
	text-transform: none;
	color: #990033;
	text-decoration: none;
	font-size: 12px;
	background-color: #f3faff;
	border: 1px solid #aee2ff;

}


a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color: #CC6600;
	text-decoration: none;
}

a:Hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color: #CC6600;
	text-decoration: underline;
}
a.softbiz {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color: #0000CC;
	text-decoration: none;
}

a.softbiz:Hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: normal;
	color: #0000FF;
	text-decoration: underline;
}

a.sbbold {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #000000;
	text-decoration: none;
}

a.sbbold:Hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #000000;
	text-decoration: underline;
}

a.side {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #FF00CC;
	text-decoration: none;
}

a.side:Hover {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: normal;
	color: #FF00FF;
	text-decoration: underline;
}

-->
</style>


<link href="sb_resource_styles.css" rel="stylesheet" type="text/css" >
<table width="100%" border="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="4" cellspacing="4" bgcolor="#F3FAFF" class="maintablestyle1">
        <tr> 
          <td width="159" valign="bottom"><img src="images/link_directory_logo.gif" width="175" height="48"></td>
        </tr>
      </table></td>
  </tr>

<?
if ( isset($_REQUEST["msg"])&&$_REQUEST['msg']<>"")
{
?>
  <tr> 
    <td><center>
        <font face="verdana, arial" size="1" color="#666666"> <?php echo $_REQUEST["msg"];
					?></font></center></td>
  </tr>
<?
}//end if
?>

</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0" dwcopytype="CopyTableCell">
  <tr>
    <td valign="top">&nbsp; </td>
    <td valign="top"><FORM name=loginForm  action="login.php"
      method=post>
        <div align="center"></div>
        <table border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td> 
              <table border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#0080C0">
                <tr> 
                  <td><table width="214" border=0 align=center cellpadding=0 cellspacing=0 bgcolor="#D2F0FF">
                      <tbody>
                        <tr valign=center> 
                          <td width="153" align="center">&nbsp; </td>
                        </tr>
                        <tr valign=center> 
                          <td><div align="center"><strong><font color="#004080" 
            size=1 face="Verdana, Arial, Helvetica, sans-serif">Username</font></strong><br>
                              <input  class="box1"  name=username value="admin">
                            </div></td>
                        </tr>
                        <tr valign=center> 
                          <td> <div align="center"><strong><font color="#004080" 
            size=1 face="Verdana, Arial, Helvetica, sans-serif">Password</font><font color="#004080" 
            size=2 face="Verdana, Arial, Helvetica, sans-serif"><br>
                              </font></strong> 
                              <input  class="box1"  type=password name=password value="<?php echo $pwd; ?>">
                            </div></td>
                        </tr>
                        <tr align=right> 
                          <td><div align="center"> 
                              <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                <tr> 
                                  <td width="33%" align="right"> <div align="center"> 
                                      <input type="submit" name="Submit" value="Goto Admin Area" class="btn">
                                      <br>
                                      <br>
                                    </div></td>
                                </tr>
                              </table>
                            </div></td>
                        </tr>
                        <tr align=right> 
                          <td><div align="center"> </div></td>
                        </tr>
                      </tbody>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td><div align="right">&nbsp;<font size="2" face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Powered 
                by SoftbizScripts</font></font> 
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
      </FORM></td>
    <td valign="top">&nbsp; </td>
  </tr>
</table><?php 
	////////// //////////////////////////////////// //////////////////////////////
	//      THE CODE OF THIS SCRIPT HAS BEEN DEVELOPED BY SOFTBIZ SOLUTIONS  /////
	//      AND IS MEANT TO BE USED ON THIS SITE ONLY AND IS NOT FOR REUSE,  /////
	//      RESALE OR REDISTRIBUTION.                                        ///// 
#	$$      IF YOU NOTICE ANY VIOLATION OF ABOVE PLEASE REPORT AT:           )))))
#	&&      admin@softbiz scripts.com                                        (((((
	//      http://www.so ftbizscripts.com                                   /////
	//      http://www.softbizsolu tions.com                                 /////  
	//// ////////////////////////// //////////////////////////////////////////////

?>