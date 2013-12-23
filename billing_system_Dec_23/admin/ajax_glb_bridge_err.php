<?php
require_once("includes/config.php");
if(!empty($_REQUEST["conferenceid"])){
$id=$_REQUEST["conferenceid"];
$cond='';
$lessfour=false;
$displayed=array();

$get_ths_conf=mysql_query("select * from tbl_bridge_raw where conference_pk_id=$id order by pk_bridge_id");
$tot_conf=mysql_num_rows($get_ths_conf);
$n=1;
$conf_border=array();
while($get_confc_arr=mysql_fetch_array($get_ths_conf)){
	if(mysql_num_rows($get_ths_conf)>4){
		if(($n%4)==0){
			$conf_border[]=$get_confc_arr['pk_bridge_id'];
		}
		$n++;
	}else{
		$limit_border=$get_confc_arr['pk_bridge_id'];
		$lessfour=true;
	}
	
}
if($lessfour===true)
	$conf_border[]=$limit_border;
if(($tot_conf%4)!=0 && mysql_num_rows($get_ths_conf)>4){
	$remd=($tot_conf%4);
	$end_val=end($conf_border);
	$conf_bord=$end_val+$remd;
	$conf_border[]=$conf_bord;
}
$status=$_POST["rec_stat"];
if($status!=""){
$cond=" and record_status='$status'";
}
$sel_conf=mysql_query("select * from tbl_bridge_error where conference_pk_id='".$id."' $cond");
$head='<table style="font-size:11px;width:100%"><tr style="background-color:#dbd2ff;">			
                            <th align="center" nowrap style="padding-left:5px;">Bridge Type</th>
                            <th align="center" nowrap  style="padding-left:5px;">Service Provider Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Customer Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Conference Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Cal Detail Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Units</th>
                            <th align="center" nowrap  style="padding-left:5px;">Unit Of Measure</th>
                            <th align="center" nowrap  style="padding-left:5px;">Item Type</th>
                            <th align="center" nowrap  style="padding-left:5px;">Chargeable Item</th>
                            <th align="center" nowrap  style="padding-left:5px;">Charge Amount</th>
                            <th align="center" nowrap  style="padding-left:5px;">Currency</th>
                            <th align="center" nowrap  style="padding-left:5px;">Start Time</th>
                            <th align="center" nowrap  style="padding-left:5px;">End Time</th>
                            <th align="center" nowrap  style="padding-left:5px;">Timezone</th>
                            <th align="center" nowrap  style="padding-left:5px;">Bridge Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Port Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">A Number</th>
                            <th align="center" nowrap  style="padding-left:5px;">B Number</th>
							<th align="center" nowrap  style="padding-left:5px;">Privacy Bit</th>
							<th align="center" nowrap  style="padding-left:5px;">Participant Name</th>
							<th align="center" nowrap  style="padding-left:5px;">Record Status</th>
							<th align="center" nowrap  style="padding-left:5px;">Error Code</th>
							<th align="center" nowrap  style="padding-left:5px;">Option</th>
                        </tr>';
if(mysql_num_rows($sel_conf)>0){
while($get_arr=mysql_fetch_assoc($sel_conf)){
	$bridge_ID_arr[]=$get_arr['pk_bridge_id'];
}
foreach($bridge_ID_arr as $bridge_ID){
	for($t=0;$t<=count($conf_border);$t++){
		if(($conf_border[$t]-$bridge_ID)<4 && ($conf_border[$t]-$bridge_ID)>=0){
			$main_brd[$bridge_ID]=$conf_border[$t];
		}
	}
}
//print_r($conf_border);
$chk_inarr=array();
	foreach($bridge_ID_arr as $bridge_ID){
				if(trim($bridge_ID)!=""){
					$border=$main_brd[$bridge_ID];
					if(!in_array($border,$displayed)){
						$displayed[]=$border;
					for($g=3;$g>=0;$g--){
						if(!in_array(($border-$g),$chk_inarr)):
							$raw=false;
							//echo "select * from tbl_bridge_error where pk_bridge_id=($border-$g) and conference_pk_id=$id";
							$chk_inarr[]=($border-$g);
							$get_all_brigs=mysql_query("select * from tbl_bridge_error where pk_bridge_id=($border-$g) and conference_pk_id=$id");
							if(mysql_num_rows($get_all_brigs)==0){
								$get_all_brigs=mysql_query("select * from tbl_bridge_raw where pk_bridge_id=($border-$g) and conference_pk_id=$id");
								$raw=true;
							}
							if($g%2==0)
								$rowclr='#f6f6f6';
							else $rowclr='#EEF2FD';
							if(mysql_num_rows($get_all_brigs)>0){
								$row.="<tr style='background:$rowclr'>";
							while($get_arr=mysql_fetch_array($get_all_brigs)){
								$row.='<td  align="center" nowrap>'.$get_arr['bridge_type'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['service_provider_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['customer_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['conference_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['cal_detail_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['units'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['unit_of_measure'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['item_type'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['chargeable_item'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['charge_amount'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['currency'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['start_time'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['end_time'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['timezone'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['bridge_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['port_id'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['a_number'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['b_number'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['privacy_bit'].'</td>';
								$row.='<td  align="center" nowrap>'.$get_arr['participant_name'].'</td>';
								if($raw===false){
									$row.='<td  align="center" nowrap>'.$get_arr['record_status'].'</td>';
									$row.='<td  align="center" nowrap>'.$get_arr['error_code'].'</td>';
									$row.="<td align='center'><a href='edit_glb_brg_err.php?errorid=".$get_arr['pk_bridge_id']."&redto=yes' onclick='return funcalert()' title='Edit Bridge details'>Edit</a></td></tr>";
								}else{
									$row.='<td  align="center" nowrap>RAW</td>';
									$row.='<td  align="center" nowrap>-</td>';
									$row.="<td align='center'>-</td></tr>";
								}
								$q++;
							}
						}
						endif;
					}
				
			
		}
	}
}


echo $head.$row."</table>";
}
}
//print_r($conf_border);
?>
