<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
$RecordsPerPage=15;
$Page=1;
if(isset($_REQUEST['HdnPage']) && is_numeric($_REQUEST['HdnPage']))
  $Page=$_REQUEST['HdnPage'];
else if(isset($_GET["pgnum"]))
  $Page=$_GET["pgnum"];

if(!is_numeric($Page))
$Page=1;

$filter_cond='';
if(isset($_POST['hdn_search']) && $_POST['hdn_search']=="hdn_val"){
	$cond='1=1';
	if($_POST['status']!=""){
		$selvalue=$_POST['status'];
		$cond.=" and record_status='$selvalue'";
	}
	if($_POST['startdate']!=""){
		$pst_str_dt=date('Y-m-d H:i:s', strtotime($_POST['startdate']." 00:00:00"));
		$StartDate=date('m/d/Y',strtotime($pst_str_dt));
		
		$cond.=" and connecttime >='$pst_str_dt'";
	}
	if($_POST['enddate']!=""){
		$pst_end_dt=date('Y-m-d H:i:s', strtotime($_POST['enddate']." 23:59:59"));
		$EndDate=date('m/d/Y',strtotime($pst_end_dt));
		
		$cond.=" and connecttime <='$pst_end_dt'";

	}

	
	$filter_cond="where ".$cond;
}
require_once("header.php");

?>
<table width="98%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td class="red1" height="20" align="left"></td>
</tr>

<tr>
    <td class="red1" align="left" ><h2>Premier Error Report</h2><?php if(isset($_GET["msg"]) && $_GET["msg"]==1){?><h4 style="color:#00CC00">Updated successfully</h4><?php }?></td>
 </tr>

<tr>
  <td height="200" align="left" valign="top" class="text" >
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" class="listTable">
		<tr>
			<td align="left" valign="top"></td>
			<td align="left" valign="top">
				<form name="errorform" id="errorform" method="POST">
				 <input type="hidden" name="HdnPage" id="HdnPage" value="<?php echo $Page; ?>">   
				 <input type="hidden" name="hdn_search" value="hdn_val">
				 <input type="hidden" name="HdnMode" id="HdnMode" value="<?php echo $Page; ?>">
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
                                        
										<td  align="left"  nowrap>&nbsp;Status:</td>
                                        <td  align="left">&nbsp;
											<select name='status' id='status'>
												<option value=''>-Select-</option>
												<option value='FIXED' <?php if($selvalue=='FIXED') echo 'selected';?>>Fixed</option>
												<option value='MANUAL_FIX' <?php if($selvalue=='MANUAL_FIX') echo 'selected';?>>Manual Fix</option>
											</select>
										</td>
                                    </tr>
                                   
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>                                        
                                        <td  align="center" colspan="6">&nbsp;<input type="submit" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='pr_error_report.php'"/></td>
                                    </tr>
									<tr height='10px'>
										<td></td>
									</tr>
                                </table>
                            </td>                             
                        </tr>                       
                  </table>
		</td>
	</tr>
	</table>
	
                  <div class="space20"></div>
                  <div class="rec-listview">                      
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
							<th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="130" sortdata="record_status" >Record Status</th>
							<th align="center" nowrap  style="padding-left:5px;" class="sort_this">Error Code</th>
							<th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="70" style='position:fixed'>Option</th>
                        </tr>
						<?php
							$q=1;
								
								$Get_SelQry=mysql_query("select * from tbl_pr_error $filter_cond");
								$intUsrCount=mysql_num_rows($Get_SelQry);
							  $TotalPages=ceil($intUsrCount/$RecordsPerPage);
                              $Start=($Page-1)*$RecordsPerPage;
							  $SelQry=mysql_query("select `raw_rec_id`, `uniquerowid`, `uniqueconfid`, `confid`, `bridgename`, `countrycode`, `intlcompanyid`, `intlclientid`, `intlcountrycode`, `participantname`, `conferencetitle`, `connecttime`, `disconnecttime`, `duration`, `bridgetype`, `accesstype`, `pin`, `ponumber`, `phone`, `reccreated`, `prepostcomm`, `scheduleddate`, `conferencetype`, `reservationtype`, `dialedout` + 0, `soundbyte` + 0, `prairiesoundbyte` + 0, `ani`, `dnis`, `destinationcountrycode`, `externalid`, `recordcount`, `created_date`, `modified_date`, `record_status`, `error_code` from tbl_pr_error $filter_cond limit $Start, $RecordsPerPage");
							if(mysql_num_rows($SelQry)>0){
								while($get_arr=mysql_fetch_assoc($SelQry)){
									if($q%2==0)
										$rowclr='#f6f6f6';
									else $rowclr='#EEF2FD';
									echo "<tr style='background:$rowclr'>";
									foreach($get_arr as $key=>$eachcell){
										
										if($key!='created_date' and $key!='modified_date' and $key!='raw_rec_id'){
											if(trim($eachcell)!="")
												echo '<td  align="center" nowrap>'.$eachcell.'</td>'; 
											else
												echo '<td  align="center" nowrap>-</td>'; 
										}
									}
									echo "<td align='center'><a href='edit_prdata.php?errorid=".$get_arr['raw_rec_id']."&pgnum=$Page' onclick='return funcalert()'>Edit</a></td></tr>";
									$q++;
								}
							}else echo '<tr><td colspan="33">No records found<td><tr>';
						?>
						</table>
						</div>
	</td>
</tr>
<tr>
	<td style="text-align:center;">
		<table class="pagingtable">
		 <?php 
			  if($TotalPages > 1)
			  {
				echo "<tr ><td align='center' colspan='8' valign='middle' class='pagination' >";
				if($TotalPages>1)
				{
					$FormName = "errorform";
					require_once ("paging.php");
				}
				echo "</td></tr>";
			  }

			?>
		</table>
	</td>
</tr>
</table>
</form>
<script>
 $(function() {
        $( "#startdate,#enddate" ).datepicker();
    });

	function funcstatus(){
		$("#errorform").submit();			

	}
	function funcalert(){
		if(confirm("Are you sure want to edit this record?"))
			return true;
		else return false;

	}
</script>
<?php

require_once("footer.php");

?>