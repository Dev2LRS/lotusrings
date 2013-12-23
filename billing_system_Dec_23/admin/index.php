<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");

$strMsg = "";
$strEmail = "";
if(isset($_POST["adminEmail"]))
{
  
  $strEmail =   mysql_real_escape_string($_POST["adminEmail"]); 
  $strPass  =   mysql_real_escape_string($_POST["adminPassword"]); 
   
	$QryChkEmail = "SELECT admin_id,email,concat(first_name,' ',last_name) as name FROM tbl_admin WHERE email='$strEmail' AND password='$strPass'";
        
        $result = mysql_query($QryChkEmail);
	$num_rows = mysql_num_rows($result);
	if($num_rows>0)
	{
		$row = mysql_fetch_array($result);
		$_SESSION["ses_adminid"]=$row['admin_id'];
                $_SESSION["ses_adminemail"]=stripslashes($row['email']);
                $_SESSION["ses_admin_name"]=stripslashes($row['name']);
		header("Location: list_processed_files.php");
	    exit;
	}
	else
	{
      $strMsg = "Invalid email address or password";
    }
}

if($_SESSION['ses_admin_id']!="") {
	$ses_id		= $_SESSION['ses_admin_id'];
	$sessionUser	= $_SESSION['ses_admin_name'];
	$ImgPath ="images/";
	$fileNameAry= pathinfo($_SERVER["PHP_SELF"]);
	$fileName   = $fileNameAry[filename];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo SITE_NAME; ?></title>
<meta NAME="Generator" CONTENT="EditPlus">
<meta NAME="Author" CONTENT="">
<meta NAME="Keywords" CONTENT="">
<meta NAME="Description" CONTENT="">
<style type="text/css"> body, img, div, td { behavior: url(iepngfix.htc) }</style>
<link rel="StyleSheet" href="<?php echo $CSSPath; ?>style2.css" type="text/css" >
<link rel="StyleSheet" href="css/style_new.css" type="text/css" >
<!--[if lt IE 8]>
<script defer type="text/javascript" src="<?php echo $ScriptPath; ?>pngfix.js"></script>
<![endif]-->
<script src="js/jquery-1.7.min.js"></script>
<style>

</style>
<!-- SCRIPTS AND CSS FOR IFRAME ENDS HERE-->
</head>
<body  bgcolor="#fff" style="background:none;background-color:#ECEFF1;" >
<div id="header">
	
		<h1><a href="#"><?php echo SITE_NAME; ?></a></h1>
		
		<div class="userprofile">
			<ul>				
				<li><?php if($_SESSION['ses_admin_id']!="") { ?>
						Welcome <?php echo $sessionUser;?>&nbsp; | <a href="logout.php">Logout</a>
						<?php } ?>
				</li>
			</ul>				
		</div>		<!-- .userprofile ends -->
</div>	
<table border="1" align='center' cellspacing="0" cellpadding="0" style="width:100%;height:91%;" >
<tr>
<td  colspan="3" valign="top" align="center">
<div class="height60Px" align="center">
         
</div>
<div class="loginbox">
                        <?php if (!empty($strMsg)) { ?>
                        <div class="message errormsg ">
                            <span id="error" ><?php echo $strMsg; ?></span>
                            <span title="Dismiss" class="close"></span>
                        </div>       
                        <?php
                        }
                        else 
                        {?>
			<div class="message errormsg " style="display: none;">
				<span id="error"></span>
				<span title="Dismiss" class="close"></span>
				
			</div>
                        <?php
                        }?>
		<form name="admin_login_frm" id="admin_login_frm"  method="post" action=""  >
			<p align="left">
				<label >Email Address:</label> <br>
                                <input type="text" maxlength="50" style="padding:2px;width:225px;height:24px;" name="adminEmail" id="adminEmail">
			</p>
			
			<p align="left">
				<label>Password:</label> <br>
				<input type="password" maxlength="25" name="adminPassword" style="padding:2px;width:225px;height:24px;" id="adminPassword">
			</p>
			
			<p class="formend">
				<input type="button" value="Submit" class="submitbt" id="submitbt" /> &nbsp; 
			</p>
		</form>
	</div>
	</td>
</tr>
<tr class="footer">
<td  colspan="3" valign="top" align="center">&copy; Copyright <?php echo date('Y'). " ".SITE_NAME; ?>.</td></tr>
</table>
</div>
</BODY>
<script type="text/javascript">
	$('.close').live('click',function(){
		$(this).parents('.message').fadeOut(1000);
	});
        $('input').keypress(function(event){

            if(event.keyCode == 13)
            {
                $('.submitbt').trigger('click');
            }
        });
</script>
</HTML>

<script>
$(document).ready(function(){
	$('#adminEmail').focus();
	$('.text').keypress(function(event){
		//e = event.which;
		//alert(e.keycode)
	});
        
});
$(".text").keydown(function () {
       $(".errormsg").fadeOut();
       $('#LoginErr').fadeTo(1000,0).remove();
    });

$('.submitbt').bind("click",function(){
                        $('#LoginErr').fadeTo(1000,0).remove();
			var x = $.trim($('#adminEmail').val());			
			var atpos=x.indexOf("@");
			var dotpos=x.lastIndexOf(".");
			if($.trim($('#adminEmail').val())=="")
			{		
			
				$(".errormsg #error").html("Please enter admin email address.");
				$(".errormsg").fadeIn(600);				
				$('#adminEmail').focus();
				$('#adminEmail').val('');					
				return false;
			}
			else if (atpos<1 || dotpos<atpos+3 || dotpos+2>=x.length)
			{
				$(".errormsg #error").html("Please enter a valid email address.");
				$(".errormsg").fadeIn(600);				
				$('#adminEmail').focus();						
					
				return false;
			}
			else if($.trim($('#adminPassword').val())=="")
			{
				$(".errormsg #error").html("Please enter admin password.");
				$(".errormsg").fadeIn(600);	
				$('#adminPassword').focus();
				$('#adminPassword').val('');
				return false;
			}			
			else
			{
                           
				$('#admin_login_frm').submit();
                                return true;
			}
		
	
});
function trim(val)
{
	return val.replace(/\s/g,"")
}
	function rightTrim( strValue ) {
/************************************************
DESCRIPTION: Trims trailing whitespace chars.

PARAMETERS:
   strValue - String to be trimmed.

RETURNS:
   Source string with right whitespaces removed.
*************************************************/
var objRegExp = /^([\w\W]*)(\b\s*)$/;

      if(objRegExp.test(strValue)) {
       //remove trailing a whitespace characters
       strValue = strValue.replace(objRegExp, '$1');
    }
  return strValue;
}

function leftTrim( strValue ) {
/************************************************
DESCRIPTION: Trims leading whitespace chars.

PARAMETERS:
   strValue - String to be trimmed

RETURNS:
   Source string with left whitespaces removed.
*************************************************/
var objRegExp = /^(\s*)(\b[\w\W]*)$/;

      if(objRegExp.test(strValue)) {
       //remove leading a whitespace characters
       strValue = strValue.replace(objRegExp, '$2');
    }
  return strValue;
}
function validatePhoneNumber(elementValue){
	var phone_number = elementValue.replace(/[\-\(\)\s]/g, "");
	if(phone_number.length !=10 || phone_number.match(/\D/g) )	{
		return true;
	}
	else
		return false;
}
</script>