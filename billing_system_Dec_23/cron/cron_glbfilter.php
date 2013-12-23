<?php
set_time_limit(0);
include('includes/config.php');
include('includes/functions.php');
$get_error_res=array();
$conf_chk_lst=array();
$conf_chk_lst=array();

$chk_last_ins=mysql_query("SELECT * FROM tbl_customer_proc_rec ORDER BY id DESC LIMIT 1");
if(mysql_num_rows($chk_last_ins)>0){
	$cus_chk_lst=mysql_fetch_assoc($chk_last_ins);
}else
	$cus_chk_lst['raw_rec_id']=0;
if($cus_chk_lst['raw_rec_id']==NULL)
	$cus_chk_lst['raw_rec_id']=0;


$chk_last_conf=mysql_query("SELECT * FROM tbl_conference_proc_rec ORDER BY id DESC LIMIT 1");
if(mysql_num_rows($chk_last_conf)){
	$conf_chk_lst=mysql_fetch_assoc($chk_last_conf);
}else
	$conf_chk_lst['raw_rec_id']=0;

if($conf_chk_lst['raw_rec_id']==NULL)
	$conf_chk_lst['raw_rec_id']=0;

$chk_lst_brg=mysql_query("SELECT * FROM tbl_bridge_proc_rec ORDER BY id DESC LIMIT 1");
if(mysql_num_rows($chk_lst_brg)>0){
	$brg_chk_lst=mysql_fetch_assoc($chk_lst_brg);
}else
	$brg_chk_lst['raw_rec_id']=0;
if($brg_chk_lst['raw_rec_id']==NULL)
	$brg_chk_lst['raw_rec_id']=0;

//***********CUSTOMER TABLE***********//

$sel_chairprsn=mysql_query("select * from tbl_customer_raw where pk_customer_id > ".$cus_chk_lst['raw_rec_id']);
if(mysql_num_rows($sel_chairprsn)>0){
while($get_cus_arr=mysql_fetch_assoc($sel_chairprsn)){
	$final_cust=array();
	$error_cust_cd=array();
	$status_cust=array();
	$get_sts_cust=array();
	$ins_err_cust=array();
	$column_nms_cust=array();
	$values_cust=array();
	$max_cus_id=$get_cus_arr['pk_customer_id'];
	foreach($get_cus_arr as $key=>$each_cus_cell){
		$ins_err_cust[]=$each_cus_cell;
		/*****************Check if chairperson_name ans other srting fields contains (')*******************/
		if($key=='chairperson_name'){
			if(strpos($each_cus_cell,"'")!=FALSE){
				$each_cus_cell=str_replace("'","",$each_cus_cell);
				$final_cust[$key]=$each_cus_cell;
				$error_cust_cd[$key][]=func_error_code(7);
				$status_cust[$key][]=func_status(1);
			}else
				$final_cust[$key]=$each_cus_cell;
		}else if(is_string($each_cus_cell)){
				if(strpos($each_cus_cell,"'")!=FALSE){
					$final_cust[$key]=$each_cus_cell;
					$error_cust_cd[$key][]=func_error_code(7);
					$status_cust[$key][]=func_status(2);
				}else
					$final_cust[$key]=$each_cus_cell;
		}else
			$final_cust[$key]=$each_cus_cell;

	}
		if(count($status_cust)>0)
			$get_sts_cust=stat_to_arr($status_cust);
		else 
			$get_sts_cust[]='PROCESSED';
		$final_cust['record_status']=get_stat_code($get_sts_cust);
		$column_nms_cust=array_keys($final_cust);
		$values_cust=array_values($final_cust);
		$data_val_cust='"'.implode('","',$values_cust).'"';
		if($final_cust['record_status']=='PROCESSED'){
			$ins_filt_cust=mysql_query('insert into tbl_customer('.implode(",",$column_nms_cust).') values('.$data_val_cust.')');
			if(!$ins_filt_cust){
				$get_error_res[]="Error while inserting into tbl_customer_filtered table ".$data_val_cust;
			
			}
		}

		if(count($error_cust_cd)>0){
		$final_cust['record_status']=get_stat_code($get_sts_cust);
		$get_main_cust='"'.implode('","',$final_cust).'"';
		$ins_error_cust_cd=mysql_query('insert into tbl_customer_error('.implode(",",$column_nms_cust).') values('.$get_main_cust.')');
		$updt_err_id=mysql_insert_id();
		$get_impl_cust=array_implode('',',',$error_cust_cd);
		
		$updt_err_code=mysql_query('update tbl_customer_error set error_code="'.$get_impl_cust.'"where id='.$updt_err_id);
			if(!$ins_error_cust_cd)
				$get_error_res[]="Error while inserting into tbl_customer_error table ".$data_val_cust;
		}
}
$ins_prco_cus_sql=mysql_query("insert into tbl_customer_proc_rec(raw_rec_id, added_date) values($max_cus_id,now())");
}


