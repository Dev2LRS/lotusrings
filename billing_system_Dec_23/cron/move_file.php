<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	Source FTP information																									//
//	-----------------------																									//
//	SRC_SERVER			-	Source FTP server name																			//
//	SRC_USERNAME		-	Source FTP username																				//
//	SRC_PASSWORD		-	Source FTP password																				//
//	SRC_SOURCE_DIR		-	Multiple source folder in the FTP seperated by "***"	[PR***GC]								//
//	SRC_DESTINATION_DIR	-	It will	be considered as target folder while upload file to target FTP.							//
//							Leave it blank, if source folder is same as the destination folder.								//
//							If its not same, you must enter	source_dir folder count should be								//
//							match as destination folder count.																//
//							(Eg)																							//
//							SRC_SOURCE_DIR		- [PR***GC]																	//
//							SRC_DESTINATION_DIR	- [PR***GC] OR [PR***PR]													//
//	SRC_SENDER_MAIL		-	"From" in mail notification																		//
//	SRC_RECEIVER_MAIL	-	"To" in mail notification [multiple mails seperated by "***"]									//
//																															//
//	Target FTP information																									//
//	-----------------------																									//
//	DEST_SERVER				-	Target FTP server name																		//
//	DEST_USERNAME			-	Target FTP username																			//
//	DEST_PASSWORD			-	Target FTP password																			//
//	DEST_SOURCE_DIR			-	Leave it blank, if Target ftp pointed to directly uploaded folder, Otherwsie give it as path//
//								of the folder from ftp connected folder like [httpdocs/mars/others/user_data/]				//
//	DEST_DESTINATION_DIR	-	Leave it blank																				//
//	DEST_SENDER_MAIL		-	"From" in mail notification																	//
//	DEST_RECEIVER_MAIL		-	"To" in mail notification [multiple mails seperated by "***"]								//
//																															//
//	Comments																												//
//	---------																												//
//	Download Process																										//
//	----------------																										//
//	This file used to download files from source FTP depend on last download time of each folder							//
//																															//
//	Upload Process																											//
//	----------------																										//
//	Once complete the download files to local server, it will start upload files to target server. It will remove the file	//															//	from local server once successfully uploaded to file to target server.													//
//																															//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
set_time_limit(0);
error_reporting(0);
//Source FTP/Folder/Mail Notification details
$SRC_SERVER			="ftp.iprepublic.com";
$SRC_USERNAME		="Allconf";
$SRC_PASSWORD		="Allc0nf!";
$SRC_SOURCE_DIR		="PR***GC***IC";
$SRC_DESTINATION_DIR="******";
$SRC_SENDER_MAIL	="prahathese.shenll@gmail.com";
$SRC_RECEIVER_MAIL	="prahathese.shenll@gmail.com***ayyappan.shenll@gmail.com";

//Target FTP/Folder/Mail Notification details
$DEST_SERVER			="";
$DEST_USERNAME			="";
$DEST_PASSWORD			="";
$DEST_SOURCE_DIR		="";
$DEST_DESTINATION_DIR	="";
$DEST_SENDER_MAIL		="";
$DEST_RECEIVER_MAIL		="";

