<?php
if(!isset($_SESSION['ses_adminid']) || trim($_SESSION['ses_adminid'])=="")
{	
  header("Location: index.php");
  exit;
}
?>