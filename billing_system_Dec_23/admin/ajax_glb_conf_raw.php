<?php
require_once("includes/config.php");
if(!empty($_POST["masterid"])){
$id=$_POST["masterid"];
$status=$_POST["rec_stat"];
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
							 <th align="center" nowrap  style="padding-left:5px;">Error Code</th>
							 <th align="center" nowrap  style="padding-left:5px;">Option</th>
                        </tr>';
						
		$sel_conf_sql=mysql_query("select * from tbl_conference_raw where master_id=".$id);
		if(mysql_num_rows($sel_conf_sql)>0){
			 $body='';
		while($disp_arr=mysql_fetch_assoc($sel_conf_sql)){
			 $body.="<tr style='background:#f6f6f6' id='brg_conf_".$disp_arr['pk_conference_id']."' onclick='funcajaxconf(\"".$disp_arr['pk_conference_id']."\")'><td><img src='images/expand.png' width='25px' id='brg_conf_img_".$disp_arr['pk_conference_id']."'></td>";
			 foreach($disp_arr as $key=>$ech_cnt){
				
				 if($key!='pk_conference_id' && $key!='created_date' && $key!='modified_date' && $key!='master_id')
					$body.="<td align='center'>".$ech_cnt."</td>";
			 }
			$body.="<td align='center'>RAW</td><td align='center'>-</td><td align='center'>-</td></tr><tr style='display:none' id='hidden_conf_".$disp_arr['pk_conference_id']."'><td></td><td colspan='20'>
										<ul>
											<li id='conf_row_".$disp_arr['pk_conference_id']."'></li>
										</ul></td></tr>";

		}
		echo $head.$body."</table>";
		}
}
?>