global $conn_id,$login_result,$servername,$username,$password,$logfile,$logfileup;
//Download files from server starts here
//Process source server  details one by one starts here
writelog("==========================================\n");
if(!empty($SRC_SERVER) && !empty($SRC_USERNAME) && !empty($SRC_PASSWORD) && !empty($SRC_SOURCE_DIR))
{
	writelog("=========Started to connect with ftp server========\n");
	$servername		=$SRC_SERVER;
	$username		=$SRC_USERNAME;
	$password		=$SRC_PASSWORD;
	$source_dir		=$SRC_SOURCE_DIR;
	$destination_dir=$SRC_DESTINATION_DIR;
	$sender_mail	=$SRC_SENDER_MAIL;
	$receiver_mail	=$SRC_RECEIVER_MAIL;
	$Arrsource_dir		=explode("***",$source_dir);
	$Arrdestinationdir	=explode("***",$destination_dir);
	$Arrlastdownload	=explode("***",fungetlastdownload());

	writelog("connect with server ==> ".$servername."\n");
	//set up basic connection
	$conn_id = ftp_connect($servername);
	writelog("login with server using username and password \n");
	// login with username and password
	$login_result = ftp_login($conn_id, $username, $password); 
	writelog("login with server passive mode \n");
	//Change mode as passive
	ftp_pasv($conn_id,true);
	if($login_result && $conn_id)//Connection successfully established
	{
		writelog("Successfully logged in to the server ==> ".$servername."\n");
		$fldrcnt=0;
		foreach($Arrsource_dir as $srcdir)
		{
			$downloadtime=time();
			writelog("Started to enter folder ==> ".$srcdir."\n");
			//Form array for change dir at last and get folder name to move files
			$Arrsrcdir=array_filter(explode("/",$srcdir));
			//change dir to download (source) folder
			if(ftp_chdir($conn_id,$srcdir)) 
			{
				writelog("Successfully entered into the folder ==> ".$srcdir."\n");
				//echo "success".$srcdir."<br>";
				//ftp_pasv($conn_id,true);
				writelog("Started to retrieve the files from folder ==> ".$srcdir."\n");
				//Check connection exists or not
				if(!$login_result || !$conn_id)//Connection successfully established
					ftpconnect($servername,$username,$password);
				$contents = ftp_nlist($conn_id, '.');
				if(count($contents)>0)
				{
					writelog("Successfully got the files from folder ==> ".$srcdir."\n");
					//print_r($contents);exit;
					$Arrsuccessfile=array();$Arrfailfile=array();
					foreach($contents as $filename)
					{
						if(is_numeric(strpos($filename,"/")))
						{	
							$Arrfilename=array_filter(explode("/",$filename));
							$filename=end($Arrfilename);
						}
						
						$Arrfiledet=pathinfo($filename);
						//Move files from ftp to local server.
						if($filename!="." && $filename!=".." && $Arrfiledet["extension"]!="")
						{
							$destdir="data/".end($Arrsrcdir)."/";
							@mkdir($destdir,0777,true);
							//Check connection exists or not
							if(!$login_result || !$conn_id)//Connection successfully established
								ftpconnect($servername,$username,$password);

							 $filemodtime = ftp_mdtm($conn_id,$filename);//It will return the file timestamp
							
							//Check connection exists or not
							if(!$login_result || !$conn_id)//Connection successfully established
								ftpconnect($servername,$username,$password);
							 $lastdownload=$Arrlastdownload[$fldrcnt];
							 echo $filename."   ---->   File last modified: ".date("d-M-Y H:i:s.",$filemodtime).'----------Last Download: '.date("d-M-Y H:i:s.",$lastdownload)."<br>";
						
							if(empty($lastdownload) || ($filemodtime>$lastdownload))
							{
								writelog("Started to move file $filename from the folder ==> ".$srcdir."\n");
								if(ftp_get($conn_id,$destdir.$filename,$filename,FTP_BINARY))
								{
									//Success	
									writelog("Successfully moved the file to local ==> ".$filename."\n");
									//Successfully moved the files to the folder
									$Arrsuccessfile[]=$filename;
								}
								else
								{
									writelog("Error occured while move the file to local ==> ".$filename."\n");
									//Error occured while moved the files to the folder
									$Arrfailfile[]=$filename;									
									/*Fail : Check connection exist or not, if not exists reconnect otherwise note the file as error */
									if(!$login_result || !$conn_id)//Connection successfully established
									{
										ftpconnect($servername,$username,$password);
									}
								}
							}
						}
					}
				}
				else
				{
					if(!$contents)
						writelog("No files or Error occured while retrieve the files from folder ==> ".$srcdir."\n");
					else if(count($contents)==0)
						writelog("No files present in the folder ==> ".$srcdir."\n");
				}
				//print_r($contents);
			}
			else
			{
				writelog("Error occured while to change dir to source dir ==> ".$srcdir."\n");
				/*Fail : Check connection exist or not, if not exists reconnect otherwise note the dir having as error */
				if(!$login_result || !$conn_id)//Connection successfully established
				{
					ftpconnect($servername,$username,$password);
				}
			}
			if(count($contents)>0)
				writelog("Finished the reading of files from folder ==> ".$srcdir."\n");
			//Change dir to ftp root
			//check last char contain "/", if hash present in the srcdir remove that
			//$srcdir=(substr($srcdir,strlen($srcdir)-1,strlen($srcdir))=="/")?substr($srcdir,0,strlen($srcdir)-1):$srcdir;
			//$Arrsrcdir=explode("/",$srcdir);
			writelog("Started to change dir to original \n");
			$srcchdir="";
			for($i=0;$i<count($Arrsrcdir);$i++)
				$srcchdir.="../";
			//Check connection exists or not
			if(!$login_result || !$conn_id)//Connection successfully established
				ftpconnect($servername,$username,$password);
			//Change dir to ftp root
			if(ftp_chdir($conn_id,$srcchdir))
			{
				//success
				writelog("Successfully changed the dir to original \n");
			}
			else
			{
				writelog("Error occured while to change dir to original \n");
				/*Fail : Check connection exist or not, if not exists reconnect otherwise note the dir having as error */
				if(!$login_result || !$conn_id)//Connection successfully established
				{
					ftpconnect($servername,$username,$password);
				}
			}
			
			//Mail send process starts here
			$from	=implode(",",explode("***",$sender_mail));
			$to		=implode(",",explode("***",$receiver_mail));
			//Success Mail
			if(count($Arrsuccessfile)>0)
			{
				$subject="Successful Transfers";
				$Arrsucmsg=array();
				foreach($Arrsuccessfile as $successfile)
					$Arrsucmsg[]=$successfile;
				$sucmsg=implode("<br>",$Arrsucmsg);
				$message="<html><body>Hi,<br><br>
				The following files has been successfully downloaded to local folders.<br>
				$sucmsg<br><br>
				-Thanks
				</body></html>";
				writelog("Starting to send mail for successfully download\n");
				$mail=sendmail($from,$to,$subject,$message);
				($mail==1)?writelog("Success Mail has been successfully send to following user ".$receiver_mail."\n"):writelog("Error occured while sending Success mail to following user ".$receiver_mail."\n");
			}
			//Failure Mail
			if(count($Arrfailfile)>0)
			{
				$subject="Fail Transfers";
				$Arrfailmsg=array();
				foreach($Arrfailfile as $failfile)
					$Arrfailmsg[]=$failfile;
				$failmsg=implode("<br>",$Arrfailmsg);
				$message="<html><body>Hi,<br><br>
				Error occured while downloading following files to local folders.<br>
				$failmsg<br><br>
				-Thanks
				</body></html>";
				writelog("Starting to send mail for failed to download\n");
				$mail=sendmail($from,$to,$subject,$message);
				($mail==1)?writelog("Failure Mail has been successfully send to following user ".$receiver_mail."\n"):writelog("Error occured while sending Failure mail to following user ".$receiver_mail."\n");
			}
			
			$Arrlastdwnlad=explode("***",fungetlastdownload());
			//Mail send process ends here
			for($i=0;$i<count($Arrsource_dir);$i++)
			{	$Arrdownloadtime[$i]=($i==$fldrcnt && count($contents)>0)?$downloadtime:$Arrlastdwnlad[$i];	}
			//Update last_download time
			if(count($Arrfailfile==0))
				funsetlastdownload(implode("***",$Arrdownloadtime));
			unset($Arrsuccessfile);unset($Arrfailfile);
			$fldrcnt++;

		}
	}
	else
		writelog("Login Failed\n");
	ftp_close($conn_id);
	writelog("=========FTP server connection ended with $servername========\n");
}
else
	writelog("No source files found for download\n");
