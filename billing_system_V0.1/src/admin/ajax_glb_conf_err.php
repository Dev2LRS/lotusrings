<?php
require_once("includes/config.php");
if(!empty($_POST["conferenceid"])){
$id=$_POST["conferenceid"];
$status=$_POST["rec_stat"];
$sel_conf=mysql_query("select * from tbl_conference_error where master_id='".$id."'");
$head='<table style="font-size:12px;width:100%"><tr style="background-color:#f5fd99;">
							<th width="25px"></th>
                            <th align="center" nowrap style="padding-left:5px;">Service Provider Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Customer Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Conference Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Authorization String</th>
                            <th align="center" nowrap  style="padding-left:5px;">First Touched Timestamp</th>
                            <th align="center" nowrap  style="padding-left:5px;">Resv Begin</th>
                            <th align="center" nowrap  style="padding-left:5px;">Resv Begin Timezone</th>
                            <th align="center" nowrap  style="padding-left:5px;">Resv End</th>
                            <th align="center" nowrap  style="padding-left:5px;">Resv End Timezone</th>
                            <th align="center" nowrap  style="padding-left:5px;">Invoice Ref</th>
                            <th align="center" nowrap  style="padding-left:5px;">Status</th>
                            <th align="center" nowrap  style="padding-left:5px;">Last Touched Timestamp</th>
                            <th align="center" nowrap  style="padding-left:5px;">Reserver</th>
                            <th align="center" nowrap  style="padding-left:5px;">Reserver Phone</th>
                            <th align="center" nowrap  style="padding-left:5px;">Reserved Total Lines</th>
                            <th align="center" nowrap  style="padding-left:5px;">Ra Master Id</th>
                            <th align="center" nowrap  style="padding-left:5px;">Access Number</th>
                            <th align="center" nowrap  style="padding-left:5px;">Access Code</th>
							<th align="center" nowrap  style="padding-left:5px;">Record Status</th>
                        </tr>';
if(mysql_num_rows($sel_conf)>0){
while($get_arr=mysql_fetch_assoc($sel_conf)){
	if($q%2==0)
		$rowclr='#f6f6f6';
	else $rowclr='#EEF2FD';
	$row.="<tr style='background:$rowclr' id='brg_conf_err".$get_arr['pk_conference_id']."' onclick='funcajaxerrconf(\"".$get_arr['pk_conference_id']."\")'><td><img src='images/expand.png' width='25px' id='brg_conf_img_".$get_arr['pk_conference_id']."'></td>";
	foreach($get_arr as $key=>$eachcell){
		if($key!='created_date' and $key!='modified_date' and $key!='pk_conference_id' and $key!='id' and $key!="master_id")
		$row.='<td  align="center" nowrap>'.$eachcell.'</td>'; 
	}
	$row.="</tr>
			<tr style='display:none' id='hidden_conf_err".$get_arr['pk_conference_id']."'><td></td><td colspan='20'>
										<ul>
											<li id='conf_row_err".$get_arr['pk_conference_id']."'></li>
										</ul></td></tr>";
	$q++;
}
echo $head.$row."</table>";
}else 
	echo $head."<tr id='brg_conf_err".$id."' onclick='funcajaxerrconf(\"".$id."\")'><td><img src='images/expand.png' width='25px' id='brg_conf_img_err".$id."'></td><td colspan='20'>No Error Record</td></tr><tr style='display:none' id='hidden_conf_err".$id."'><td></td><td colspan='20'>
										<ul>
											<li id='conf_row_err".$id."'></li>
										</ul></td></tr><table>";
}

?>
