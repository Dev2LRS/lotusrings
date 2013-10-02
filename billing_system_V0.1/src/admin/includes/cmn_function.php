<?php
function funSendMail($ToAddress,$FromAddress,$Subject,$Message)
{
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: ".$FromAddress."\r\n";
	@mail($ToAddress, $Subject, $Message, $headers);
}

function funGetRecrdcount($strQry,$strKey)
{
	 $result = mysql_query($strQry);
	 $num_rows = mysql_num_rows($result);
	  $row = mysql_fetch_array($result);	 
	  $strOuput =$row[$strKey];
 
	 return $strOuput;
}

function CSVExport($query) {
    
	
   $sql_csv = mysql_query($query) or die("Error: " . mysql_error()); //Replace this line with what is appropriate for your DB abstraction layer
   
    if(mysql_num_rows($sql_csv) > 0) {
       $row = mysql_fetch_assoc($sql_csv);
       $arry = array_keys($row);
       $csv_output = '"' . stripslashes(implode('","',$arry)) . "\"\n";
    }

   $sql_csv2 = mysql_query($query) or die("Error: " . mysql_error()); //Replace this line with what is appropriate for your DB abstraction layer

   header("Content-type:text/octect-stream");
   header("Content-Disposition:attachment;filename=data.csv");
   while($row2 = mysql_fetch_row($sql_csv2)) {
      $csv_output  .= '"' . stripslashes(implode('","',$row2)) . "\"\n";
    }
   print  $csv_output;
   exit;
}

 
function funChknum($num)
{
	$num=trim($num);
	if($num!="" && is_numeric($num))
	{
		return $num;
	}
	else
	{
		 return $num;
	}
}
 
function createThumb($source,$dest,$thumb_size = 150)
{	
	$size = getimagesize($source);
	$width = $size[0];
	$height = $size[1];

	if($width > $height) {
		$x = ceil(($width - $height) / 2 );
		$width = $height;
	} elseif($height > $width) {
		$y = ceil(($height - $width) / 2);
		$height = $width;
	}

	$new_im = ImageCreatetruecolor($thumb_size,$thumb_size);
	$im = imagecreatefromjpeg($source);
	imagecopyresampled($new_im,$im,0,0,$x,$y,$thumb_size,$thumb_size,$width,$height);
	imagejpeg($new_im,$dest,100);
}


//function used to resize an images
function funResizeImagesgd($SrcImage,$DesImage,$dependson,$Fromside,$width=620,$height=560)
{ 
    if(is_file($SrcImage) && filesize($SrcImage)>0)
	{	  
	    require_once('includes/class.asido.php');
	
		/*** Set the correct driver: this depends on your local environment	*/
		//if(RUNNING_ON=="local")
            
		  asido::driver('gd');
                  
		//else
          //asido::driver('imagick_shell');

		 //Create an Asido_Image object and provide the name of the source
		 // image, and the name with which you want to save the file
		 $i1 = asido::image($SrcImage,$DesImage);

		if($dependson=="width")
		{
		 //Resize the image proportionally only by setting only the width, and the height will be corrected accordingly
		 Asido::width($i1,$width);
		}
		else if($dependson=="height")
		{		 
		//Resize the image proportionally only by setting only the height, and the width will be corrected accordingly
		 Asido::height($i1,$height);
		}
		else if($dependson=="fit")
		{	 
		 //Resize an image by "fitting" in the provided width and height
		 Asido::fit($i1,$width,$height);		
		}

		 // Save the result
		 $i1->save(ASIDO_OVERWRITE_ENABLED);
                 

	}//is_file($SrcImage) check ends
}


function remove_empty(&$item, &$key)
{
   $item =  empty($item)?'-':trim($item);
}
?>