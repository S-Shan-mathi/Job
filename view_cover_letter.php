<?php
//include "logincheck.php";
include_once "myconnect.php";
//include_once "date_time_format.php";

$sbq_resume='select * from sbjbs_cover_letters where sb_approved="yes" and sb_id='.$_REQUEST["sb_id"];//.' and sb_seeker_id='.$_SESSION["sbjbs_userid"];

	$resume=mysql_fetch_array(mysql_query($sbq_resume));

if(!$resume)
{
	header("Location: gen_confirm_mem.php?errmsg=".urlencode("Unauthorised access or admin disabled"));
	die();
}

function main()
{
	global $resume;
	//	$imgpmt=" width=80 height=100 ";
?><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td valign="top" > <div align="center"><font class='red'> </font> </div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;View Cover Letter</td>
                    </tr>
                    <tr> 
                      <td align="left" valign="top"> <table width="100%" border="0" cellpadding="2" cellspacing="0">
                          <tr valign="top"> 
                            <td> 
                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                <tr> 
                                  <td><strong><font class="normal">&nbsp; 
                                    <?php 
								  echo $resume["sb_title"];
								  ?>
                                    </font></strong></td>
                                </tr>
                                <tr > 
                                  <td> 
                                    <form name="form1" method="post" action="">
                                      &nbsp; 
                                      <!--class="letterstyle"--><textarea name="textfield"  id="textfield" readonly class="letterstyle"><?php
                            echo $resume["sb_contents"];        
							//echo str_replace("\n","<br>",str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$resume["sb_contents"]));
								    ?></textarea>
                                    </form></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td align="center"></td>
        </tr>
        <tr> 
          <td valign="top">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
  <?
}// end main
include "template.php";
?>