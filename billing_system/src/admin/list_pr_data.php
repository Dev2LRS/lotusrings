<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");

$PRData = "strong";
/** Delete*/
if(!empty($_SESSION['request'])){
    $_REQUEST = $_SESSION['request'] ;
    unset($_SESSION['request']);
}
    
if($_POST['HdnDel'] == 'yes'){
    $Delid = base64_decode($_POST['HdnDelId']); 
    $DeleteQry = "DELETE FROM  tbl_pr_data WHERE record_id = '$Delid'";
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
if (empty($_REQUEST['ConferenceId']) === false) {
    $ConferenceId = trim($_REQUEST['ConferenceId']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " confid = '$ConferenceId'";
}
if (empty($_REQUEST['countrycode']) === false) {
    $CountryCode = trim($_REQUEST['countrycode']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
   $WhereQuery .= " countrycode = '$CountryCode'";
}
if (empty($_REQUEST['ClientId']) === false) {
    $ClientId = trim($_REQUEST['ClientId']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " intlclientid = '$ClientId'";
}
if (empty($_REQUEST['conferencetype']) === false) {
    $ConferenceType = trim($_REQUEST['conferencetype']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    if($ConferenceType == 'other')
        $WhereQuery .= " conferencetype NOT IN (1,2,3,4,6,7,8,11,16,18,19,29)";
    else
        $WhereQuery .= " conferencetype IN ($ConferenceType)";
}
if (empty($_REQUEST['Dialedout']) === false) {
    $Dialedout  = trim($_REQUEST['Dialedout']);
    if (empty($WhereQuery) === false)
        $WhereQuery .= " AND ";
    $WhereQuery .= " dialedout = '$Dialedout'";
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

$QryGetCountry = "select DISTINCT(countrycode) from  tbl_pr_data";
$QryExecCountry = mysql_query($QryGetCountry);

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
    <td class="red1" align="left" ><h2>PR Report</h2></td>
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
                                    <tr>
                                        <td  align="left"  nowrap>&nbsp;From Date:</td>
                                        <td  align="left"><input type="text" name="startdate" id="startdate" value="<?php echo $StartDate;?>" readonly/></td>  

                                        <td  align="left"  nowrap>&nbsp;To Date:</td>
                                        <td  align="left"> <input type="text" name="enddate" id="enddate" value="<?php echo $EndDate;?>" readonly/></td>  
                                        
										<td  align="left"  nowrap>&nbsp;Conference Id:</td>
                                        <td  align="left">&nbsp;<input type="text" name="ConferenceId" id="ConferenceId" value="<?php echo $ConferenceId;?>"/></td>
                                    </tr>
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>
                                        <td  align="left"  nowrap>&nbsp;Client Id:</td>
                                        <td  align="left" >&nbsp;<input type="text" name="ClientId" id="ClientId" value="<?php echo $ClientId;?>"/></td>
                                        <td  align="left"  nowrap >&nbsp;Country Code:</td>
                                        <td  align="left" >&nbsp;
                                            <select type="text" name="countrycode" id="countrycode" >
                                                <option value="">Select Country</option>
                                                <?php 
                                                        if($QryExecCountry):
                                                            while ($row = mysql_fetch_array($QryExecCountry)) 
                                                            {
                                                            
                                                            ?>
                                                                <option value="<?php echo $row['countrycode'];?>"><?php echo $row['countrycode'];?></option>
                                                            <?php 
                                                            }
                                                        endif;
                                                    ?>
                                            </select>
                                        <?php if(!empty($CountryCode)):
                                            ?>
                                            <script>
                                                $('#countrycode').val('<?php echo $CountryCode;?>');
                                            </script>
                                            <?php
                                        endif;
?></td>
                                        <td  align="left"  nowrap >&nbsp;Conference Type:</td>
                                        <td  align="left" >&nbsp;
                                            <select type="text" name="conferencetype" id="conferencetype" >
                                                <option value="">Select Conference Type</option>
                                                <option value="1">Ready Conference</option>
                                                <option value="2">Scheduled Conference </option>
                                                <option value="3">Operator Assisted </option>
                                                <option value="4">Fully Moderated </option>
                                                <option value="7">Auditorium </option>
                                                <option value="6,8,9">SoundByte </option>
                                                <option value="11,16">Replay </option>
                                                <option value="18,29">Global Meet </option>
                                                <option value="other">Other</option>                                                
                                            </select>
                                            <?php if(!empty($ConferenceType)):
                                            ?>
                                            <script>
                                                $('#conferencetype').val('<?php echo $ConferenceType;?>');
                                            </script>
                                            <?php
                                        endif;
?>
                                            
                                    </tr>
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>
                                        <td  align="left"   nowrap>&nbsp;Dialed out:</td>
                                        <td  align="left" >&nbsp;<input type="text" name="Dialedout" id="Dialedout" value="<?php echo $Dialedout;?>"/></td>
                                        
                                    </tr>
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>                                        
                                        <td  align="center" colspan="6">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='list_pr_data.php'"/></td>
                                    </tr>
                                </table>
                            </td>                             
                        </tr>                       
                        
                        <tr >
                            <th height="10"></th>
                        </tr>
                        
                  </table>
                  <div class="space20"></div>
<?php                   $QryGetSummary = "select SUM(duration) as TotalTime,COUNT(DISTINCT intlclientid) as CustomerCount, COUNT(*) as ConfCount from  tbl_pr_data $WhereQuery";
                        $QryExecSummary = mysql_query($QryGetSummary);
                        $numRows = mysql_num_rows($QryExecSummary);
                        if($numRows > 0 )
                        {
                            $rowsum = mysql_fetch_array($QryExecSummary);
                            $TotalTime = $rowsum['TotalTime'];
                            $CustomerCount = $rowsum['CustomerCount'];
                            $ConfCount = $rowsum['ConfCount'];
                            
                        }
?>
                  <table class="sortable" width="100%;" align="center" cellpadding="0" cellspacing="0" valign="top"  border="0" style="border:1px solid #CAE1F9;"> 
                       <tr style="background-color:#CAE1F9;">
                          <th height="25" align="left"  style="">&nbsp;Summary</th>
                      </tr>
                      <tr>
                          <td class="summary">&nbsp;Number 0f Conferences :&nbsp;&nbsp;&nbsp;<b><?php echo intval($ConfCount);?></b></td>
                      </tr>
                      <tr>
                          <td class="summary">&nbsp;Number 0f Customers :&nbsp;&nbsp;&nbsp;<b><?php echo intval($CustomerCount);?></b></td>
                      </tr>
                      <tr>
                          <td class="summary">&nbsp;Total Minutes :&nbsp;&nbsp;&nbsp;<b><?php echo intval($TotalTime);?></b></td>
                      </tr>
                  </table>
                  
                  <div class="space20"></div>
                  <div class="list_div" style="width:100%;">                      
                      <table class="sortable" align="center" cellpadding="2" cellspacing="2" valign="top"  border="0" > 
                        <tr style="background-color:#CAE1F9;">			
                            <th align="center" nowrap style="padding-left:5px;" class="sort_this" width="120" sortdata="uniquerowid" >Unique Row id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="uniqueconfid"  >Unique Conf Id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="confid" >Conf Id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="bridgename" >Bridge Name</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="countrycode" >Country Code</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="intlcompanyid" >Company Id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="intlclientid" >Intl Client Id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="intlcountrycode" >Intl Country Code</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="140" sortdata="participantname" >Participant Name</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="140" sortdata="conferencetitle" >Conference Title</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="connecttime" >Connect Time</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="disconnecttime" >Disconnect Time</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="duration" >Duration</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="bridgetype" >Bridge Type</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="accesstype" >Access Type</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="pin" >Pin</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="ponumber" >Po number</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="phone" >Phone</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="reccreated" >Rec Created</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="120" sortdata="prepostcomm" >Pre Post comm</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="scheduleddate" >Scheduled Date</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="conferencetype" >Conference Type</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="140" sortdata="reservationtype" >Reservation Type</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="dialedout" >Dialed Out</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="soundbyte" >Sound Byte</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="145" sortdata="prairiesoundbyte" >Prairie Sound Byte</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="ani" >Ani</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="dnis" >Dnis</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="185" sortdata="destinationcountrycode" >Destination Country Code</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="externalid" >External Id</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="100" sortdata="recordcount" >Record Count</th>
                            <th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="created_date" >Processed Date</th>
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

                        $QryGetUsr = " select * from  tbl_pr_data $WhereQuery";

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

                                                $uniquerowid      = ucfirst(stripslashes(trim($Row['uniquerowid'])));
                                                $uniqueconfid      = ucfirst(stripslashes(trim($Row['uniqueconfid'])));
                                                $confid      = ucfirst(stripslashes(trim($Row['confid'])));
                                                $bridgename 	      = ucfirst(stripslashes(trim($Row['bridgename'])));
                                                $countrycode 	      = ucfirst(stripslashes(trim($Row['countrycode'])));
                                                $intlcompanyid 	      = ucfirst(stripslashes(trim($Row['intlcompanyid'])));
                                                $intlclientid 	      = ucfirst(stripslashes(trim($Row['intlclientid'])));
                                                $intlcountrycode 	      = ucfirst(stripslashes(trim($Row['intlcountrycode'])));
                                                $participantname 	      = ucfirst(stripslashes(trim($Row['participantname'])));
                                                $conferencetitle 	      = ucfirst(stripslashes(trim($Row['conferencetitle'])));
                                                $connecttime 	      = ucfirst(stripslashes(trim($Row['connecttime'])));
                                                $disconnecttime 	      = ucfirst(stripslashes(trim($Row['disconnecttime'])));
                                                $duration 	      = ucfirst(stripslashes(trim($Row['duration'])));
                                                $bridgetype 	      = ucfirst(stripslashes(trim($Row['bridgetype'])));
                                                $accesstype 	      = ucfirst(stripslashes(trim($Row['accesstype'])));
                                                $pin 	      = ucfirst(stripslashes(trim($Row['pin'])));
                                                $ponumber 	      = ucfirst(stripslashes(trim($Row['ponumber'])));
                                                $phone 	      = ucfirst(stripslashes(trim($Row['phone'])));
                                                $reccreated 	      = ucfirst(stripslashes(trim($Row['reccreated'])));
                                                $prepostcomm 	      = ucfirst(stripslashes(trim($Row['prepostcomm'])));
                                                $scheduleddate 	      = ucfirst(stripslashes(trim($Row['scheduleddate'])));
                                                $conferencetype 	      = ucfirst(stripslashes(trim($Row['conferencetype'])));
                                                $reservationtype 	      = ucfirst(stripslashes(trim($Row['reservationtype'])));
                                                $dialedout 	      = ucfirst(stripslashes(trim($Row['dialedout'])));
                                                $soundbyte 	      = ucfirst(stripslashes(trim($Row['soundbyte'])));
                                                $prairiesoundbyte 	      = ucfirst(stripslashes(trim($Row['prairiesoundbyte'])));
                                                $ani 	      = ucfirst(stripslashes(trim($Row['ani'])));
                                                $dnis 	      = ucfirst(stripslashes(trim($Row['dnis'])));
                                                $destinationcountrycode 	      = ucfirst(stripslashes(trim($Row['destinationcountrycode'])));
                                                $externalid 	      = ucfirst(stripslashes(trim($Row['externalid'])));
                                                $recordcount 	      = ucfirst(stripslashes(trim($Row['recordcount'])));
                                                $created_date      = ucfirst(stripslashes(trim($Row['created_date'])));



                                                if($row%2==0)

                                                  $display_color="#EEF2FD";

                                                else

                                                  $display_color="#f6f6f6";

                                        ?>

                                                <tr bgcolor="<?php echo $display_color?>" >
                                                        <td  align="center">&nbsp;<?php echo $uniquerowid?></td>                                                              
                                                        <td  align="center">&nbsp;<?php echo $uniqueconfid?></td>
                                                        <td  align="center">&nbsp;<?php echo $confid?></td>
                                                        <td  align="center">&nbsp;<?php echo $bridgename?></td>
                                                        <td  align="center">&nbsp;<?php echo $countrycode?></td>
                                                        <td  align="center">&nbsp;<?php echo $intlcompanyid?></td>
                                                        <td  align="center">&nbsp;<?php echo $intlclientid?></td>
                                                        <td  align="center">&nbsp;<?php echo $intlcountrycode?></td>
                                                        <td  align="center">&nbsp;<?php echo $participantname?></td>
                                                        <td  align="center">&nbsp;<?php echo $conferencetitle?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($connecttime) === false && $connecttime != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($connecttime)):"-";?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($disconnecttime) === false && $disconnecttime != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($disconnecttime)):"-";?></td>
                                                        <td  align="center">&nbsp;<?php echo $duration?></td>
                                                        <td  align="center">&nbsp;<?php echo $bridgetype?></td>
                                                        <td  align="center">&nbsp;<?php echo $accesstype?></td>
                                                        <td  align="center">&nbsp;<?php echo $pin?></td>
                                                        <td  align="center">&nbsp;<?php echo $ponumber?></td>
                                                        <td  align="center">&nbsp;<?php echo $phone?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($reccreated) === false && $reccreated != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($reccreated)):"-";?></td>
                                                        <td  align="center">&nbsp;<?php echo $prepostcomm?></td>
                                                        <td  align="center">&nbsp;<?php echo (empty($scheduleddate) === false && $scheduleddate != '0000-00-00 00:00:00' )?date('m-d-Y H:i:s', strtotime($scheduleddate)):"-";?></td>
                                                        <td  align="center">&nbsp;<?php echo $conferencetype?></td>
                                                        <td  align="center">&nbsp;<?php echo $reservationtype?></td>
                                                        <td  align="center">&nbsp;<?php echo $dialedout?></td>
                                                        <td  align="center">&nbsp;<?php echo $soundbyte?></td>
                                                        <td  align="center">&nbsp;<?php echo $prairiesoundbyte?></td>
                                                        <td  align="center">&nbsp;<?php echo $ani?></td>
                                                        <td  align="center">&nbsp;<?php echo $dnis?></td>
                                                        <td  align="center">&nbsp;<?php echo $destinationcountrycode?></td>
                                                        <td  align="center">&nbsp;<?php echo $externalid?></td>
                                                        <td  align="center">&nbsp;<?php echo $recordcount?></td>
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
                    <?php 

					  //Paging starts
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
       if($('#startdate').val() == '' && 
           $('#enddate').val() == '' && 
           $('#ConferenceId').val() == ''&& 
           $('#ClientId').val() == ''&& 
           $('#countrycode').val() == '' &&
           $('#conferencetype').val() == '' &&
           $('#Dialedout').val() == '' 
   )
        {
            alert('Please enter atleast one value to search');
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