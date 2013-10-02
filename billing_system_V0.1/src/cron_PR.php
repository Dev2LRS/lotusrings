<?php 
//echo phpinfo();
 set_time_limit(0);
include("includes/config.php");
$dir = "data/PR";
$files = scandir($dir);
$table_name = 'tbl_pr_data';
$insertString = '';$error_message = '';
foreach($files as $file) {
    if($file == "." || $file == "..")
    {
        continue;
    }    
    
     break;
}
		$row = 0;$processed = 0;
		$error_rows = '';$status='error';
		if (empty($file) === false && ($handle = fopen("$dir/$file", "r")) != FALSE) 
        {
            $processed = 1 ;
		    while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
              
			$row++;
			$num = count($data);
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
			array_walk($data,"removeQuotes");
           
			$insertData = implode("','", $data);
			$imsertQry = mysql_query("INSERT INTO `tbl_pr_data` (`uniquerowid`, `uniqueconfid`, `confid`, 
                `bridgename`, `countrycode`, `intlcompanyid`, `intlclientid`, `intlcountrycode`,`participantname`, `conferencetitle`, 
                `connecttime`, `disconnecttime`, `duration`, `bridgetype`,`accesstype`, `pin`, `ponumber`, `phone`, `reccreated`, `prepostcomm`,
                `scheduleddate`, `conferencetype`, `reservationtype`, `dialedout`, `soundbyte`, `prairiesoundbyte`, `ani`, `dnis`, 
                `destinationcountrycode`, `externalid`, `recordcount`) VALUES ('$insertData')");
            //echo "INSERT INTO `tbl_pr_data` (`uniquerowid`, `uniqueconfid`, `confid`, 										`bridgename`, `countrycode`, `intlcompanyid`, `intlclientid`, `intlcountrycode`, 										`participantname`, `conferencetitle`, `connecttime`, `disconnecttime`, `duration`, `bridgetype`, 										`accesstype`, `pin`, `ponumber`, `phone`, `reccreated`, `prepostcomm`, `scheduleddate`, 										`conferencetype`, `reservationtype`, `dialedout`, `prairiesoundbyte`, `ani`, `dnis`, 										`destinationcountrycode`, `externalid`, `recordcount`) VALUES ('$insertData')";
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
                if (copy("$dir/$file", "data/processed_pr/$file")) {
                    unlink("$dir/$file");
                }else
                    die('cannot able move files to porocessed folder');
            }
            
		   

		
		
	
function removeQuotes(&$value,$key)
{
	$value=addslashes(stripslashes($value));
}


?>


