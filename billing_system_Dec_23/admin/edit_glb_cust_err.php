<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
require_once("../includes/functions.php");
$redirect='';
$err_disp='';
if(isset($_GET["pgnum"]))
	$page=$_GET["pgnum"];
$u_id=$_GET["errorid"];
if(isset($_GET["redto"]) && $_GET["redto"]=='yes')
$redirect="document.location.href='glb_err_cmp.php?pgnum=$page'";
else
$redirect="document.location.href='glb_brg_err.php?pgnum=$page'";

if(!empty($_POST["hidden_pst"])){
	foreach($_POST as $key=>$each_cus_cell){
		if($key!="hidden_pst"){
			if($key=='chairperson_name'){
			$final_cust[$key]=str_replace("'","",$each_cus_cell);
			$status_cust[$key][]=func_status(1);
		}else if(is_string($each_cus_cell)){
				if(strpos($each_cus_cell,"'")!=FALSE){
					$final_cust[$key]=$each_cus_cell;
					$status_cust[$key][]=func_status(2);
				}else
					$final_cust[$key]=$each_cus_cell;
		}else
			$final_cust[$key]=$each_cus_cell;
	}
}

if(count($status_cust)>0){
			$get_sts_cd=stat_to_arr($status_cust);
			$final_cust['record_status']=get_stat_code($get_sts_cd);
	}
	foreach($final_cust as $key=>$eachfield){
		$fdata.=$key."='".mysql_real_escape_string($eachfield)."',";
	}
	
	$fdata=trim($fdata,',');
	$updt_brg=mysql_query("update tbl_customer_error set $fdata where pk_customer_id=$u_id");
if($updt_brg)
	$err_disp="<span style='font-size:12px;color:green'>Updated successfully</span>";
else
	$err_disp="<span style='font-size:12px;color:red'>Error while updating</span>";
}
if(!empty($_GET["errorid"]) && preg_match('/^[0-9]+$/',$_GET["errorid"])){
	$id=$_GET["errorid"];
	$get_rec=mysql_query("select * from tbl_customer_error where pk_customer_id=$id");
	if(mysql_num_rows($get_rec)>0){
		$get_elem=mysql_fetch_assoc($get_rec);
		extract($get_elem);
	}else{
		$err_disp="<span style='font-size:12px;color:red'>Invalid id accessed</span>";
	}
}else{
	header("location:glb_cust_err.php");
	exit;
}
require_once("header.php");
?>
<div style='height:50px'>
</div>
<div><?php echo $err_disp;?></div>
<form name='cust_edit_form' id='' method='post' action=''>
<input type='hidden' name='hidden_pst' value="<?php echo $_GET["errorid"];?>">
<table width="50%" class='edit_error' align="center" cellpadding="0" cellspacing="0"   border="0"> 
		<tr><td colspan='2'><h4>Edit Customer Record</h4></td></tr>
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
			<td>Customer Name</td>
			<td><input type='text' name='customer_name' value="<?php echo $customer_name;?>"></td>
		</tr>
		<tr>
			<td>Organization Id</td>
			<td><input type='text' name='organization_id' value="<?php echo $organization_id;?>"></td>
		</tr>
		<tr>
			<td>Subaccount Id</td>
			<td><input type='text' name='subaccount_id' value="<?php echo $subaccount_id;?>"></td>
		</tr>
		<tr>
			<td>Subaccount Name</td>
			<td><input type='text' name='subaccount_name' value="<?php echo $subaccount_name;?>"></td>
		</tr>
		<tr>
			<td>Chairperson Id</td>
			<td><input type='text' name='chairperson_id' value="<?php echo $chairperson_id;?>"></td>
		</tr>
		<tr>
			<td>Chairperson Name</td>
			<td><input type='text' name='chairperson_name'  value="<?php echo $chairperson_name;?>"></td>
		</tr>
		<tr>
			<td>Chairperson Phone</td>
			<td><input type='text' name='chairperson_phone' value="<?php echo $chairperson_phone;?>"></td>
		</tr>
		<tr>
			<td>Account Type</td>
			<td><input type='text' name='account_type' value="<?php echo $account_type;?>"></td>
		</tr>
		<tr>
			<td>Address1</td>
			<td><input type='text' name='address1' value="<?php echo $address1;?>"></td>
		</tr>
		<tr>
			<td>Address2</td>
			<td><input type='text' name='address2' value="<?php echo $address2;?>"></td>
		</tr>
		<tr>
			<td>Address3</td>
			<td><input type='text' name='address3' value="<?php echo $address3;?>"></td>
		</tr>
		<tr>
			<td>City Name</td>
			<td><input type='text' name='city_name' value="<?php echo $city_name;?>"></td>
		</tr>
		<tr>
			<td>Country Name</td>
			<td><input type='text' name='country_name' value="<?php echo $country_name;?>"></td>
		</tr>
		<tr>
			<td>State Code</td>
			<td><input type='text' name='state_code' value="<?php echo $state_code;?>"></td>
		</tr>
		<tr>
			<td>Zip</td>
			<td><input type='text' name='zip'  value="<?php echo $zip;?>"></td>
		</tr>
		<tr>
			<td>Country Code</td>
			<td><input type='text' name='country_code' value="<?php echo $country_code;?>"></td>
		</tr>
		<tr>
			<td>Anniversary Date</td>
			<td><input type='text' name='anniversary_date' value="<?php echo $anniversary_date;?>"></td>
		</tr>
		<tr>
			<td>Account Status</td>
			<td><input type='text' name='account_status' value="<?php echo $account_status;?>"></td>
		</tr>
		<tr>
			<td>Sales Code</td>
			<td><input type='text' name='sales_code' value="<?php echo $sales_code;?>"></td>
		</tr>
		<tr>
			<td>Payment Type</td>
			<td><input type='text' name='payment_type' value="<?php echo $payment_type;?>"></td>
		</tr>
		<tr>
			<td>Wholesale Unique Id</td>
			<td><input type='text' name='wholesale_unique_id' value="<?php echo $wholesale_unique_id;?>"></td>
		</tr>
		<tr>
			<td>Sp Unique Id</td>
			<td><input type='text' name='sp_unique_id' value="<?php echo $sp_unique_id;?>"></td>
		</tr>
		<tr>
			<td>Credit Card Number</td>
			<td><input type='text' name='credit_card_number' value="<?php echo $credit_card_number;?>"></td>
		</tr>
		<tr>
			<td>Cardholder Name</td>
			<td><input type='text' name='cardholder_name' value="<?php echo $cardholder_name;?>"></td>
		</tr>
		<tr>
			<td>Expiration Date</td>
			<td><input type='text' name='expiration_date' value="<?php echo $expiration_date;?>"></td>
		</tr>
		<tr>
			<td>Finance Charge Flag</td>
			<td><input type='text' name='finance_charge_flag' value="<?php echo $finance_charge_flag;?>"></td>
		</tr>
		<tr>
			<td>Late Notice Flag</td>
			<td><input type='text' name='late_notice_flag' value="<?php echo $late_notice_flag;?>"></td>
		</tr>
		<tr>
			<td>Federal Tax Exempt</td>
			<td><input type='text' name='federal_tax_exempt' value="<?php echo $federal_tax_exempt;?>"></td>
		</tr>
		<tr>
			<td>State Tax Exempt</td>
			<td><input type='text' name='state_tax_exempt' value="<?php echo $state_tax_exempt;?>"></td>
		</tr>
		<tr>
			<td>Local Tax Exempt</td>
			<td><input type='text' name='local_tax_exempt' value="<?php echo $local_tax_exempt;?>"></td>
		</tr>
		<tr>
			<td>Misc Tax Exempt</td>
			<td><input type='text' name='misc_tax_exempt' value="<?php echo $misc_tax_exempt;?>"></td>
		</tr>
		<tr>
			<td>Volume Discount Plan</td>
			<td><input type='text' name='volume_discount_plan' value="<?php echo $volume_discount_plan;?>"></td>
		</tr>
		<tr>
			<td>Flexbill Flag</td>
			<td><input type='text' name='flexbill_flag' value="<?php echo $flexbill_flag;?>"></td>
		</tr>
		<tr>
			<td>Floppy Detail Flag</td>
			<td><input type='text' name='floppy_detail_flag' value="<?php echo $floppy_detail_flag;?>"></td>
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