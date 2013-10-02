<?php
set_time_limit(0);
include('includes/config.php');
include('includes/functions.php');
$get_error_res=array();
$conf_chk_lst=array();
$conf_chk_lst=array();

$chk_last_ins=mysql_query("select max(pk_customer_id) as id from tbl_customer_bckup");
if(mysql_num_rows($chk_last_ins)>0){
	$cus_chk_lst=mysql_fetch_assoc($chk_last_ins);
}else
	$cus_chk_lst['id']=0;
$bck_cust_qry=mysql_query("insert into tbl_customer_bckup select * from tbl_customer where pk_customer_id > ".$cus_chk_lst['id']);

$chk_last_conf=mysql_query("select max(pk_conference_id) as id from tbl_conference_bckup");
if(mysql_num_rows($chk_last_conf)){
	$conf_chk_lst=mysql_fetch_assoc($chk_last_conf);
}else
	$conf_chk_lst['id']=0;
$bck_conf_qry=mysql_query("insert into tbl_conference_bckup select * from tbl_conference where pk_conference_id > ".$conf_chk_lst['id']);

$chk_lst_brg=mysql_query("select max(pk_bridge_id) as id from tbl_bridge_bckup");
if(mysql_num_rows($chk_lst_brg)>0){
	$brg_chk_lst=mysql_fetch_assoc($chk_lst_brg);
}else
	$brg_chk_lst['id']=0;

$bck_brg_qry=mysql_query("insert into tbl_bridge_bckup select * from tbl_bridge where pk_bridge_id > ".$brg_chk_lst['id']);

//***********CUSTOMER TABLE***********//
$sel_chairprsn=mysql_query("select * from tbl_customer where pk_customer_id > ".$cus_chk_lst['id']);
if(mysql_num_rows($sel_chairprsn)>0){
while($get_cus_arr=mysql_fetch_assoc($sel_chairprsn)){
	$final_cust=array();
	$error_cust_cd=array();
	$status_cust=array();
	$get_sts_cust=array();
	$ins_err_cust=array();
	$column_nms_cust=array();
	$values_cust=array();
	foreach($get_cus_arr as $key=>$each_cus_cell){
		$ins_err_cust[]=$each_cus_cell;
		/*****************Check if chairperson_name ans other srting fields contains (')*******************/
		if($key=='chairperson_name'){
			$final_cust[$key]=str_replace("'","",$each_cus_cell);
			$error_cust_cd[$key][]=func_error_code(7);
			$status_cust[$key][]=func_status(1);
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
		$ins_filt_cust=mysql_query('insert into tbl_customer_filtered('.implode(",",$column_nms_cust).') values('.$data_val_cust.')');
		if(!$ins_filt_cust){
			$get_error_res[]="Error while inserting into tbl_customer_filtered table ".$data_val_cust;
		
		}

		if(count($error_cust_cd)>0){
		$ins_err_cust['record_status']=get_stat_code($get_sts_cust);
		$get_main_cust='"'.implode('","',$ins_err_cust).'"';
		$ins_error_cust_cd=mysql_query('insert into tbl_customer_error('.implode(",",$column_nms_cust).') values('.$get_main_cust.')');
		$updt_err_id=mysql_insert_id();
		$get_impl_cust=array_implode('',',',$error_cust_cd);
		
		$updt_err_code=mysql_query('update tbl_customer_error set error_code="'.$get_impl_cust.'"where id='.$updt_err_id);
		if(!$ins_error_cust_cd)
			$get_error_res[]="Error while inserting into tbl_customer_error table ".$data_val_cust;
			}

}
}

//**********Conference Table**************//
$sel_conf=mysql_query("select * from tbl_conference where pk_conference_id > ".$conf_chk_lst['id']);
if(mysql_num_rows($sel_conf)>0){
	while($get_conf_arr=mysql_fetch_assoc($sel_conf)){
		$final_conf=array();
		$error_conf_cd=array();
		$status_conf=array();
		$get_sts_conf=array();
		$ins_err_conf=array();
		$column_nms_conf=array();
		$values_conf=array();
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
		$ins_filt_conf=mysql_query('insert into tbl_conference_filtered('.implode(",",$column_nms_conf).') values('.$data_val_conf.')');
		if(!$ins_filt_conf){
			$get_error_res[]="Error while inserting into tbl_conference_filtered table ".$data_val_conf;
		}

		if(count($error_conf_cd)>0){
		$ins_err_conf['record_status']=get_stat_code($get_sts_conf);
		$get_main_conf='"'.implode('","',$ins_err_conf).'"';
		$ins_error_conf_cd=mysql_query('insert into tbl_conference_error('.implode(",",$column_nms_conf).') values('.$get_main_conf.')');
		$updt_err_id=mysql_insert_id();
		$get_impl_conf=array_implode('',',',$error_conf_cd);
		$updt_err_code=mysql_query('update tbl_conference_error set error_code="'.$get_impl_conf.'"where id='.$updt_err_id);
		if(!$ins_error_conf_cd)
			$get_error_res[]="Error while inserting into tbl_conference_error table ".$data_val_conf;
			}
	}
}



//************Bridge Table**************//
$sel_brg=mysql_query("select * from tbl_bridge where pk_bridge_id > ".$brg_chk_lst['id']);
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
	foreach($get_brg_arr as $key=>$each_brg_cell){
		$ins_err_brg[$key]=$each_brg_cell;
		if($key=='participant_name'){
			$each_brg_cell=str_replace("'","",$each_brg_cell);
			$final_brg[$key]=$each_brg_cell;
			$error_brg[$key][]=func_error_code(7);
			$status_brg[$key][]=func_status(1);
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
			if(!preg_match('/^[0-9]+$/',$each_brg_cell)){
				$error_brg[$key][]=func_error_code(3);
				$status_brg[$key][]=func_status(2);
			}

		}else 
			$final_brg[$key]=$each_brg_cell;

		if($key=='bridge_type'){
			$final_brg[$key]=$each_brg_cell;
			if($cnt%2==0){
				if($each_brg_cell!="nb")
					$status_brg[$key][]=func_status(2);
			}else
				{
					if($each_brg_cell!="b")
						$status_brg[$key][]=func_status(2);
				}
		}

	}
		if(count($status_brg)>0)
			$get_sts_brg=stat_to_arr($status_brg);
		else 
			$get_sts_brg[]='PROCESSED';
		$final_brg['record_status']=get_stat_code($get_sts_brg);
		$column_nms_brg=array_keys($final_brg);
		$values_brg=array_values($final_brg);
		$data_val_brg='"'.implode('","',$values_brg).'"';
		$ins_filt_brg=mysql_query('insert into tbl_bridge_filtered('.implode(",",$column_nms_brg).') values('.$data_val_brg.')');
		if(!$ins_filt_brg){
			$get_error_res[]="Error while inserting into tbl_bridge_filtered table ".$data_val_brg;
		}

		if(count($error_brg)>0){
			$ins_err_brg['record_status']=get_stat_code($get_sts_brg);
			$get_main_brg='"'.implode('","',$ins_err_brg).'"';
			$ins_error_brg=mysql_query('insert into tbl_bridge_error('.implode(",",$column_nms_brg).') values('.$get_main_brg.')');
			$updt_err_id=mysql_insert_id();
			$get_impl_brg=array_implode('',',',$error_brg);
			$updt_err_code=mysql_query('update tbl_bridge_error set error_code="'.$get_impl_brg.'"where id='.$updt_err_id);
			if(!$ins_error_brg)
				$get_error_res[]="Error while inserting into tbl_bridge_error table ".$data_val_brg;
		}
		$cnt++;
}
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