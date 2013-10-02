<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
$fdata='';
if(isset($_GET['errcmp']) && $_GET['errcmp']=='yes'){
$redirect="document.location.href='pr_error_cmp.php'";
}else
$redirect="document.location.href='pr_error_report.php'";
if(!empty($_POST['uniquerowid'])){
	$get_data=$_POST;
	$set_error=FALSE;
	$id=$_GET["errorid"];
	$special_char=array(',','"','\'');
	$columns=array_keys($get_data);
	foreach($get_data as $key=>$eachcell){
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
			$status[$key][]=func_status(1);
			$eachcell=clean($eachcell);
		}else
			$final_arr[$key]=$eachcell;
		//******END OF SPECIAL CHARS**********//

	if($key=='ani' || $key=='phone'){
		//*****CHECK IF phone AND ani contains predefined strings or not **********// 
		
		if(strtoupper($eachcell)=="ANONYMOUS" || strtoupper($eachcell)=="RESTRICTED" || strtoupper($eachcell)=="UNAVAILABLE"){
			$final_arr[$key]='';
			$eachcell='';
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
				$status[$key][]=func_status(1);
				}
		}
		//*****CHECK IF phone AND ani conatins any characters********//
		if(!is_numeric($eachcell) && !empty($eachcell)){
			$final_arr[$key]=($eachcell);
			$status[$key][]=func_status(2);
		}

		//*******CHECK IF phone AND ani are as per phone format*****//
		if(!preg_match('/[0-9]+$/',$eachcell) && !empty($eachcell)){
			$final_arr[$key]=($eachcell);
			$status[$key][]=func_status(2);
		}else
			$status[$key][]=func_status(1);
	}

	//*******CHECK IF intlclientid IS EMPTY OR NOT******//
		if($key=='intlclientid'){
			if(trim($eachcell)==""){
				$status[$key][]=func_status(2);
				$final_arr[$key]=$eachcell;
			}else
				$final_arr[$key]=$eachcell;
		}
	}
	if(count($status)>0){
			$get_sts_cd=stat_to_arr($status);
			$final_arr['record_status']=get_stat_code($get_sts_cd);
	}

	$data=array_values($final_arr);
	foreach($final_arr as $key=>$eachfield){
		$fdata.=$key."='".mysql_real_escape_string($eachfield)."',";
	}
	
	$fdata=trim($fdata,',');
	//echo "update tbl_pr_filtered set $fdata where id=$id";exit;
	$ins_qry=mysql_query("update tbl_pr_filtered set $fdata where id=$id");
	if($ins_qry){
		if(!isset($_GET['errcmp']))
			header("location:pr_error_report.php?msg=1");
		else
			header("location:pr_error_cmp.php?msg=1");
		exit;
	}
}
if(isset($_GET["errorid"])){
$id=$_GET["errorid"];
$selqry=mysql_query("select * from tbl_pr_filtered where id=$id");
$getarr=mysql_fetch_assoc($selqry);
}

function clean($string){
   return preg_replace('/[^A-Za-z0-9\s]/', '', $string); 
}

function clean_phone($string) {
   return preg_replace('/[^0-9]/', '', $string); 
}


