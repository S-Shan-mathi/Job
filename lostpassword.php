<?
function main()
{
?> 
<script language="JavaScript">
	//=======
	function emailCheck (emailStr)
	{
		var emailPat=/^(.+)@(.+)$/;
		var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]";
		var validChars="\[^\\s" + specialChars + "\]";
		var quotedUser="(\"[^\"]*\")";
		var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
		var atom=validChars + '+';
		var word="(" + atom + "|" + quotedUser + ")";
		var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
		var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
		var matchArray=emailStr.match(emailPat);
		if (matchArray==null) 
			{
		alert("Please verify Email Address (check @ and .'s)");
		//form.email.focus();

		return(false);
		}
	var user=matchArray[1];
	var domain=matchArray[2];
	if (user.match(userPat)==null) 
	{
    	alert("Please Specify a valid username in Email Address!");
    	return(false);
	}
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) 
	{
    // this is an IP address
	  	for (var i=1;i<=4;i++) 
		{
	    	if (IPArray[i]>255) 
			{
	        	alert("Destination IP address is invalid!");
				return(false);
	    	}
    	}
    	return(true);
	}
	var domainArray=domain.match(domainPat);
	if (domainArray==null) 
	{
		alert("Please Specify a valid domain name!");
    	return(false);
	}
	var atomPat=new RegExp(atom,"g");
	var domArr=domain.match(atomPat);
	var len=domArr.length;
	if (domArr[domArr.length-1].length<2 || domArr[domArr.length-1].length>4)
	{
   		alert("The address must end in a valid domain, or two letter country.");
   		return(false);
	}
	if (len<2) 
	{
   		var errStr="This address is missing a hostname!";
   		alert(errStr);
   		return(false);
	}
	return true;
}

	function validate(form)
	{
		if(form.email.value=='')
		{
			alert("Please Specify Email Address!");
			form.email.focus();
			return(false);
		}
		else
		{	//==============
			if(!form.email.value.match(/[a-zA-Z\.\@\d\_]/)) 
			{
           		alert('Please Specify a valid Email Address.');
				form.email.focus();
	        	return false;
           	}

			if (!emailCheck (form.email.value) )
			{
				form.email.focus();
				return (false);
			}
			
		}	//=================
		return(true);
	}
</script>
<form name="frm1" method="post" action="sendpassword.php" onSubmit="return validate(this);" >
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="onepxtable">
    <tr> 
      <td height="25" class="titlestyle"><b>&nbsp; Forgot Password</b></td>
    </tr>
    <tr> 
      <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
          <tr> 
            <td width="100%" valign="top"> <div align="center"> 
                <table width="100%" border="0" cellpadding="2" cellspacing="5">
                  <tr valign="top"> 
                    <td width="36%" height="27" class="innertablestyle"> <div align="right"><strong><font class='normal'>Email 
                        ID </font></strong></div></td>
                    <td width="6"><font class="red">*</font></td>
                    <td width="64%"> <input name="email" type="text" id="email"> 
                      <br> <font class='smalltext'>Please provide your email id. 
                      We will send your password in email </font></td>
                  </tr>
                  <tr> 
                    <td height="27" class="innertablestyle">&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td><input type="submit" name="Submit" value="Send Request"> 
                    </td>
                  </tr>
                </table>
              </div></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <strong><font color="#000000"> </font></strong> 
</form>
  <?
  }// end sub 
include "template.php";?>