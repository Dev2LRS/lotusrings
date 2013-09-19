//function used to return the extension of the given file
function funCheckNumeric(strInput)
{
  if(strInput=="" || (strInput!="" && isNaN(strInput)))
   strOutput=0;
  else
   strOutput=strInput;

  return strOutput;
}

//function used to check if the given date is valid or not
function funValidate_Date2(strDate)
{
      var validate = false;
	   if(trimAll(strDate)!="")
	   {
		 strDate= replace(strDate,"-","/");
		 var arrDate = strDate.split('/');
		 if(arrDate.length==3)
		 {
		   var month = trimAll(arrDate[0]);
		   var date= trimAll(arrDate[1]);
		   var year= trimAll(arrDate[2]);

		   if(isNaN(month) || isNaN(date) || isNaN(year) || month.length!=2|| date.length!=2|| year.length<2 || year.length>4 || year.length==3)
			validate = false;
		   else
			 {
			  month = parseFloat(month);
		      date= parseFloat(date);
		      year= parseFloat(year);

			   if(month>0 && month<=12 && date>0 && date<=31 && year>6)
			     validate = true;
			 }

		}//arr length check ends

		

	  }//trimAll(strDate)!="" check ends

	  else

		validate = true;



    return  validate;

}



//function used to check if the given date is valid or not

function funIsValidDate(strDate)

{

      var validate = false;

	   if(trimAll(strDate)!="")

	   {

		 strDate= replace(strDate,"-","/");		

		 var arrDate = strDate.split('/');		

		 if(arrDate.length==3)

		 {

		   var month = trimAll(arrDate[0]);

		   var date= trimAll(arrDate[1]);

		   var year= trimAll(arrDate[2]);



		   //if(isNaN(month) || isNaN(date) || isNaN(year) || month.length!=2|| date.length!=2|| year.length!=4)

		   if(isNaN(month) || isNaN(date) || isNaN(year) || year.length<2 || year.length>4 || year.length==3)

		   {           

			  validate = false;

		   }

		   else

			 {

			  month = parseFloat(month);

		      date= parseFloat(date);

		      year= parseFloat(year);



			   if(month>0 && month<=12 && date>0 && date<=31 && year>0)

			     validate = true;		

			 }

		}//arr length check ends

		

	  }//trimAll(strDate)!="" check ends

	  else

		  validate = true;



    return  validate;

}


function  validateString( strValue ) {

 var objRegExp  =  /(^[a-zA-Z]+$)/; 

  return objRegExp.test(strValue);

}

function  validateNumeric( strValue ) {

/******************************************************************************

DESCRIPTION: Validates that a string contains only valid numbers.



PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

******************************************************************************/

  var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/; 

 

  //check for numeric characters 

  return objRegExp.test(strValue);

}



function validateInteger( strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains only 

    valid integer number.

    

PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

******************************************************************************/

  var objRegExp  = /(^-?\d\d*$)/;

 

  //check for integer characters

  return objRegExp.test(strValue);

}



function validateNotEmpty( strValue ) {

/************************************************

DESCRIPTION: Validates that a string is not all

  blank (whitespace) characters.

    

PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

*************************************************/

   var strTemp = strValue;

   strTemp = trimAll(strTemp);

   if(strTemp.length > 0){

     return true;

   }  

   return false;

}



function validateEmail( strValue) {

/************************************************

DESCRIPTION: Validates that a string contains a 

  valid email pattern. 

  

 PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

   

REMARKS: Accounts for email with country appended

  does not validate that email contains valid URL

  type (.com, .gov, etc.) and optionally,

  a valid country suffix.  Since email has many

  forms this expression only tests for near valid

  address.  Some additional validation may be

  required.

*************************************************/

var objRegExp  = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;

  //check for valid email

  return objRegExp.test(strValue);

}



function rightTrim( strValue ) {

/************************************************

DESCRIPTION: Trims trailing whitespace chars.

    

PARAMETERS:

   strValue - String to be trimmed.  

      

RETURNS:

   Source string with right whitespaces removed.

*************************************************/

var objRegExp = /^([\w\W]*)(\b\s*)$/;

 

      if(objRegExp.test(strValue)) {

       //remove trailing a whitespace characters

       strValue = strValue.replace(objRegExp, '$1');

    }

  return strValue;

}



