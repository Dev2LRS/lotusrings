<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
$filter_cond='1=1';
$CHK_FLT='';
$WhereQuery='';
$RecordsPerPage=15;
$Page=1;
if(isset($_REQUEST['HdnPage']) && is_numeric($_REQUEST['HdnPage']))
  $Page=$_REQUEST['HdnPage'];
else if(isset($_GET["pgnum"]))
  $Page=$_GET["pgnum"];

if(!is_numeric($Page))
$Page=1;
$chk_inarr=array();
$master_arr=array();
$filter_cond='1=1';
$WhereQuery='';
$sts_cond=$CHK_FLT='';
$instatusfilt=false;
$Unq_cond=$brd_qry =' 1=1 ';
$selvalue='1=1';
$selvalue=' record_status="FIXED" or record_status="MANUAL_FIX"';
if(isset($_POST['hdn_search']) && $_POST['hdn_search']=='hdn_val'){
		if(isset($_POST['status']) && $_POST['status']!=''){
			$selvalue=' record_status="'.$_POST['status'].'"';
			$CHK_FLT=$_POST['status'];
			$get_mast_brgsql=mysql_query("select DISTINCT master_id from tbl_bridge_error where $selvalue");
			if(mysql_num_rows($get_mast_brgsql)>0){
				while($get_mast_brg_arr=mysql_fetch_assoc($get_mast_brgsql)){
					$master_arr_sts[]=$get_mast_brg_arr["master_id"];
				}
			}
			$get_mast_confsql=mysql_query("select DISTINCT master_id from tbl_conference_error where $selvalue");
			if(mysql_num_rows($get_mast_confsql)>0){
				while($get_mast_conf_arr=mysql_fetch_assoc($get_mast_confsql)){
					$master_arr_sts[]=$get_mast_conf_arr["master_id"];
				}
			}
			$get_mast_Qry=mysql_query("select DISTINCT master_id from tbl_bridge_error where $selvalue");
				if(mysql_num_rows($get_mast_Qry)>0){
						while($mast_Arr=mysql_fetch_array($get_mast_Qry)){
							$master_arr_sts[]=$mast_Arr['master_id'];
						}
				}
				$sts_cond=" and master_id in(".implode(',',$master_arr_sts).")";
		}
		if (empty($_REQUEST['startdate']) === false) {
			 $StartDate = $_REQUEST['startdate'];
			$nodtfilter=true;
			 $brd_qry .= " AND start_time >= '" . date('Y-m-d H:i:s', strtotime($StartDate." 00:00:00")) . "'";
		}
		if (empty($_REQUEST['enddate']) === false) {
			$EndDate = $_REQUEST['enddate'];
			$nodtfilter=true;
			$brd_qry.= " AND start_time <= '" . date('Y-m-d H:i:s', strtotime($EndDate." 23:59:59")) . "'";
		}
			

		if(!empty($brd_qry)){
			
			$get_mast_Qry=mysql_query("select * from tbl_bridge_error where $brd_qry $sts_cond");
			if(mysql_num_rows($get_mast_Qry)>0){
					while($mast_Arr=mysql_fetch_array($get_mast_Qry)){
						$master_arr[]=$mast_Arr['master_id'];
					}
					
			}
		}
	}else if(empty($_REQUEST['startdate']) && empty($_REQUEST['enddate'])) {
		$get_mast_brgsql=mysql_query("select master_id from tbl_bridge_error where $selvalue");
		if(mysql_num_rows($get_mast_brgsql)>0){
			while($get_mast_brg_arr=mysql_fetch_assoc($get_mast_brgsql)){
				$master_arr[]=$get_mast_brg_arr["master_id"];
			}
		}
		
		$get_mast_confsql=mysql_query("select master_id from tbl_conference_error where $selvalue");
		if(mysql_num_rows($get_mast_confsql)>0){
			while($get_mast_conf_arr=mysql_fetch_assoc($get_mast_confsql)){
				$master_arr[]=$get_mast_conf_arr["master_id"];
			}
		}
		$get_mastersql=mysql_query("select pk_customer_id from tbl_customer_error where $selvalue");
		if(mysql_num_rows($get_mastersql)>0){
			while($get_mast_arr=mysql_fetch_assoc($get_mastersql)){
				$master_arr[]=$get_mast_arr["pk_customer_id"];
			}
		}
	}

	
if (empty($WhereQuery) === true)
	$WhereQuery= " AND 1=1";
	if(count($master_arr)>0){
		$unq_master=array_unique($master_arr);
		$filter_cond=" pk_customer_id in (".implode(",",$unq_master).") $WhereQuery";
		$Unq_cond=" pk_customer_id in (".implode(",",$unq_master).")";
	}