//**********Conference Table**************//
$sel_conf=mysql_query("select * from tbl_conference_raw where pk_conference_id > ".$conf_chk_lst['raw_rec_id']);
if(mysql_num_rows($sel_conf)>0){
	while($get_conf_arr=mysql_fetch_assoc($sel_conf)){
		$final_conf=array();
		$error_conf_cd=array();
		$status_conf=array();
		$get_sts_conf=array();
		$ins_err_conf=array();
		$column_nms_conf=array();
		$values_conf=array();
		$max_conf_id=$get_conf_arr["pk_conference_id"];
		foreach($get_conf_arr as $key=>$each_conf_cell){
			$ins_err_conf[]=$each_conf_cell;
			/**************************If access code field is empty*************************/
			if($key=='access_code'){
				if(trim($each_conf_cell)==""){
					$final_conf[$key]=$each_conf_cell;
					$error_conf_cd[$key][]=func_error_code(11);
					$status_conf[$key][]=func_status(2);
				}else
					$final_conf[$key]=$each_conf_cell;
			}else
				$final_conf[$key]=$each_conf_cell;
		}
	
		if(count($status_conf)>0)
			$get_sts_conf=stat_to_arr($status_conf);
		else 
			$get_sts_conf[]='PROCESSED';
		$final_conf['record_status']=get_stat_code($get_sts_conf);
		$column_nms_conf=array_keys($final_conf);
		$values_conf=array_values($final_conf);
		$data_val_conf='"'.implode('","',$values_conf).'"';
		if($final_conf['record_status']=='PROCESSED'){
			$ins_filt_conf=mysql_query('insert into tbl_conference('.implode(",",$column_nms_conf).') values('.$data_val_conf.')');
			if(!$ins_filt_conf){
				$get_error_res[]="Error while inserting into tbl_conference table ".$data_val_conf;
			}
		}

		if(count($error_conf_cd)>0){
		$final_conf['record_status']=get_stat_code($get_sts_conf);
		$get_main_conf='"'.implode('","',$final_conf).'"';
		$ins_error_conf_cd=mysql_query('insert into tbl_conference_error('.implode(",",$column_nms_conf).') values('.$get_main_conf.')');
		$updt_err_id=mysql_insert_id();
		$get_impl_conf=array_implode('',',',$error_conf_cd);
		$updt_err_code=mysql_query('update tbl_conference_error set error_code="'.$get_impl_conf.'"where id='.$updt_err_id);
		if(!$ins_error_conf_cd)
			$get_error_res[]="Error while inserting into tbl_conference_error table ".$data_val_conf;
			}
	}
	$ins_prco_conf_sql=mysql_query("insert into tbl_conference_proc_rec(raw_rec_id, added_date) values($max_conf_id,now())");
}





