<?php
include_once "myconnect.php";
include_once "session.php";

///////////////////////////////////////////////////////////////////////////////
///      THE CODE OF THIS SCRIPT HAS BEEN DEVELOPED BY SOFTBIZ SOLUTIONS  /////
///      AND IS MEANT TO BE USED ON THIS SITE ONLY AND IS NOT FOR REUSE,  /////
///      RESALE OR REDISTRIBUTION.                                        ///// 
///      IF YOU NOTICE ANY VIOLATION OF ABOVE PLEASE REPORT AT:           /////
///      admin@softbizscripts.com                                         /////
///      http://www.softbizscripts.com                                    /////
///      http://www.softbizsolutions.com                                  /////  
///////////////////////////////////////////////////////////////////////////////


// LOAD style number from the config file
$config=mysql_fetch_array(mysql_query("select * from sbjbs_config "));

if (!isset($_SESSION["softbiz_jbs_provided"]) && !isset($_REQUEST["provided"]) )
{
$provided=$config["sb_style_list"];
}

// RELOAD if its in the SESSION 
if (  (isset($_SESSION["softbiz_jbs_provided"])) )
{
$provided=$_SESSION["softbiz_jbs_provided"];
//echo "Loaded from Session " . $_SESSION["softbiz_jbs_provided"];
}

// RELOAD if its in the REQUEST AND SET SESSION
if (  (isset($_REQUEST["provided"])) )
{
$provided=$_REQUEST["provided"];
$_SESSION["softbiz_jbs_provided"]=$_REQUEST["provided"];
//echo "Loaded from Request " .$_REQUEST["provided"];

}
//$style=mysql_fetch_array(mysql_query("select * from sbjbs_styles where id=$style_num "));
/// is style provided //////

/////////

$style=mysql_fetch_array(mysql_query("select * from sbjbs_styles where sb_id=$provided "));

////////////  SET ALL DEFAULT COLORS////////////
//Default

$softbiz_page_bg="#ffffff";    		//Overal page background
$softbiz_section_bg="#f5f5f5"; 		//section background colot
$softbiz_seperator_color="#665577";	 	//Seperator color

//////////////////////////////////
// THE SITE FONTS////////////////
/////////////////////////////////

//Used as major site font, main table style , title bar style 
$softbiz_normal_fontstyle="Arial, Helvetica, sans-serif";
$softbiz_normal_fontcolor="#003399";
$softbiz_normal_fontsize="12px";

$softbiz_maintable_bg="#ffffff"; //Main table bg
$softbiz_inner_table_bg="#f5f5f5"; //Inner table bg
$softbiz_title_fontcolor="#ffffff";//Used as Title Bar Color
$softbiz_title_bg="#C33100"; //Title bar bg
$softbiz_sidetitle_fontcolor="#ffffff";//Used as Title Bar Color
$softbiz_sidetitle_bg="#C33100"; //Title bar bg
$softbiz_subtitle_bg="#C33100"; //Title bar bg
$softbiz_highlighted="#FFFFCC";
$softbiz_highlighted1="#FFFFDD";
// Link style#C33100
$softbizlinkstyle="Arial, Helvetica, sans-serif";
$softbizlinkcolor="#990000";
$softbizlinksize="12px";

/////////// DEFAULT COLORS HAVE BEEN SET ////////////

if ($style)
{

//Load values
$softbiz_page_bg="#" . $style["sb_page_bg"];    		//Overal page background
$softbiz_section_bg="#" . $style["sb_table_bg"]; 		//section background colot
$softbiz_seperator_color="#" . $style["sb_seperator"];	 	//Seperator color

//////////////////////////////////
// THE SITE FONTS////////////////
/////////////////////////////////

//Used as major site font, main table style , title bar style 
$softbiz_normal_fontstyle=$style["sb_normal_font"];
$softbiz_normal_fontcolor="#" . $style["sb_normal_font_color"];
$softbiz_normal_fontsize=$style["sb_normal_font_size"] . "px";

$softbiz_maintable_bg="#" . $style["sb_normal_table_bg"]; //Main table bg
$softbiz_inner_table_bg="#" . $style["sb_inner_table_bg"]; //Inner table bg
$softbiz_title_fontcolor="#" . $style["sb_title_font_color"];//Used as Title Bar Color
$softbiz_title_bg="#" . $style["sb_title_bg"]; //Title bar bg
$softbiz_sidetitle_fontcolor="#" . $style["sb_side_title_font_color"];//Used as Title Bar Color
$softbiz_sidetitle_bg="#" . $style["sb_side_title_bg"];//Used as Title Bar Color
$softbiz_subtitle_bg="#" . $style["sb_sub_title_bg"];//Used as Title Bar Color
$softbiz_highlighted="#".$style["sb_highlighted"];
$softbiz_highlighted1="#".$style["sb_highlighted1"];

// Link style#C33100
$softbizlinkstyle=$style["sb_link_font"];
$softbizlinkcolor="#" . $style["sb_link_font_color"];
$softbizlinksize=$style["sb_link_font_size"] . "px";
}
$strpass="";
foreach($_REQUEST as $key=>$value)
{
if(($key<>"provided"))
{
$strpass.="&".$key."=$value";
}
}
?> 
<style type="text/css">
<!--

.mainbgcolor {
	background-color: <?php echo $softbiz_section_bg; ?>;
}

.seperatorstyle 
{
	background-color: <?php echo $softbiz_seperator_color; ?>;
}

