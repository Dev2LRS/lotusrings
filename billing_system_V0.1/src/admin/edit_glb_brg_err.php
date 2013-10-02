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
if(!empty($_POST["hidden_pst"]) && ($_POST["bridge_type"]=='b' || $_POST["bridge_type"]=='nb')){
	$u_id=$_GET["errorid"];
	$hdn_conf_id=$_POST["hdn_conf_id"];
	$get_min_id=mysql_query("select min(id) As min_id from tbl_bridge_filtered");
	$min_id=mysql_fetch_assoc($get_min_id);
	foreach($_POST as $key=>$each_brg_cell){
		$each_brg_cell=trim($each_brg_cell);
		if($key!="hidden_pst" && $key!='hdn_conf_id'){
			if($key=='participant_name'){
				$each_brg_cell=str_replace("'","",$each_brg_cell);
				$final_brg[$key]=$each_brg_cell;
				$status_brg[$key][]=func_status(1);
			}else if(is_string($each_brg_cell)){
				if(strpos($each_brg_cell,"'")!=FALSE){
					$final_brg[$key]=$each_brg_cell;
					$status_brg[$key][]=func_status(2);
				}else
					$final_brg[$key]=$each_brg_cell;
		}else
			$final_brg[$key]=$each_brg_cell;

			if($key=='bridge_id'){
			if($each_brg_cell=='AR-WEB' || $each_brg_cell=='AR-REC'){
				$each_brg_cell=preg_replace('/[^A-Z]/', '', $each_brg_cell); 
				$final_brg[$key]=$each_brg_cell;
				$status_brg[$key][]=func_status(1);
			}else
				$final_brg[$key]=$each_brg_cell;
		}else
			$final_brg[$key]=$each_brg_cell;

		if($key=='chargeable_item'){
			$nocon=TRUE;
			if($each_brg_cell=="U106" && $_POST["bridge_type"]=="b"){
				$each_brg_cell="U972";
				$final_brg[$key]="U972";
				$status_brg[$key][]=func_status(1);
				$nocon=FALSE;
			}

			if($each_brg_cell=="RARH" && $_POST["bridge_type"]=="nb"){
				$each_brg_cell="RARP";
				$final_brg[$key]="RARP";
				$nocon=FALSE;
			}

			if($nocon)
				$final_brg[$key]=$each_brg_cell;

		}else 
			$final_brg[$key]=$each_brg_cell;

		if($key=='a_number'){
			$final_brg[$key]=$each_brg_cell;
			if(!preg_match('/^[0-9]+$/',$each_brg_cell)){
				$status_brg[$key][]=func_status(2);
			}

		}else 
			$final_brg[$key]=$each_brg_cell;

		// check if user is entering BBNN series in bridge_type
		if($u_id > $min_id["min_id"]){
			$final_brg[$key]=$each_brg_cell;
			$check_prv_brg=mysql_query("select bridge_type from tbl_bridge_filtered where id=".($u_id-1)." and conference_pk_id=$hdn_conf_id");
			if(mysql_num_rows($check_prv_brg)>0){
				$chk_brg_typ=mysql_fetch_assoc($check_prv_brg);
				if($chk_brg_typ["bridge_type"]==$final_brg["bridge_type"]){
					//set status to manual_fix
					$status_brg[$key][]=func_status(2);
				}
			}else{
				$check_aft_brg=mysql_query("select bridge_type from tbl_bridge_filtered where id=".($u_id+1)." and conference_pk_id=$hdn_conf_id");
				if(mysql_num_rows($check_aft_brg)>0){
					$chk_brg_typ=mysql_fetch_assoc($check_aft_brg);
					if($chk_brg_typ["bridge_type"]==$final_brg["bridge_type"]){
						//set status to manual_fix
						$status_brg[$key][]=func_status(2);
					}
				}
			}
		}
	}
}
if(count($status_brg)>0){
			$get_sts_cd=stat_to_arr($status_brg);
			$final_brg['record_status']=get_stat_code($get_sts_cd);
	}

$data=array_values($final_brg);
	foreach($final_brg as $key=>$eachfield){
		$fdata.=$key."='".mysql_real_escape_string($eachfield)."',";
	}
	
	$fdata=trim($fdata,',');
	//echo "update tbl_bridge_filtered set $fdata where id=$u_id";
	$updt_brg=mysql_query("update tbl_bridge_filtered set $fdata where id=$u_id");
if($updt_brg)
	$err_disp="<span style='font-size:12px;color:green'>Updated successfully</span>";
else
	$err_disp="<span style='font-size:12px;color:red'>Error while updating</span>";
}
if(!empty($_GET["errorid"]) && preg_match('/^[0-9]+$/',$_GET["errorid"])){
	$id=$_GET["errorid"];
	$get_rec=mysql_query("select * from tbl_bridge_filtered where id=$id");
	if(mysql_num_rows($get_rec)>0){
		$get_elem=mysql_fetch_assoc($get_rec);
		extract($get_elem);
	}else{
		$err_disp="<span style='font-size:12px;color:red'>Invalid id accessed</span>";
	}
}else{
	header("location:glb_brg_err.php");
	exit;
}
require_once("header.php");
?>
<div style='height:50px'>
</div>
<div><?php echo $err_disp;?></div>
<form name='brg_edit_form' id='' method='post' action=''>
<input type='hidden' name='hidden_pst' value="<?php echo $_GET["errorid"];?>">
<input type="hidden" name='hdn_conf_id' value="<?php echo $conference_pk_id?>">
<table width="50%" class='edit_error' align="center" cellpadding="0" cellspacing="0"   border="0"> 
		<tr><td colspan='2'><h4>Edit Bridge Record</h4></td></tr>
		<tr height='10px'><td colspan='2'></td></tr>
		<tr>
			<td>Bridge Type</td>
			<td><input type='text' name='bridge_type' value="<?php echo $bridge_type;?>"></td>
		</tr>
		<tr>
			<td>Service Provider Id</td>
			<td><input type='text' name='service_provider_id' value="<?php echo $service_provider_id;?>"></td>
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
			<td>Cal Detail Id</td>
			<td><input type='text' name='cal_detail_id' value="<?php echo $cal_detail_id;?>"></td>
		</tr>
		<tr>
			<td>Units</td>
			<td><input type='text' name='units' value="<?php echo $units;?>"></td>
		</tr>
		<tr>
			<td>Unit of Measure</td>
			<td><input type='text' name='unit_of_measure' value="<?php echo $unit_of_measure;?>"></td>
		</tr>
		<tr>
			<td>Item Type</td>
			<td><input type='text' name='item_type'  value="<?php echo $item_type;?>"></td>
		</tr>
		<tr>
			<td>Chargeable Item</td>
			<td><input type='text' name='chargeable_item' value="<?php echo $chargeable_item;?>"></td>
		</tr>
		<tr>
			<td>Charge Amount</td>
			<td><input type='text' name='charge_amount' value="<?php echo $charge_amount;?>"></td>
		</tr>
		<tr>
			<td>Currency</td>
			<td><input type='text' name='currency' value="<?php echo $currency;?>"></td>
		</tr>
		<tr>
			<td>Start Time</td>
			<td><input type='text' name='start_time' value="<?php echo $start_time;?>"></td>
		</tr>
		<tr>
			<td>End Time</td>
			<td><input type='text' name='end_time' value="<?php echo $end_time;?>"></td>
		</tr>
		<tr>
			<td>Timezone</td>
			<td><input type='text' name='timezone' value="<?php echo $timezone;?>"></td>
		</tr>
		<tr>
			<td>Bridge Id</td>
			<td><input type='text' name='bridge_id' value="<?php echo $bridge_id;?>"></td>
		</tr>
		<tr>
			<td>Port Id</td>
			<td><input type='text' name='port_id' value="<?php echo $port_id;?>"></td>
		</tr>
		<tr>
			<td>A Number</td>
			<td><input type='text' name='a_number'  value="<?php echo $a_number;?>"></td>
		</tr>
		<tr>
			<td>B Number</td>
			<td><input type='text' name='b_number' value="<?php echo $b_number;?>"></td>
		</tr>
		<tr>
			<td>Privacy Bit</td>
			<td><input type='text' name='privacy_bit'  value="<?php echo $privacy_bit;?>"></td>
		</tr>
		<tr>
			<td>Participant Name</td>
			<td><input type='text' name='participant_name' value="<?php echo $participant_name;?>"></td>
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