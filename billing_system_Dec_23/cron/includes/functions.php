<?php
function func_error_code($code){
switch($code){
case 1:
	return $rtn_code='ERR_PR_INV_CHAR';
	break;
case 2:
	return $rtn_code='ERR_PR_INV_STR';
	break;
case 3:
	return $rtn_code='ERR_PR_INV_ALPHA';
	break;
case 4:
	return $rtn_code='ERR_PR_INV_FORMAT';
	break;
case 5:
	return $rtn_code='ERR_PR_INV_PHONE';
	break;
case 6:
	return $rtn_code='ERR_PR_INV_PRID';
	break;
case 7:
	return $rtn_code='ERR_GC_INV_CHAR';
	break;
case 8:
	return $rtn_code='ERR_GC_CHRG_CODE';
	break;
case 9:
	return $rtn_code='ERR_GC_INV_CHAR';
	break;
case 10:
	return $rtn_code='ERR_GC_INV_RECORD';
	break;
case 11:
	return $rtn_code='ERR_GC_ACC_CODE';
	break;

}
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

function get_stat_code($scode){
if(in_array('MANUAL_FIX',$scode))
	return 'MANUAL_FIX';
else if(in_array('FIXED',$scode))
	return 'FIXED';
else if(in_array('PROCESSED',$scode))
		return 'PROCESSED';
}
//*******Convert multidimensional error array to string***********//
function array_implode( $glue, $separator, $array ) {
    if ( ! is_array( $array ) ) return $array;
    $string = array();
    foreach ( $array as $key => $val ) {
        if ( is_array( $val ) )
            $val = implode( ',', $val );
        $string[] = "{$val}";

    }
    return implode( $separator, $string );
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

function clean($string) {
   return preg_replace('/[^A-Za-z0-9\s]/', '', $string); 
}

function clean_phone($string) {
   return preg_replace('/[^0-9]/', '', $string); 
}
?>