.onepxtable
{
border: 1px solid <?php echo $softbiz_seperator_color; ?>;
}
.sidepanelstyle
{
	border-top: thin none;
	border-right: 1px solid <?php echo $softbiz_seperator_color; ?>;
	border-bottom: thin none;
	border-left: thin none;

}

a {
	font-family: <?php echo $softbizlinkstyle; ?>;
	font-size: <?php echo $softbizlinksize; ?>;
	font-weight: normal;
	color: <?php echo $softbizlinkcolor; ?>;
	text-decoration: none;/*underline;*/
}
a.help_style {
	font-family: <?php echo $softbizlinkstyle; ?>;
	font-size: 11px;
	font-weight: normal;
	color: <?php echo $softbizlinkcolor; ?>;
	text-decoration: none;/*underline;*/
}
a.small_link{
	font-family: <?php echo $softbizlinkstyle; ?>;
	font-size: 11px;
	font-weight: normal;
	color: <?php echo $softbizlinkcolor; ?>;
	text-decoration: none;/*underline;*/
}

a.titlelink {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_sidetitle_fontcolor; ?>;
/*	background-color: <?php echo $softbiz_title_bg; ?>;*/
	text-decoration: Underline;
}
a.bottomlink {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: bold;
	font-size: 11px<?php // echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_sidetitle_fontcolor; ?>;
/*	background-color: <?php echo $softbiz_title_bg; ?>;
	text-decoration: Underline;*/
}

.titlestyle {
	font-family: <?php echo $softbiz_normal_fontstyle;?>;
	line-height: 25px;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_title_fontcolor; ?>;
	background-color: <?php echo $softbiz_title_bg; ?>;
}
.activetab {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	line-height: 25px;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_sidetitle_fontcolor; ?>;
	background-color: <?php echo $softbiz_sidetitle_bg; ?>;
	border-top: 1px solid <?php echo $softbiz_sidetitle_bg; ?>;
	border-right: 1px solid <?php echo $softbiz_sidetitle_bg; ?>;
	border-bottom: thin none;
	border-left: 1px solid <?php echo $softbiz_sidetitle_bg; ?>;
}
.inactivetab
{
	font-family: <?php echo $softbiz_normal_fontstyle;?>;
	line-height: 20px;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_subtitle_bg; ?>;
	border-top: 1px solid <?php echo $softbiz_seperator_color; ?>;
	border-right: 1px solid <?php echo $softbiz_seperator_color; ?>;
	border-bottom: thin none;
	border-left: 1px solid <?php echo $softbiz_seperator_color; ?>;
}

.sidetitle {
	/*background-image: url(images/menu_bg.gif);
	background-repeat: repeat-x;*/
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	line-height: 25px;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_sidetitle_fontcolor;//"#FFFFFF"; ?>;
	background-color: <?php echo $softbiz_sidetitle_bg; ?>;
}
.bottomstyle {
	/*background-image: url(images/menu_bg.gif);
	background-repeat: repeat-x;*/
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_sidetitle_fontcolor;//"#FFFFFF"; ?>;
	background-color: <?php echo $softbiz_sidetitle_bg; ?>;
}

.subtitle {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	line-height: 20px;
	font-weight: bold;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_subtitle_bg; ?>;
}
.alternatecolor {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	/*line-height: 20px;*/
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_subtitle_bg; ?>;
}
.maintablestyle {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: normal;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_section_bg; ?>;
}
.innertablestyle {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: normal;
	/*font-size: <?php echo $softbiz_normal_fontsize; ?>;*/
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_inner_table_bg; ?>;
}
.highlighted1 {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	/*line-height: 20px;*/
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_highlighted1; ?>;
}
.highlighted {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: normal;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_highlighted; ?>;
}
.errorstyle {
	font-family: <?php echo $softbiz_normal_fontstyle; ?>;
	font-weight: normal;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color: #990000 <?php // echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_inner_table_bg; ?>;
	border: 1px solid #ff0000 <?php // echo $softbiz_seperator_color; ?>;
}
.msgstyle {
	font-family: <?php echo $softbiz_normal_fontstyle;?>;
	/*list-style-image: url(images/main_bullet.gif);*/
	font-weight: normal;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color:  <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_inner_table_bg; ?>;
	border: 1px solid  <?php  echo $softbiz_seperator_color; ?>;
	/*border: 1px double #ff0000;*/
}
.helpstyle {
	font-family: <?php echo $softbiz_normal_fontstyle;?>;
	list-style-type: square;
	font-weight: normal;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
	color:  <?php echo $softbiz_normal_fontcolor; ?>;
	background-color: <?php echo $softbiz_inner_table_bg; ?>;
	border: 1px solid  <?php  echo $softbiz_seperator_color; ?>;
	/*border: 1px double #ff0000;*/
}

font.normal {
	font-family:  <?php echo $softbiz_normal_fontstyle; ?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
}

font.red{
	font-family:  <?php echo $softbiz_normal_fontstyle; ?>;
	color: #990000;
	/*font-size: <?php echo $softbiz_normal_fontsize; ?>;*/
}

font.smalltext {
	font-family:  <?php echo $softbiz_normal_fontstyle; ?>;
	/*color: <?php echo $softbiz_normal_fontcolor; ?>;*/
	font-size: 11px;
}
li {
	list-style-type: square;
}
textarea.letterstyle {
	height: 350px;
	width: 500px;	
	font-family:  <?php echo $softbiz_normal_fontstyle;?>;
	color: <?php echo $softbiz_normal_fontcolor; ?>;
	font-size: <?php echo $softbiz_normal_fontsize; ?>;
}

-->
</style>
