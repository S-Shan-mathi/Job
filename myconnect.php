<?php
//include_once "session.php";
define("SBCURRENCY","$");
// YOU NEED TO CHANGE THE CONTENTS OF THE values of variables given in single quotes

//CONFIGURATION SECTION STARTS ///

$servername='localhost' ;  // Replace this 'localhost' with your server name 
$database_username='root'; // Replace this  with your username 
$database_password='vertrigo';  // Replace this  with your password
$database_name='sbjobseekers';  // Replace this 'db' with your database name

// CONFIGURATION SECTION ENDS ////

mysql_connect($servername,$database_username,$database_password);
mysql_select_db($database_name);

$to_secs=200;
$t_stamp = date("YmdHis",time()); 
$timeout = $t_stamp - $to_secs; 
$ip=$_SERVER['REMOTE_ADDR'];
$uid=-1;
if(isset($_SESSION["sbjbs_userid"]))
{
$uid=$_SESSION["sbjbs_userid"];
$to_secs=500;
$timeout = $t_stamp - $to_secs; 
}
//==========================online visitors
mysql_query( "DELETE FROM sbjbs_online WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(sb_ontime) >$to_secs") ;
$online=mysql_fetch_array(mysql_query("select * from sbjbs_online where sb_ip='$ip'"));
 if($online)
 {
 mysql_query("update sbjbs_online set sb_ontime=$t_stamp,sb_uid=$uid where sb_ip='$ip'");
 }
 else
 {
 mysql_query("insert into sbjbs_online (sb_ontime,sb_ip,sb_uid) values($t_stamp,'$ip',$uid)"); 
 }
/* $num=mysql_num_rows(mysql_query("select * from sbjbs_online"));
 echo $num;*/

?>