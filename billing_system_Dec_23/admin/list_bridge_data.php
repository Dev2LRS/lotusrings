<?php

require_once("includes/config.php");

require_once("includes/cmn_function.php");

require_once("session_check.php");


$BridgeData = "strong";
/*
 * Delete
 */
if(!empty($_SESSION['request'])){
    $_REQUEST = $_SESSION['request'] ;
    unset($_SESSION['request']);
}
    
if($_POST['HdnDel'] == 'yes'){
    
    $Delid = base64_decode($_POST['HdnDelId']); 
    $DeleteQry = "DELETE FROM   tbl_bridge_raw WHERE record_id = '$Delid'";
    if(mysql_query($DeleteQry)){		
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Category has been deleted successfully.';
        unset($_POST['HdnDel'],$_POST['HdnDelId']);
        $_SESSION['request'] = $_POST;
        header('location:list_pr_data.php');
        exit;
    }else{
        $_SESSION['status'] = 'error';
        $_SESSION['message'] = 'We cannot process this now.Please Contact admin.';
    }
}

/*For Paging*/

$RecordsPerPage=RECORDS_PER_PAGE;

$Page=1;

$Administration = $Categories = "strong";

if(isset($_REQUEST['HdnPage']) && is_numeric($_REQUEST['HdnPage']))

 $Page=$_REQUEST['HdnPage'];

else if(isset($_GET["pgnum"]))

  $Page=$_GET["pgnum"];



if(!is_numeric($Page))
  $Page=1;
$WhereQuery = "";
$QueryString = "";

/*
 * Date Filter
 */

 /*
if (empty($_REQUEST['startdate']) === false) {
    $StartDate = trim($_REQUEST['startdate']);
    $WhereQuery = " created_date >= '" . date('Y-m-d H:i:s', strtotime($StartDate." 00:00:00")) . "'";
}
if (empty($_REQUEST['enddate']) === false) {
    $EndDate = trim($_REQUEST['enddate']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " created_date <= '" . date('Y-m-d H:i:s', strtotime($EndDate." 23:59:59")) . "'";
}
*/