require_once("header.php");

?>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td class="red1" height="20" align="left"></td>
	</tr>
	<tr>
		<td class="red1" align="left" ><h2>Global Error Records Comparision</h2></td>
	 </tr>
	<tr>
		 <td height="200" align="left" valign="top" class="text" >
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="listTable">
		<tr>
			<td align="left" valign="top"></td>
			<td align="left" valign="top">
				<form name="err_brg_form" id="err_brg_form" method="POST">
				<input type="hidden" name="hdn_search" value="hdn_val">
				 <input type="hidden" name="HdnPage" id="HdnPage" value="<?php echo $Page; ?>">   
				 <input type="hidden" name="HdnMode" id="HdnMode" value="<?php echo $Page; ?>">
					<table width="100%"class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;"> 
                       <tr>
							<th height="25" align="left"  style="background-color:#CAE1F9;">
                                            &nbsp;Search
                            </th>
                        </tr>
                        <tr>
                            <td align="center">
                                <table class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0"> 
                                     <tr> <td colspan="6" height="15"></td></tr>
                                     <tr>
                                        <td  align="left"  nowrap>&nbsp;Start Date:</td>
                                        <td  align="left"><input type="text" name="startdate" id="startdate" value="<?php echo $StartDate;?>" readonly/></td>  

                                        <td  align="left"  nowrap>&nbsp;End Date:</td>
                                        <td  align="left"> <input type="text" name="enddate" id="enddate" value="<?php echo $EndDate;?>" readonly/></td>  
                                        
										<td  align="left"  nowrap>&nbsp;Status:</td>
                                        <td  align="left">&nbsp;
											<select name='status' id='status'>
												<option value=''>-Select-</option>
												<option value='FIXED' <?php if($CHK_FLT=='FIXED') echo 'selected';?>>Fixed</option>
												<option value='MANUAL_FIX' <?php if($CHK_FLT=='MANUAL_FIX') echo 'selected';?>>Manual Fix</option>
											</select>
										</td>
                                    </tr>
                                   
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>                                        
                                        <td  align="center" colspan="6">&nbsp;<input type="submit" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='glb_err_cmp.php'"/></td>
                                    </tr>
									<tr height='10px'>
										<td></td>
									</tr>
                                </table>
                            </td>                             
                        </tr>                       
                  </table>
			</form>
		</td>
	</tr>
	</table>
	
                  <div class="space20"></div>
                  <div class="rec-listview">                      
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                        <tr style="background-color:#CAE1F9;">
							<th width='50px'></th>
							<th align="center" nowrap style="padding-left:5px;"  width="140"  nowrap>Service Povider Id</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Customer Id</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Customer Name</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Organization Id</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Subaccount Id</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Subaccount Name</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="120"  nowrap>Chairperson Id</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="140"  nowrap>Chairperson Name</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="140"  nowrap>Chairperson Phone</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="130"  nowrap>Account Type</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="130"  nowrap>Address1</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100"  nowrap>Address2</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100"  nowrap>Address3</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100"  nowrap>City Name</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100" nowrap>Country Name</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100" nowrap>State Code</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="100">Zip</th>
                            <th align="center" nowrap  style="padding-left:5px;"  width="130"  nowrap>Country Code</th>
							<th align="center" nowrap  style="padding-left:5px;"  width="130" nowrap>Anniversary Date</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Account Status</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Sales Code</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Payment Type</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Wholesale Unique Id</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Sp Unique Id</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Credit Card Number</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Cardholder Name</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Expiration Date</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Finance Charge Flag</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Late Notice Flag</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Federal Tax Exempt</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>State Tax Exempt</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Local Tax Exempt</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Misc Tax Exempt</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Volume Discount Plan</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Flexbill Flag</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Floppy Detail Flag</th>
							<th align="center" nowrap  style="padding-left:5px;"  nowrap>Record Status</th>
							<th align="center" nowrap  style="padding-left:5px;">Error Code</th>
							<th align="center" nowrap  style="padding-left:5px;"  width="70">Option</th>
                        </tr>
						<?php
							$q=1;
							if($filter_cond!='1=1'){

							$Get_SelQry=mysql_query("SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`,`record_status`,`error_code` FROM `tbl_customer_error` where $filter_cond union SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`, '' AS record_status, '' AS error_code FROM `tbl_customer_raw` where  $Unq_cond");
							$intUsrCount=mysql_num_rows($Get_SelQry);
							 $TotalPages=ceil($intUsrCount/$RecordsPerPage);
                              $Start=($Page-1)*$RecordsPerPage;
							  $SelQry=mysql_query("SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`, '' AS record_status, '' AS error_code FROM `tbl_customer_raw` where  $Unq_cond union SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`,`record_status`,`error_code` FROM `tbl_customer_error` where $filter_cond  order by pk_customer_id limit $Start, $RecordsPerPage");

							 
							if(mysql_num_rows($SelQry)>0){
								while($get_arr=mysql_fetch_assoc($SelQry)){
									$noedit=false;
									if($q%2==0)
										$rowclr='#f6f6f6';
									else $rowclr='#EEF2FD';
									if(!in_array($get_arr["pk_customer_id"],$chk_inarr,true)){
										$chk_inarr[]=$get_arr["pk_customer_id"];
									echo "<tr style='background:$rowclr' id='brg_row_".$get_arr['pk_customer_id']."' onclick='funcajaxsh(\"".$get_arr['pk_customer_id']."\")'><td><img src='images/expand.png' width='30px' id='brg_row_img_".$get_arr['pk_customer_id']."'></td>";
									foreach($get_arr as $key=>$eachcell){
										if($key!='created_date' and $key!='modified_date' and $key!='pk_customer_id' and $key!='id' and $key!='record_status' and $key!='error_code')
											echo '<td  align="center" nowrap>'.$eachcell.'</td>'; 
										if($key=='record_status'){
											if(trim($eachcell)!="")
												echo '<td  align="center" nowrap>'.$eachcell.'</td>'; 
											else{
												echo '<td  align="center" nowrap>RAW</td>'; 
												$noedit=TRUE;
											}
										}
											
										if($key=='error_code'){
											if(trim($eachcell)!="")
												echo '<td  align="center" nowrap>'.$eachcell.'</td>'; 
											else
												echo '<td  align="center" nowrap>-</td>'; 

										}
									}
									if(($get_arr["record_status"]=="FIXED") || ($get_arr["record_status"]=="MANUAL_FIX"))
										echo "<td align='center'></td></tr><tr style='display:none' id='hidden_row_".$get_arr['pk_customer_id']."'><td></td><td  colspan='38'>
										<ul>
											<li id='cust_row_".$get_arr['pk_customer_id']."'></li>
										</ul>
										</td>";
									else
										echo "<td align='center' ></td></tr><tr style='display:none' id='hidden_row_".$get_arr['pk_customer_id']."'><td></td><td  colspan='38'>
										<ul>
											<li id='cust_row_".$get_arr['pk_customer_id']."'></li>
										</ul></td>";
										
										echo "</tr><tr style='background:#f587a8' id='brg_row_err".$get_arr['pk_customer_id']."' onclick='funcajaxerrsh(\"".$get_arr['pk_customer_id']."\")'><td><img src='images/expand.png' width='30px' id='brg_row_img_err".$get_arr['pk_customer_id']."'></td>";

								
									$SelError_qry=mysql_query("SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`,`record_status`,`error_code` FROM `tbl_customer_error` where pk_customer_id=".$get_arr["pk_customer_id"]." union SELECT `pk_customer_id`, `service_povider_id`, `customer_id`, `customer_name`, `organization_id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local_tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill_flag`, `floppy_detail_flag`,'' AS record_status,'' AS error_code FROM `tbl_customer_raw` where pk_customer_id=".$get_arr["pk_customer_id"]);
									
									$error_arr=mysql_fetch_assoc($SelError_qry);
									foreach($error_arr as $e_key=>$err_cell){
										if($e_key!="id" && $e_key!="created_date" && $e_key!="modified_date" && $e_key!="pk_customer_id" && $e_key!="record_status" && $e_key!="error_code")
											echo "<td  align='center' nowrap>".$err_cell."</td>";
										if($e_key=='record_status'){
											if(trim($err_cell)!="")
												echo '<td  align="center" nowrap>'.$err_cell.'</td>'; 
											else{
												echo '<td  align="center" nowrap>RAW</td>'; 
												$noedit=TRUE;
											}
										}
											
										if($e_key=='error_code'){
											if(trim($err_cell)!="")
												echo '<td  align="center" nowrap>'.$err_cell.'</td>'; 
											else
												echo '<td  align="center" nowrap>-</td>'; 

										}
									}
									if($error_arr["record_status"]=="FIXED" || $error_arr["record_status"]=="MANUAL_FIX")
										echo "<td align='center'><a href='edit_glb_cust_err.php?errorid=".$get_arr['pk_customer_id']."&redto=yes' onclick='return funcalert()' title='Edit Customer Details'>Edit</a></td></tr><tr style='display:none' id='hidden_row_err".$get_arr['pk_customer_id']."'><td></td><td  colspan='38'>
										<ul>
											<li id='cust_row_err".$get_arr['pk_customer_id']."'></li>
										</ul>
									
									</td></tr>";
									else
										echo "<td align='center'>-</td></tr><tr style='display:none' id='hidden_row_err".$get_arr['pk_customer_id']."'><td></td><td  colspan='38'>
										<ul>
											<li id='cust_row_err".$get_arr['pk_customer_id']."'></li>
										</ul>
									
									</td></tr>";
									$q++;
								}
								}
							}else echo '<tr><td colspan="33">No records found<td><tr>';
						}else echo '<tr><td colspan="33">No records found<td><tr>';
						?>
						</table>
						</div>
	</td>
	</tr>
	<tr>
	<td >
		<table style="margin:auto">
		 <?php 
			  if($TotalPages > 1)
			  {
				echo "<tr><td align='center' colspan='8' valign='middle' class='pagination'>";
				if($TotalPages>1)
				{
					$FormName = "err_brg_form";
					require_once ("paging.php");
				}
				echo "</td></tr>";
			  }

			?>
		</table>
	</td>
