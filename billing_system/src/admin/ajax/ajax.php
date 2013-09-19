<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include '../includes/config.php';

$Action = $_REQUEST['action'];
switch($Action){
    case 'checkUname':
        $uname = $_REQUEST['uname'];
        $recordID = $_REQUEST['objid'];
        $qryExec = mysql_query("SELECT * FROM tbl_service_person WHERE user_name = '$uname' AND vehicle_id <> '$recordID' ");
        if(mysql_num_rows($qryExec)){
            $return = array("status" => "error");
        }else{
            $return = array("status" => "success");
        }
        break;
   
    case 'checkeCustomeremail':
        $email = $_REQUEST['email'];
        $recordID = $_REQUEST['objid'];
        $qryExec = mysql_query("SELECT * FROM tbl_customers WHERE email = '$email' AND customer_id <> '$recordID' AND active = '1'");
        if(mysql_num_rows($qryExec)){
            $return = array("status" => "error");
        }else{
            $return = array("status" => "success");
        }
        break;
    case 'checkCustomerphone':
        $phone = $_REQUEST['phone'];
        $recordID = $_REQUEST['objid'];
        $qryExec = mysql_query("SELECT * FROM tbl_customers WHERE phone = '$phone' AND customer_id <> '$recordID' AND active = '1'");
        if(mysql_num_rows($qryExec)){
            $return = array("status" => "error");
        }else{
            $return = array("status" => "success");
        }
        break;
		case 'checkeCustomerJob':
        $cusid = $_REQUEST['cus'];
        $recordID = $_REQUEST['objid'];
        $qryExec = mysql_query("SELECT * FROM tbl_jobs WHERE job_status = 'scheduled' AND customer_id = '$cusid'  AND job_id <> '$recordID'");
        if(mysql_num_rows($qryExec)){
            $return = array("status" => "error");
        }else{
            $return = array("status" => "success");
        }
        break;
}

if(!isset($return))
    $return = array("status" => "error","message" => "We cannot process this now.");
echo json_encode($return);

?>
