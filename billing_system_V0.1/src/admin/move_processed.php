<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");



require_once("header.php");

?>
<table width="100%">
<tr height='20px'>
		<td></td>
	</tr>
	<tr>
		<td class="red1" align="left" ><h2>Move Processed Records</h2></td>
	</tr>
	<tr height='20px'>
		<td></td>
	</tr>
	<tr>
		<td>
			<table width="100%"class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;height:250px">
				
					 <tr>
							<th height="25" align="left"  style="background-color:#CAE1F9;">
                                           Move Processed Records
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <table class="sortable"  cellpadding="0" cellspacing="0" valign="top"  border="0" style='margin-left:100px;width:40%'> 
                                     <tr> <td colspan="4" height="15"></td></tr>
                                     <tr><td><b>Move Premier Records</b></td><td></td><td colspan='2' align='center'><input type='button' value="Move" class='button' style='width:60px' onclick='funcprocss("1")'></td></tr>
									  <tr> <td colspan="4" height="15"></td></tr>
									  <tr><td><b>Move Global Records</b></td><td></td><td colspan='2' align='center'><input type='button' value="Move" class='button' style='width:60px' onclick='funcprocss("2")'></td></tr>
                                </table>
				</td></tr>
			</table>
		</td>
	</tr>
</table>
<script>
function funcprocss(getid){
	if(confirm("Are you sure want to move records")==true){
		if(getid=='1'){
			window.open('move_prm.php','premier_window','height=300,width=450');
			return true;
		}else if(getid=='2'){
			window.open('move_glb.php','premier_window','height=300,width=450');
			return true;
		}
	}else 
		return false;
}
</script>