writelog("==========================================\n");
//Download files from server ends here
if(!empty($DEST_SERVER) && !empty($DEST_USERNAME) && !empty($DEST_PASSWORD) && !empty($SRC_SOURCE_DIR))
{
	//Upload files from server starts here
	writelogupload("==========================================\n");
	writelogupload("=========Started to connect with ftp server========\n");
	//print_r($Arrserverdetails);
	$servername		=$DEST_SERVER;
	$username		=$DEST_USERNAME;
	$password		=$DEST_PASSWORD;

	writelogupload("connect with server ==> ".$servername."\n");
	//set up basic connection
	$conn_id = ftp_connect($servername);
	writelogupload("login with server using username and password \n");
	// login with username and password
	$login_result = ftp_login($conn_id, $username, $password); 
	writelogupload("login with server passive mode \n");
	//Change mode as passive
	ftp_pasv($conn_id,true);
	if($login_result && $conn_id)//Connection successfully established
	{
		$changedirflag=0;
		if(!empty($DEST_SOURCE_DIR))
		{
			if(ftp_chdir($conn_id,$DEST_SOURCE_DIR))
			{
				$changedirflag=1;
				writelogupload("Successfully entered into the destination source folder ==> ".$DEST_SOURCE_DIR."\n");
			}
			else
			{
				$changedirflag=0;
				writelogupload("Error occured while change directory destination source folder ==> ".$DEST_SOURCE_DIR."\n");
			}
		}
		else 
			$changedirflag=1;
		if($changedirflag==1)
		{
			$ArrSourceDir=explode("***",$SRC_SOURCE_DIR);
			$fldrcnt=0;
			foreach($ArrSourceDir as $SourceDir)
			{
				$Arrsrc	=array_filter(explode("/",$SourceDir));
				$src_folder			=end($Arrsrc);
				if($SRC_DESTINATION_DIR!="")
				{
					$ArrDestDir=array_filter(explode("***",$SRC_DESTINATION_DIR));
					if(trim($ArrDestDir[$fldrcnt])!="")
					{
						$Arrdest=array_filter(explode("/",trim($ArrDestDir[$fldrcnt])));
						$destination_folder=end($Arrdest)."/".$src_folder;
					}
					else
						$destination_folder=$src_folder;
				}
				$src_folder	="data/".$src_folder;
				if(is_dir($src_folder))
				{
					//Create folder in target server if not exists
					$Arrdestdir=array_filter(explode("/",$destination_folder));
					writelogupload("Started to creating the folder ".$destination_folder."\n");
					foreach($Arrdestdir as $destdir)
					{
						$contents = ftp_nlist($conn_id, '.');
						if(!in_array($destdir,$contents))
						{
							if(ftp_mkdir($conn_id,$destdir))//Returns name of the path
							{
								writelogupload("Folder has been created successfully ==> ".$destdir."\n");
								if(ftp_chdir($conn_id,$destdir))
								{
									//Success
									writelogupload("Successfully entered into the folder ==> ".$destdir."\n");
									$newfolderflag=1;
								}
								else
								{
									writelogupload("Error occured while changing dir to destination folder ==> ".$destdir."\n");
									if(!$login_result || !$conn_id)//Connection successfully established
									{
										ftpconnect($servername,$username,$password);
									}
								}
							}
							else
							{
								//Fail
								writelogupload("Error occured while creating new destination folder ==> ".$destdir."\n");
								if(!$login_result || !$conn_id)//Connection successfully established
								{
									ftpconnect($servername,$username,$password);
								}
							}
						}
						else
						{
							if(ftp_chdir($conn_id,$destdir))
							{
								//Success
								writelogupload("Successfully entered into the folder ==> ".$destdir."\n");
								$newfolderflag=1;
							}
							else
							{
								writelogupload("Error occured while changing dir to destination folder ==> ".$destdir."\n");
								if(!$login_result || !$conn_id)//Connection successfully established
								{
									ftpconnect($servername,$username,$password);
								}
							}
						}
					}
					$Arrsrcfiles=scandir($src_folder);
					if(count($Arrsrcfiles)>0)
					{
						foreach($Arrsrcfiles as $srcfile)
						{
							$Arrfiledet=pathinfo($srcfile);
							if($srcfile!="." && $srcfile!=".." && $Arrfiledet["extension"]!="")
							{
								$source_file		=$src_folder."/".$srcfile;
								$destination_file	=$srcfile;
								if(ftp_put($conn_id,$destination_file,$source_file,FTP_BINARY))
								{
									writelogupload("Successfully moved the file from to local to destination folder ==> ".$destination_file."\n");
									//Delete file from folder
									@unlink($source_file);
									//update flag status as 0 for the file
									$Arrsuccessfile[]=$destination_file;
								}
								else
								{
									writelogupload("1 == Error occured while moving the file from to local to destination folder ==> ".$destination_file."\n");
									$Arrfailfile[]=$destination_file;
									if(!$login_result || !$conn_id)//Connection successfully established
									{
										ftpconnect($servername,$username,$password);
									}
								}
							}
						}
					}
					$destchdir="";
					for($i=0;$i<count($Arrdestdir);$i++)
						$destchdir.="../";
					
					if(ftp_chdir($conn_id,$destchdir))
					{
						//success
						writelogupload("Successfully changed the dir to original \n");
					}
					else
					{
						writelogupload("Error occured while to change dir to original \n");
						/*Fail : Check connection exist or not, if not exists reconnect otherwise note the dir having as error */
						if(!$login_result || !$conn_id)//Connection successfully established
						{
							ftpconnect($servername,$username,$password);
						}
					}
					//Mail send process starts here
					$from	=implode(",",explode("***",$DEST_SENDER_MAIL));
					$to		=implode(",",explode("***",$DEST_RECEIVER_MAIL));
					//Success Mail
					if(count($Arrsuccessfile)>0)
					{
						$subject="Successful Transfers";
						$Arrsucmsg=array();
						foreach($Arrsuccessfile as $successfile)
							$Arrsucmsg[]=$successfile;
						$sucmsg=implode("<br>",$Arrsucmsg);
						$message="<html><body>Hi,<br><br>
						The following files has been successfully uploaded to local folders.<br>
						$sucmsg<br><br>
						-Thanks
						</body></html>";
						writelogupload("Starting to send mail for successfully upload\n");
						$mail=sendmail($from,$to,$subject,$message);
						($mail==1)?writelogupload("Success Mail has been successfully send to following user ".$DEST_RECEIVER_MAIL."\n"):writelogupload("Error occured while sending Success mail to following user ".$DEST_RECEIVER_MAIL."\n");
					}
					//Failure Mail
					if(count($Arrfailfile)>0)
					{
						$subject="Fail Transfers";
						$Arrfailmsg=array();
						foreach($Arrfailfile as $failfile)
							$Arrfailmsg[]=$failfile;
						$failmsg=implode("<br>",$Arrfailmsg);
						$message="<html><body>Hi,<br><br>
						Error occured while uploading following files to local folders.<br>
						$failmsg<br><br>
						-Thanks
						</body></html>";
						writelogupload("Starting to send mail for failed to upload\n");
						$mail=sendmail($from,$to,$subject,$message);
						($mail==1)?writelogupload("Failure Mail has been successfully send to following user".$DEST_RECEIVER_MAIL."\n"):writelogupload("Error occured while sending Failure mail to following user".$DEST_RECEIVER_MAIL."\n");
					}
				}
				$fldrcnt++;
			}
		}
	}
	ftp_close($conn_id);
	writelogupload("=========FTP server connection ended========\n");
}

