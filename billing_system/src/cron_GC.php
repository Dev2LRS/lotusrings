<?php 
//echo phpinfo();
 set_time_limit(0);
include("includes/config.php");
$dir = "data/GC";
$files = scandir($dir);
$table_name = 'tbl_gc_data';
$insertString = '';$error_message = '';
foreach($files as $file) 
{
    if($file == "." || $file == "..")
    {
        continue;
    }    
    
     break;
}

		$row = 0;$processed = 0;
		$error_rows = '';$status='error';
		if (empty($file) === false ) 
        {
            $filename = "$dir/$file";
            $processed = 1 ;		    
            $fGetContents = file_get_contents($filename);
            if( empty($fGetContents) === false)
            {
                $rowdata = explode("\n", $fGetContents);
                $rows = count($rowdata);
                if(is_array($rowdata))
                {
                    for ($i = 0; $i < $rows; $i++) {
                        $row++;

                        $data = explode("|", $rowdata[$i]);
                        $num = count($data);
                        /* 	
                          Check whether Each Row having 31 fields
                         */
                        if ($num <= 1) {
                            $error_rows[] = $row;
                            continue;
                        }                       

                        /*
                          Remove Quotes in array values
                         */
                        array_walk($data, "removeQuotes");                        
                        array_pop($data);
                        $insertQry = false;
                        $Type = $data[0];
                        $data = array_reverse($data);
                        array_pop($data);
                        $data = array_reverse($data);
                        $insertData = implode("','", $data);
                        switch ($Type)
                        {
                            case "H":                                
                                $insertQry = mysql_query("INSERT INTO `tbl_header` (`no_of_records` ,`total_charges`) VALUES ('$insertData')");
                                break;
                            
                            case "M":
                                 $insertQry = mysql_query("INSERT INTO `tbl_customer` (`service_povider_id`, `customer_id`, `customer_name`, `organization _id`, `subaccount_id`, `subaccount_name`, `chairperson_id`, `chairperson_name`, `chairperson_phone`, `account_type`, `address1`, `address2`, `address3`, `city_name`, `country_name`, `state_code`, `zip`, `country_code`, `anniversary_date`, `account_status`, `sales_code`, `payment_type`, `wholesale_unique_id`, `sp_unique_id`, `credit_card_number`, `cardholder_name`, `expiration_date`, `finance_charge_flag`, `late_notice_flag`, `federal_tax_exempt`, `state_tax_exempt`, `local _tax_exempt`, `misc_tax_exempt`, `volume_discount_plan`, `flexbill _flag`, `floppy_detail_flag`) VALUES ('$insertData')");
                                break;
                            
                            case "C":
                                 $insertQry = mysql_query("INSERT INTO `tbl_conference` (`service_povider_id`, `customer_id`, `conference_id`, `authorization_string`, `first_touched_timestamp`, `resv_begin`, `resv_begin_timezone`, `resv_end`, `resv_end_timezone`, `invoice_ref`, `status`, `last_touched_timestamp`, `reserver`, `reserver_phone`, `reserved_total_lines`, `ra_master_id`, `access_number`, `access_code`) VALUES ('$insertData')");
                                break;
                            
                            case "B":                                  
                            case "N":
                                $record_type = ($Type == 'B')?'b':'nb';
                                //echo "INSERT INTO `tbl_bridge` (`bridge_type`, `service_provider_id`, `customer_id`, `conference_id`, `cal_detail_id`, `units`, `unit_of_measure`, `item_type`, `chargeable_item`, `charge_amount`, `currency`, `start_time`, `end_time`, `timezone`, `bridge_id`, `port_id`, `a_number`, `b_number`, `privacy_bit`, `participant_name`) VALUES ('$record_type','$insertData')";
                                 $insertQry = mysql_query("INSERT INTO `tbl_bridge` (`bridge_type`, `service_provider_id`, `customer_id`, `conference_id`, `cal_detail_id`, `units`, `unit_of_measure`, `item_type`, `chargeable_item`, `charge_amount`, `currency`, `start_time`, `end_time`, `timezone`, `bridge_id`, `port_id`, `a_number`, `b_number`, `privacy_bit`, `participant_name`) VALUES ('$record_type','$insertData')");
                               break;
                        }
						if ($insertQry === false) {
                            $error_rows[] = $row;
                            $error_message .= addslashes(mysql_error()) . "<br/>";
                        }
                        else
                            $status = 'success';
                        
                    }
                }
            }
            else 
            {
                $error_message = "No records found";
            }
            
          
			if(is_array($error_rows))
				$error_rows = implode(',',$error_rows); 

			 mysql_query("INSERT INTO `tbl_processed_files` ( `file_name`, `status`, `error_message`,
                 `error_row_numbers`, `table_name`, `created_date`, `modified_date`) VALUES 
                 ( '$file', '$status', '$error_message', '$error_rows', '$table_name',  CURRENT_TIMESTAMP, 'NOW')");
             
             
             
		    }
            else
            {
                 mysql_query("INSERT INTO `tbl_processed_files` ( `file_name`, `status`, `error_message`,`error_row_numbers`, `table_name`, `created_date`, `modified_date`) VALUES ( '$file', '$status', 'Error in read file', '', '', '', CURRENT_TIMESTAMP, NOW)");
                 
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


