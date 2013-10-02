<?php

require_once("includes/config.php");

require_once("includes/cmn_function.php");

require_once("session_check.php");


$CustomerData = "strong";
/*
 * Delete
 */
if(!empty($_SESSION['request'])){
    $_REQUEST = $_SESSION['request'] ;
    unset($_SESSION['request']);
}
    
if($_POST['HdnDel'] == 'yes'){
    
    $Delid = base64_decode($_POST['HdnDelId']); 
    $DeleteQry = "DELETE FROM   tbl_customer WHERE record_id = '$Delid'";
    if(mysql_query($DeleteQry)){		
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Category has been deleted successfully.';
        unset($_POST['HdnDel'],$_POST['HdnDelId']);
        $_SESSION['request'] = $_POST;
        header('location:list_customer_data.php');
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
if (empty($_REQUEST['startdate']) === false) {
    $StartDate = $_REQUEST['startdate'];
    $WhereQuery = " created_date >= '" . date('Y-m-d H:i:s', strtotime($StartDate." 00:00:00")) . "'";
}
if (empty($_REQUEST['enddate']) === false) {
    $EndDate = $_REQUEST['enddate'];
    if (empty($WhereQuery) === false)
        $WhereQuery.= " AND ";
    $WhereQuery.= " created_date <= '" . date('Y-m-d H:i:s', strtotime($EndDate." 23:59:59")) . "'";
}
if (empty($_REQUEST['customer_name']) === false) {
    $CustomerName = trim($_REQUEST['customer_name']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery.= " customer_name LIKE'%$CustomerName%'";
}
if (empty($_REQUEST['chairperson_name']) === false) {
    $ChairpersonName = trim($_REQUEST['chairperson_name']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
     $WhereQuery.= " chairperson_name LIKE'%$ChairpersonName%'";
}
if (empty($_REQUEST['zip']) === false) {
    $Zip = trim($_REQUEST['zip']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery.= " zip LIKE'%$Zip%'";
}
if (empty($_REQUEST['country_code']) === false) {
    $CountryCode = trim($_REQUEST['country_code']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " country_code LIKE'%$CountryCode%'";
}


if (empty($WhereQuery) === false)
    $Page = 1;
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
    <td class="red1" align="left" ><h2>Customer Report</h2></td>
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
                                        <td  align="left"  nowrap>&nbsp;From Date:</td>
                                        <td  align="center" width="185">&nbsp;<input type="text" name="startdate" id="startdate" readonly value="<?php echo $StartDate; ?>"/></td>
                                        <td  align="left"  nowrap>&nbsp;To Date:</td>
                                        <td  align="center"  width="185">&nbsp;<input type="text" name="enddate" id="enddate" readonly value="<?php echo $EndDate; ?>" /></td>
                                        <td  align="left"  nowrap>&nbsp;Customer Name:</td>
                                        <td  align="center" width="185">&nbsp;<input type="text" name="customer_name" id="customer_name" value="<?php echo $CustomerName; ?>"/></td>
                                            
                                            
                                    </tr>
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>
                                        <td  align="left"  nowrap >&nbsp;Chairperson Name:</td>
                                        <td  align="center" >&nbsp;<input type="text" name="chairperson_name" id="chairperson_name" value="<?php echo $ChairpersonName ?>"/></td>
                                        <td  align="left"  nowrap>&nbsp;Zip:</td>
                                        <td  align="center" >&nbsp;<input type="text" name="zip" id="zip" value="<?php echo $Zip; ?>"/></td>
                                        <td  align="left"  nowrap>&nbsp;Country Code:</td>
                                        <td  align="center" >&nbsp;<input type="text" name="country_code" id="country_code" value="<?php echo $CountryCode; ?>"/></td>
                                    </tr> 
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr> <td  align="center"  colspan="5">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='list_customer_data.php'"/></td>
                                    </tr>
                                </table>
                            </td>                             
                        </tr>                       
                        
                        <tr >
                            <th height="10"></th>
                        </tr>
                        
                  </table>
                  <div class="space20"></div>
                  <div class="list_div" style="width:100%;">
                      
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                        <tr style="background-color:#CAE1F9;">	
                                                                                               
                            <th width="140" nowrap="" align="left" sortdata="service_povider_id" class="sort_this" style="padding-left:5px;">Service Provider Id</th>
                            <th width="120" nowrap="" align="left" sortdata="customer_id" class="sort_this" style="padding-left:5px;">Customer Id</th>
                            <th width="135" nowrap="" align="left" sortdata="customer_name" class="sort_this" style="padding-left:5px;">Customer Name</th>
                            <th width="120" nowrap="" align="left" sortdata="organization_id" class="sort_this" style="padding-left:5px;">Organization Id</th>
                            <th width="120" nowrap="" align="left" sortdata="subaccount_id" class="sort_this" style="padding-left:5px;">Sub Account Id</th>
                            <th width="150" nowrap="" align="left" sortdata="subaccount_name" class="sort_this" style="padding-left:5px;">Sub Account Name</th>
                            <th width="120" nowrap="" align="left" sortdata="chairperson_id" class="sort_this" style="padding-left:5px;">Chairperson Id</th>
                            <th width="220" nowrap="" align="left" sortdata="chairperson_name" class="sort_this" style="padding-left:5px;">Chairperson Name</th>
                            <th width="140" nowrap="" align="left" sortdata="chairperson_phone" class="sort_this" style="padding-left:5px;">Chairperson Phone</th>
                            <th width="120" nowrap="" align="left" sortdata="account_type" class="sort_this" style="padding-left:5px;">Account Type</th>
                            <th width="120" nowrap="" align="left" sortdata="address1" class="sort_this" style="padding-left:5px;">Address 1</th>
                            <th width="200" nowrap="" align="left" sortdata="address2" class="sort_this" style="padding-left:5px;">Address 2</th>
                            <th width="120" nowrap="" align="left" sortdata="address3" class="sort_this" style="padding-left:5px;">Address 3</th>
                            <th width="120" nowrap="" align="left" sortdata="city_name" class="sort_this" style="padding-left:5px;">City Name</th>
                            <th width="120" nowrap="" align="left" sortdata="country_name" class="sort_this" style="padding-left:5px;">Country Name</th>
                            <th width="120" nowrap="" align="left" sortdata="state_code" class="sort_this" style="padding-left:5px;">State Code</th>
                            <th width="120" nowrap="" align="left" sortdata="zip" class="sort_this" style="padding-left:5px;">Zip</th>
                            <th width="120" nowrap="" align="left" sortdata="country_code" class="sort_this" style="padding-left:5px;">Country Code</th>
                            <th width="130" nowrap="" align="left" sortdata="anniversary_date" class="sort_this" style="padding-left:5px;">Anniversary Date</th>
                            <th width="120" nowrap="" align="left" sortdata="account_status" class="sort_this" style="padding-left:5px;">Account Status</th>
                            <th width="120" nowrap="" align="left" sortdata="sales_code" class="sort_this" style="padding-left:5px;">Sales Code</th>
                            <th width="120" nowrap="" align="left" sortdata="payment_type" class="sort_this" style="padding-left:5px;">Payment Type</th>
                            <th width="150" nowrap="" align="left" sortdata="wholesale_unique_id" class="sort_this" style="padding-left:5px;">Wholesale Unique Id</th>
                            <th width="120" nowrap="" align="left" sortdata="sp_unique_id" class="sort_this" style="padding-left:5px;">Sp Unique Id</th>
                            <th width="150" nowrap="" align="left" sortdata="credit_card_number" class="sort_this" style="padding-left:5px;">Credit Card Number</th>
                            <th width="130" nowrap="" align="left" sortdata="cardholder_name" class="sort_this" style="padding-left:5px;">Card Holder Name</th>
                            <th width="120" nowrap="" align="left" sortdata="expiration_date" class="sort_this" style="padding-left:5px;">Expiration Date</th>
                            <th width="160" nowrap="" align="left" sortdata="finance_charge_flag" class="sort_this" style="padding-left:5px;">Finance Charge Flag</th>
                            <th width="120" nowrap="" align="left" sortdata="late_notice_flag" class="sort_this" style="padding-left:5px;">Late Notice Flag</th>
                            <th width="150" nowrap="" align="left" sortdata="federal_tax_exempt" class="sort_this" style="padding-left:5px;">Federal Tax Exempt</th>
                            <th width="130" nowrap="" align="left" sortdata="state_tax_exempt" class="sort_this" style="padding-left:5px;">State Tax Exempt</th>
                            <th width="130" nowrap="" align="left" sortdata="local_tax_exempt" class="sort_this" style="padding-left:5px;">Local Tax Exempt</th>
                            <th width="130" nowrap="" align="left" sortdata="misc_tax_exempt" class="sort_this" style="padding-left:5px;">Misc Tax Exempt</th>
                            <th width="170" nowrap="" align="left" sortdata="volume_discount_plan" class="sort_this" style="padding-left:5px;">Volume Discount Plan</th>
                            <th width="120" nowrap="" align="left" sortdata="flexbill_flag" class="sort_this" style="padding-left:5px;">Flex bill Flag</th>
                            <th width="140" nowrap="" align="left" sortdata="floppy_detail_flag" class="sort_this" style="padding-left:5px;">Floppy Detail Flag</th>
                            <th width="130" nowrap="" align="left" sortdata="created_date" class="sort_this" style="padding-left:5px;">Processed Date</th>
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

                        $QryGetUsr = "select * from   tbl_customer $WhereQuery";
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
                                                $customer_name    = ucfirst(stripslashes(trim($Row['customer_name'])));
                                                $organization_id	      = ucfirst(stripslashes(trim($Row['organization_id'])));
                                                $subaccount_id	      = ucfirst(stripslashes(trim($Row['subaccount_id'])));
                                                $subaccount_name	      = ucfirst(stripslashes(trim($Row['subaccount_name'])));
                                                $chairperson_id	      = ucfirst(stripslashes(trim($Row['chairperson_id'])));
                                                $chairperson_name	      = ucfirst(stripslashes(trim($Row['chairperson_name'])));
                                                $chairperson_phone	      = ucfirst(stripslashes(trim($Row['chairperson_phone'])));
                                                $account_type	      = ucfirst(stripslashes(trim($Row['account_type'])));
                                                $address1	      = ucfirst(stripslashes(trim($Row['address1'])));
                                                $address2 	      = ucfirst(stripslashes(trim($Row['address2'])));
                                                $address3 	      = ucfirst(stripslashes(trim($Row['address3'])));
                                                $city_name 	      = ucfirst(stripslashes(trim($Row['city_name'])));
                                                $country_name	      = ucfirst(stripslashes(trim($Row['country_name'])));
                                                $state_code	      = ucfirst(stripslashes(trim($Row['state_code'])));
                                                $zip	      = ucfirst(stripslashes(trim($Row['zip'])));
                                                $country_code	      = ucfirst(stripslashes(trim($Row['country_code'])));                                               
                                                $anniversary_date	      = ucfirst(stripslashes(trim($Row['anniversary_date'])));                                               
                                                $account_status	      = ucfirst(stripslashes(trim($Row['account_status'])));                                               
                                                $sales_code	      = ucfirst(stripslashes(trim($Row['sales_code'])));                                               
                                                $payment_type	      = ucfirst(stripslashes(trim($Row['payment_type'])));                                               
                                                $wholesale_unique_id    = ucfirst(stripslashes(trim($Row['wholesale_unique_id'])));
                                                $sp_unique_id    = ucfirst(stripslashes(trim($Row['sp_unique_id'])));
                                                $credit_card_number   = ucfirst(stripslashes(trim($Row['credit_card_number'])));
                                                $cardholder_name   = ucfirst(stripslashes(trim($Row['cardholder_name'])));
                                                $expiration_date   = ucfirst(stripslashes(trim($Row['expiration_date'])));
                                                $finance_charge_flag  = ucfirst(stripslashes(trim($Row['finance_charge_flag'])));
                                                $late_notice_flag    = ucfirst(stripslashes(trim($Row['late_notice_flag'])));
                                                $federal_tax_exempt   = ucfirst(stripslashes(trim($Row['federal_tax_exempt'])));
                                                $state_tax_exempt   = ucfirst(stripslashes(trim($Row['state_tax_exempt'])));
                                                $local_tax_exempt    = ucfirst(stripslashes(trim($Row['local_tax_exempt'])));
                                                $misc_tax_exempt    = ucfirst(stripslashes(trim($Row['misc_tax_exempt'])));
                                                $volume_discount_plan   = ucfirst(stripslashes(trim($Row['volume_discount_plan'])));
                                                $flexbill_flag   = ucfirst(stripslashes(trim($Row['flexbill_flag'])));
                                                $floppy_detail_flag  = ucfirst(stripslashes(trim($Row['floppy_detail_flag'])));
                                                $created_date      = ucfirst(stripslashes(trim($Row['created_date'])));
                                                



                                                if($row%2==0)

                                                  $display_color="#EEF2FD";

                                                else

                                                  $display_color="#f6f6f6";

                                        ?>                                                                    
                                                                   
                                                                    
                                                <tr bgcolor="<?php echo $display_color?>" >
                                                        <td  align="center">&nbsp;<?php echo $service_povider_id?></td>                                                              
                                                        <td  align="center">&nbsp;<?php echo $customer_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $customer_name ?></td>
                                                        <td  align="center">&nbsp;<?php echo $organization_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $subaccount_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $subaccount_name?></td>
                                                        <td  align="center">&nbsp;<?php echo $chairperson_id?></td>
                                                        <td  align="center">&nbsp;<?php echo $chairperson_name?></td>
                                                        <td  align="center">&nbsp;<?php echo $chairperson_phone?></td>
                                                        <td  align="center">&nbsp;<?php echo $account_type?></td>
                                                        <td  align="center">&nbsp;<?php echo $address1?></td>
                                                        <td  align="center">&nbsp;<?php echo $address2?></td>
                                                        <td  align="center">&nbsp;<?php echo $address3?></td>
                                                        <td  align="center">&nbsp;<?php echo $city_name?></td>    
                                                        <td  align="center">&nbsp;<?php echo $country_name?></td> 
                                                        <td  align="center">&nbsp;<?php echo $state_code?></td> 
                                                        <td  align="center">&nbsp;<?php echo $zip?></td> 
                                                        <td  align="center">&nbsp;<?php echo $country_code?></td> 
                                                        <td  align="center">&nbsp;<?php echo $anniversary_date?></td> 
                                                        <td  align="center">&nbsp;<?php echo $account_status?></td> 
                                                        <td  align="center">&nbsp;<?php echo $sales_code?></td> 
                                                        <td  align="center">&nbsp;<?php echo $payment_type?></td> 
                                                        <td  align="center">&nbsp;<?php echo $wholesale_unique_id?></td> 
                                                        <td  align="center">&nbsp;<?php echo $sp_unique_id ?></td> 
                                                        <td  align="center">&nbsp;<?php echo $credit_card_number?></td> 
                                                        <td  align="center">&nbsp;<?php echo $cardholder_name?></td> 
                                                        <td  align="center">&nbsp;<?php echo $expiration_date;?></td> 
                                                        <td  align="center">&nbsp;<?php echo $finance_charge_flag?></td> 
                                                        <td  align="center">&nbsp;<?php echo $late_notice_flag ?></td> 
                                                        <td  align="center">&nbsp;<?php echo $federal_tax_exempt?></td> 
                                                        <td  align="center">&nbsp;<?php echo $state_tax_exempt?></td> 
                                                        <td  align="center">&nbsp;<?php echo $local_tax_exempt?></td> 
                                                        <td  align="center">&nbsp;<?php echo $misc_tax_exempt?></td> 
                                                        <td  align="center">&nbsp;<?php echo $volume_discount_plan?></td> 
                                                        <td  align="center">&nbsp;<?php echo $flexbill_flag?></td> 
                                                        <td  align="center">&nbsp;<?php echo $floppy_detail_flag?></td>
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
        $( "#startdate,#enddate" ).datepicker();
    });
    
    $('#SearchBt').bind('click',function(){
       if($('#startdate').val() == '' && $('#enddate').val() == ''&& $('#customer_name').val() == ''&& $('#chairperson_name').val() == '' && $('#zip').val() == '' && $('#country_code').val() == '')
        {
              alert('Please enter the values to be searched');
        }
        else if($('#startdate').val() != '' && validateUSDate($('#startdate').val()) == false)
        {
            alert('Please enter the valid from date');
        }
        else if($('#enddate').val() != '' && validateUSDate($('#enddate').val()) == false)
        {
            alert('Please enter the valid to date');
        }
        else
        {
            $('#Listing').submit();
        }
        
    });
   
</script>
