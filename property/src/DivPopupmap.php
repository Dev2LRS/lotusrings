<?php 
include_once "configure.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <title> New Document </title>
<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="js/jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="js/lytebox_demo.js"></script>
<script type="text/javascript" src="js/open-popup.js"></script>
<link rel="stylesheet" type="text/css" href="js/lytebox.css" media="screen">
<style>
  #fade { /*--Transparent background layer--*/
	display: none; /*--hidden by default--*/
	background: #000;
	position: fixed; left: 0; top: 0;
	width: 100%; height: 100%;
	opacity: .1;
	z-index: 9999;
}
.popup_block{
	display: none; /*--hidden by default--*/
	background: #fff;
	padding: 20px;
	border: 20px solid #ddd;
	float: left;
	font-size: 1.2em;
	position: fixed;
	top: 50%; left: 50%;
	z-index: 99999;
	/*--CSS3 Box Shadows--*/
	-webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
	/*--CSS3 Rounded Corners--*/
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
img.btn_close {
	float: right;
	margin: -55px -55px 0 0;
}
/*--Making IE6 Understand Fixed Positioning--*/
*html #fade {
	position: absolute;
}
*html .popup_block {
	position: absolute;
}
</style>
 </head>

 <body>
  <a href="javascript:;" onclick="showpopup('popup_name');" style="cursor:pointer">Test</a>	
   <div id="popup_name" class="popup_block">	
		<table align="center" border="0" width="30%" bordercolor="red" class="subpage-table" style="border:none">
			<tr style="height:5px"><td style="border:none"></td></tr>			
			<tr>
			 <td style="border:none;text-align:left" colspan="5"><b>Please select the Icon</b></td>
             </tr>
			<tr>
			 <td align="center">
			   <table align="center" border="0">
				<?php
					$cellDvdr=1;
					$resIcn=mysql_query("SELECT * FROM `icon`");
					$TotIcncount=mysql_num_rows($resIcn);
					while($rowIcn=mysql_fetch_array($resIcn))
					{		
						if(($cellDvdr%10)==1)
						 echo "<tr>";

						$selct="";
						if($cellDvdr==1)
						 $selct="checked";
				?>				  
					   <td><input type="radio" name="rdIcon" id="rdIcon<?php echo $rowIcn["iconid"];?>" value="<?php echo $rowIcn["iconid"];?>" <?php echo $selct?> >&nbsp;</td>
					   <td align="left"><label for="rdIcon<?php echo $rowIcn["iconid"];?>"><img src="<?php echo "http://shenll.net/property/Display-Icon.php?iconid=".$rowIcn["iconid"];?>"></label>&nbsp;</td>				  
				 <?php
						if(($cellDvdr%10)==0 || $TotIcncount==$cellDvdr)
							 echo "</tr>";

						$cellDvdr++;
					}
				 ?>
				  </table>
				 </td>
				</tr>
				<tr><td align='center'><input type='button' id='btnIconadd' value='Add to Favorites' onclick="addPlaceFav()"></td></tr>
			    <tr style="height:5px"><td style="border:none"></td></tr>
		</table>
   </div>
 </body>
</html>