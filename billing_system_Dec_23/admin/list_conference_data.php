<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");

$ConferenceData = "strong";
/*
 * Delete
 */
if(!empty($_SESSION['request'])){
    $_REQUEST = $_SESSION['request'] ;
    unset($_SESSION['request']);
}
    
if($_POST['HdnDel'] == 'yes'){
    
    $Delid = base64_decode($_POST['HdnDelId']); 
    $DeleteQry = "DELETE FROM tbl_conference_raw WHERE record_id = '$Delid'";
    if(mysql_query($DeleteQry)){		
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Category has been deleted successfully.';
        unset($_POST['HdnDel'],$_POST['HdnDelId']);
        $_SESSION['request'] = $_POST;
        header('location:list_ conference_data.php');
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
$strttime='';
 if (empty($_REQUEST['startdate']) === false) {
    $StartDate = trim($_REQUEST['startdate']);
    $strttime = " start_time >= '" . date('Y-m-d H:i:s', strtotime($StartDate." 00:00:00")) . "'";
}
if (empty($_REQUEST['enddate']) === false) {
    $EndDate = trim($_REQUEST['enddate']);  
	if(!empty($strttime))
			$strttime .=' and ';
    $strttime .= " start_time <= '" . date('Y-m-d H:i:s', strtotime($EndDate." 23:59:59")) . "'";
}
if(!empty($strttime)){
	
$get_filter=mysql_query("select * from tbl_bridge_raw where $strttime");
if(mysql_num_rows($get_filter)>0){
	while($get_fil_arr=mysql_fetch_array($get_filter)){
		$conf_ids[]=$get_fil_arr['conference_pk_id'];
	}
	$unq_confids=array_unique($conf_ids);
}else  $WhereQuery.=' pk_conference_id=NULL';


}
if(isset($_REQUEST['ConferenceId']) && trim($_REQUEST['ConferenceId'])!="")
{
    $ConferenceId =trim(addslashes(stripslashes($_REQUEST['ConferenceId'])));
	$WhereQuery=" conference_id='$ConferenceId'";
}

if(isset($_REQUEST['CustomerId']) && trim($_REQUEST['CustomerId'])!="")
{
    $CustomerId =trim(addslashes(stripslashes($_REQUEST['CustomerId'])));
	
    if($WhereQuery!="")
	 $WhereQuery.=" and ";
	 $WhereQuery.=" customer_id='$CustomerId'";
}


if (empty($_REQUEST['resv_begin']) === false) {
    $ResvBegin = trim($_REQUEST['resv_begin']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";

    $WhereQuery.= " DATE(resv_begin) >= '" . date('Y-m-d', strtotime($ResvBegin)) . "'";
}

if (empty($_REQUEST['resv_end']) === false) {
    $ResvEnd = trim($_REQUEST['resv_end']);
    if (empty($WhereQuery) === false)
      $WhereQuery .= " AND ";

    $WhereQuery .= " DATE(resv_end) <= '" . date('Y-m-d', strtotime($ResvEnd)) . "'";
}

if(!empty($unq_confids)){
	if($WhereQuery!="")
	 $WhereQuery.=" and ";
$WhereQuery .= " pk_conference_id in (" .implode(',',$unq_confids).")";
}


if(!empty($WhereQuery))
        $WhereQuery =" WHERE ".$WhereQuery;
 
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
    <td class="red1" align="left" ><h2>Conference Report</h2></td>
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
                            <th height="10"></th>
                        </tr>
                        <tr>
                            <td align="center">
                                <table class="sortable" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0"> 
                                    <tr> <td colspan="6" height="15"></td></tr>
									<tr>
										<td  align="left"  nowrap>&nbsp;From Date:</td><td> <input type="text" name="startdate" id="startdate" value="<?php echo $StartDate;?>" readonly/></td>
										 <td  align="left"  nowrap>&nbsp;To Date: </td><td><input type="text" name="enddate" id="enddate" value="<?php echo $EndDate;?>" readonly/></td>
                                         <td  align="left" nowrap>&nbsp;Customer Id:&nbsp;</td><td><input type="text" name="CustomerId" id="CustomerId" value="<?php echo stripslashes($CustomerId);?>"/></td>
									</tr>
									  <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>
                                       
                                        <td  align="left" nowrap>&nbsp;&nbsp;Conference Id:&nbsp;</td><td><input type="text" name="ConferenceId" id="ConferenceId" value="<?php echo stripslashes($ConferenceId);?>" /></td>
                                        <td  align="left" nowrap>&nbsp;&nbsp;Resv Begin:&nbsp;</td><td><input type="text" name="resv_begin" id="resv_begin" readonly value="<?php echo $ResvBegin;?>"/></td>
                                        <td  align="left" nowrap>&nbsp;&nbsp;Resv End:&nbsp;</td><td><input type="text" name="resv_end" id="resv_end" readonly value="<?php echo $ResvEnd;?>"/></td>
									</tr>
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>
                                        <td  align="center" colspan="6">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='list_conference_data.php'"/></td>
                                    </tr>
                                </table>
                            </td>                             
                        </tr> 
                        <tr><td colspan="6" height="15"></td></tr>
                  </table>
				  <?php                  
				  $Qrygetsummary = "select COUNT(*) as ConfCount from  tbl_conference_raw $WhereQuery";
                        $QryexecSummary = mysql_query($Qrygetsummary);
                        $numRows = mysql_num_rows($QryexecSummary);
                        if($numRows > 0 )
                        {
                            $rowsum = mysql_fetch_array($QryexecSummary);
                            $ConfCount = $rowsum['ConfCount'];
                            
                        }
					?>
				   <div class="space20"></div>
				   <table class="sortable" width="100%;" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;"> 
                       <tr style="background-color:#CAE1F9;">
                          <th height="25" align="left"  style="">&nbsp;Summary</th>
                      </tr>
                      <tr>
                          <td class="summary">&nbsp;Number of Conferences :&nbsp;&nbsp;&nbsp;<b><?php echo intval($ConfCount);?></b></td>
                      </tr>                     
                  </table>
                  <div class="space20"></div>
                  <div class="rec-listview">
                      
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                        <tr style="background-color:#CAE1F9;">			
                            <th align="left" nowrap style="padding-left:5px;" class="sort_this" width="140" sortdata="service_povider_id" >Service Povider Id</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="customer_id"  >Customer Id</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="conference_id" >Conference Id</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="150" sortdata="authorization_string" >Authorization String</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="175" sortdata="first_touched_timestamp" >First Touched Timestamp</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="resv_begin" >Resv Begin</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="150" sortdata="resv_begin_timezone" >Resv Begin Timezone</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="resv_end" >Resv End</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="140" sortdata="resv_end_timezone" >Resv End Timezone</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="140" sortdata="invoice_ref" >Invoice Refe</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="status" >Status</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="170" sortdata="last_touched_timestamp" >Last Touched Timestamp</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="reserver" >Reserver</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="reserver_phone" >Reserver Phone</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="170" sortdata="reserved_total_lines" >Reserved Total Lines</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="ra_master_id" >Ra Master Id</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="110" sortdata="access_number" >Access Number</th>
                            <th align="left" nowrap  style="padding-left:5px;" class="sort_this" width="110" sortdata="access_code" >Access Code</th>
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

                         $QryGetUsr = "select * from   tbl_conference_raw $WhereQuery";
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

                                                $service_povider_id      = ucfirst(stripslashes(trim($Row['service_povider_id'])));
                                                $customer_id      = ucfirst(stripslashes(trim($Row['customer_id'])));
                                                $conference_id      = ucfirst(stripslashes(trim($Row['conference_id'])));
                                                $authorization_string 	      = ucfirst(stripslashes(trim($Row['authorization_string'])));
                                                $first_touched_timestamp 	      = ucfirst(stripslashes(trim($Row['first_touched_timestamp'])));
                                                $resv_begin 	      = ucfirst(stripslashes(trim($Row['resv_begin'])));
                                                $resv_begin_timezone 	      = ucfirst(stripslashes(trim($Row['resv_begin_timezone'])));
                                                $resv_end 	      = ucfirst(stripslashes(trim($Row['resv_end'])));
                                                $resv_end_timezone	      = ucfirst(stripslashes(trim($Row['resv_end_timezone'])));
                                                $invoice_ref 	      = ucfirst(stripslashes(trim($Row['invoice_ref'])));
                                                $status 	      = ucfirst(stripslashes(trim($Row['status'])));
                                                $last_touched_timestamp 	      = ucfirst(stripslashes(trim($Row['last_touched_timestamp'])));
                                                $reserver 	      = ucfirst(stripslashes(trim($Row['reserver'])));
                                                $reserver_phone 	      = ucfirst(stripslashes(trim($Row['reserver_phone'])));
                                                $reserved_total_lines	      = ucfirst(stripslashes(trim($Row['reserved_total_lines'])));
                                                $ra_master_id 	      = ucfirst(stripslashes(trim($Row['ra_master_id'])));
                                                $access_number 	      = ucfirst(stripslashes(trim($Row['access_number'])));
                                                $access_code	      = ucfirst(stripslashes(trim($Row['access_code'])));                                               
                                                $created_date      = ucfirst(stripslashes(trim($Row['created_date'])));



                                                if($row%2==0)

                                                  $display_color="#EEF2FD";

                                                else

                                                  $display_color="#f6f6f6";

                                        ?>

                                                <tr bgcolor="<?php echo $display_color?>" >
                                                        <td  align="center">&nbsp;<?php echo $service_povider_id?></td>                                                              
                                                        <td  align="center">&nbsp;<?php echo $customer_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $conference_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $authorization_string?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($first_touched_timestamp) === false && $first_touched_timestamp != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($first_touched_timestamp)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($resv_begin) === false && $resv_begin != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($resv_begin)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo $resv_begin_timezone?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($resv_end) === false && $resv_end != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($resv_end)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo $resv_end_timezone?></td>
                                                        <td  align="center">&nbsp;<?php echo $invoice_ref?></td>
                                                        <td  align="center">&nbsp;<?php echo $status?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($last_touched_timestamp) === false && $last_touched_timestamp != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($last_touched_timestamp)):"-"?></td>
                                                        <td  align="center">&nbsp;<?php echo $reserver?></td>
                                                        <td  align="center">&nbsp;<?php echo $reserver_phone?></td>
                                                        <td  align="center">&nbsp;<?php echo $reserved_total_lines?></td>
                                                        <td  align="center">&nbsp;<?php echo $ra_master_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $access_number?></td>
                                                        <td  align="center">&nbsp;<?php echo $access_code?></td>                                                        
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
        $( "#resv_begin,#resv_end" ).datepicker();
		$( "#startdate,#enddate" ).datepicker();
    });
    
    $('#SearchBt').bind('click',function(){
       
            $('#Listing').submit();
        
        
    });
</script>