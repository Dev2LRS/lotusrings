<?php
require_once("includes/config.php");
if(!empty($_POST["conferenceid"])){
$id=$_POST["conferenceid"];
$cond='';
$status=$_POST["rec_stat"];
if($status!=""){
$cond=" and record_status='$status'";
}

$sel_conf=mysql_query("select * from tbl_bridge_raw where conference_pk_id='".$id."' $cond");

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
	if($q%2==0)
		$rowclr='#f6f6f6';
	else $rowclr='#EEF2FD';
	$row.="<tr style='background:$rowclr'>";
	foreach($get_arr as $key=>$eachcell){
		if($key!='created_date' and $key!='modified_date' and $key!='pk_bridge_id' and $key!='id' and $key!="master_id" and $key!="conference_pk_id")
		$row.='<td  align="center" nowrap>'.$eachcell.'</td>'; 
	}
	$row.="<td align='center'>RAW</td><td align='center'>-</td><td align='center'>-</td></tr>";
	$q++;
}
echo $head.$row."</table>";
}
}