function leftTrim( strValue ) {

/************************************************

DESCRIPTION: Trims leading whitespace chars.

    

PARAMETERS:

   strValue - String to be trimmed

   

RETURNS:

   Source string with left whitespaces removed.

*************************************************/

var objRegExp = /^(\s*)(\b[\w\W]*)$/;

 

      if(objRegExp.test(strValue)) {

       //remove leading a whitespace characters

       strValue = strValue.replace(objRegExp, '$2');

    }

  return strValue;

}



function trimAll( strValue ) {

/************************************************

DESCRIPTION: Removes leading and trailing spaces.



PARAMETERS: Source string from which spaces will

  be removed;



RETURNS: Source string with whitespaces removed.

*************************************************/ 

 var objRegExp = /^(\s*)$/;



    //check for all spaces

    if(objRegExp.test(strValue)) {

       strValue = strValue.replace(objRegExp, '');

       if( strValue.length == 0)

          return strValue;

    }

    

   //check for leading & trailing spaces

   objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;

   if(objRegExp.test(strValue)) {

       //remove leading and trailing whitespace characters

       strValue = strValue.replace(objRegExp, '$2');

    }

  return strValue;

}



function validateCurrency( strValue)  {

/************************************************

DESCRIPTION: Validates that a string contains a 

  valid currency format. 

  

 PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

*************************************************/

  var objRegExp = /(^\$\d{1,3}(,\d{3})*\.\d{2}$)|(^\(\$\d{1,3}(,\d{3})*\.\d{2}\)$)/;



  return objRegExp.test( strValue );

}



function validateTime ( strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains a 

  valid 12 hour time format. Seconds are optional.

  

 PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.



REMARKS: Returns True for time formats such as:

  HH:MM or HH:MM:SS or HH:MM:SS.mmm (where the

  .mmm is milliseconds as used in SQL Server 

  datetime datatype.  Also, the .mmm portion will 

  accept 1 to 3 digits after the period)

*************************************************/

  var objRegExp = /^([1-9]|1[0-2]):[0-5]\d(:[0-5]\d(\.\d{1,3})?)?$/;



  return objRegExp.test( strValue );



}



function validateState (strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains a 

  valid state abbreviation. 

  

 PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

*************************************************/



var objRegExp = /^(AK|AL|AR|AZ|CA|CO|CT|DC|DE|FL|GA|HI|IA|ID|IL|IN|KS|KY|LA|MA|MD|ME|MI|MN|MO|MS|MT|NB|NC|ND|NH|NJ|NM|NV|NY|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VA|VT|WA|WI|WV|WY)$/i; 

  return objRegExp.test(strValue);

}



function validateSSN( strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains a 

  valid social security number. 

  

 PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

*************************************************/

var objRegExp  = /^\d{3}\-\d{2}\-\d{4}$/;

 

  //check for valid SSN

  return objRegExp.test(strValue);



}







function validateUSPhone( strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains valid

  US phone pattern. 

  Ex. (999) 999-9999 or (999)999-9999

  

PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

*************************************************/

  var objRegExp  = /^\([1-9]\d{2}\)\s?\d{3}\-\d{4}$/;

 

  //check for valid us phone with or without space between 

  //area code

  return objRegExp.test(strValue); 

}





function validateUSZip( strValue ) {

/************************************************

DESCRIPTION: Validates that a string a United

  States zip code in 5 digit format or zip+4

  format. 99999 or 99999-9999

    

PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.



*************************************************/

var objRegExp  = /(^\d{5}$)|(^\d{5}-\d{4}$)/;

 

  //check for valid US Zipcode

  return objRegExp.test(strValue);

}





function validateUrl(strValue) 

	{ 

	var objRegExp=/^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.|http:\/\/|https:\/\/){1}([\w]+)(.[\w]+){1,2}$/;

	return objRegExp.test(strValue);

	} 





//added by anitha on may 19 2008

function escapeSpecialChars(strValue)

{

	strValue=strValue.replace(/,/g,"\\,")

	return strValue;

}







