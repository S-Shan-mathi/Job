<?
include "logincheck.php";
include_once "myconnect.php";
include_once "date_time_format.php";

function main()
{

	$sbq_off='select * from sbjbs_search_results where sb_uid='.$_SESSION["sbjbs_userid"];
//	echo $sbq_off;
	$sbrs_off=mysql_query($sbq_off);

	?>
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
	
<table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr> 
    <td valign="top" > <div align="center"></div>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"> <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
              <tr > 
                <td valign="top"> <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0" class="innertablestyle">
                    <tr> 
                      <td height="25" class="titlestyle">&nbsp;Saved Search Results</td>
                    </tr>
                    <tr> 
                      <td valign="top"> <p><font class='normal'>To remove a result 
                          just click the check box and click the remove button 
                          below.</font></p></td>
                    </tr>
                    <tr> 
                      <td valign="top"><form name="form2" method="post" action="delete_search.php">
                          <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1">
                            <tr class="subtitle"> 
                              <td width="20"> <input type="checkbox" name="check_all" value="yes" onClick="select_all();"> 
                              </td>
                              <td align="left"><b><font class='normal'>Title</font></b></td>
                            </tr>
                            <?php 	
						while( $sbrow_off=mysql_fetch_array($sbrs_off) )
		  				{
						?>
                            <tr> 
                              <td ><input name="checkbox<?php echo $sbrow_off["sb_id"];?>" type="checkbox" id="checkbox<?php echo $sbrow_off["sb_id"];?>" value="<?php echo $sbrow_off["sb_id"];?>"></td>
                              <td width="100%" valign="top" >
							  <a href="show_search.php?sb_id=<?php echo $sbrow_off["sb_id"]; ?>"><?php 
							  echo $sbrow_off["sb_title"]; ?></a>&nbsp;</td>
                            </tr>
                            <?
}
?>
                            <tr> 
                              <td colspan="3"> <input type="hidden" name="cnt" value="<?php echo $cnt; ?>"> 
                                <input type="submit" name="Submit" value="Remove"> 
                              </td>
                            </tr>
                          </table>
                        </form></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td align="center" height="25"><font class="red">&nbsp; 
            <? //if($msg1<>"") echo $msg1;?>
            </font></td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
  <?
}// end main
include "template.php";
?>