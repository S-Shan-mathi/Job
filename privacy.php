<?php 
include_once'myconnect.php';
include_once'styles.php'; 

	$sbq_con="select * from sbjbs_policies where sb_id=1";
	$sbrow_con=mysql_fetch_array(mysql_query($sbq_con));
	$sb_privacy=$sbrow_con["sb_privacy"];
?>
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="seperatorstyle">
  <tr>
    <td height="25" class="titlestyle">&nbsp;Privacy Policy</td>
  </tr>
  <tr>
    <td>
	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="innertablestyle">
        <tr>
          <td><table width="90%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td><font class="normal"><?php echo $sb_privacy; ?></font></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
