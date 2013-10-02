<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
require_once("../includes/functions.php");
$redirect='';
$err_disp='';
if(isset($_GET["redto"]) && $_GET["redto"]=='yes')
$redirect="document.location.href='glb_err_cmp.php'";
else
$redirect="document.location.href='glb_brg_err.php'";
if(!empty($_POST["hidden_pst"])){
	$u_id=$_GET["errorid"];
	foreach($_POST as $key=>$each_conf_cell){
		if($key!="hidden_pst"){
			if($key=='access_code'){
				if(trim($each_conf_cell)==""){
					$final_conf[$key]=$each_conf_cell;
					$status_conf[$key][]=func_status(2);
				}else{
					$final_conf[$key]=$each_conf_cell;
					$status_conf[$key][]=func_status(1);
				}
			}else
				$final_conf[$key]=$each_conf_cell;
	}
}
if(count($status_conf)>0){
			$get_sts_cd=stat_to_arr($status_conf);
			$final_conf['record_status']=get_stat_code($get_sts_cd);
	}
$data=array_values($final_conf);
foreach($final_conf as $key=>$eachfield){
	$fdata.=$key."='".mysql_real_escape_string($eachfield)."',";
}
	
	$fdata=trim($fdata,',');
	//echo "update tbl_conference_filtered set $fdata where id=$u_id";exit;
	$updt_brg=mysql_query("update tbl_conference_filtered set $fdata where id=$u_id");
if($updt_brg)
	$err_disp="<span style='font-size:12px;color:green'>Updated successfully</span>";
else
	$err_disp="<span style='font-size:12px;color:red'>Error while updating</span>";
}
if(!empty($_GET["errorid"]) && preg_match('/^[0-9]+$/',$_GET["errorid"])){
	$id=$_GET["errorid"];
	$get_rec=mysql_query("select * from tbl_conference_filtered where id=$id");
	if(mysql_num_rows($get_rec)>0){
		$get_elem=mysql_fetch_assoc($get_rec);
		extract($get_elem);
	}else{
		$err_disp="<span style='font-size:12px;color:red'>Invalid id accessed</span>";
	}
}else{
	header("location:glb_conf_err.php");
	exit;
}
require_once("header.php");
?>
<div style='height:50px'>
</div>
<div><?php echo $err_disp;?></div>
<form name='conf_edit_form' id='' method='post' action=''>
<input type='hidden' name='hidden_pst' value="<?php echo $_GET["errorid"];?>">
<table width="50%" class='edit_error' align="center" cellpadding="0" cellspacing="0"   border="0"> 
		<tr><td colspan='2'><h4>Edit Conference Record</h4></td></tr>
		<tr height='10px'><td colspan='2'></td></tr>
		<tr>
			<td>Service Povider Id</td>
			<td><input type='text' name='service_povider_id' value="<?php echo $service_povider_id;?>"></td>
		</tr>
		<tr>
			<td>Customer Id</td>
			<td><input type='text' name='customer_id' value="<?php echo $customer_id;?>"></td>
		</tr>
		<tr>
			<td>Conference Id</td>
			<td><input type='text' name='conference_id' value="<?php echo $conference_id;?>"></td>
		</tr>
		<tr>
			<td>Authorization String</td>
			<td><input type='text' name='authorization_string' value="<?php echo $authorization_string;?>"></td>
		</tr>
		<tr>
			<td>First Touched Timestamp</td>
			<td><input type='text' name='first_touched_timestamp' value="<?php echo $first_touched_timestamp;?>"></td>
		</tr>
		<tr>
			<td>Resv Begin</td>
			<td><input type='text' name='resv_begin' value="<?php echo $resv_begin;?>"></td>
		</tr>
		<tr>
			<td>Resv Begin Timezone</td>
			<td><input type='text' name='resv_begin_timezone' value="<?php echo $resv_begin_timezone;?>"></td>
		</tr>
		<tr>
			<td>Resv End</td>
			<td><input type='text' name='resv_end'  value="<?php echo $resv_end;?>"></td>
		</tr>
		<tr>
			<td>Resv End Timezone</td>
			<td><input type='text' name='resv_end_timezone' value="<?php echo $resv_end_timezone;?>"></td>
		</tr>
		<tr>
			<td>Invoice Ref</td>
			<td><input type='text' name='invoice_ref' value="<?php echo $invoice_ref;?>"></td>
		</tr>
		<tr>
			<td>Status</td>
			<td><input type='text' name='status' value="<?php echo $status;?>"></td>
		</tr>
		<tr>
			<td>Last Touched Timestamp</td>
			<td><input type='text' name='last_touched_timestamp' value="<?php echo $last_touched_timestamp;?>"></td>
		</tr>
		<tr>
			<td>Reserver</td>
			<td><input type='text' name='reserver' value="<?php echo $reserver;?>"></td>
		</tr>
		<tr>
			<td>Reserver Phone</td>
			<td><input type='text' name='reserver_phone' value="<?php echo $reserver_phone;?>"></td>
		</tr>
		<tr>
			<td>Reserved Total Lines</td>
			<td><input type='text' name='reserved_total_lines' value="<?php echo $reserved_total_lines;?>"></td>
		</tr>
		<tr>
			<td>Ra Master Id</td>
			<td><input type='text' name='ra_master_id' value="<?php echo $ra_master_id;?>"></td>
		</tr>
		<tr>
			<td>Access Number</td>
			<td><input type='text' name='access_number'  value="<?php echo $access_number;?>"></td>
		</tr>
		<tr>
			<td>Access Code</td>
			<td><input type='text' name='access_code' value="<?php echo $access_code;?>"></td>
		</tr>
		
		<tr height='20px'><td colspan='2'></td></tr>
	  <tr>
		<td colspan='2' style='text-align:center;'>
			<input type='submit' value='Submit' class='button'>&nbsp;&nbsp;&nbsp;<input type='button' value='Cancel' class='button' onclick="<?php echo $redirect;?>">
		</td>
	  </tr>
	</table>
</form>
<?php

require_once("footer.php");

?>