//Process source server  details one by one ends here

//Upload files from server ends here
function fungetlastdownload()
{
	if(!is_dir("billing_CDR/last_download"))
		@mkdir("billing_CDR/last_download/",0777,true);
	$lastdwnlad="";
	if(is_file("billing_CDR/last_download/last_download.don"))
		$lastdwnlad=file_get_contents("billing_CDR/last_download/last_download.don");
	return $lastdwnlad;
}

function funsetlastdownload($lastdwnladtme='')
{
	if(!is_dir("billing_CDR/last_download"))
		@mkdir("billing_CDR/last_download/",0777,true);
	$lastdwnladfle="billing_CDR/last_download/last_download.don";
	$fp = fopen($lastdwnladfle, 'w');
	fwrite($fp, $lastdwnladtme);
	fclose($fp);
}

//Process source server  details one by one ends here
function writelog($logmsg)
{
	global $logfile;
	if(!is_dir("billing_CDR/log"))
		@mkdir("billing_CDR/log/",0777,true);
	$loglimit=1048576;//check to exceed 1MB=1048576, 1KB=1024
	$logfileflag=0;$filesize=0;
	if($logfile!="")
	{
		clearstatcache();
		$filesize=filesize($logfile);
		if($filesize>$loglimit)
			$logfileflag=1;
	}
	else
		$logfileflag=1;
	if($logfileflag==1)
	{
		//check file size
		$file='billing_CDR/log/log_download_'.date("d_m_Y");
		$Arrfiles=glob($file."*.txt");
		//Asc the file
		foreach($Arrfiles as $ffile)
		{
			$Arrpathfile=pathinfo($ffile);
			list($filename1,$filecnt)=explode("-",$Arrpathfile["filename"]);
			$Arrfilesort[$filecnt]=$ffile;
		}
		ksort($Arrfilesort);
		if(count($Arrfilesort)>0)
		{
			$lastfile=end($Arrfilesort);
			$Arrpath=pathinfo($lastfile);
			list($filename1,$filecnt)=explode("-",$Arrpath["filename"]);
			$filecnt=($filesize>$loglimit)?++$filecnt:$filecnt;
		}
		else
			$filecnt=1;
		$logfile=$file."-".$filecnt.".txt";
	}
	$fp = fopen($logfile, 'a');
	fwrite($fp, $logmsg);
	fclose($fp);
}