//************Bridge Table**************//
$sel_brg=mysql_query("select * from tbl_bridge_raw where pk_bridge_id > ".$brg_chk_lst['raw_rec_id']);
if(mysql_num_rows($sel_brg)>0){
	$cnt=1;
while($get_brg_arr=mysql_fetch_assoc($sel_brg)){
	$status_brg=array();
	$final_brg=array();
	$error_brg=array();
	$column_nms_brg=array();
	$values_brg=array();
	$ins_err_brg=array();
	$get_sts_brg=array();
	$max_brg_id=$get_brg_arr["pk_bridge_id"];
	foreach($get_brg_arr as $key=>$each_brg_cell){
		$ins_err_brg[$key]=$each_brg_cell;
		if($key=='participant_name'){
			if(strpos($each_brg_cell,"'")!=FALSE){
				$each_brg_cell=str_replace("'","",$each_brg_cell);
				$final_brg[$key]=$each_brg_cell;
				$error_brg[$key][]=func_error_code(7);
				$status_brg[$key][]=func_status(1);
			}else
				$final_brg[$key]=$each_brg_cell;
		}else if(is_string($each_brg_cell)){
				if(strpos($each_brg_cell,"'")!=FALSE){
					$final_brg[$key]=$each_brg_cell;
					$error_brg[$key][]=func_error_code(7);
					$status_brg[$key][]=func_status(2);
				}else
					$final_brg[$key]=$each_brg_cell;
		}else
			$final_brg[$key]=$each_brg_cell;


		if($key=='bridge_id'){
			if($each_brg_cell=='AR-WEB' || $each_brg_cell=='AR-REC'){
				$each_brg_cell=preg_replace('/[^A-Z]/', '', $each_brg_cell); 
				$final_brg[$key]=$each_brg_cell;
				$error_brg[$key][]=func_error_code(7);
				$status_brg[$key][]=func_status(1);
			}else
				$final_brg[$key]=$each_brg_cell;
		}else
			$final_brg[$key]=$each_brg_cell;

		if($key=='chargeable_item'){
			$nocon=TRUE;
			if($each_brg_cell=="U106" && $get_brg_arr["bridge_type"]=="b"){
				$each_brg_cell="U972";
				$final_brg[$key]="U972";
				$error_brg[$key][]=func_error_code(8);
				$status_brg[$key][]=func_status(1);
				$nocon=FALSE;
			}

			if($each_brg_cell=="RARH" && $get_brg_arr["bridge_type"]=="nb"){
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
			if(!empty($each_brg_cell)){
				if(!preg_match('/^[0-9]+$/',$each_brg_cell)){
					$error_brg[$key][]=func_error_code(3);
					$status_brg[$key][]=func_status(2);
				}
			}
		}else 
			$final_brg[$key]=$each_brg_cell;


	}
	
	$chk_nb_qry=mysql_query("select * from tbl_bridge_raw where bridge_type='".$get_brg_arr["bridge_type"]."' and master_id='".$get_brg_arr["master_id"]."' and pk_bridge_id=".($get_brg_arr["pk_bridge_id"]-1));
			if(mysql_num_rows($chk_nb_qry)>0){
				$status_brg["bridge_type"][]=func_status(2);
			}
	
		
		if(count($status_brg)>0)
			$get_sts_brg=stat_to_arr($status_brg);
		else 
			$get_sts_brg[]='PROCESSED';
		$final_brg['record_status']=get_stat_code($get_sts_brg);
		$column_nms_brg=array_keys($final_brg);
		$values_brg=array_values($final_brg);
		$data_val_brg='"'.implode('","',$values_brg).'"';
		if($final_brg['record_status']=='PROCESSED'){
			//echo 'insert into tbl_bridge('.implode(",",$column_nms_brg).') values('.$data_val_brg.')'.'/n';
			$ins_filt_brg=mysql_query('insert into tbl_bridge('.implode(",",$column_nms_brg).') values('.$data_val_brg.')');
			if(!$ins_filt_brg){
				$get_error_res[]="Error while inserting into tbl_bridge table ".$data_val_brg;
			}
		}else if($final_brg['record_status']=='MANUAL_FIX' || $final_brg['record_status']=='FIXED'){
			//$get_main_brg='"'.implode('","',$final_brg).'"';
			
			$ins_error_brg=mysql_query('insert into tbl_bridge_error('.implode(",",$column_nms_brg).') values('.$data_val_brg.')');
			$updt_err_id=mysql_insert_id();
			$get_impl_brg=array_implode('',',',$error_brg);
			$updt_err_code=mysql_query('update tbl_bridge_error set error_code="'.$get_impl_brg.'"where id='.$updt_err_id);
			if(!$ins_error_brg)
				$get_error_res[]="Error while inserting into tbl_bridge_error table ".$data_val_brg;
		}
		$cnt++;
}
$ins_prco_conf_sql=mysql_query("insert into tbl_bridge_proc_rec(raw_rec_id, added_date) values($max_brg_id,now())");
}



?>
<html>
<body>
<table width='100%'>
	<?php
		if(count($get_error_res)>0){
		foreach($get_error_res as $error_row){
			echo "<tr><td>".$error_row."</td></tr>";
		}
}else
			echo "<tr><td>Process completed</td></tr>";
?>
</table>
</body>
</html>