<?php
//include_once "myconnect.php";
function sb_date($unx_stamp)
{
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
$date_str="";
$time_str="";
switch($config["sb_date_format"])
{
	case 1: $date_str=(date("Y-m-d",$unx_stamp)); break;
	case 2: $date_str=(date("m-d-Y",$unx_stamp)); break;
	case 3: $date_str=(date("d-m-Y",$unx_stamp)); break;
	case 4: $date_str=(date("d M Y",$unx_stamp)); break;
	case 5: $date_str=(date("d F Y",$unx_stamp)); break;
	case 6: $date_str=(date("M jS,Y",$unx_stamp)); break;
	//case 6: $date_str=date("M j",$unx_stamp)."<sup>".date("S",$unx_stamp)."</sup>".date(", Y",$unx_stamp); break;
	case 7: $date_str=(date("D M dS,Y",$unx_stamp)); break;
	case 8: $date_str=(date("l M jS,Y",$unx_stamp)); break;
	case 9: $date_str=(date("l F jS,Y",$unx_stamp)); break;
	case 10: $date_str=(date("d F Y l",$unx_stamp)); break;
	
}//end switch date string
switch($config["sb_time_format"])
{
	case 1: $time_str=(date("h:i a",$unx_stamp)); break;
	case 2: $time_str=(date("h:i A",$unx_stamp)); break;
	case 3: $time_str=(date("H:i",$unx_stamp)); break;
	
}//end switch time string

return($date_str." ".$time_str);
}//end date function
?>