function replace(argvalue, x, y) {



  if ((x == y) || (parseInt(y.indexOf(x)) > -1)) {

    errmessage = "replace function error: \n";

    errmessage += "Second argument and third argument could be the same ";

    errmessage += "or third argument contains second argument.\n";

    errmessage += "This will create an infinite loop as it's replaced globally.";

    alert(errmessage);

    return false;

  }

    

  while (argvalue.indexOf(x) != -1) {

    var leading = argvalue.substring(0, argvalue.indexOf(x));

    var trailing = argvalue.substring(argvalue.indexOf(x) + x.length, 

	argvalue.length);

    argvalue = leading + y + trailing;

  }



  return argvalue;



}

function validateUSDate( strValue ) {

/************************************************

DESCRIPTION: Validates that a string contains only 

    valid dates with 2 digit month, 2 digit day, 

    4 digit year. Date separator can be ., -, or /.

    Uses combination of regular expressions and 

    string parsing to validate date.

    Ex. mm/dd/yyyy or mm-dd-yyyy or mm.dd.yyyy

    

PARAMETERS:

   strValue - String to be tested for validity

   

RETURNS:

   True if valid, otherwise false.

   

REMARKS:

   Avoids some of the limitations of the Date.parse()

   method such as the date separator character.

*************************************************/

  var objRegExp = /^\d{1,2}(\-|\/|\.)\d{1,2}\1\d{4}$/

 

  //check to see if in correct format

  if(!objRegExp.test(strValue))

    return false; //doesn't match pattern, bad date

  else{

    var arrayDate = strValue.split(RegExp.$1); //split date into month, day, year

	var intDay = parseInt(arrayDate[1],10); 

	var intYear = parseInt(arrayDate[2],10);

    var intMonth = parseInt(arrayDate[0],10);

	

	//check for valid month

	if(intMonth > 12 || intMonth < 1) {

		return false;

	}

	

    //create a lookup for months not equal to Feb.

    var arrayLookup = { '01' : 31,'03' : 31, '04' : 30,'05' : 31,'06' : 30,'07' : 31,

                        '08' : 31,'09' : 30,'10' : 31,'11' : 30,'12' : 31}

  

    //check if month value and day value agree

    if(arrayLookup[arrayDate[0]] != null) {

      if(intDay <= arrayLookup[arrayDate[0]] && intDay != 0)

        return true; //found in lookup table, good date

    }

		

    //check for February

	var booLeapYear = (intYear % 4 == 0 && (intYear % 100 != 0 || intYear % 400 == 0));

    if( ((booLeapYear && intDay <= 29) || (!booLeapYear && intDay <=28)) && intDay !=0)

      return true; //Feb. had valid number of days

  }

  return false; //any other values, bad date

}



function validateValue( strValue, strMatchPattern ) {

/************************************************

DESCRIPTION: Validates that a string a matches

  a valid regular expression value.

    

PARAMETERS:

   strValue - String to be tested for validity

   strMatchPattern - String containing a valid

      regular expression match pattern.

      

RETURNS:

   True if valid, otherwise false.

*************************************************/

var objRegExp = new RegExp( strMatchPattern);

 

 //check if string matches pattern

 return objRegExp.test(strValue);

}