function writelogupload($logmsg)
{
	global $logfileup;
	if(!is_dir("billing_CDR/log"))
		@mkdir("billing_CDR/log/",0777,true);
	$loglimit=1048576;//check to exceed 1MB=1048576, 1KB=1024
	$logfileflag=0;
	if($logfileup!="")
	{
		clearstatcache();
		$filesize=filesize($logfileup);
		if($filesize>$loglimit)
			$logfileflag=1;
	}
	else
		$logfileflag=1;
	if($logfileflag==1)
	{
		//check file size
		$file='billing_CDR/log/log_upload_'.date("d_m_Y");
		$Arrfiles=glob($file."*.txt");
		//Asc the file
		foreach($Arrfiles as $ffile)
		{
			$Arrpathfile=pathinfo($ffile);
			list($filename1,$filecnt)=explode("-",$Arrpathfile["filename"]);
			$Arrfilesort[$filecnt]=$ffile;
		}
		ksort($Arrfilesort);
		if(count($Arrfilesort)>0)
		{
			$lastfile=end($Arrfilesort);
			$Arrpath=pathinfo($lastfile);
			list($filename1,$filecnt)=explode("-",$Arrpath["filename"]);
			$filecnt=($filesize>$loglimit)?++$filecnt:$filecnt;
		}
		else
			$filecnt=1;
		$logfileup=$file."-".$filecnt.".txt";
	}
	$fp = fopen($logfileup, 'a');
	fwrite($fp, $logmsg);
	fclose($fp);
}

function ftpconnect($servername,$username,$password)
{
	global $conn_id,$login_result,$servername,$username,$password;
	writelog("Started to Reconnect with server ==> ".$servername."\n");
	//set up basic connection
	$conn_id = ftp_connect($servername);
	writelog("Relogin with server using username and password \n");
	// login with username and password
	$login_result = ftp_login($conn_id, $username, $password); 
	writelog("Relogin with server passive mode \n");
	//Change mode as passive
	ftp_pasv($conn_id,true);
}

function sendmail($from,$to,$subject,$message)
{
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$headers.="From:".$from;
	return $retmsg=(@mail($to,$subject,$message,$headers))?1:0;

}
?>