</tr>
</table>
<script>
 $(function() {
        $( "#startdate,#enddate" ).datepicker();
    });

	function funcstatus(){
		$("#err_brg_form").submit();			

	}
function funcalert(){
		if(confirm("Are you sure want to edit this record?"))
			return true;
		else return false;
}

function funcajaxsh(custid){
	var hiddenrow='hidden_row_'+custid;
	var hiddenconfrow='cust_row_'+custid;
	document.getElementById("brg_row_img_"+custid).src="images/collapse.png";
	var e = document.getElementById("status");
	var status = e.options[e.selectedIndex].value;
	if(document.getElementById(hiddenrow).style.display=='none'){
	$.ajax({
	  type: "POST",
	  url: "ajax_glb_conf_raw.php",
	  data: { masterid: custid, rec_stat:status, redto:'yes'}
	})
	  .done(function( msg ) {
		document.getElementById(hiddenconfrow).innerHTML=msg;
		document.getElementById(hiddenrow).style.display='';
	  });
	}else{
		document.getElementById("brg_row_img_"+custid).src="images/expand.png";
		document.getElementById(hiddenrow).style.display='none';
	}
}
function funcajaxconf(confid){
var hiddenrow='hidden_conf_'+confid;
	var hiddenconfrow='conf_row_'+confid;
	document.getElementById("brg_conf_img_"+confid).src="images/collapse.png";
	var e = document.getElementById("status");
	var status = e.options[e.selectedIndex].value;
	if(document.getElementById(hiddenrow).style.display=='none'){
	$.ajax({
	  type: "POST",
	  url: "ajax_glb_bridge_raw.php",
	  data: { conferenceid: confid, rec_stat:status, redto:'yes'}
	})
	  .done(function( msg ) {
		document.getElementById(hiddenconfrow).innerHTML=msg;
		document.getElementById(hiddenrow).style.display='';
	  });
	}else{
			document.getElementById("brg_conf_img_"+confid).src="images/expand.png";
			document.getElementById(hiddenrow).style.display='none';
	}
}

