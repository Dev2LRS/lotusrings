<?php
//session_start();
//error_reporting(E_WARNING);

$StrSiteMode="live";

if($StrSiteMode=="local") {
	define('MASTER_SERVER',"localhost");
	define('MASTER_DB',"ratc");
	define('MASTER_USERNAME',"root");
	define('MASTER_PASSWORD',"");
	define('ROOT_PATH',"C:\\Program Files\\");

} else if($StrSiteMode=="demo") {
	define('MASTER_SERVER',"localhost");
	define('MASTER_DB',"theturng_thomas");
	define('MASTER_USERNAME',"theturng_thomas");
	define('MASTER_PASSWORD',"P0AKfLq%=TKM");
	define('ROOT_PATH',"/home/theturng/public_html/thomas/");	
} else {

echo "wel";

	//define('MASTER_SERVER',"65.99.240.66");
	define('MASTER_SERVER',"localhost");
	define('MASTER_DB',"srguiden_thomascircle");
	define('MASTER_USERNAME',"srguiden_thomas");
	define('MASTER_PASSWORD',"th0m@sc1rc13");
	define('ROOT_PATH',"");	
}

$link = mysql_connect(MASTER_SERVER, MASTER_USERNAME, MASTER_PASSWORD);
mysql_select_db(MASTER_DB,$link);

exit;

define('SITE_NAME',"Admin Control Panel");

$pass='Q2!3_2^~"15+@#u';
$Qry="INSERT INTO `tbl_admin` (`admin_type`, `first_name`, `last_name`, `admin_email`, `password`, `status`, `add_on_date`) VALUES
('admin', 'Thomas', '', 'loriw@zillner.com', '$pass', 'Y', '2012-03-19 23:14:32'),
('admin', 'Admin', '', 'rodney@theturngroup.com', 'zs13214072', 'Y', '2012-03-19 23:14:23');";

$Qry="update tbl_admin set `first_name`='Loriw' where admin_email='loriw@zillner.com'";
$Qry="update tbl_admin set `first_name`='Rodney' where admin_email='rodney@theturngroup.com'";

if(!mysql_query($Qry))
 echo mysql_error ();


?>