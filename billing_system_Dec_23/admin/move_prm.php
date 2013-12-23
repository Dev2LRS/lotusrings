<?php
require_once("includes/config.php");
?>
<div width="100%" height='300px' style='text-align:center'>
 <img src='images/processing.gif'  style='width:100px;height:100px;align:center' id="proc_img"><div style="display:none" id="msg"><p style="color:green;font-size:18px;">Process Completed</p></div>
</div>
<?php
$get_last_id=mysql_query("insert into tbl_pr_data  select `raw_rec_id`, `uniquerowid`, `uniqueconfid`, `confid`, `bridgename`, `countrycode`, `intlcompanyid`, `intlclientid`, `intlcountrycode`, `participantname`, `conferencetitle`, `connecttime`, `disconnecttime`, `duration`, `bridgetype`, `accesstype`, `pin`, `ponumber`, `phone`, `reccreated`, `prepostcomm`, `scheduleddate`, `conferencetype`, `reservationtype`, `dialedout`, `soundbyte`, `prairiesoundbyte`, `ani`, `dnis`, `destinationcountrycode`, `externalid`, `recordcount`, `created_date`, `modified_date`, `record_status` from tbl_pr_error where record_status='FIXED' ON DUPLICATE KEY UPDATE tbl_pr_data.`raw_rec_id`=tbl_pr_error.`raw_rec_id`");


if(mysql_errno()){
echo "<p style='color:red'>MySQL Error while processing records:".mysql_error()."</p>";
exit;
}
?>
<script>
setTimeout(function(){
document.getElementById("proc_img").style.display="none";
document.getElementById("msg").style.display="";
},1000);

</script>