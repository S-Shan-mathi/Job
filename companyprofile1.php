<?
include_once("myconnect.php");
include_once("logincheck.php");
function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
}
$errcnt=0;
if(count($_POST)<>0)		//IF SOME FORM WAS POSTED DO VALIDATION
{
	$sql="Select * from sbjbs_groups where sb_memtype=".$_SESSION["sbjbs_memtype"];
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	
    $cats=$rs0["sb_profilecat_cnt"];
	$allowed= $rs0["sb_profile"];
	$posturl= $rs0["sb_posturl"];
	
		if(!get_magic_quotes_gpc())
		{
			$companyname=str_replace("$","\$",addslashes($_REQUEST["companyname"]));
			$logo=str_replace("$","\$",addslashes($_REQUEST["list1"]));
			$services=str_replace("$","\$",addslashes($_REQUEST["services"]));
			$yearestablished=str_replace("$","\$",addslashes($_REQUEST["yearestablished"]));
			$othermarkets=str_replace("$","\$",addslashes($_REQUEST["othermarkets"]));
			$companyprofile=str_replace("$","\$",addslashes($_REQUEST["companyprofile"]));
			$ceo=str_replace("$","\$",addslashes($_REQUEST["ceo"]));
			$phone=str_replace("$","\$",addslashes($_REQUEST["phone"]));
			$phone1=str_replace("$","\$",addslashes($_REQUEST["phone1"]));
			$phone2=str_replace("$","\$",addslashes($_REQUEST["phone2"]));
			$fax=str_replace("$","\$",addslashes($_REQUEST["fax"]));
			$fax1=str_replace("$","\$",addslashes($_REQUEST["fax1"]));
			$fax2=str_replace("$","\$",addslashes($_REQUEST["fax2"]));
			$website=str_replace("$","\$",addslashes($_REQUEST["website"]));
		}
		else
		{
			$companyname=str_replace("$","\$",$_REQUEST["companyname"]);
			$logo=str_replace("$","\$",$_REQUEST["list1"]);
			$services=str_replace("$","\$",$_REQUEST["services"]);
			$yearestablished=str_replace("$","\$",$_REQUEST["yearestablished"]);
			$othermarkets=str_replace("$","\$",$_REQUEST["othermarkets"]);
			$companyprofile=str_replace("$","\$",$_REQUEST["companyprofile"]);
			$ceo=str_replace("$","\$",$_REQUEST["ceo"]);
			$phone=str_replace("$","\$",$_REQUEST["phone"]);
			$phone1=str_replace("$","\$",$_REQUEST["phone1"]);
			$phone2=str_replace("$","\$",$_REQUEST["phone2"]);
			$fax=str_replace("$","\$",$_REQUEST["fax"]);
			$fax1=str_replace("$","\$",$_REQUEST["fax1"]);
			$fax2=str_replace("$","\$",$_REQUEST["fax2"]);
			$website=str_replace("$","\$",$_REQUEST["website"]);
	}

	$phone_no="";
	if(strlen(trim($phone))<>0)
	{$phone_no.=$phone;}
	$phone_no.="-";
	if(strlen(trim($phone1))<>0)
	{$phone_no.=$phone1;}
	$phone_no.="-";
	if(strlen(trim($phone2))<>0)
	{$phone_no.=$phone2;}

	$fax_no="";
	if(strlen(trim($fax))<>0)
	{$fax_no.=$fax;}
	$fax_no.="-";
	if(strlen(trim($fax1))<>0)
	{$fax_no.=$fax1;}
	$fax_no.="-";
	if(strlen(trim($fax2))<>0)
	{$fax_no.=$fax2;}

	$markets="0";
	$rs_query_t=mysql_query("select * from sbjbs_markets order by sb_id");
	
	$cnt=1;
	
	while( ( $rs_t=mysql_fetch_array($rs_query_t) ))
	{
	$indx="market".$cnt;
	$cnt++;
		if ( isset($_REQUEST[$indx]) )					   
		{
		$markets .=",".$rs_t["sb_id"];
		}
	}
	
	$cat_name=str_replace(";",",",$_REQUEST["category"]);
	$sbcid_list=str_replace(";",",",$_REQUEST["cid"]);
	$cat=explode(",",$sbcid_list);

	if ( strlen(trim($companyname)) == 0 )
	{
		$errs[$errcnt]="Company Name must be provided";
   		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["companyname"]))
	{
		$errs[$errcnt]="Company Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if ( $_REQUEST["businesstype"]=="" )
	{
		$errs[$errcnt]="Business Type must be choosen";
		$errcnt++;
	}
	
	if ( $_REQUEST["cid"]=="")
	{
		$errs[$errcnt]="Category(ies) must be provided";
		$errcnt++;
	}
	
	if ( count($cat)>$cats)
	{
		$errs[$errcnt]="You are not allowed to choose more than $cats category(ies).";
		$errcnt++;
	}

	if ( strlen(trim($services)) == 0 )
	{
		$errs[$errcnt]="Product/Services must be specified.";
		$errcnt++;
	}
	/*elseif(preg_match ("/[;<>&]/", $_REQUEST["services"]))
	{
		$errs[$errcnt]="Product/Services can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}*/

	if ( $markets=="-1" && (strlen(trim($othermarkets)) == 0))
	{
		$errs[$errcnt]="At least one market must be choosen";
		$errcnt++;
	}
	
	if ( $_REQUEST["productfocus"]=="" )
	{
		$errs[$errcnt]="Product Focus must be choosen";
		$errcnt++;
	}
	if ( $_REQUEST["employees"]=="" )
	{
		$errs[$errcnt]="Employees must be choosen";
		$errcnt++;
	}
	
	if ( strlen(trim($companyprofile)) == 0 )
	{
		$errs[$errcnt]="Company Profile must be given";
		$errcnt++;
	}
	/*elseif(preg_match ("/[;<>&]/", $_REQUEST["companyprofile"]))
	{
		$errs[$errcnt]="Company Profile can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}*/
	
	if ( strlen(trim($ceo)) == 0 )
	{
		$errs[$errcnt]="CEO/Owner's Name Must be provided";
		$errcnt++;
	}
	elseif(preg_match ("/[;<>&]/", $_REQUEST["ceo"]))
	{
		$errs[$errcnt]="CEO/Owner's Name can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}
	if(preg_match ("/[;<>&]/", $phone_no))
	{
		$errs[$errcnt]="Phone No. can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if(preg_match ("/[;<>&]/", $fax_no))
	{
		$errs[$errcnt]="Fax can not have any special character (e.g. & ; < >)";
   		$errcnt++;
	}

	if($errcnt==0)
	{
		$approved="yes";
		$sb_msg='Company Profile has been updated.';
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		if($config["sb_profile_approval"]=="admin")
		{
		$approved="no";
		$sb_msg='Company Profile has been sent for admin approval.';
		}
	
		if($_POST["profile_id"]==0)
		{
		if($approved=="no")
		{ $approved="new";}
		$insert_query="insert into sbjbs_companyprofiles (sb_companyname,sb_logo,sb_businesstype,sb_services,sb_yearestablished,sb_markets,sb_othermarkets,sb_productfocus,sb_companyprofile,sb_employees,sb_ceo,sb_website,sb_uid,sb_type,sb_phone,sb_fax,sb_approved,sb_viewed,sb_postedon) values ('$companyname','$logo',".$_REQUEST["businesstype"].",'$services',$yearestablished,'$markets','$othermarkets',".$_REQUEST["productfocus"].",'$companyprofile',".$_REQUEST["employees"].",'$ceo','$website',".$_SESSION["sbjbs_userid"].",0,'$phone_no','$fax_no','$approved',0,'".date("YmdHis",time())."')";
		
	mysql_query($insert_query);
	if(mysql_affected_rows()>0)
	{
		$profile=mysql_fetch_array(mysql_query("select max(sb_id) from sbjbs_companyprofiles"));
		foreach($cat as $cid)
		{
		 	$check_cat=mysql_fetch_array(mysql_query("select * from sbjbs_profile_cats where sb_cid=$cid and sb_profile_id=".$profile[0]));
		 	if(!$check_cat)
		 	{
			 	mysql_query("insert into sbjbs_profile_cats (sb_cid,sb_profile_id) values ($cid,".$profile[0].")");
		 	}
		}
////------------mails

if($config["sb_profile_approval"]=='auto')
{		//////-------mail to fav cats but if approval is auto 'coz otherwise it would be unapproved			
	$sbq_mail="SELECT * FROM sbjbs_mails where sb_id=24";
	$sbrs_mail=mysql_query($sbq_mail);
	if ( ($sbrow_mail=mysql_fetch_array($sbrs_mail))  && ($sbrow_mail['sb_status']=='yes'))
	{
////////----------getting full path ids 
  	$cat_query=mysql_query("Select * from sbjbs_categories where sb_id in ($sbcid_list)");
	$temp_cid_list=-1;
	while ($rs=mysql_fetch_array($cat_query))
    {
	
    $temp_cid_list .=",".$rs["sb_id"]; 
	$cid=$rs["sb_id"]; 
	
		$cat_query1=mysql_query("Select * from sbjbs_categories where sb_id=" . $cid );
		while ($rs1=mysql_fetch_array($cat_query1))
		{
		$temp_cid_list.="," .$rs1["sb_id"]; 
		$cat_query1=mysql_query("Select * from sbjbs_categories where sb_id=" . $rs1["sb_pid"] );
		}
	}
		
		$sbcid_list=$temp_cid_list;
//echo "<br>cats----".$temp_cid_list."----<br>";
//die();

//////-----------------------------------------
		$rs_con=mysql_fetch_array(mysql_query("select * from sbjbs_config where sb_id=1"));
		$sb_null_char=$rs_con["sb_null_char"];
		$login_url=$rs_con["sb_site_root"]."/signin.php";
		$sb_offer_url=$rs_con["sb_site_root"]."/view_profile?id=".$profile[0];
		
		$sbuser_id_list="-1";
		$sbq_fav_cat="select * from sbjbs_fav_cats where sb_type='profile' and cid in ($sbcid_list)";
			//echo $sbq_off_cat;
		$sbrs_fav_cat=mysql_query($sbq_fav_cat);
		while($sbrow_fav_cat=mysql_fetch_array($sbrs_fav_cat))
		{
			$sbuser_id_list.=",".$sbrow_fav_cat["mid"];
		}
			
		$sbq3_mem="select * from sbjbs_members where sb_id in ($sbuser_id_list)";
		$sbrs3_mem=mysql_query($sbq3_mem);
		while($sbrow3_mem=mysql_fetch_array($sbrs3_mem))
		{	//send mail 
//////---getting category name only first matching cat for a user
			$sbq1_fav_cat="select * from sbjbs_fav_cats where cid in ($sbcid_list) and mid=".$sbrow3_mem["sb_id"];
			//echo $sbq_off_cat;
			$sbrs1_fav_cat=mysql_query($sbq1_fav_cat);
			$sbrow_fav_cat=mysql_fetch_array($sbrs1_fav_cat);
			
			$sbq4_cat="select * from sbjbs_categories where sb_id=".$sbrow_fav_cat["cid"];
			//echo $sbq_off_cat;
			$sbrow4_cat=mysql_fetch_array(mysql_query($sbq4_cat));
			$sb_cat_name=$sbrow4_cat["sb_cat_name"];
///////----------------------			
			$from =$sbrow_mail["sb_fromid"];
			$to = $sbrow3_mem["sb_email"];
			$subject =$sbrow_mail["sb_subject"];
			$header="From:" . $from . "\r\n" ."Reply-To:". $from  ;
		
	$body=str_replace("<email>", $sb_null_char,str_replace("<password>",$sb_null_char,str_replace("<lname>", $sbrow3_mem["sb_lastname"],str_replace("<fname>",$sbrow3_mem["sb_firstname"],str_replace("<username>",$sbrow3_mem["sb_username"], $sbrow_mail["sb_mail"]) )))); 
						
	$body=str_replace("<signup_url>",$sb_null_char,str_replace("<login_url>",$login_url,$body));
		
	$body=str_replace("<message_text>",$sb_null_char,str_replace("<message_title>",$sb_null_char,str_replace("<sender_username>",$sb_null_char,str_replace("<message_date>",$sb_null_char,$body))));	
		 
	$body=str_replace("<visitor_name>",$sb_null_char,$body);
				
	$body=str_replace("<offer_title>",$companyname,str_replace("<offer_url>",$sb_offer_url,str_replace("<offer_id>",$profile[0],$body)));
	
	$body=str_replace("<category>",$sb_cat_name,$body);
	
			if(isset($sbrow_mail["sb_html_format"])&&($sbrow_mail["sb_html_format"]=="yes"))
			{
				$header .= "\r\nMIME-Version: 1.0";
				$header .= "\r\nContent-type: text/html; charset=iso-8859-1\r\n";
				$body=str_replace("\n","<br>",$body);
			}
		 
//		 	echo "--from:-$from----to:-$to---sub:-$subject----head:-$header----";
//			echo "<pre>$body</pre>";
//		 die();
					if( $sbrow_mail["sb_status"]=='yes')
						mail($to,$subject,$body,$header);
		}		//end while sbrow3_mem
//////////////////////////////////////////////////////////
	}	// end if 
//die();
}	//end if approval == auto
////////////////////////////////////////////////------------------------------------
	}
}
else
{
		$approved="yes";
		$sb_msg='Company Profile has been updated.';
		$config=mysql_fetch_array(mysql_query("select * from sbjbs_config"));
		if($config["sb_profile_approval"]=="admin")
		{
		$approved="no";
		$sb_msg='Company Profile has been sent for admin approval.';
		}
		

		$update_query="update sbjbs_companyprofiles set 
		sb_companyname='$companyname',
		sb_logo='$logo',
		sb_businesstype=".$_REQUEST["businesstype"].",
		sb_services='$services',
		sb_yearestablished=$yearestablished,
		sb_markets='$markets',
		sb_othermarkets='$othermarkets',
		sb_productfocus=".$_REQUEST["productfocus"].",
		sb_companyprofile='$companyprofile',
		sb_employees=".$_REQUEST["employees"].",
		sb_ceo='$ceo',
		sb_phone='$phone_no',
		sb_fax='$fax_no',
		sb_website='$website',
		sb_approved='$approved' 
		where sb_uid=".$_SESSION["sbjbs_userid"];
		mysql_query($update_query);

		mysql_query("delete from sbjbs_profile_cats where sb_profile_id=".$_POST["profile_id"]);
		foreach($cat as $cid)
		{
		 $check_cat=mysql_fetch_array(mysql_query("select * from sbjbs_profile_cats 
		 where sb_cid=$cid and sb_profile_id=".$_POST["profile_id"]));
		 if(!$check_cat)
		 {
		 mysql_query("insert into sbjbs_profile_cats (sb_cid,sb_profile_id) 
		 values ($cid,".$_POST["profile_id"].")");
		 }
	   }

		if(mysql_affected_rows()>0)
		{

	if($approved=="yes")
	{
	header ("Location: gen_confirm_mem.php?sb_type=3&id=$sb_offer_id&errmsg=".urlencode($sb_msg));
	}
	else
	{
	header ("Location: gen_confirm_mem.php?errmsg=".urlencode($sb_msg));
	}
	die();
}
	header("Location: gen_confirm_mem.php?err=companyprofile&errmsg=".urlencode("Sorry, no updations carried out."));
			die();

		}
	}// end if no errs
}//if form posted
function main()
{
	global $errs, $errcnt;
	$sql="Select * from sbjbs_groups where sb_memtype=".$_SESSION["sbjbs_memtype"];
	$rs0_query=mysql_query($sql);
	$rs0=mysql_fetch_array($rs0_query);
	
    $cats=$rs0["sb_profilecat_cnt"];
	$allowed= $rs0["sb_profile"];
	$posturl= $rs0["sb_posturl"];

if ($allowed!="yes")
{
?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="errorstyle">
  <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            
          
    <td colspan="2">You are not allowed to post a company profile.</td>
          </tr>
          <tr> 
            <td colspan="2"> You can consider upgrading your membership level if you are at bronze or silver level.</td>          
          </tr>
        </table>
<?php
}
else
{


?>
<script language="JavaScript" type="text/javascript" src="richtext.js"></script>
<script language="JavaScript">
<!--
					function add_category()
					{
					if(document.form123.cats.value!=0)
					{
					var id=document.form123.cats.selectedIndex;
					if(document.form123.category.value=="")
					{
					document.form123.cid.value=document.form123.cats.value;
					document.form123.category.value=document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					else
					{
					document.form123.cid.value=document.form123.cid.value+";"+document.form123.cats.value;
					document.form123.category.value=document.form123.category.value+";"+document.form123.cats.options[id].text;
					document.form123.category.focus();
					document.form123.cats.selectedIndex=0;
					}
					}
					else
					{
					alert('Choose a Category to add!');
					}
					}
					
					function remove_category()
					{
					var s1=window.document.form123.category.value;
					var s2=s1.split(";");
					var i=0;
					var id=document.form123.cats.selectedIndex;
					var s3=document.form123.cats.options[id].text;
					
					var id_list=document.form123.cid.value;
					var id_split=id_list.split(";");
					var rem_id=document.form123.cats.value;
										
					window.document.form123.category.value="";
					window.document.form123.cid.value="";
					
					while(i<s2.length)
					{
					//alert('Checking '+s2[i]+' nnn  with'+s3+' mm');
						if(s3==s2[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.category.value=="")
							{
							document.form123.category.value=s2[i];
							}
							else
							{
							document.form123.category.value=document.form123.category.value+";"+s2[i];
							}
						
						}
						if(rem_id==id_split[i])
						{
						//continue;
						//	alert('Removing'+s3);
						}
						else
						{
							if(document.form123.cid.value=="")
							{
							document.form123.cid.value=id_split[i];
							}
							else
							{
							document.form123.cid.value=document.form123.cid.value+";"+id_split[i];
							}
						
						}
					i++;
					}
					//window.document.form123.related.value="";
					//window.document.form123.rel_id.value="";
					}

function attachment(box)
{
str="fileupload.php?box="  + box;

window.open(str,"Attachment","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=350,height=450,location=no,directories=no,scrollbars=yes");
}

function category(box)
{
str="choosecategory.php?box="  + box;

window.open(str,"Category","top=5,left=30,toolbars=no,maximize=yes,resize=yes,width=550,height=450,location=no,directories=no,scrollbars=yes");
}



function removeattachment(box)
{
window.document.form123.list1.value=""
}

function validate(form)
{
	updateRTEs();
	if ( form.companyname.value == "" ) {
       	   alert('Please Specify Companyname!');
		   form.companyname.focus();
	   return false;
	   }
	if(form.companyname.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from Companyname (e.g. &  < >)");
			form.companyname.focus();
			return(false);
		}
	if ( form.businesstype.value == "" ) {
       	   alert('Please Choose a Business Type!');
		   form.businesstype.focus();
	   return false;
	   }

		var checked='no';
		var count=form.cnt.value;
		var i=1;
		
		for(i=1;i<form.elements.length;i++)
		{
		if(form.elements[i].checked==true)
		{
		checked='yes';
		}
		}
		
		if((checked=='no')&&(form.othermarkets.value==""))
		{
		alert(' Please choose atleast one market.')
		form.othermarkets.focus();
		return false;
		}

	if ( form.services.value == "" ) {
   	   alert('Please Specify Products/Services!');
	   return false;
	   }
	if ( form.productfocus.value == "" ) {
       	   alert('Please Choose Main Product Focus!');
		   form.productfocus.focus();
	   return false;
	   }
	if ( form.employees.value == "" ) {
       	   alert('Please Choose Number of Employees!');
		   form.employees.focus();
	   return false;
	   }
	if ( form.companyprofile.value == "" ) {
   	   alert('Please Specify Company Profile!');
	   return false;
	   }
	if ( form.ceo.value == "" ) {
       	   alert("Please Specify CEO/Owner's Name!");
		   form.ceo.focus();
	   return false;
	   }
	if(form.ceo.value.match(/[&<>]+/))
		{
			alert("Please remove Invalid characters from CEO/Owner's Name (e.g. &  < >)");
			form.ceo.focus();
			return(false);
		}
	return true;

}
//-->
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td> 
      <?

$pofile_id=0;

$rs=mysql_query("Select * from sbjbs_companyprofiles  where sb_uid =" . $_SESSION["sbjbs_userid"] );
$profile=mysql_fetch_array($rs);
if($profile)
{
$profile_id=$profile["sb_id"];
$companyname=$profile["sb_companyname"];
$logo=$profile["sb_logo"];
$businesstype=$profile["sb_businesstype"];
$services=$profile["sb_services"];
$yearestablished=$profile["sb_yearestablished"];
$markets=$profile["sb_markets"];
$othermarkets=$profile["sb_othermarkets"];
$productfocus=$profile["sb_productfocus"];
$companyprofile=$profile["sb_companyprofile"];
$employees=$profile["sb_employees"];
$ceo=$profile["sb_ceo"];
$website=$profile["sb_website"];
$cat_list="";
$cid_list="";
$profile_cat_query=mysql_query("select * from sbjbs_profile_cats where sb_profile_id=".$profile["sb_id"]);
while($profile_cat=mysql_fetch_array($profile_cat_query))
{
$cat=mysql_fetch_array(mysql_query("select * from sbjbs_categories where sb_id=".$profile_cat["sb_cid"]));
		  $cat_path=$cat["sb_cat_name"];
 		  $par=mysql_query("select * from sbjbs_categories where sb_id=".$cat["sb_pid"]);
		  while($parent=mysql_fetch_array($par))
		  {
			$cat_path=$parent["sb_cat_name"].">".$cat_path;
			$par=mysql_query("select * from sbjbs_categories where sb_id=".$parent["sb_pid"]);
			}

	if($cat_list=="")
	{
	$cat_list=$cat_path;
	$cid_list=$cat["sb_id"];
	}
	else
	{
	$cat_list.=";".$cat_path;
	$cid_list.=";".$cat["sb_id"];
	}


}
$phone_arr=explode("-",$profile["sb_phone"]);
$phone=$phone_arr[0];
$phone1=$phone_arr[1];
$phone2=$phone_arr[2];

$fax_arr=explode("-",$profile["sb_fax"]);
$fax=$fax_arr[0];
$fax1=$fax_arr[1];
$fax2=$fax_arr[2];

}
else
{
$cid_list="";
$companyname="";
$logo="";
$businesstype="";
$services="";
$yearestablished="";
$markets="";
$othermarkets="";
$productfocus="";
$companyprofile="";
$employees="";
$ceo="";
$website="";
$cat_list="";

$phone="";
$phone1="";
$phone2="";

$fax="";
$fax1="";
$fax2="";

}
//IF SOME FORM WAS POSTED DO VALIDATION
if ( count($_POST)<>0 )
{
$companyname=$_REQUEST["companyname"];
$logo=$_REQUEST["list1"];
$businesstype=$_REQUEST["businesstype"];
$services=$_REQUEST["services"];
$yearestablished=$_REQUEST["yearestablished"];
$othermarkets=$_REQUEST["othermarkets"];
$productfocus=$_REQUEST["productfocus"];
$companyprofile=$_REQUEST["companyprofile"];
$employees=$_REQUEST["employees"];
$ceo=$_REQUEST["ceo"];
$website=$_REQUEST["website"];
global $markets;

$cid_list=$_REQUEST["cid"];
$cat_list=$_REQUEST["category"];

$phone=$_REQUEST["phone"];
$phone1=$_REQUEST["phone1"];
$phone2=$_REQUEST["phone2"];

$fax=$_REQUEST["fax"];
$fax1=$_REQUEST["fax1"];
$fax2=$_REQUEST["fax2"];
}

if  (count($_POST)>0)
{
if ( $errcnt<>0 )
{

?>
      <table width="90%" border="0" align="center" cellpadding="2" cellspacing="0" class="errorstyle">
        <tr> 
          <td colspan="2"><strong>&nbsp;Your request cannot be processed due to 
            following reasons</strong></td>
        </tr>
        <tr height="10"> 
          <td colspan="2"></td>
        </tr>
        <?

for ($i=0;$i<$errcnt;$i++)
{
?>
        <tr valign="top"> 
          <td width="6%">&nbsp;<?php echo $i+1;?></td>
          <td width="94%"><?php echo  $errs[$i]; ?></td>
        </tr>
        <?
}
?>
      </table>
      <?

}

}


?>
      <form name="form123" method="post" action="companyprofile.php" onSubmit="return validate(this);">
        <table width="90%" border="0" align="center" cellpadding="2" cellspacing="5" class="onepxtable">
          <tr class="titlestyle"> 
            <td colspan="3">&nbsp;Company Profile</td>
          </tr>
          <tr valign="top"> 
            <td width="40%" align="right" class="innertablestyle"><font class="normal"><strong>Company 
              Name </strong></font></td>
            <td width="6"><font class="red">*</font></td>
            <td width="60%"><font class="normal">&nbsp; </font><font class="normal"> 
              <input name="companyname" type="text" value="<?php echo $companyname ; ?>">
              <input name="profile_id" type="hidden" id="profile_id" value="<? echo $profile_id;?>">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Company 
              Logo </strong></font></td>
            <td>&nbsp;</td>
            <td><font size="2" face="Arial, Helvetica, sans-serif" color="#666666"> 
              <input name = "list1" type = "text" id="list1" value="<?php echo $logo; ?>" size="20" readOnly >
              <input type=BUTTON name=btn_name2 value="Attach" onClick=attachment('list1')>
              <input type=BUTTON name=buttonname2 value="Remove" onClick=removeattachment()>
              </font><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;&nbsp;</font><font class="normal">&nbsp; 
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Business 
              Type </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <select name="businesstype">
                <option value="">Please Select</option>
                <?
				$rs_query=mysql_query("Select * from sbjbs_businesstypes " );
	while ($rs=mysql_fetch_array($rs_query))
	{
				?>
                <option value="<?php echo $rs["sb_id"]; ?>"
				<?php
				if ( $rs["sb_id"]==$businesstype )
				{
				echo "  selected ";
				}
				?>
				
				><?php echo $rs["sb_businesstype"]; ?></option>
                <?
			  }
			  ?>
              </select>
              &nbsp; </font><font class="normal">&nbsp; </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Category<br>
              </strong></font><font class="smalltext">(max <? echo "<b>".$cats."</b>";?> 
              category(ies))</font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <?
						 						 
                            ?>
              <textarea name="category" cols="37" rows="5" readonly="readonly" id="textarea"><? echo $cat_list;?></textarea>
              <br>
              <font class="normal"> 
              <select name="cats" id="select" >
                <option value="0">Choose a category</option>
                <?
				  $rs_query=mysql_query("select * from sbjbs_categories order by sb_pid");
				  
				  while($rst=mysql_fetch_array($rs_query))
				  {
				  $cat_path="";
		$child=mysql_fetch_array(mysql_query("select * from sbjbs_categories where sb_pid=".$rst["sb_id"]));
			  if($child)
			  {
			  continue;
			  }
			  $cat_path.=$rst["sb_cat_name"];
		 $par=mysql_query("select * from sbjbs_categories where sb_id=".$rst["sb_pid"]);
		 				while($parent=mysql_fetch_array($par))
		 				{
						$cat_path=$parent["sb_cat_name"].">".$cat_path;
						$par=mysql_query("select * from sbjbs_categories where sb_id=".$parent["sb_pid"]);
						}
                                      ?>
                <option value="<? echo $rst["sb_id"];?>" ><? echo $cat_path;?></option>
                <?
									  }
									  ?>
              </select>
              </font> 
              <input name="cid" type="hidden" id="cid2" value="<? echo $cid_list;?>">
              <input name="add" type="button" id="add2" value="Add" onClick="add_category()">
              <input name="Remove" type="button" id="Remove2" value="Remove" onClick="remove_category()">
              &nbsp; </font><font class="normal">&nbsp; </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Product/Services 
              We Offer</strong></font></td>
            <td><font class="red">*</font></td>
            <TD valign="middle"> <font class='normal'> 
              <script language="JavaScript" type="text/javascript">
<!--

<?
$content = $services;
$content = RTESafe($content);
?>//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('services', '<?=$content?>', 450, 200, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
/*document.write("<br><font class='smalltext'>Description length must not exceed <?php echo $sbrow_con['sb_description_length'];?> characters</font>");*/
</script>
              </font> <noscript>
              <p><font class="normal"><b>Javascript must be enabled to use this 
                form.</b></font></p>
              </noscript></TD>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Year 
              Established </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <select name="yearestablished">
                <?php
				for ($i=2003;$i>=1900;$i--)
				{
				?>
                <option 
				<?php
				if ( $i==$yearestablished  )
				{
				echo "  selected ";
				}
				?>
				><?php echo $i; ?></option>
                <?
				}
				?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Main 
              Market </strong></font></td>
            <td><font class="red">*</font></td>
            <td><table cellspacing=0 cellpadding=4 width="100%" align=top 
                  border=0>
                <tbody>
                  <tr> 
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <?php
					   $cnt=1;
					   $rs_query_t=mysql_query("select * from sbjbs_markets");
					   while($rs_t=mysql_fetch_array($rs_query_t))
					   {
					   ?>
                        <?php if ($cnt%2==1) { ?>
                        <tr> 
                          <?php } ?>
                          <td><font class="normal"> 
                            <input  type="checkbox" value="<?php echo $rs_t["sb_id"];?>" name="market<?php echo $cnt;?>" 
						  		<?php
						if (strpos($markets,$rs_t["sb_id"] ) )
						{
						echo " checked ";
						}
						
						?>>
                            <?php echo $rs_t["sb_market"] ;?> </font></td>
                          <?php if ($cnt%2==0) { ?>
                        </tr>
                        <?php } ?>
                        <?php
						$cnt++;
					   }
					   ?>
                      </table></td>
                  </tr>
                  <tr> 
                    <td width="100%"><font class="normal">
                      <input name="cnt" type="hidden" id="cnt" value="<? echo $cnt;?>">
                      Other Markets: <br>
                      <input name=othermarkets  type="text" value="<?php echo $othermarkets;  ?>" 
                        maxlength=40>
                      </font></td>
                  </tr>
                </tbody>
              </table></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Main 
              Product Focus</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <select   name=productfocus>
                <option value="">Please Select</option>
                <?
				$rs_query=mysql_query("Select * from sbjbs_productfocus  order by sb_productfocus" );
	while ($rs=mysql_fetch_array($rs_query))
	{
				?>
                <option value="<?php echo $rs["sb_id"]; ?>"
				<?php
				if ( $rs["sb_id"]==$productfocus )
				{
				echo "  selected ";
				}
				?>
				
				><?php echo $rs["sb_productfocus"]; ?></option>
                <?
			  }
			  ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Number 
              of Employees</strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <select name=employees>
                <option value="">Please Select</option>
                <?
				$rs_query=mysql_query("Select * from sbjbs_employees order by sb_id" );
	while ($rs=mysql_fetch_array($rs_query))
	{
				?>
                <option value="<?php echo $rs["sb_id"]; ?>"
				<?php
				if ( $rs["sb_id"]==$employees )
				{
				echo "  selected ";
				}
				?>
				
				><?php echo $rs["sb_employees"]; ?></option>
                <?
			  }
			  ?>
              </select>
              </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Company 
              Profile</strong></font></td>
            <td><font class="red">*</font></td>
            <TD valign="middle"> <font class='normal'> 
              <script language="JavaScript" type="text/javascript">
<!--

<?
$content = $companyprofile;
$content = RTESafe($content);
?>//Usage: initRTE(imagesPath, includesPath, cssFile)
initRTE("images/", "", "");

//Usage: writeRichText(fieldname, html, width, height, buttons)
writeRichText('companyprofile', '<?=$content?>', 450, 200, true, false);

//uncomment the following to see a demo of multiple RTEs on one page
//document.writeln('<br><br>');
//writeRichText('rte2', 'read-only text', 450, 100, true, false);
//-->
/*document.write("<br><font class='smalltext'>Description length must not exceed <?php echo $sbrow_con['sb_description_length'];?> characters</font>");*/
</script>
              </font> <noscript>
              <p><font class="normal"><b>Javascript must be enabled to use this 
                form.</b></font></p>
              </noscript></TD>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>CEO/Owner's 
              Name </strong></font></td>
            <td><font class="red">*</font></td>
            <td><font class="normal"> 
              <input name="ceo" type="text" value="<?php echo $ceo;  ?>">
              </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Phone</strong></font></td>
            <td>&nbsp;</td>
            <td><table border="0" align="left" cellpadding="0" cellspacing="0">
                <tr> 
                  <td><font class="smalltext">Country Code </font></td>
                  <td><font class="smalltext">Area Code </font></td>
                  <td><font class="smalltext">Phone Number</font></td>
                </tr>
                <tr> 
                  <td width="90"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone" type="text"  id="phone"  value="<?php echo $phone; ?>" size="5" maxlength="5" >
                    </font></td>
                  <td width="77"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone1" type="text"  id="phone1"   value="<?php echo $phone1; ?>" size="8" maxlength="8" >
                    </font></td>
                  <td width="148"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="phone2" type="text"  id="phone2"   value="<?php echo $phone2; ?>" size="20" maxlength="20" >
                    </font></td>
                </tr>
              </table>
              <font face="Arial, Helvetica, sans-serif" size="2">&nbsp; </font></td>
          </tr>
          <tr valign="top"> 
            <td height="24" align="right" class="innertablestyle"><font class="normal"><strong>Fax</strong></font></td>
            <td>&nbsp;</td>
            <td> <table border="0" align="left" cellpadding="0" cellspacing="0">
                <tr> 
                  <td><font class="smalltext">Country Code </font></td>
                  <td><font class="smalltext">Area Code </font></td>
                  <td><font class="smalltext">Number</font></td>
                </tr>
                <tr> 
                  <td width="90"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="fax" type="text"  id="fax"   value="<?php echo $fax; ?>" size="5" maxlength="5" >
                    </font></td>
                  <td width="77"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="fax1" type="text"  id="fax1"   value="<?php echo $fax1; ?>" size="8" maxlength="8" >
                    </font></td>
                  <td width="148"><font face="Arial, Helvetica, sans-serif" size="2"> 
                    <input name="fax2" type="text"  id="fax2"   value="<?php echo $fax2; ?>" size="20" maxlength="20" >
                    </font></td>
                </tr>
              </table></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle"><font class="normal"><strong>Homepage</strong></font></td>
            <td>&nbsp;</td>
            <td><font class='normal'>http:// 
              <input name="website" type="text" id="website" value="<?php echo $website;  ?>">
              &nbsp; </font><font class="normal">&nbsp; </font></td>
          </tr>
          <tr valign="top"> 
            <td align="right" class="innertablestyle">&nbsp;</td>
            <td>&nbsp;</td>
            <td><table width="340" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr> 
                  <td width="143"> <input type="reset" name="Reset" value="Revert Changes"> 
                  </td>
                  <td width="197"> <input type="submit" name="Submit2" value="Post / Edit Profile"> 
                  </td>
                </tr>
              </table></td>
          </tr>
        </table>
        <p align="center">&nbsp;</p>
        </form>
</td>
  </tr>
</table>
<?
}
}  //End of main
include_once("template1.php");
?>