if (empty($_REQUEST['start_time']) === false) {
    $StartTime = trim($_REQUEST['start_time']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery.= " DATE(start_time) >= '" . date('Y-m-d H:i:s', strtotime($StartTime." 00:00:00")) . "'";
}
if (empty($_REQUEST['end_time']) === false) {
    $EndTime = trim($_REQUEST['end_time']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " DATE(end_time) <= '" . date('Y-m-d H:i:s', strtotime($EndTime." 23:59:59")) . "'";
}
if (empty($_REQUEST['bridge_id']) === false) {
    $BridgeId = trim($_REQUEST['bridge_id']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " bridge_id LIKE'%$BridgeId'";
}

if (empty($_REQUEST['participantname']) === false) {
    $ParticipantName = trim($_REQUEST['participantname']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " participant_name LIKE'%$ParticipantName'";
}


if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " bridge_type = 'b'";
    
if(!empty($WhereQuery))
        $WhereQuery =" WHERE $WhereQuery ";
 
/*
 * Sorting
 */
if(isset($_REQUEST['HdnSort'])){
    $Sort = $_REQUEST['HdnSort'];
    $SortType = $_REQUEST['HdnSortType'];
}



require_once("header.php");

?>
<style type="text/css">
	.listTable td {
		font-size:12px;		
	}
</style>

<!--content starts here -->

<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="red1" height="20" align="left"></td>
</tr>

<tr>
    <td class="red1" align="left" ><h2>Bridge Report</h2></td>
 </tr>

<tr>
  <td height="200" align="left" valign="top" class="text" >
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="listTable">
	<tr>
	  <td align="left" valign="top"></td>
	  <td align="left" valign="top">
			<form name="Listing" id="Listing" method="POST">
                  
                  <input type="hidden" name="HdnPage" id="HdnPage" value="<?php echo $Page; ?>">                      
                  <input type="hidden" name="HdnSort" id="HdnSort" value="<?php echo $Sort; ?>">                      
                  <input type="hidden" name="HdnSortType" id="HdnSortType" value="<?php echo $SortType; ?>">                      
                  <input type="hidden" name="HdnMode" id="HdnMode" value="<?php echo $Page; ?>">
                  <input type="hidden" name="HdnDel" id="HdnDel" value="">
                  <input type="hidden" name="HdnDelId" id="HdnDelId" value="">
                      
                  <input type="hidden"  name="Hdnaction" value=""/>
                  <table width="100%"class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;"> 
                       <tr>
                            <th height="25" align="left"  style="background-color:#CAE1F9;">
                                            &nbsp;Search
                            </th>
                        </tr>
                        <tr>
                            <td align="center">
                               <table class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0"> 
                                     <tr> <td colspan="6" height="15"></td></tr>
                      <!--               <tr>
                                        <td  align="left"  nowrap>&nbsp;From Date:</td>
                                        <td  align="center" width="185">&nbsp;<input type="text" name="startdate" id="startdate" readonly value="<?php echo $StartDate;?>"/></td>
                                        <td  align="left"  nowrap>&nbsp;To Date:</td>
                                        <td  align="center"  width="185">&nbsp;<input type="text" name="enddate" id="enddate" readonly value="<?php echo $EndDate;?>" /></td>
                                        <td  align="left"  nowrap>&nbsp;Bridge ID:</td>
                                        <td  align="center" width="185">&nbsp;<input type="text" name="bridge_id" id="bridge_id" value="<?php echo $BridgeId;?>"/></td>                                       
                                        
                                    </tr>
                                    <tr> <td colspan="6" height="15"></td></tr> -->
                                    <tr>
                                        <td  align="left"  nowrap>&nbsp;Participant Name:</td>
                                        <td  align="left" >&nbsp;<input type="text" name="participantname" id="participantname" value="<?php echo $ParticipantName;?>"/></td>
										<td  align="left"  nowrap>&nbsp;&nbsp;Bridge ID:</td>
                                        <td  align="left" width="185">&nbsp;<input type="text" name="bridge_id" id="bridge_id" value="<?php echo $BridgeId;?>"/></td>                                                 
										<td  align="left"  nowrap >&nbsp;&nbsp;Start Time:</td>
                                        <td  align="left" >&nbsp;<input type="text" name="start_time" id="start_time" readonly value="<?php echo $StartTime ?>"/></td>
                                        <td  align="left"  nowrap>&nbsp;&nbsp;End Time:</td>
                                        <td  align="left" >&nbsp;<input type="text" name="end_time" id="end_time" readonly value="<?php echo $EndTime;?>"/></td>
                                    </tr> 
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>                                      
                                        <td  align="center" colspan="7">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='list_bridge_data.php'"/></td>
                                    </tr>
                                </table>
                            </td>                             
                        </tr>                       
                        
                        <tr >
                            <th height="10"></th>
                        </tr>
                        
                  </table>
				   <?php                  
				      $Qrygetsummary = "select COUNT(*) as BridCount, SUM(units) as totmin from  tbl_bridge_raw $WhereQuery";
                        $QryexecSummary = mysql_query($Qrygetsummary);
                        $numRows = mysql_num_rows($QryexecSummary);
                        if($numRows > 0 )
                        {
                            $rowsum = mysql_fetch_array($QryexecSummary);
                            $BridCount = $rowsum['BridCount'];
							$totmins=$rowsum['totmin'];
                            
                        }
					?>
				   <div class="space20"></div>
				   <table class="sortable" width="100%;" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;"> 
                       <tr style="background-color:#CAE1F9;">
                          <th height="25" align="left"  style="">&nbsp;Summary</th>
                      </tr>
                      <tr>
                          <td class="summary">&nbsp;Number of Bridges :&nbsp;&nbsp;&nbsp;<b><?php echo intval($BridCount);?></b></td>
                      </tr>   
					   <tr>
                          <td class="summary">&nbsp;Number of Minutes :&nbsp;&nbsp;&nbsp;<b><?php echo intval($totmins);?></b></td>
                      </tr>  
                  </table>
                  <div class="space20"></div>
                        <div class="rec-listview">                     
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                        <tr style="background-color:#CAE1F9;">			
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="140" sortdata="service_provider_id" >Service Provider Id</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="customer_id" >Customer Id</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="conference_id" >Conference Id</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="cal_detail_id" >Cal Detail Id</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="units" >Units</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="unit_of_measure" >Unit Of Measure</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="item_type" >Item Type</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="chargeable_item" >Chargeable Item</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="charge_amount" >Charge Amount</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="currency" >Currency</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="start_time" >Start Time</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="end_time" >End Time</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="timezone" >Time zone</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="bridge_id" >Bridge Id</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="port_id" >PortId</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="a_number" >A Number</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="b_number" >B Number</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="privacy_bit" >Privacy Bit</th>
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="130" sortdata="participant_name" >Participant Name</th>                            
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="created_date" >Processed Date</th>
<!--                                            <th colspan="2" style="padding-left:5px;"  width="10%">Action</th>			-->

                        </tr>

                        <?php
                        if( empty($Sort) === false)
                        {
                            ?>
                        <script>
                            $('.sort_this[sortdata="<?php echo $Sort;?>"]').addClass("<?php echo $SortType;?>")
                        </script>
                        <?php
                        }
                        //Get users Details

                        $QryGetUsr = "select * from   tbl_bridge_raw $WhereQuery";

                        $ResUsr = mysql_query($QryGetUsr);

                        $intUsrCount = mysql_num_rows($ResUsr);

                        if($intUsrCount>0)

                        {

                                // Code for sorting

                                if($Sort != '')
                                    $QryGetUsr.=" ORDER BY $Sort $SortType";
                                
                                ///code for paging starts

                                $TotalPages=ceil($intUsrCount/$RecordsPerPage);

                                $Start=($Page-1)*$RecordsPerPage;

                                $sno=$Start+1;

                                $QryGetUsr.=" limit $Start,$RecordsPerPage";													

                                $ResUsr = mysql_query($QryGetUsr);					


                                $intUsrCount = mysql_num_rows($ResUsr);

                                if($intUsrCount>0)

                                {

                                        $row=1;

                                        while($Row = mysql_fetch_array($ResUsr))

                                        {
                                            array_walk_recursive($Row,"remove_empty");

                                                $list_pr_data = $Row['list_pr_data'];
                                                
                                                $service_provider_id      = ucfirst(stripslashes(trim($Row['service_provider_id'])));
                                                $customer_id      = ucfirst(stripslashes(trim($Row['customer_id'])));
                                                $conference_id      = ucfirst(stripslashes(trim($Row['conference_id'])));
                                                $cal_detail_id 	      = ucfirst(stripslashes(trim($Row['cal_detail_id'])));
                                                $units 	      = ucfirst(stripslashes(trim($Row['units'])));
                                                $unit_of_measure 	      = ucfirst(stripslashes(trim($Row['unit_of_measure'])));
                                                $item_type 	      = ucfirst(stripslashes(trim($Row['item_type'])));
                                                $chargeable_item 	      = ucfirst(stripslashes(trim($Row['chargeable_item'])));
                                                $charge_amount 	      = ucfirst(stripslashes(trim($Row['charge_amount'])));
                                                $currency 	      = ucfirst(stripslashes(trim($Row['currency'])));
                                                $start_time 	      = ucfirst(stripslashes(trim($Row['start_time'])));
                                                $end_time 	      = ucfirst(stripslashes(trim($Row['end_time'])));
                                                $timezone 	      = ucfirst(stripslashes(trim($Row['timezone'])));
                                                $bridge_id 	      = ucfirst(stripslashes(trim($Row['bridge_id'])));
                                                $port_id 	      = ucfirst(stripslashes(trim($Row['port_id'])));
                                                $a_number 	      = ucfirst(stripslashes(trim($Row['a_number'])));
                                                $b_number 	      = ucfirst(stripslashes(trim($Row['b_number'])));
                                                $privacy_bit 	      = ucfirst(stripslashes(trim($Row['privacy_bit'])));
                                                $participant_name 	      = ucfirst(stripslashes(trim($Row['participant_name'])));
                                                
                                                $created_date      = ucfirst(stripslashes(trim($Row['created_date'])));



                                                if($row%2==0)

                                                  $display_color="#EEF2FD";

                                                else

                                                  $display_color="#f6f6f6";

                                        ?>

                                                <tr bgcolor="<?php echo $display_color?>" >
                                                    
                                                        <td  align="center">&nbsp;<?php echo $service_provider_id?></td>                                                              
                                                        <td  align="center">&nbsp;<?php echo $conference_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $customer_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $cal_detail_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $units?></td>
                                                        <td  align="center">&nbsp;<?php echo $unit_of_measure?></td>
                                                        <td  align="center">&nbsp;<?php echo $item_type?></td>
                                                        <td  align="center">&nbsp;<?php echo $chargeable_item?></td>
                                                        <td  align="center">&nbsp;<?php echo $charge_amount?></td>
                                                        <td  align="center">&nbsp;<?php echo $currency?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($start_time) === false && $start_time != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($start_time)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($end_time) === false && $end_time != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($end_time)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo $timezone?></td>
                                                        <td  align="center">&nbsp;<?php echo $bridge_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $port_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $a_number?></td>
                                                        <td  align="center">&nbsp;<?php echo $b_number?></td>
                                                        <td  align="center">&nbsp;<?php echo $privacy_bit?></td>                                                       
                                                        <td  align="center">&nbsp;<?php echo $participant_name?></td>
                                                        <td  align="left">&nbsp;<?php echo date('m-d-Y H:i:s', strtotime($created_date));?></td>
<!--                                                                    <td  align="center" style="padding-left:5px;">
                                                            <table width="100%" cellpadding="0" cellspacing="4" >
                                                                <tr>
                                                                    <td  align="center" style="padding-left:5px;"><a href="#" class="bluelink">Edit</a></td>
                                                                    <td  align="center" style="padding-left:5px;"><a href="javascript:;" class="bluelink">Delete</a></td>
                                                                </tr>
                                                            </table>                                                                    
                                                        </td>-->



                                                </tr>



                                        <?php

                                                        $row++;

                                                        $sno++;

                                        }				

                                             

                                }

                                else

                                 echo "<tr><td align='center' colspan='10' class='TextRed' style='height:25px; color:red;'>No record(s) found.</td></tr>";

                        }

                        else

                          echo "<tr><td align='center' colspan='10' class='TextRed' style='height:25px; color:red;'>No record(s) found.</td></tr>";

                        ?>
                                                <tr height="20" ><td colspan='10'></td></tr>

                            </table>
                      
                  </div>
                    <?php  //Paging starts

                                                if($TotalPages > 1)

                                                  {

                                                        echo "<tr><td align='center' colspan='8' valign='middle' class='pagination'>";

                                                        if($TotalPages>1)

                                                        {

                                                                $FormName = "Listing";

                                                                require_once ("paging.php");

                                                        }

                                                        echo "</td></tr>";

                                                  }//paging if conditoin ends here
                                                  ?>
              </form>
		</td>
		  <td align="right" valign="top" </td>
		</tr>
	  </table>
	  </td>
	</tr>

</table>

<!--content ends here -->

<?php

require_once("footer.php");

?>
<script>
    $('.list_div').css('width',$(window).width()-230);
    $(document).ready(function(){
         /*if($('.asc').length)
             {
                 $('.list_div').animate({scrollLeft:$('.asc').offset().left-230},0);
             }
         if($('.desc').length)
             {
                 $('.list_div').animate({scrollLeft:$('.desc').offset().left-230},0);
             }*/
    });
</script>

<script>
    $(function() {
        $( "#start_time,#end_time" ).datepicker();
    });
    
    $('#SearchBt').bind('click',function(){
       if($('#participantname').val() == '' && 
           $('#bridge_id').val() == '' && 
           $('#start_time').val() == ''&& 
           $('#end_time').val() == '')
        {
            alert('Please enter atleast one value to search');
        }
        else if($('#start_time').val() != '' && validateUSDate($('#start_time').val()) == false)
        {
            alert('Please enter the valid start date');
        }
        else if($('#end_time').val() != '' && validateUSDate($('#end_time').val()) == false)
        {
            alert('Please enter the valid end date');
        }
        else
        {
            $('#Listing').submit();
        }
        
    });
   
</script>