<?php 
//echo phpinfo();
 set_time_limit(0);
include("includes/config.php");
$dir = "data/PR";
$process_fld=false;
$processed_array=array();
$get_processed=mysql_query("select * from tbl_processed_files");
while($getArr=mysql_fetch_array($get_processed)){
$processed_array[]=$getArr['file_name'].'.csv';
}
$files = scandir($dir);
$process_fld=array_search('processed',$files);
if($process_fld!=false){
	unset($files[$process_fld]);
}
$table_name = 'tbl_pr_data';
$insertString = '';$error_message = '';

foreach($files as $file) {
    if($file == "." || $file == ".." || in_array($file,$processed_array))
    {
       continue;
    }    
		$row = 0;$processed = 0;
		$error_rows = '';$status='error';
		if (empty($file) === false && ($handle = fopen("$dir/$file", "r")) != FALSE) 
        {
			
            $processed = 1 ;
		    while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
              $error_message='-';
			$row++;
			$num = count($data);
			foreach($data as $key=>$singlecell){
				if($key==10 || $key==11 || $key==18 || $key==20){
					$data[$key]=date("Y-m-d H:i:s", strtotime($singlecell));
				}
				
			}
			
			/*	
				First Row for colum names, So it will be omitted
			*/
			if($row <= 2 )
				continue;

			/*	
				Check whether Each Row having 31 fields
			*/
			if( $num  !=  31)
			{
				$error_rows[]= $row;
				continue;
			}			
			
			/*
				Remove Quotes in array values
			*/
			$insertData='';
			array_walk($data,"removeQuotes");
           $Qto="','";
			//$insertData = implode("','", $data);
			foreach($data as $key=>$addquotes){
				if($key==23 || $key==24 || $key==25){
						$insertData.="b"."'".$addquotes."',";

				}else
					$insertData.="'".$addquotes."',";
			
			}
				$insertData=trim($insertData,',');;
				
			$imsertQry = mysql_query("INSERT INTO `tbl_pr_data_raw` (`uniquerowid`, `uniqueconfid`, `confid`, 
                `bridgename`, `countrycode`, `intlcompanyid`, `intlclientid`, `intlcountrycode`,`participantname`, `conferencetitle`, 
                `connecttime`, `disconnecttime`, `duration`, `bridgetype`,`accesstype`, `pin`, `ponumber`, `phone`, `reccreated`, `prepostcomm`,
                `scheduleddate`, `conferencetype`, `reservationtype`, `dialedout`, `soundbyte`, `prairiesoundbyte`, `ani`, `dnis`, 
                `destinationcountrycode`, `externalid`, `recordcount`) VALUES ($insertData)");
            
			if($imsertQry === false)
			{
				$error_rows[]= $row;
				$error_message .= addslashes(mysql_error())."<br/>";
			}
			else
				$status='success';
			
			}
            if($row <= 2)
            {
                $error_message = "No records found";
            }
			if(is_array($error_rows))
				$error_rows = implode(',',$error_rows); 

			 mysql_query("INSERT INTO `tbl_processed_files` ( `file_name`, `status`, `error_message`,
                 `error_row_numbers`, `table_name`, `created_date`, `modified_date`) VALUES 
                 ( '$file', '$status', '$error_message', '$error_rows', '$table_name',  CURRENT_TIMESTAMP, 'NOW')");
             
             fclose($handle);
             
		    }
            else
            {
                 mysql_query("INSERT INTO `tbl_processed_files` ( `file_name`, `status`, `error_message`, 							`error_row_numbers`, `table_name`, `created_date`, `modified_date`) VALUES 							( '$file', '$status', 'Error in read file', '', '', '', CURRENT_TIMESTAMP, NOW)");
                 
            }
            if(empty($file) === false && $processed)
            {
                if (copy("$dir/$file", "$dir/processed/$file")) {
                    unlink("$dir/$file");
					echo "in unlink file";
                }else
                    die('cannot able move files to porocessed folder');
            }
            
		   

}
		
	
function removeQuotes(&$value,$key)
{
	$value=addslashes(stripslashes($value));
}


?>


