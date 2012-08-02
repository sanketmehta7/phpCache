function makeUnixFilename($string)  
{
	$replace=""; //what you want to replace the bad characters with
	$pattern="/([[:alnum:]_\-]*)/"; //basically all the filename-safe characters
	$bad_chars=preg_replace($pattern,$replace,$string); //leaves only the "bad" characters
	$bad_arr=str_split($bad_chars); //split them up into an array for the str_replace() func.
	$string=str_replace($bad_arr,$replace,$string); 
	return $string;
}  

function getfromurl($url="",$attachment=null,$timeout=10){//function to make curl request.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
	if(isset($attachment) && is_array($attachment)){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
	}
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;

}  