function func_status($st_code){
switch($st_code){
case 1:
	return $rtn_code='FIXED';
	break;
case 2:
	return $rtn_code='MANUAL_FIX';
	break;
case 3:
	return $rtn_code='PROCESSED';
	break;
}
}
function stat_to_arr($mularr){
	foreach($mularr as $arr){
		if(is_array($arr)){
			foreach($arr as $elm){
				$main_arr[]=$elm;
			}
		}
	}
	return $main_arr;
}
function get_stat_code($scode){
	if(in_array('MANUAL_FIX',$scode))
		return 'MANUAL_FIX';
	else if(in_array('FIXED',$scode))
		return 'FIXED';
	else if(in_array('PROCESSED',$scode))
			return 'PROCESSED';
}
require_once("header.php");
?>
<div style='height:1100px'>
<form name='edit_error' id='edit_error' action='' method='post'>
<table width="50%" class='edit_error' align="center" cellpadding="0" cellspacing="0"   border="0"> 
	<tr>
		<th height="25" align="left"  style="background-color:#CAE1F9;" colspan='2'>Edit Details</th>
	</tr>
	<tr height='20px'>
		<td colspan='2'>
		</td>
	</tr>
	<tr>
	   <td>Uniquerowid</td>
	   <td><input type="text" name='uniquerowid' id='uniquerowid' value='<?php echo $getarr["uniquerowid"]?>'></td>
	 </tr>
	  <tr>
		<td>Uniqueconfid</td>
		<td><input type="text" name='uniqueconfid' id='uniqueconfid' value='<?php echo $getarr["uniqueconfid"]?>'></td>
	  </tr>
	  <tr>
		<td>Confid</td>
		<td><input type="text" name='confid' id='confid'  value='<?php echo $getarr["confid"]?>'></td>
	  </tr>
	  <tr>
		<td>Bridgename</td>
		<td><input type="text" name='bridgename' id='bridgename' value='<?php echo $getarr["bridgename"]?>'></td>
	  </tr>
	  <tr>
		<td>Countrycode</td>
		<td><input type="text" name='countrycode' id='countrycode' value='<?php echo $getarr["countrycode"]?>'></td>
	  </tr>
	  <tr>
		<td>Intlcompanyid</td>
		<td><input type="text" name='intlcompanyid' id='intlcompanyid' value='<?php echo $getarr["intlcompanyid"]?>'></td>
	  </tr>
	  <tr>
		<td>Intlclientid</td>
		<td><input type="text" name='intlclientid' id='intlclientid' value='<?php echo $getarr["intlclientid"]?>'></td>
	  </tr>
	  <tr>
		<td>Intlcountrycode</td>
		<td><input type="text" name='intlcountrycode' id='intlcountrycode'  value='<?php echo $getarr["intlcountrycode"]?>'></td>
	  </tr>
	  <tr>
		<td>Participantname</td>
		<td><input type="text" name='participantname' id='participantname' value='<?php echo $getarr["participantname"]?>'></td>
	  </tr>
	  <tr>
		<td>Conferencetitle</td>
		<td><input type="text" name='conferencetitle' id='conferencetitle' value='<?php echo $getarr["conferencetitle"]?>'></td>
	  </tr>
	  <tr>
		<td>Connecttime</td>
		<td><input type="text" name='connecttime' id='connecttime' value='<?php echo $getarr["connecttime"]?>'></td>
	  </tr>
	  <tr>
		<td>Disconnecttime</td>
		<td><input type="text" name='disconnecttime' id='disconnecttime' value='<?php echo $getarr["disconnecttime"]?>'></td>
	  </tr>
	  <tr>
		<td>Duration</td>
		<td><input type="text" name='duration' id='duration' value='<?php echo $getarr["duration"]?>'></td>
	  </tr>
	  <tr>
		<td>Bridgetype</td>
		<td><input type="text" name='bridgetype' id='bridgetype' value='<?php echo $getarr["bridgetype"]?>'></td>
	  </tr>
	  <tr>
		<td>Accesstype</td>
		<td><input type="text" name='accesstype' id='accesstype' value='<?php echo $getarr["accesstype"]?>'></td>
	  </tr>
	  <tr>
		<td>Pin</td>
		<td><input type="text" name='pin' id='pin' value='<?php echo $getarr["pin"]?>'></td>
	  </tr>
	  <tr>
		<td>Ponumber</td>
		<td><input type="text" name='ponumber' id='ponumber' value='<?php echo $getarr["ponumber"]?>'></td>
	  </tr>
	  <tr>
		<td>Phone</td>
		<td><input type="text" name='phone' id='phone' value='<?php echo $getarr["phone"]?>'></td>
	  </tr>
	  <tr>
		<td>Reccreated</td>
		<td><input type="text" name='reccreated' id='reccreated'  value='<?php echo $getarr["reccreated"]?>'></td>
	  </tr>
	  <tr>
		<td>Prepostcomm</td>
		<td><input type="text" name='prepostcomm' id='prepostcomm'  value='<?php echo $getarr["prepostcomm"]?>'></td>
	  </tr>
	  <tr>
		<td>Scheduleddate</td>
		<td><input type="text" name='scheduleddate' id='scheduleddate' value='<?php echo $getarr["scheduleddate"]?>'></td>
	  </tr>
	  <tr>
		<td>Conferencetype</td>
		<td><input type="text" name='conferencetype' id='conferencetype' value='<?php echo $getarr["conferencetype"]?>'></td>
	  </tr>
	  <tr>
		<td>Reservationtype</td>
		<td><input type="text" name='reservationtype' id='reservationtype' value='<?php echo $getarr["reservationtype"]?>'></td>
	  </tr>
	  <tr>
		<td>Dialedout</td>
		<td><input type="text" name='dialedout' id='dialedout' value='<?php echo ($getarr["dialedout"]);?>'></td>
	  </tr>
	  <tr>
		<td>Soundbyte</td>
		<td><input type="text" name='soundbyte' id='soundbyte' value='<?php echo ($getarr["soundbyte"]);?>'></td>
	  </tr>
	  <tr>
		<td>Prairiesoundbyte</td>
		<td><input type="text" name='prairiesoundbyte' id='prairiesoundbyte' value='<?php echo ($getarr["prairiesoundbyte"])?>'></td>
	  </tr>
	  <tr>
		<td>Ani</td>
		<td><input type="text" name='ani' id='ani'  value='<?php echo $getarr["ani"]?>'></td>
	  </tr>
	  <tr>
		<td>Dnis</td>
		<td><input type="text" name='dnis' id='dnis'  value='<?php echo $getarr["dnis"]?>'></td>
	  </tr>
	  <tr>
		<td>Destinationcountrycode</td>
		<td><input type="text" name='destinationcountrycode' id='destinationcountrycode' value='<?php echo $getarr["destinationcountrycode"]?>'></td>
	  </tr>
	  <tr>
		<td>Externalid</td>
		<td><input type="text" name='externalid' id='externalid' value='<?php echo $getarr["externalid"]?>'></td>
	  </tr>
	  <tr>
		<td>Recordcount</td>
		<td><input type="text" name='recordcount' id='recordcount' value='<?php echo $getarr["recordcount"]?>'></td>
	  </tr>
	  <tr height='20px'><td colspan='2'></td></tr>
	  <tr>
		<td colspan='2' style='text-align:center;'>
			<input type='submit' value='Submit' class='button'>&nbsp;&nbsp;&nbsp;<input type='button' value='Cancel' class='button' onclick="<?php echo $redirect;?>">
		</td>
	  </tr>
 </table>
 </div>
 </form>
 <?php

require_once("footer.php");

?>