function removeCurrency( strValue ) {

/************************************************

DESCRIPTION: Removes currency formatting from 

  source string.

  

PARAMETERS: 

  strValue - Source string from which currency formatting

     will be removed;



RETURNS: Source string with commas removed.

*************************************************/

  var objRegExp = /\(/;

  var strMinus = '';

 

  //check if negative

  if(objRegExp.test(strValue)){

    strMinus = '-';

  }

  

  objRegExp = /\)|\(|[,]/g;

  strValue = strValue.replace(objRegExp,'');

  if(strValue.indexOf('$') >= 0){

    strValue = strValue.substring(1, strValue.length);

  }

  return strMinus + strValue;

}



function addCurrency( strValue ) {

/************************************************

DESCRIPTION: Formats a number as currency.



PARAMETERS: 

  strValue - Source string to be formatted



REMARKS: Assumes number passed is a valid 

  numeric value in the rounded to 2 decimal 

  places.  If not, returns original value.

*************************************************/

  var objRegExp = /-?[0-9]+\.[0-9]{2}$/;

   

    if( objRegExp.test(strValue)) {

      objRegExp.compile('^-');

      strValue = addCommas(strValue);

      if (objRegExp.test(strValue)){

        strValue = '($' + strValue.replace(objRegExp,'') + ')';

      }

      else {

        strValue = '$' + strValue;

      }

      return  strValue;

    }

    else

      return strValue;

}



function removeCommas( strValue ) {

/************************************************

DESCRIPTION: Removes commas from source string.



PARAMETERS: 

  strValue - Source string from which commas will 

    be removed;



RETURNS: Source string with commas removed.

*************************************************/

  var objRegExp = /,/g; //search for commas globally

 

  //replace all matches with empty strings

  return strValue.replace(objRegExp,'');

}



function addCommas( strValue ) {

/************************************************

DESCRIPTION: Inserts commas into numeric string.



PARAMETERS: 

  strValue - source string containing commas.

  

RETURNS: String modified with comma grouping if

  source was all numeric, otherwise source is 

  returned.

  

REMARKS: Used with integers or numbers with

  2 or less decimal places.

*************************************************/

  var objRegExp  = new RegExp('(-?[0-9]+)([0-9]{3})'); 



    //check for match to search criteria

    while(objRegExp.test(strValue)) {

       //replace original string with first group match, 

       //a comma, then second group match

       strValue = strValue.replace(objRegExp, '$1,$2');

    }

  return strValue;

}



function removeCharacters( strValue, strMatchPattern ) {

/************************************************

DESCRIPTION: Removes characters from a source string

  based upon matches of the supplied pattern.



PARAMETERS: 

  strValue - source string containing number.

  

RETURNS: String modified with characters

  matching search pattern removed

  

USAGE:  strNoSpaces = removeCharacters( ' sfdf  dfd', 

                                '\s*')

*************************************************/

 var objRegExp =  new RegExp( strMatchPattern, 'gi' );

 

 //replace passed pattern matches with blanks

  return strValue.replace(objRegExp,'');

}





//function for validating credit card number



function is_valid_credit_card_number(cardNumber, cardType)//sample card type visa no 4992739871642 

{

  //alert(cardType);

  var isValid = false;

  var ccCheckRegExp = /[^\d ]/;

  isValid = !ccCheckRegExp.test(cardNumber);



  if (isValid)

  {

    var cardNumbersOnly = cardNumber.replace(/ /g,"");

    var cardNumberLength = cardNumbersOnly.length;

    var lengthIsValid = false;

    var prefixIsValid = false;

    var prefixRegExp;



    switch(cardType)

    {

      case "mastercard","MasterCard":

        lengthIsValid = (cardNumberLength == 16);

        prefixRegExp = /^5[1-5]/;

        break;



      case "visa","Visa":

        lengthIsValid = (cardNumberLength == 16 || cardNumberLength == 13);

        prefixRegExp = /^4/;

        break;



      case "amex","Amex":

        lengthIsValid = (cardNumberLength == 15);

        prefixRegExp = /^3(4|7)/;

        break;

	  case "discover","Discover":

		lengthIsValid = (cardNumberLength == 16);

        prefixRegExp = /^6011/;

        break;  

      default:

        prefixRegExp = /^$/;

        alert("Card type not found");

    }



    prefixIsValid = prefixRegExp.test(cardNumbersOnly);

    isValid = prefixIsValid && lengthIsValid;

  }



  if (isValid)

  {

    var numberProduct;

    var numberProductDigitIndex;

    var checkSumTotal = 0;



    for (digitCounter = cardNumberLength - 1; 

      digitCounter >= 0; 

      digitCounter--)

    {

      checkSumTotal += parseInt (cardNumbersOnly.charAt(digitCounter));

      digitCounter--;

      numberProduct = String((cardNumbersOnly.charAt(digitCounter) * 2));

      for (var productDigitCounter = 0;

        productDigitCounter < numberProduct.length; 

        productDigitCounter++)

      {

        checkSumTotal += 

          parseInt(numberProduct.charAt(productDigitCounter));

      }

    }



    isValid = (checkSumTotal % 10 == 0);

  }

  //isValid=true;	

  return isValid;

}



//to check for numeric

function IsNumeric(sText)
{

   var ValidChars = "0123456789.,";var IsNumber=true;var Char;
   for (i = 0; i < sText.length && IsNumber == true; i++) 

      { 

      Char = sText.charAt(i); 

      if (ValidChars.indexOf(Char) == -1) 

         {

         IsNumber = false;

         }

      }

   return IsNumber;

}
 
// Get base url
url = document.location.href;
xend = url.lastIndexOf("/") + 1;
var base_url = url.substring(0, xend);
var total_pic_quantity, total_pic_price, http_req;

function addPropFav(pid, count)
{
	try
	 {
		 if(pid!="")
		 {
			 url="saveFavorite.php?favType=property&pid="+pid;

			//Does URL begin with http?
			if(url.substring(0, 4) != 'http') 
			{
			  url = base_url + url;
			}

			http_req = null;
			if(window.XMLHttpRequest)http_req = new XMLHttpRequest();
			else if (window.ActiveXObject) http_req = new ActiveXObject("Microsoft.XMLHTTP");

			if(http_req)
			{
			  http_req.onreadystatechange = function(){
			  if(http_req.readyState==4)
			  {
				 response=http_req.responseText;	
				 if(response!="")
				 { 	 
					if(document.getElementById("btn"+pid))
					 document.getElementById("btn"+pid).style.display="none";
					
					var infoWindowInstance = getHomeInfoWindowInstance(count);
					var strInfoContent = infoWindowInstance.getContent();
					var newInfoContent = strInfoContent.replace("<input type='button' id='btn"+pid+"' value='Add to Favorites' onclick='addPropFav("+pid+","+count+")'>",'');
					infoWindowInstance.setContent(newInfoContent);

					alert("Property has been added in your favorites");
				 }

			  }	//http_req.readyState==4 check  ends

			};

			http_req.open("GET",url,true);
			http_req.send("");
		   }

	     }//pid!="" check ends
	}
	catch(err)
	{
		alert(err.message);
	}
}

var Plc_name,Plc_formatted_address,Plc_website,Plc_types,Plc_id,Plc_reference,Plc_rating;
var Plc_lan,Plc_lat,Plc_curCtrl;

function initAddPlaceFav(name,formatted_address,website,types,id,reference,rating,lan,lat,curCtrl)
{
	try
	{	  
		Plc_name=name;
		Plc_formatted_address=formatted_address;
		Plc_website=website;
		Plc_types=types;
		Plc_id=id;
		Plc_reference=reference;
		Plc_rating=rating;
		Plc_lan=lan;
		Plc_lat=lat;		
		Plc_curCtrl=curCtrl;

		//alert(Plc_name+" "+Plc_formatted_address+" "+Plc_website+ " " +Plc_types+" "+Plc_id+ " " +Plc_reference+" "+Plc_rating+ " " +Plc_lan+" "+Plc_lat);
		document.getElementById("btnIconadd").style.display="";
		document.getElementById("lblPlswait").style.display="none";
		document.getElementById("rdIcon1").checked=true;	  

		showpopup('popup_name');
	}
	catch(err)
	{
		alert(err.message);
	}
}

function addPlaceFav()
{
	try
	 {				
		    Plc_name=Plc_name.replace(/~~/g,"'"); 
			Plc_formatted_address=Plc_formatted_address.replace(/~~/g,"'"); 

			document.getElementById("btnIconadd").style.display="none";
			document.getElementById("lblPlswait").style.display="";

			var iconid="";
			var IcnCount=document.forms["frmAddFav"].hdnIcnCount.value;			
			for(i=1;i<=IcnCount;i++)
			{
			    if(document.getElementById("rdIcon"+i).checked)
				{
				   iconid=document.getElementById("rdIcon"+i).value;
				   break;
				}
		    }

			var url="http://shenll.net/property/saveFavorite.php?favType=place&name="+Plc_name+"&iconid="+iconid+"&address="+Plc_formatted_address+"&website="+Plc_website+"&lan="+Plc_lan+"&lat="+Plc_lat+"&types="+Plc_types+"&id="+Plc_id+"&reference="+Plc_reference+"&rating="+Plc_rating;

			//Does URL begin with http?
			if(url.substring(0, 4) != 'http') 
			{
			  url = base_url + url;
			}

			http_req = null;
			if(window.XMLHttpRequest)http_req = new XMLHttpRequest();
			else if (window.ActiveXObject) http_req = new ActiveXObject("Microsoft.XMLHTTP");

			if(http_req)
			{
			  http_req.onreadystatechange = function(){
			  if(http_req.readyState==4)
			  {
				 response=http_req.responseText;	
				 if(response!="")
				 { 	 
					if(document.getElementById("btnAddFavPlc_"+Plc_curCtrl))
					 document.getElementById("btnAddFavPlc_"+Plc_curCtrl).style.display="none";

					if(document.getElementById("btnShowRoute_"+Plc_curCtrl))
					 document.getElementById("btnShowRoute_"+Plc_curCtrl).style.display="";
					
					var markerInstance = getMarkerInstance(Plc_name);
					markerInstance.setIcon("http://shenll.net/property/Display-Icon.php?iconid="+iconid);

					if(Plc_website!="")
					{
						 resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>"+Plc_name+"</b></td></tr><tr><td align='left'>"+Plc_formatted_address+"</td></tr><tr><td align='left'><a href='"+Plc_website+"'>"+Plc_website+"</a></td></tr><tr><td align='left' id='tdShowDistance_"+Plc_curCtrl+"' style='display:none'>Distance:</td></tr> <tr><td align='left' id='tdShowDuration_"+Plc_curCtrl+"' style='display:none'>Duration:</td></tr> <tr style='display:rut;'><td align='left'>TravelMode: <select id='drpdwn_mode_"+Plc_curCtrl+"'><option value='DRIVING'>Driving</option><option value='WALKING'>Walking</option><option value='BICYCLING'>Bicycling</option><option value='TRANSIT'>Transit</option></select></td></tr><tr><td align='left'><input type='button' id=\"btnShowRoute_"+Plc_curCtrl+"\" value='Show Route' onclick=\"calcRoute(this,"+Plc_lat+","+Plc_lan+",'"+Plc_name+"')\" ></td></tr></table>";
					}
					 else
					 {
						 resultInfoString = "<table width='200' align='center' border='0' cellpadding='5' cellspacing='0'><tr><td align='left'><b>"+Plc_name+"</b></td></tr><tr><td align='left'>"+Plc_formatted_address+"</td></tr><tr><td align='left' id='tdShowDistance_"+Plc_curCtrl+"' style='display:none'>Distance:</td></tr><tr><td align='left' id='tdShowDuration_"+Plc_curCtrl+"' style='display:none'>Duration:</td></tr> <tr  style='display:rut;'><td align='left'>TravelMode: <select id='drpdwn_mode_"+Plc_curCtrl+"'><option value='DRIVING'>Driving</option><option value='WALKING'>Walking</option><option value='BICYCLING'>Bicycling</option><option value='TRANSIT'>Transit</option></select></td></tr> <tr><td align='left'><input type='button' id=\"btnShowRoute_"+Plc_curCtrl+"\" value='Show Route' onclick=\"calcRoute(this,"+Plc_lat+","+Plc_lan+",'"+Plc_name+"')\" ></td></tr></table>";
					 }

					var infoWindowInstance = getInfoWindowInstance(Plc_name);
					infoWindowInstance.setContent(resultInfoString);

					arrFavPlaceNames.push(Plc_name);
					arrFavPlaceIconId.push(iconid);
					arrFavPlaceLoc.push(new google.maps.LatLng(Plc_lat,Plc_lan));

					favPlacesearchInfoWindows.push(getInfoWindowInstance(Plc_name));	
					favPlacesearchMarkers.push(getMarkerInstance(Plc_name));	

					alert("Place has been added in your favorites");
					closePopup('popID');
				 }

			  }	//http_req.readyState==4 check  ends

			};

			http_req.open("GET",url,true);
			http_req.send("");		

	     }//pid!="" check ends
	}
	catch(err)
	{
		alert(err.message);
	}
}