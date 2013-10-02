<?php 
//echo "TEST".$_SESSION['ses_admin_id'];
if($_SESSION['ses_admin_id']!="") 
{
	$ses_id			= $_SESSION['ses_admin_id'];
	$sessionUser            = $_SESSION['ses_admin_name'];
	$ImgPath ="images/";
	$fileNameAry= pathinfo($_SERVER["PHP_SELF"]);
	$fileName   = $fileNameAry[filename];
    $fileName =  trim(trim($fileName, "manage-"),"add-");
    $$fileName = "strong";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo SITE_NAME; ?></title>
<meta NAME="Generator" CONTENT="EditPlus">
<meta NAME="Author" CONTENT="">
<meta NAME="Keywords" CONTENT="">
<meta NAME="Description" CONTENT="">
<style type="text/css"> body, img, div, td { behavior: url(iepngfix.htc) }</style>
<link rel="StyleSheet" href="css/style_new.css" type="text/css" >
<link rel="StyleSheet" href="css/ui-lightness/jquery-ui-1.8.23.custom.css" type="text/css" >

<!--[if lt IE 7]>
<script defer type="text/javascript" src="<?php echo $ScriptPath; ?>pngfix.js"></script>
<![endif]-->
<script src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jscript.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
<style>

</style>
<!-- SCRIPTS AND CSS FOR IFRAME ENDS HERE-->
</head>
<body  bgcolor="#fff">
<div id="header">
    
			<h1><a href="#">
                       <?php echo SITE_NAME; ?>
                    </a></h1>
		
		<div class="userprofile">
			<ul>				
				<li><?php if($_SESSION['ses_adminemail']!="") { ?>
						Welcome <?php echo $_SESSION['ses_admin_name'];?>&nbsp; | <a href="logout.php">Logout</a>
						<?php } else {?>
							<a href="index.php">Login</a>
						<?php } ?>
				</li>
			</ul>				
		</div>		<!-- .userprofile ends -->
			
	</div>	

<table border="0" align='left' cellspacing="0" cellpadding="0" style="width:100%;height:90%;">
 <td valign="top" width="240"align='left'>
    <div id="sidebar">	
		<ul id="nav">
				<li><a class="<?php echo $ProcessedFiles;?>" href="list_processed_files.php" ><strong><img src="images/dashboard.png" alt=""/> Processed Files</strong></a></li>
				<li><a href="#"><img src="images/pages.png" alt="" />Premiere Conferencing</a>
					<ul>	
						<li><a class="<?php echo $PRData;?>" href="list_pr_data.php"><img src="images/pages.png" alt="" />PC Report</a></li>
						<li><a class="<?php echo $view_premier;?>" href="pr_error_report.php"><img src="images/pages.png" alt="" />Premeir Error Report</a></li>
						<li><a class="<?php echo $comp_premier;?>" href="pr_error_cmp.php"><img src="images/pages.png" alt="" />Error Record Comparision</a></li>
					</ul>
				
				</li>

				<li>
					<a href="#" class="<?php echo $Administration;?>">Global Crossing CDR</a>
					<ul>	
						<li><a class="<?php echo $ConferenceData;?>" href="list_conference_data.php"><img src="images/pages.png" alt="" />Conference Report</a></li>
						<li><a class="<?php echo $CustomerData;?>" href="list_customer_data.php"><img src="images/pages.png" alt="" /> Customer Report</a></li>
						<li><a class="<?php echo $BridgeData;?>" href="list_bridge_data.php"><img src="images/pages.png" alt="" /> Bridge Report</a></li>
						<li><a class="<?php echo $NonBridgeData;?>" href="list_nonbridge_data.php"><img src="images/pages.png" alt="" />Non-Bridge Report</a></li>
						<li><a class="<?php echo $view_global;?>" href="glb_brg_err.php"><img src="images/pages.png" alt="" />Global Error report</a>
						</li>
						<li><a class="<?php echo $comp_global;?>" href="glb_err_cmp.php"><img src="images/pages.png" alt="" />Error Record Comparision</a>
						</li>
					</ul>
				</li>
				<li><a  href="move_processed.php"><img src="images/support.png" alt="" /> Move Processed Records</a></li>
				<li><a  href="logout.php"><img src="images/support.png" alt="" /> Logout</a></li>
		</ul>
	</div>	
    </td>
    <td valign="top"  align='left'>
        <?php
            if(!empty($_SESSION['message'])){
                $message = $_SESSION['message'];
                $className = ($_SESSION['status'] == 'success')?'success':'errormsg';
                unset($_SESSION['message'],$_SESSION['status']);
        ?>
        <div class="message">            
                <div class="<?php echo $className;?>"><?php echo $message;?></div>            
        </div>
        <?php } ?>