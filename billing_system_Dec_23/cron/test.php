<?php
error_reporting(E_ALL);
$fp = fopen('/var/www/html/lotusstaging.com/cron/data.txt', 'a');
echo date('l jS \of F Y h:i:s A') . "<BR>";
$str = fwrite($fp, date('l jS \of F Y h:i:s A') . "\n");
echo $str;
fclose($fp);
if (!function_exists('file_put_contents'))
{
	function file_put_contents($filename, $data) {
		$f = @fopen($filename, 'w');
		if (!$f) {
			return false;
		} else {
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
	}
}
function get_file_contents($filename)
/* Returns the contents of file name passed*/
{
	if (!function_exists('file_get_contents'))
	{
		$fhandle = fopen($filename, "r");
		$fcontents = fread($fhandle, filesize($filename));
		fclose($fhandle);
	}
	else
	{
		$fcontents = file_get_contents($filename);
	}
	return $fcontents;
}
?>