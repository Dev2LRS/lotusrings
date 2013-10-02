<?php
set_time_limit(0);
include('includes/config.php');
include('includes/functions.php');
//Move records to backup table
$chk_last_ins=mysql_query("select max(uniquerowid) as id from tbl_pr_data_bckup");
if(mysql_num_rows($chk_last_ins)>0){
	$max_id=mysql_fetch_assoc($chk_last_ins);
}else
	$max_id["id"]=0;

$bck_qry=mysql_query("insert into tbl_pr_data_bckup select * from tbl_pr_data where uniquerowid > ".$max_id["id"]);
$sel_pr=mysql_query("select * from tbl_pr_data where uniquerowid > ".$max_id["id"]);
$special_char=array(',','"','\'');
if(mysql_num_rows($sel_pr)>0){
$k=1;
$error_cd=array();
$status=array();
$final_arr=array();
$get_sts_cd=array();
while($get_arr=mysql_fetch_assoc($sel_pr)){
foreach($get_arr as $key=>$eachcell){
	$spcl_chr_exst=FALSE;
	$to_ins[$key]=mysql_real_escape_string($eachcell);
	//******REMOVE SPECIAL CHARS*******//
	foreach($special_char as $spchar){
		
		if(strpos($eachcell,$spchar)!=FALSE){
				$spcl_chr_exst=TRUE;
			}
		}
		if($spcl_chr_exst){
			$final_arr[$key]=clean($eachcell);
			$error_cd[$key][]=func_error_code(1);
			$status[$key][]=func_status(1);
			$eachcell=clean($eachcell);
		}else
			$final_arr[$key]=$eachcell;
		$spcl_chr_exst=FALSE;
		//******END OF SPECIAL CHARS**********//

	if($key=='ani' || $key=='phone'){
		//*****CHECK IF phone AND ani contains predefined strings or not **********// 
		
		if(strtoupper($eachcell)=="ANONYMOUS" || strtoupper($eachcell)=="RESTRICTED" || strtoupper($eachcell)=="UNAVAILABLE"){
			$final_arr[$key]='';
			$eachcell='';
			$error_cd[$key][]=func_error_code(2);
			$status[$key][]=func_status(1);
		}

		//*****CHECK IF phone AND ani are numeric and exponential********//
		if(preg_match('/^[0-9]+$/',$eachcell) || is_numeric($eachcell)){
			$act_cell=$eachcell;
			$i=0;
			$i=$i+$eachcell;
			$eachcell=$i;
				
				if(strripos($act_cell,'E')!=FALSE){
				$final_arr[$key]=number_format($eachcell,0,'','');
				$error_cd[$key][]=func_error_code(5);
				$status[$key][]=func_status(1);
				}
		}
		//*****CHECK IF phone AND ani conatins any characters********//
		if(!is_numeric($eachcell) && !empty($eachcell)){
			$final_arr[$key]=($eachcell);
			$error_cd[$key][]=func_error_code(3);
			$status[$key][]=func_status(2);
		}

		//*******CHECK IF phone AND ani are as per phone format*****//
		if(!preg_match('/[0-9]+$/',$eachcell) && !empty($eachcell)){
			$final_arr[$key]=($eachcell);
			$error_cd[$key][]=func_error_code(4);
			$status[$key][]=func_status(2);
		}
	}

	//*******CHECK IF intlclientid IS EMPTY OR NOT******//
	if($key=='intlclientid'){
		$eachcell=trim($eachcell);
		if(empty($eachcell)){
			$error_cd[$key][]=func_error_code(6);
			$status[$key][]=func_status(2);
			$final_arr[$key]=$eachcell;
		}else
			$final_arr[$key]=$eachcell;
	}
	}
	
	/*
	echo "##########FINAL ARRAY###########<br><code>";
	var_dump($final_arr);
	echo "</code><br>#########ERROR CODE###########<br><code>";
	var_dump($error_cd);
	echo "</code><br>#########STATUS#########<br>";
		var_dump($status);
		*/
		//*******INSERT INTO FILTERED TABLE*******//
		
		if(count($status)>0)
			$get_sts_cd=stat_to_arr($status);
		else 
			$get_sts_cd[]='PROCESSED';
		$final_arr['record_status']=get_stat_code($get_sts_cd);
		$column_names=array_keys($final_arr);
		$values=array_values($final_arr);
		$data_values='"'.implode('","',$values).'"';
		$ins_filtered=mysql_query('insert into tbl_pr_filtered('.implode(",",$column_names).') values('.$data_values.')');
		if(!$ins_filtered){
			echo "Database error while processing";
		}
		//*********INSERT INTO ERROR TABLE*************//
		if(count($error_cd)>0){
		$to_ins['record_status']=get_stat_code($get_sts_cd);
		$get_main='"'.implode('","',$to_ins).'"';
		$ins_error=mysql_query('insert into tbl_pr_error('.implode(",",$column_names).') values('.$get_main.')');
		$updt_id=mysql_insert_id();
		$get_impl_err=array_implode('',',',$error_cd);
		$updt_err_code=mysql_query('update tbl_pr_error set error_code="'.$get_impl_err.'"where error_id='.$updt_id);
		if(!$ins_error)
			echo "Database error while processing";
		}
		unset($final_arr);
		unset($error_cd);
		unset($status);
		unset($get_sts_cd);
	
}
}



?>