<?php
error_reporting(E_ALL);
$dir=$_SERVER['DOCUMENT_ROOT'];
$dest= $dir.'/cron/data/PR/processed/AC_DailyTraffic_01232012.csv';
$source= $dir.'/cron/data/PR/AC_DailyTraffic_01232012.csv';
echo $dest;
copy('http://lotusstaging.com/cron/data/PR/AC_DailyTraffic_01232012.csv' , $dest); 
$errors= error_get_last();
    echo "COPY ERROR: ".$errors['type'];
    echo "<br />\n".$errors['message'];
?>