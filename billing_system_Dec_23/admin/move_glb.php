<?php
require_once("includes/config.php");
?>
<div width="100%" height='300px' style='text-align:center'>
 <img src='images/processing.gif'  style='width:100px;height:100px;align:center' id="proc_img"><div style="display:none" id="msg"><p style="color:green;font-size:18px;">Process Completed</p></div>
</div>

<?php 

$chk_brd_rec=mysql_query("select `id`, `pk_bridge_id`, `master_id`, `conference_pk_id`, `bridge_type`, `service_provider_id`, `customer_id`, `conference_id`, `cal_detail_id`, `units`, `unit_of_measure`, `item_type`, `chargeable_item`, `charge_amount`, `currency`, `start_time`, `end_time`, `timezone`, `bridge_id`, `port_id`, `a_number`, `b_number`, `privacy_bit`, `participant_name`, `created_date`, `modified_date`, `record_status` from tbl_bridge_error where record_status='FIXED'");
if(mysql_num_rows($chk_brd_rec)>0){
	while($get_arr=mysql_fetch_assoc($chk_brd_rec)){
		$chk_brd_main=mysql_query("select * from tbl_bridge where master_id='".$get_arr["master_id"]."' and pk_bridge_id='".$get_arr["pk_bridge_id"]."'");
		if(mysql_num_rows($chk_brd_main)==0){
			$brd_ins=mysql_query("insert into tbl_bridge(`pk_bridge_id`, `master_id`, `conference_pk_id`, `bridge_type`, `service_provider_id`, `customer_id`, `conference_id`, `cal_detail_id`, `units`, `unit_of_measure`, `item_type`, `chargeable_item`, `charge_amount`, `currency`, `start_time`, `end_time`, `timezone`, `bridge_id`, `port_id`, `a_number`, `b_number`, `privacy_bit`, `participant_name`, `created_date`, `modified_date`, `record_status`) select `pk_bridge_id`, `master_id`, `conference_pk_id`, `bridge_type`, `service_provider_id`, `customer_id`, `conference_id`, `cal_detail_id`, `units`, `unit_of_measure`, `item_type`, `chargeable_item`, `charge_amount`, `currency`, `start_time`, `end_time`, `timezone`, `bridge_id`, `port_id`, `a_number`, `b_number`, `privacy_bit`, `participant_name`, `created_date`, `modified_date`, `record_status` from tbl_bridge_error where record_status='FIXED' and `pk_bridge_id`='".$get_arr["pk_bridge_id"]."'");
		}
	}
}


$chk_conf_rec=mysql_query("select `id`, `pk_conference_id`, `master_id`, `service_povider_id`, `customer_id`, `conference_id`, `authorization_string`, `first_touched_timestamp`, `resv_begin`, `resv_begin_timezone`, `resv_end`, `resv_end_timezone`, `invoice_ref`, `status`, `last_touched_timestamp`, `reserver`, `reserver_phone`, `reserved_total_lines`, `ra_master_id`, `access_number`, `access_code`, `created_date`, `modified_date`, `record_status` from tbl_conference_error where record_status='FIXED'");

if(mysql_num_rows($chk_conf_rec)>0){
	while($get_conf_arr=mysql_fetch_assoc($chk_conf_rec)){
		
		$chk_conf_main=mysql_query("select * from tbl_conference where master_id='".$get_conf_arr["master_id"]."' and pk_conference_id='".$get_conf_arr["pk_conference_id"]."'");
		if(mysql_num_rows($chk_conf_main)==0){
			
			$conf_ins=mysql_query("insert into tbl_conference(`pk_conference_id`, `master_id`, `service_povider_id`, `customer_id`, `conference_id`, `authorization_string`, `first_touched_timestamp`, `resv_begin`, `resv_begin_timezone`, `resv_end`, `resv_end_timezone`, `invoice_ref`, `status`, `last_touched_timestamp`, `reserver`, `reserver_phone`, `reserved_total_lines`, `ra_master_id`, `access_number`, `access_code`, `created_date`, `modified_date`, `record_status`) select `pk_conference_id`, `master_id`, `service_povider_id`, `customer_id`, `conference_id`, `authorization_string`, `first_touched_timestamp`, `resv_begin`, `resv_begin_timezone`, `resv_end`, `resv_end_timezone`, `invoice_ref`, `status`, `last_touched_timestamp`, `reserver`, `reserver_phone`, `reserved_total_lines`, `ra_master_id`, `access_number`, `access_code`, `created_date`, `modified_date`, `record_status` from tbl_conference_error where record_status='FIXED' and pk_conference_id='".$get_conf_arr["pk_conference_id"]."'");

		}
}
}


$chk_cust_rec=mysql_query("select `id`, `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`, `created_date`, `modified_date`, `record_status` from tbl_customer_error where record_status='FIXED'");


if(mysql_num_rows($chk_cust_rec)>0){
	while($get_cus_arr=mysql_fetch_assoc($chk_cust_rec)){
		$chk_cust_main=mysql_query("select * from tbl_customer where pk_customer_id='".$get_cus_arr["pk_customer_id"]."'");
		if(mysql_num_rows($chk_cust_main)==0){
			
			$cust_ins=mysql_query("insert into tbl_customer(`pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`, `created_date`, `modified_date`, `record_status`) select `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`, `created_date`, `modified_date`, `record_status` from tbl_customer_error where record_status='FIXED' and pk_customer_id='".$get_cus_arr["pk_customer_id"]."'");

		}
	}
}
?>

<script>
setTimeout(function(){
document.getElementById("proc_img").style.display="none";
document.getElementById("msg").style.display="";
},1000);

</script>