<?php

require_once("includes/config.php");

require_once("includes/cmn_function.php");

require_once("session_check.php");


$ProcessedFiles = "strong";
/*
 * Delete
 */
if(!empty($_SESSION['request'])){
    $_REQUEST = $_SESSION['request'] ;
    unset($_SESSION['request']);
}
    
if($_POST['HdnDel'] == 'yes'){
    
    $Delid = base64_decode($_POST['HdnDelId']); 
    $DeleteQry = "DELETE FROM tbl_processed_files WHERE record_id = '$Delid'";
    if(mysql_query($DeleteQry)){		
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Category has been deleted successfully.';
        unset($_POST['HdnDel'],$_POST['HdnDelId']);
        $_SESSION['request'] = $_POST;
        header('location:list_processed_files.php');
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
    <td class="red1" align="left" ><h2>Manage Processed Files</h2></td>
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
                                  <tr>
                                      <td  align="center" width="70">&nbsp;From Date:</td>
                                      <td  align="center" width="200">&nbsp;<input type="text" name="startdate" id="startdate" readonly value="<?php echo $StartDate; ?>"/></td>
                                      <td  align="center" width="60">&nbsp;To Date:</td>
                                      <td  align="center"  width="200">&nbsp;<input type="text" name="enddate" id="enddate" readonly value="<?php echo $EndDate; ?>" /></td>
                                      <td  align="center">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='list_processed_files.php'"/></td>
                                  </tr>
                              </table>
                          </td>                             
                      </tr>                       
                            
                      <tr >
                          <th height="10"></th>
                      </tr>
                            
                  </table>
                  <div class="space20"></div>
                  <div class="list_div">
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                          
                          
                          <tr style="background-color:#CAE1F9;">
                              
                              <th align="center" style="padding-left:5px;" width="70">&nbsp;#&nbsp;</th>			
                              <th align="left" style="padding-left:5px;" class="sort_this" width="200" sortdata="file_name" >File Name</th>
                              <th align="left" style="padding-left:5px;" class="sort_this" width="70" sortdata="status"  >Status</th>
                              <th align="left" style="padding-left:5px;" class="sort_this" width="200" sortdata="error_message" >Error Message</th>
                              <th align="left" style="padding-left:5px;" class="sort_this" width="200" sortdata="error_row_numbers" >Error Rows</th>
                              <th align="left" style="padding-left:5px;" class="sort_this" width="140" sortdata="created_date" >Processed Date</th>
<!--                                            <th colspan="2" style="padding-left:5px;"  width="10%">Action</th>			-->
    
                          </tr>
                              
                          <?php
                          if (empty($Sort) === false) {
                              ?>
                              <script>
                                  $('.sort_this[sortdata="<?php echo $Sort; ?>"]').addClass("<?php echo $SortType; ?>")
                              </script>
                              <?php
                          }
                          //Get users Details

                          $QryGetUsr = "select * from tbl_processed_files $WhereQuery";

                          $ResUsr = mysql_query($QryGetUsr);

                          $intUsrCount = mysql_num_rows($ResUsr);

                          if ($intUsrCount > 0) {

                              // Code for sorting

                              if ($Sort != '')
                                  $QryGetUsr.=" ORDER BY $Sort $SortType";

                              ///code for paging starts

                              $TotalPages = ceil($intUsrCount / $RecordsPerPage);

                              $Start = ($Page - 1) * $RecordsPerPage;

                              $sno = $Start + 1;

                              $QryGetUsr.=" limit $Start,$RecordsPerPage";

                              $ResUsr = mysql_query($QryGetUsr);


                              $intUsrCount = mysql_num_rows($ResUsr);

                              if ($intUsrCount > 0) {

                                  $row = 1;

                                  while ($Row = mysql_fetch_array($ResUsr)) {
                                      array_walk_recursive($Row, "remove_empty");

                                      $list_processed_files = $Row['list_processed_files'];

                                      $file_name = ucfirst(stripslashes(trim($Row['file_name'])));
                                      $status = ucfirst(stripslashes(trim($Row['status'])));
                                      $error_message = ucfirst(stripslashes(trim($Row['error_message'])));
                                      $error_row_numbers = ucfirst(stripslashes(trim($Row['error_row_numbers'])));
                                      $created_date = ucfirst(stripslashes(trim($Row['created_date'])));






                                      if ($row % 2 == 0)
                                          $display_color = "#EEF2FD";

                                      else
                                          $display_color = "#f6f6f6";
                                      ?>
                                                                    
                                      <tr bgcolor="<?php echo $display_color ?>" >
                                          
                                          <td  align="center">&nbsp;<?php echo $sno ?></td>                                                              
                                          <td  align="left">&nbsp;<?php echo $file_name ?></td>
                                          <td  align="center">&nbsp;<?php echo $status ?></td>
                                          <td  align="center">&nbsp;<?php echo $error_message ?></td>
                                          <td  align="center">&nbsp;<?php echo $error_row_numbers ?></td>
                                          <td  align="left">&nbsp;<?php echo date('m-d-Y H:i:s', strtotime($created_date)); ?></td>
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

        //Paging starts

        if ($TotalPages > 1) {

            echo "<tr><td align='center' colspan='8' valign='middle' class='pagination'>";

            if ($TotalPages > 1) {

                $FormName = "Listing";

                require_once ("paging.php");
            }

            echo "</td></tr>";
        }//paging if conditoin ends here
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
    $(function() {
        $( "#startdate,#enddate" ).datepicker();
    });
    
    $('#SearchBt').bind('click',function(){
        if($('#startdate').val() == '' && $('#enddate').val() == '')
        {
            alert('Please select the from date or to date');
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