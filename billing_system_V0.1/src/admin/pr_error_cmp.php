<?php
require_once("includes/config.php");
require_once("includes/cmn_function.php");
require_once("session_check.php");
$comp_premier='strong';
$RecordsPerPage=10;
$Page=1;
if(isset($_REQUEST['HdnPage']) && is_numeric($_REQUEST['HdnPage']))
  $Page=$_REQUEST['HdnPage'];
else if(isset($_GET["pgnum"]))
  $Page=$_GET["pgnum"];

if(!is_numeric($Page))
$Page=1;

$filter_cond='1=1';
if(isset($_POST['status']) && $_POST['status']!=""){
$selvalue=$_POST['status'];
 $filter_cond=" record_status='$selvalue'";
}
require_once("header.php");

?>
<form name="pr_err_cmp" id="pr_err_cmp" method="post" action="">
	<input type="hidden" name="HdnPage" id="HdnPage" value="<?php echo $Page; ?>">   
	<input type="hidden" name="HdnMode" id="HdnMode" value="<?php echo $Page; ?>">

	<table width='1100px'>
	<tr height="20px"><td></td></tr>
	<tr>
		<td class="red1" align="left" ><h2>Premier Error Record Comparision</h2></td>
	</tr>
	<tr>
		<td>
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
                                        <td  align="left"  nowrap>&nbsp;Start Date:</td>
                                        <td  align="left"><input type="text" name="startdate" id="startdate" value="<?php echo $StartDate;?>" readonly/></td>  

                                        <td  align="left"  nowrap>&nbsp;End Date:</td>
                                        <td  align="left"> <input type="text" name="enddate" id="enddate" value="<?php echo $EndDate;?>" readonly/></td>  
                                        
										<td  align="left"  nowrap>&nbsp;Status:</td>
                                        <td  align="left">&nbsp;
											<select name='status' id='status' onchange='funcstatus()'>
												<option value=''>-Select-</option>
												<option value='FIXED' <?php if($selvalue=='FIXED') echo 'selected';?>>Fixed</option>
												<option value='MANUAL_FIX' <?php if($selvalue=='MANUAL_FIX') echo 'selected';?>>Manual Fix</option>
												<option value='PROCESSED' <?php if($selvalue=='PROCESSED') echo 'selected';?>>Processed</option>
											</select>
										</td>
                                    </tr>
                                   
                                    <tr> <td colspan="6" height="15"></td></tr>
                                    <tr>                                        
                                        <td  align="center" colspan="6">&nbsp;<input type="button" class="button" value="Search" id="SearchBt"/>&nbsp;&nbsp;&nbsp;<input type="button" class="button" value="Show All" id="Showall" onclick="document.location='pr_error_report.php'"/></td>
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
	<tr height="20px"><td></td></tr>
	<tr>
		<td>
			<div class="" style="width:1100px;overflow:auto;">                      
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
						<th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="70" style='position:fixed'>Error Code</th>
						<th align="center" nowrap  style="padding-left:5px;" class="sort_this" width="70" style='position:fixed'>Option</th>
					</tr>
					<?php
							$j=1;
							$Get_sel_err_rec=mysql_query("select * from tbl_pr_filtered where uniquerowid in (select uniquerowid from tbl_pr_error) and $filter_cond");
							$intUsrCount=mysql_num_rows($Get_sel_err_rec);
						if($intUsrCount>0){
							  $TotalPages=ceil($intUsrCount/$RecordsPerPage);
                              $Start=($Page-1)*$RecordsPerPage;
							  $sel_err_rec=mysql_query("select * from tbl_pr_filtered where uniquerowid in (select uniquerowid from tbl_pr_error) and $filter_cond limit $Start, $RecordsPerPage");
								while($get_array=mysql_fetch_assoc($sel_err_rec)){
									$id=$get_array["id"];
									if($j%2==0)
										$color='#f6f6f6';
									else
										$color='#EEF2FD';
									$row_id=$get_array["uniquerowid"];
									echo "<tr style='background:$color' >";
									foreach($get_array as $key=>$cell){
										if($key!='id' && $key!='created_date' && $key!='modified_date')
										echo "<td align='center'>".$cell."</td>";
									}
									echo "<td></td><td rowspan='2' align='center'><a href='edit_prdata.php?errorid=$id&errcmp=yes' onclick='return funcalert()'>Edit</a></td></tr><tr style='background:#fc98a9'>";
									$get_err=mysql_query("select * from tbl_pr_error where uniquerowid=$row_id");
									$get_err_arr=mysql_fetch_assoc($get_err);
									
									foreach($get_err_arr as $key=>$err_cell){
										if($key!='error_id' && $key!='created_date' && $key!='modified_date')
										echo "<td align='center'>".$err_cell."</td>";
									}
									
									echo "</tr>";
									$j++;
								}
						}else {
								echo "<tr><td colspan='34'>No records found</td></tr>";
						}
					?>
				</table>
			</div>
		</td>
	</tr>
	<tr>
	<td >
		<table style="margin:auto">
		 <?php 
			  if($TotalPages > 1)
			  {
				echo "<tr><td align='center' colspan='8' valign='middle' class='pagination'>";
				if($TotalPages>1)
				{
					$FormName = "pr_err_cmp";
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
function funcalert(){
		if(confirm("Are you sure want to edit this record?"))
			return true;
		else return false;

	}

 $(function() {
        $( "#startdate,#enddate" ).datepicker();
    });

	function funcstatus(){
		$("#pr_err_cmp").submit();			

	}
</script>





 <?php

require_once("footer.php");

?>