function funcajaxerrsh(custid){
	var hiddenrow='hidden_row_err'+custid;
	var hiddenconfrow='cust_row_err'+custid;
	document.getElementById("brg_row_img_err"+custid).src="images/collapse.png";
	var e = document.getElementById("status");
	var status = e.options[e.selectedIndex].value;
	if(document.getElementById(hiddenrow).style.display=='none'){
	$.ajax({
	  type: "POST",
	  url: "ajax_glb_conf_err.php",
	  data: { conferenceid: custid, rec_stat:status}
	})
	  .done(function( msg ) {
		document.getElementById(hiddenconfrow).innerHTML=msg;
		document.getElementById(hiddenrow).style.display='';
	  });
	}else{
		document.getElementById("brg_row_img_err"+custid).src="images/expand.png";
		document.getElementById(hiddenrow).style.display='none';
	}
}
function funcajaxerrconf(confid){
var hiddenrow='hidden_conf_err'+confid;
	var hiddenconfrow='conf_row_err'+confid;
	document.getElementById("brg_conf_img_err"+confid).src="images/collapse.png";
	var e = document.getElementById("status");
	var status = e.options[e.selectedIndex].value;
	if(document.getElementById(hiddenrow).style.display=='none'){
	$.ajax({
	  type: "POST",
	  url: "ajax_glb_bridge_err.php",
	  data: { conferenceid: confid, rec_stat:status}
	})
	  .done(function( msg ) {
		document.getElementById(hiddenconfrow).innerHTML=msg;
		document.getElementById(hiddenrow).style.display='';
	  });
	}else{
			document.getElementById("brg_conf_img_err"+confid).src="images/expand.png";
			document.getElementById(hiddenrow).style.display='none';
	}


}
</script>

<?php
	require_once("footer.php");
?>