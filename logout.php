<?php
// if session exists, unregister all variables that exist and destroy session
include_once("myconnect.php");
session_start();

mysql_query( "DELETE FROM sbjbs_online WHERE sb_uid=".$_SESSION["sbjbs_userid"]) ;

session_unregister("sbjbs_username");
session_unregister("sbjbs_userid");

header("Location: ". "gen_confirm.php?errmsg=" . urlencode("You have been successfully logged out.") );
die();
?>