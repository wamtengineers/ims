<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Karachi');
/*--------------Site Configuration--------------*/
function get_config($var){
	global $dblink;
	if(!isset($_SESSION["current_school_id"])){
		$sql="select value from config_variable where `key`='".slash($var)."'";
		
	}
	else{
		$sql="select value from config_variable where `key`='".slash($var)."' and school_id='".$_SESSION["current_school_id"]."'";
	}
	$resConfig=doquery($sql,$dblink);
	if(numrows($resConfig)>0){
		while($rowConfig=dofetch($resConfig))
			return unslash($rowConfig["value"]);
	}
}
$admin_email=get_config("admin_email");
$site_title=get_config("site_title");
$school_address=get_config("school_address");
$school_logo=get_config("school_logo");
$idcard_color=get_config("idcard_color");
$site_url=get_config("site_url");
$admin_logo=get_config("admin_logo");
$student_meta_fields = array( "nationality","birth_place","cnic_no","gender","religion","phone","corresponding_address","corresponding_phone","father_occupation","father_email","father_number","guardian","mother_name","mother_occupation","mother_email","mother_office_phone","gr_no","admission_level","group","board_name","domicile_district","notes","last_school_attended_to_date","last_school_attended_from_date","balance" , "houses_id", "password");
$employee_meta_fields = array( "surname","birth_place","gender","date_of_birth","shift","team_work_type","employee_type","date_of_app","subject","level","address","mobile_number","telephone_number","religion","nationality","cnic_number","cnic_expiry_date","qualification","work_experiance","blood_group","present_leave","leave_date","basic_salary","timing_from","timing_to");
function admin_logo(){
	global $admin_logo, $site_title, $site_url;
	echo '<a href="'.$site_url.'/kids-tracker">';
	if(!empty($admin_logo)){
		echo '<img src="./uploads/config/'.$admin_logo.'" alt="'.$site_title.'" title="'.$site_title.'" />';
	}
	else
		echo $site_title;
	echo '</a>';
}
function check_admin_cookie(){
	global $dblink;
	if(isset($_COOKIE["_admin_logged_in"])){
		$r=doquery("select * from admin where id='".$_COOKIE["_admin_logged_in"]."'", $dblink);
		if(numrows($r)>0){
			$r=dofetch($r);
			$_SESSION["logged_in_admin"]=$r;
			return true;
		}
	}
	return false;
}
/*--------------Image Type Validation--------------*/
$file_upload_root="./uploads/".$_SESSION[ "session_school_id" ].'/';
$file_upload_url=$site_url."/uploads/".$_SESSION[ "session_school_id" ].'/';
$imagetypes=array("image/bmp","image/x-windows-bmp","image/jpg","image/jpeg","image/pjpeg","image/gif","image/png","image/x-png");
$ziptypes=array("rar","zip");
$month_array=array("Januray","February","March","April","May","June","July","August","September","October","November","December");
$videotypes=array("video/mpeg", "video/mpeg4", "video/avi", "video/flv", "video/mov", "video/avi", "video/mpg", "video/wmv", "video/vid");
$day_name=array('Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za');
$month_name=array('jan','feb','maa','apr','mei','juni','juli','aug','sep','oct','nov','dec');
/*--------------Send Mail Function--------------*/
function sendmail($to, $subject, $message, $efrom){
	@$headers  = 'MIME-Version: 1.0' . "\r\n";
	@$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	@$headers .= "From: ".$efrom. "\r\n";
	@$headers .= "Reply-To: ".$efrom. "\r\n";
	@$headers .= "X-Mailer: PHP/".phpversion();
	@mail($to,$subject,$message,$headers) or die("Email Sending Failed");
}
function sendsms_bulk($mobile_number, $text){
	global $dblink;
	//$mobile_number = '923463891662';
	doquery( "insert into sms_bulk( mobile_number, text ) values( '".slash( $mobile_number )."', '".slash( $text )."' )", $dblink );
}
function sendsms($mobile_number, $text){
	sendsms_bulk($mobile_number, $text); return;
	if( empty($mobile_number) ){
		return;
	}
	//$mobile_number = "923455055959";
	$mobile_number = str_replace( "-", "", $mobile_number);
	if( substr( $mobile_number, 0, 2 ) != "92" ) {
		$first = substr( $mobile_number, 0, 1 );
		$last = substr( $mobile_number, 1 );
		if( $first == "0" ) {
			$mobile_number = "92".$last;
		}
		else {
			$mobile_number = "92".$first.$last;
		}
	}
	$username = get_config('sms_provider_username');
	$password = get_config('sms_provider_password');
	$sender = get_config('sms_masking');
	/*$url = "http://sendpk.com/api/sms.php?username=".$username."&password=".$password."&mobile=".$mobile_number."&sender=".urlencode($sender)."&message=".urlencode($text)." ";
	$ch = curl_init();
	$timeout = 30;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$responce = curl_exec($ch);
	curl_close($ch);*/
	//unset($_SESSION[ "sms_session" ]);
	$ctx = stream_context_create(array('http'=>
		array(
			'timeout' => 15,  //1200 Seconds is 20 Minutes
		)
	));
	$session = "";
	$dir = str_replacE( "inc", '', __DIR__);
	if( file_exists( __DIR__."/../telenor.txt" ) ) {
		$sessions = explode( "|", file_get_contents( $dir."telenor.txt" ) );
		if( isset( $sessions[1] ) && time()-$sessions[1] < 550000  ) {
			$session = $sessions[0];
		}
	}
	$timeout = 30;
	if(empty($session )){//if( !isset( $_SESSION[ "sms_session" ] ) ) {
		$url = "https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=".$username."&password=".$password;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: 212.55.100.204", "HTTP_X_FORWARDED_FOR: 	212.55.100.204"));
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		$response = curl_exec($ch);
		echo curl_error($ch);
		curl_close($ch);
		$response = file_get_contents( $url, false, $ctx );
		die;
		if( ($start = strpos( $response, '<data>' ) ) !== false ) {
			$start += 6;
			$end = strpos( $response, '</data>', $start );
			$data = substr( $response, $start, $end-$start );
			if( strpos( $data, 'Error' ) === false  ){
				$session = $data;
				file_put_contents( __DIR__."/../telenor.txt", $session."|".time() );
			}
			else{
				return;
			}
		}
		else{
			return;
		}
	}
	if(!empty($session ) && strlen( $mobile_number ) == 12){//if( isset( $_SESSION[ "sms_session" ] ) ) {
		//$url ='https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id='.$_SESSION[ "sms_session" ].'&to='.$mobile_number.'&text='.urlencode($text).(!empty($sender)?'&mask='.urlencode($sender):'');
		$url ='https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id='.$session.'&to='.$mobile_number.'&text='.urlencode($text).(!empty($sender)?'&mask='.urlencode($sender):'');
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$response = curl_exec($ch);
		curl_close($ch);
		//$response = file_get_contents( $url, false, $ctx );
		if( strpos( $response, 'Error' ) !== false ){
			//unset( $_SESSION[ "sms_session" ] );
			file_put_contents( __DIR__."/../".$_SESSION[ "session_school_id" ].".txt", $response );
			//return sendsms($mobile_number, $text);	
		}
		else {
			return $response;
		}
	}
}
/*--------------Email Validation--------------*/
function emailok($email) {
  return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
}
/*----------------------------------------*/
function slash($str){
	if(!is_array($str))
		return utf8_encode(addslashes($str));
	else{
		for($i=0; $i<count($str); $i++)
			$str[$i]=slash($str[$i]);
		return $str;
	}
}
function unslash($str){
	return stripslashes(utf8_decode($str));
	}
function url_encode($str){
	return base64_encode(urlencode($str));
	}
function url_decode($str){
	return urldecode(base64_decode($str));
	}			
/*--------------  Function--------------*/
function getrealip(){
    $ip = FALSE;
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
        if ($ip) {
            array_unshift($ips, $ip);
            $ip = FALSE;
        }
        for ($i = 0; $i < count($ips); $i++) {
            if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                if (version_compare(phpversion(), "5.0.0", ">=")) {
                    if (ip2long($ips[$i]) != false) {
                        $ip = $ips[$i];
                        break;
                    }
                } else {
                    if (ip2long($ips[$i]) != -1) {
                        $ip = $ips[$i];
                        break;
                    }
                }
            }
        }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/*--------------Create Thumb Function--------------*/
function createThumb($image_path,$image_type,$thumb_size,$thumb_path, $height=""){
	$img=$image_path;
	$newfilename=$thumb_path;
	$w=$thumb_size;
	$h=$thumb_size;
	if($height!="")
		$h=$height;
	//Check if GD extension is loaded
	if (!extension_loaded('gd') && !extension_loaded('gd2')) {
	    trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }
    //Get Image size info
    $imgInfo = getimagesize($img);
    switch ($imgInfo[2]) {
        case 1: $im = imagecreatefromgif($img); break;
        case 2: $im = imagecreatefromjpeg($img);  break;
        case 3: $im = imagecreatefrompng($img); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }
    //If image dimension is smaller, do not resize
    if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
        $nHeight = $imgInfo[1];
        $nWidth = $imgInfo[0];
    }else{
    	if($height==""){
			if ($w/$imgInfo[0] < $h/$imgInfo[1]) {
				$nWidth = $w;
				$nHeight = $imgInfo[1]*($w/$imgInfo[0]);
	        }else{
				$nWidth = $imgInfo[0]*($h/$imgInfo[1]);
		        $nHeight = $h;
			}
		}
		else{
			$nWidth=$w;
			$nHeight=$h;
		}
	}
	$nWidth = round($nWidth);
	$nHeight = round($nHeight);
	$newImg = imagecreatetruecolor($nWidth, $nHeight);
	/* Check if this image is PNG or GIF, then set if Transparent*/  
	if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	}
    imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
	//Generate the file, and rename it to $newfilename
    switch ($imgInfo[2]) {
	    case 1: imagegif($newImg,$newfilename); break;
        case 2: imagejpeg($newImg,$newfilename);  break;
        case 3: imagepng($newImg,$newfilename); break;
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }
    return $newfilename;
}
/*--------------ImageCreateFromBMP Function--------------*/
function ImageCreateFromBMP($filename){
   if (! $f1 = fopen($filename,"rb")) return FALSE;
   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
   if ($FILE['file_type'] != 19778) return FALSE;
   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] = 4-(4*$BMP['decal']);
   if ($BMP['decal'] == 4) $BMP['decal'] = 0;
   $PALETTE = array();
   if ($BMP['colors'] < 16777216)
   {
    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
   }
   $IMG = fread($f1,$BMP['size_bitmap']);
   $VIDE = chr(0);
   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
   $P = 0;
   $Y = $BMP['height']-1;
   while ($Y >= 0)
   {
    $X=0;
    while ($X < $BMP['width'])
    {
     if ($BMP['bits_per_pixel'] == 24)
        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
     elseif ($BMP['bits_per_pixel'] == 16)
     { 
        $COLOR = unpack("n",substr($IMG,$P,2));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 8)
     { 
        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 4)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 1)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     else
        return FALSE;
     imagesetpixel($res,$X,$Y,$COLOR[1]);
     $X++;
     $P += $BMP['bytes_per_pixel'];
    }
    $Y--;
    $P+=$BMP['decal'];
   }
   fclose($f1);
 return $res;
}
function get_image($img, $size, $folder){
	global $site_url;
	if(!empty($img)){
		$ext=explode(".", $img);
		$ext=$ext[count($ext)-1];
		$image_name=str_replace(".".$ext, "", $img);
		if(file_exists($folder."/thumbnails/".$image_name."_".$size.".".$ext)){
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
		else{
			switch($size){
				case "large": $width=800; break;
				case "medium": $width=240; break;
				case "thumbnail": $width=130; break;
				case "avatar": $width=56; break;
		}
			if(!is_dir($folder."/thumbnails"))
				mkdir($folder."/thumbnails");
			createThumb($folder."/".$img,"", $width, $folder."/thumbnails/".$image_name."_".$size.".".$ext);
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
	}
	return;
}
function get_image_mcp($img, $size, $folder){
	if(!empty($img)){
		$ext=explode(".", $img);
		$ext=$ext[count($ext)-1];
		$image_name=str_replace(".".$ext, "", $img);
		if(file_exists($folder."/thumbnails/".$image_name."_".$size.".".$ext)){
			unlink($folder."/thumbnails/".$image_name."_".$size.".".$ext);
		}
		switch($size){
			case "large": $width=800; break;
			case "medium": $width=240; break;
			case "thumbnail": $width=130; break;
			case "avatar": $width=56; break;
			
			if(!is_dir($folder."/thumbnails"))
				mkdir($folder."/thumbnails");
			createThumb($folder."/".$img,"", $width, $folder."/thumbnails/".$image_name."_".$size.".".$ext);
			return $folder."/thumbnails/".$image_name."_".$size.".".$ext;
		}
	}
	return;
}
/*-------------- Function--------------*/
function get_bitly( $url ){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );
	$url='http://api.bit.ly/shorten?version=2.0.1&longUrl='.$url.'&login=sacom&apiKey=R_792325a8f2b7d40db961199f59672dfe';
    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );
    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
	$sar=explode('"',$content);
	return $sar[17];
}
/*--------------getCountryCombo Function--------------*/
function getCountryCombo($country){
 	global $dblink;
 	$rs=doquery("select iso, printable_name from country",$dblink);
 	$str="<select name='country'><option value=''>Select Any</option>";
	 while($r=dofetch($rs)){
	 	if($country==$r["iso"])
			$selected="selected";
		else
			$selected="";
		$str.="<option value='".$r["iso"]."' ".$selected.">".$r["printable_name"]."</option>";
 	}
 	$str.="</select>";
 	return $str;
}
/*--------------getCountryName Function--------------*/
function getCountryname($country){
	global $dblink;
	$r=dofetch(doquery("select printable_name from country where iso='$country'",$dblink));
	return $r["printable_name"];
}
/*--------------getPaymentType Function--------------*/
function getPaymentType($value){
	if($value)
		return "Debit";
	else
		return "Credit";
}
/*--------------Sorttable Function--------------*/
function sorttable($table,$id,$sort,$type,$more_cond=''){
	global $dblink;
	if($more_cond!="")
		$more_cond=' and '.$more_cond;
	if($type=="add"){
		$res=doquery("select sortorder from ".$table." where sortorder>=".$sort.$more_cond,$dblink);
		if(numrows($res)>0){
			doquery("update ".$table." set sortorder=sortorder+1 where sortorder >=".$sort.$more_cond,$dblink);
		}
		doquery("update ".$table." set sortorder=".$sort." where id=".$id,$dblink);
	}
	if($type=="edit"){
		$rs=doquery("select sortorder from $table where id='$id'",$dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			if($r["sortorder"]>$sort){
				doquery("update $table set sortorder=sortorder+1 where sortorder>=$sort and sortorder<".$r["sortorder"].$more_cond,$dblink);
			}
			elseif($r["sortorder"]<$sort){
				doquery("update $table set sortorder=sortorder-1 where sortorder<=$sort and sortorder>".$r["sortorder"].$more_cond,$dblink);
			}
			doquery("update $table set sortorder=$sort where id='".$id."'",$dblink);		
		}
	}
	if($type=="delete"){
		$rs=doquery("select sortorder from $table where id='$id'",$dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			doquery("update $table set sortorder=sortorder-1 where sortorder>".$r["sortorder"].$more_cond, $dblink);
		}
	}
}
/*--------------getCMS Function--------------*/
function getCMS($id){
	global $dblink;
	$rs=doquery("select title, body from cms where id='$id'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$r["title"]=stripslashes($r["title"]);
		$r["body"]=stripslashes($r["body"]);
	}
	else{
		$r["title"]="Oops Page not found";
		$r["body"]="The page you requested is not found on this server.";
	}
	return $r;
}
/*--------------generate_seo_link Function--------------*/
function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array()){
	$return = trim(preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input)));
	if($remove_words){
		$return = remove_words($return,$replace,$words_array);
	}
	return str_replace(' ',$replace,$return);
}
/*--------------Remove_words Function--------------*/
function remove_words($input,$replace,$words_array = array(),$unique_words = true){
	$input_array = explode(' ',$input);
	$return = array();
	foreach($input_array as $word){
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true)){
			$return[] = $word;
		}
	}
	return implode($replace,$return);
}
/*--------------getFilename Function--------------*/
function getFilename($originalname, $title){
	$ext=explode(".", $originalname);
	$ext=$ext[count($ext)-1];
	return generate_seo_link($title).".".$ext;
}
$admin_types = array("No","Yes");
$account_type = array("Current Assets","Fixed Assets","Capital");
$working_days = array("No","Yes");
$fees_type = array("Onetime","Annually","Monthly","Every two months","Quarterly","Every four months","Bi-anually");
function getAdminType($value){
	if( $value == 1 ) {
		return "Yes";
	}
	else {
		return "No";
	}
}
function getAccountType($value){
	if( $value == 1 ) {
		return "Fixed Assets";
	}
	elseif( $value == 2 ) {
		return "Capital";
	}
	else {
		return "Current Assets";
	}
}
function getSchoolType($value){
	global $school_instances;
	if( $value > 0 ) {
		return $school_instances[ $value ];
	}
	else {
		return "All School";
	}
}
/*--------------getSortCombo Function--------------*/
function getSortCombo($table,$selected,$type,$more_cond='')
{
	global $dblink;
	if($more_cond!="")
		$more_cond=' and '.$more_cond;
	$sql="select count(id) from $table where 1".$more_cond;
	$res=doquery($sql,$dblink);
	$row=dofetch($res);
	if($type=="add")
		$cnt=$row[0]+1;
	else
		$cnt=$row[0];
	echo "<select name='sortorder'>";
	for($i=1;$i<=$cnt;$i++)
	{
		if($selected>0)
		{
			if($i==$selected)
				echo "<option value='$i' selected>$i</option>";
			else
				echo "<option value='$i'>$i</option>";
		}
		else
		{
			if($i==$cnt)
				echo "<option value='$i' selected>$i</option>";
			else
				echo "<option value='$i'>$i</option>";
		}
	}
	echo "</select>";
}
/*--------------getInputBox Function--------------*/
function getInputBox($type, $value, $id, $class,$default_values){
	global $file_upload_url;
	switch($type){
		case "text":
				echo '<input type="text" size="62%" name="text_'.$id.'" class="'.$class.'" value="'.$value.'" />';
        break;
		case "submit":
			echo '<input type="submit" name="submit_'.$id.'" class="'.$class.'" value="'.$value.'" />';
	    break;
		case "button":
			echo '<input type="button" name="submit_'.$id.'" class="'.$class.'" value="'.$value.'" />';
	    break;
		case "file":
			if ($value != "") {
			echo '<input type="file" size="50%" name="file_'.$id.'" class="'.$class.'" /><a href="'.$file_upload_url.'/config/'.$value.'" target="_blank" style=" color:#000;">&nbsp;&nbsp;Previous File</a>';
			}
			else{
				echo '<input type="file" size="50%" name="file_'.$id.'" class="'.$class.'" />&nbsp;&nbsp;No File Exist';
				}
        break;
		case "textarea":
			echo '<textarea name="textarea_'.$id.'" class="'.$class.'" cols="80" rows="5">'.$value.'</textarea>';
        break;
		case "editor";
			echo '<textarea name="editor_'.$id.'" id="editor_'.$id.'" class="'.$class.'" cols="30" rows="5">'.$value.'</textarea><br /><a rev="editor_'.$id.'" class="UploadCenterButton" href="#">Upload Center</a>';
        break;
		case "radio":
			$radioarray=explode(";",$default_values);
			foreach($radioarray as $radio){
			if(strpos($radio, ":selected")!== FALSE){
				$selected='checked="checked"';
				$radio=str_replace(":selected", "", $radio);
			}
			else
				$selected="";
			echo '<input type="radio" name="radio_'.$id.'" value="'.$radio.'" '.$selected.' class="'.$class.'" />'.$radio.'';
			}
        break;
		case "checkbox":
			$checkarray=explode(";",$default_values);
			foreach($checkarray as $check){
			if(strpos($check, ":selected")!== FALSE){
				$selected='checked="checked"';
				$check=str_replace(":selected", "", $check);
			}
			else
				$selected="";
			echo '<input type="checkbox" name="checkbox_'.$id.'[]" value="'.$check.'" '.$selected.' class="'.$class.'" />'.$check.'';
			}
		break;
		case "combobox":
			$optionsarray=explode(";",$default_values);
			echo '<select name="combobox_'.$id.'" class="'.$class.'">';
			foreach($optionsarray as $option){
			if(strpos($option, ":selected")!== FALSE){
				$selected='selected="selected"';
				$option=str_replace(":selected", "", $option);
			}
			else
				$selected="";
			echo '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
			}
			echo '</select>';
		break;
	}
}
/////////////////////////Date Convert///////////////////////////
function date_dbconvert($date){
	if( !empty( $date ) ) {
		$date = explode("/", $date);
		return date("Y-m-d", strtotime($date[2]."-".$date[1]."-".$date[0]));
	}
	else {
		return $date;
	}
}
function date_convert($date_added){
	if( !empty( $date_added ) ) {
		return date("d/m/Y", strtotime($date_added));
	}
	else{
		return $date_added;
	}
}
function datetime_dbconvert($date){
	$datetime = explode(" ", $date);
	$date = date_dbconvert($datetime[0]);
	return date("Y/m/d H:i:s", strtotime($date." ".$datetime[1]." ".$datetime[2]));
}
function datetime_convert($date_added){
	return date("d/m/Y h:i A", strtotime($date_added));
}
function get_title($table_id, $table){
 global $dblink;
 $rs=doquery("select title from $table where id=$table_id", $dblink);
 if(numrows($rs)>0){
  $r=dofetch($rs);
  return unslash($r["title"]);
 }
}
function get_field($table_id, $table, $field_name='title'){
 	global $dblink;
 	$rs=doquery("select ".$field_name." from $table where id='".$table_id."'", $dblink);
 	if(numrows($rs)>0){
 		$r=dofetch($rs);
 		return unslash($r[$field_name]);
 	}
}
function get_id($table_field, $table, $field_name='title', $where=''){
 	global $dblink;
 	$rs=doquery("select id from $table where ".$field_name."='".$table_field."' ".$where, $dblink);
 	if(numrows($rs)>0){
 		$r=dofetch($rs);
 		return unslash($r["id"]);
 	}
}
function get_country($table_id, $table){
	global $dblink;
	$rs=doquery("select country from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["country"]);
	}
}
function get_page_url($page_id){
	global $dblink, $site_url;
	$page=doquery("select seo_url_path, seo_url from pages where id='".$page_id."'", $dblink);
	$url="";
	if(numrows($page)>0){
		$page=dofetch($page);
		$path=unslash($page["seo_url_path"]);
		$seo_url=unslash($page["seo_url"]);
		$url=$site_url."/".($path!=""? $path."/":"").$seo_url.".html";
	}
	return $url;
}
function get_name($table_id, $table){
	global $dblink;
	$rs=doquery("select name from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["name"]);
	}
}
function get_username($table_id, $table){
	global $dblink;
	$rs=doquery("select username from $table where id=$table_id", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		return unslash($r["username"]);
	}
}
function get_menu($position, $parent=0){
	global $dblink, $site_url;
	$rs=doquery("select * from frontmenus where position='".$position."' and status=1 and parentid='".$parent."' order by sortorder", $dblink);
	if(numrows($rs)>0){
		$str='<ul>';
		while($r=dofetch($rs)){
			if(unslash($r["url"])=="#age-group"){
				$str.='<li><a href="#">'.unslash($r["title"]).'</a>';
				$rs1=doquery("select title, seo_url from age_group where status='1' order by sortorder", $dblink);
				if(numrows($rs1)>0){
					$str.='<ul>';
					while($r1=dofetch($rs1)){
						$str.='<li><a href="'.$site_url."/".unslash($r1["seo_url"]).'.html">'.unslash($r1["title"]).'</a></li>';
					}
					$str.='</ul>';
				}
				$str.='</li>';
			}
			else{
				$str.='<li><a href="'.(strpos($r["url"], "//")!==false? unslash($r["url"]):$site_url."/".unslash($r["url"])).'">'.unslash($r["title"]).'</a>';
				$str.=get_menu($position, $r["id"]);
				$str.='</li>';
			}
		}
		$str.='</ul><div class="clr"></div>';
		return $str;
	}
}
function curr_format($amount){
	return get_config("currency_symbol").number_format($amount, 0, '.',',')." ".get_config("currency_code");
}
$all_sites_array=array();
function get_site($site_name){
	global $dblink;
	if(!isset($all_sites_array[$site_name])){
		$rs=doquery("select * from auction_site where title='".slash($site_name)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$all_sites_array[$site_name]=$r;
		}
	}
	if(isset($all_sites_array[$site_name]))
		return $all_sites_array[$site_name];
}
function file_content($url, $site_name){
	$filename=generate_seo_link($url).".html";
	if(is_file("module/".$site_name."/cache/".$filename) && (time()-filemtime("module/".$site_name."/cache/".$filename))<3600){
		$content=file_get_contents("module/".$site_name."/cache/".$filename);
	}
	else{
		$site=get_site($site_name);
		if(isset($_SESSION["current_running_site"][$site_name]["total_pages"]) && $site["batch_size"]<=$_SESSION["current_running_site"][$site_name]["total_pages"]){
			sleep($site["batch_delay"]);
			$_SESSION["current_running_site"][$site_name]["total_pages"]=0;
		}
		if(!isset($_SESSION["current_running_site"][$site_name]["total_pages"]))
			$_SESSION["current_running_site"][$site_name]["total_pages"]=1;
		else
			$_SESSION["current_running_site"][$site_name]["total_pages"]++;
		$content=file_get_contents($url);
		file_put_contents("module/".$site_name."/cache/".$filename, $content);
	}
	return $content;
}
function clean_text($str){
	return trim(preg_replace('/\s+/', ' ', strip_tags($str)));
}
/*-----------------------------Get Image--------------------------*/
function deleteFile($filepath){
	if(is_file($filepath)){
		unlink($filepath);
	}
	global $site_url;
	$ext=substr($filepath, strrpos($filepath, '.')+1);
	$filepath=substr($filepath, 0, strrpos($filepath, '.'));
	$image_path=substr($filepath, 0, strrpos($filepath, '/'));
	$image_name=substr($filepath, strrpos($filepath, '/')+1);
	$image_name=$image_path."/thumbnails/".$image_name;
	if(file_exists($image_name."_large.".$ext)){
		unlink($image_name."_large.".$ext);
	}
	if(file_exists($image_name."_medium.".$ext)){
		unlink($image_name."_medium.".$ext);
	}
	if(file_exists($image_name."_thumbnails.".$ext)){
		unlink($image_name."_thumbnail.".$ext);
	}
	if(file_exists($image_name."_avatar.".$ext)){
		unlink($image_name."_avatar.".$ext);
	}
}
function get_category_id($str){
	global $dblink;
	$str=explode(" - ", $str);
	$str=$str[0];
	$rs=doquery("select id from auction_category where title='".slash($str)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$category_id=$r["id"];
	}
	else{
		$rs=doquery("select auction_category_id from auction_category_acronym where title='".slash($str)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$category_id=$r["auction_category_id"];
		}
		else{
			/*if(!empty($str)){
				doquery("insert into auction_category(title) values('".slash($str)."')", $dblink);
				$category_id=mysql_insert_id();
			}
			else*/
				$category_id=0;
		}
	}
	return $category_id;
}
function get_location_id($str){
	global $dblink;
	$rs=doquery("select id from auction_location where title='".slash($str)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$location_id=$r["id"];
	}
	else{
		$rs=doquery("select auction_location_id from auction_location_acronym where title='".slash($str)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			$location_id=$r["auction_location_id"];
		}
		else{
			if(!empty($str)){
				doquery("insert into auction_location(title) values('".slash($str)."')", $dblink);
				$location_id=mysql_insert_id();
			}
			else
				$location_id=0;
		}
	}
	return $location_id;
}
function get_category_name($category_id){
	$title=get_title($category_id, "auction_category");
	return empty($title)?"Uncategorized":$title;
}
function get_location_name($location_id){
	$title=get_title($location_id, "auction_location");
	return empty($title)?"Unknown":$title;
}
function submission_count($cat_id=0){
	global $dblink;
	$sql="select count(1) from submission where status=1";
	if($cat_id!=0)
		$sql.=" and (category_id='".$cat_id."' or category_id in (select id from category where parent_id='".$cat_id."'))";
	$rs=dofetch(doquery($sql, $dblink));
	return $rs["count(1)"];
}
function get_the_excerpt($str){
	if(strlen($str)>35)
		return substr($str, 0, 35)."...";
	else
		return $str;
}
function user_link($user_id, $return=0, $extra=""){
	global $dblink, $site_url;
	$rs=doquery("select username from users where id='".$user_id."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$username=unslash($r["username"]);
	}
	else
		$username='Anonymous';
	$link=$site_url.'/profile/'.$username;
	if($extra!="")
		$link.="?".$extra;
	if($return)
		return $link;
	else
		echo 'by <a href="'.$link.'">'.$username.'</a>';
}
function post_link($post_id, $extra=""){
	global $site_url;
	$link='post/'.$post_id."/".generate_seo_link(get_title($post_id, "submission")).".html";
	if($extra!="")
		$link.='?'.$extra;
	return $site_url."/".$link;
}
function blog_post_link($post_id, $extra=""){
	global $site_url;
	$link='blog/post/'.$post_id."/".generate_seo_link(get_title($post_id, "blog_post")).".html";
	if($extra!="")
		$link.='?'.$extra;
	return $site_url."/".$link;
}
function submission_link($submission_id, $extra="",$page){
	$link=$page.'?id='.$submission_id;
	if($extra!="")
		$link.='&'.$extra;
	return $link;
}
function get_parent_cat($id){
	global $dblink;
	$r=doquery("select parent_id from category where id='".$id."'", $dblink);
	if(numrows($r)>0){
		$r=dofetch($r);
		return $r["parent_id"]==0?$id:$r["parent_id"];
	}
	else
		return 0;
}
function user_avatar($user){
	global $site_url;
	if(isset($user["avatar"]) && !empty($user["avatar"]))
		echo '<img class="avatar" src="'.$site_url."/".get_image(unslash($user["avatar"]), 'avatar', 'uploads/user_avatar').'" alt="'.$user["username"].'" width="56" height="56">';
	else
		echo '<img class="avatar" src="'.$site_url.'/images/default-user.png" alt="'.$user["username"].'" width="56" height="56">';
}
function get_user($user_id){
	global $dblink;
	$r=doquery("select * from users where id='".$user_id."'", $dblink);
	if(numrows($r)>0){
		$r=dofetch($r);
		return $r;
	}
	return false;
}
function get_time_diff($time){
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$difference     = $now - strtotime($time);
    $tense         = "ago";
   	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
   	}
	$difference = round($difference);
	if($difference != 1) {
    	$periods[$j].= "s";
   	}
   	if($j==0)
   		$difference="few";
   	return "$difference $periods[$j] ago";
}
function calc_time($time1, $time2){
	if( $time1 == '0000-00-00 00:00:00' || empty( $time1 )  || is_null( $time1 ) || $time2 == '0000-00-00 00:00:00' || empty( $time2 )  || is_null( $time2 ) )
		return "--";
	$now = strtotime( $time2 );
	$difference     = $now - strtotime($time1);
    $hours = floor( $difference / 3600 );
	$difference -= $hours*3600;
	$mins = ceil( $difference / 60 );
	return array($hours,$mins);
}
function follow_link($user_id){
	global $dblink;
	$is_follower=false;
	if(isset($_SESSION["user"])){
		$rs=doquery("select * from user_followers where user_id='".$user_id."' and follower_id='".$_SESSION["user"]["id"]."'", $dblink);
		if(numrows($rs)>0)
			$is_follower=true;
	}
	if($is_follower)
		echo '<a href="'.user_link($user_id, 1, "follow=1").'" class="bttn mini sub follow green"><i class="fa fa-check"></i> Following</a>';
	else
		echo '<a href="'.user_link($user_id, 1, "follow=1").'" class="bttn mini sub follow"><i class="fa fa-rss"></i> Follow</a>';
}
function comments_count($post_id){
	global $dblink;
	$r=dofetch(doquery("select count(1) as total from post_comments where status=1 and post_id='".$post_id."'", $dblink));
	if($r["total"]==1)
		$rtn="<strong>1</strong> Comment";
	else
		$rtn="<strong>".$r["total"]."</strong> Comments";
	echo $rtn;
}
function blog_comments_count($post_id){
	global $dblink;
	$r=dofetch(doquery("select count(1) as total from blog_post_comments where status=1 and post_id='".$post_id."'", $dblink));
	if($r["total"]==1)
		$rtn="<strong>1</strong> Comment";
	else
		$rtn="<strong>".$r["total"]."</strong> Comments";
	echo $rtn;
}
function rand_str($length){
	$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
	$str='';
	for($i=0; $i<$length; $i++){
		$str.=$chars[rand(0, strlen($chars))];
	}
	return $str;
}
function put_random_link($content, $url){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	if(strpos($paragraph, '<')!==false){
		$raw_sentenses=explode("<", $paragraph);
		$sentenses=array();
		foreach($raw_sentenses as $k=>$v){
			$temp_sentense=rtrim(trim(substr($v, strpos($v, ">")), ">"));
			if($temp_sentense!="")
				$sentenses[]=$temp_sentense;
		}
		if(count($sentenses)>0){
			$sentense=rand(1, count($sentenses));
			$sentense=$sentenses[$sentense-1];
		}
	}
	if(isset($sentense)){
		$words=explode(" ", $sentense);
		if(count($words)>7)
			$total_words=7;
		else
			$total_words=count($words);
		$word_count=rand(1, $total_words);
		$start_word=rand(1, count($words)-$word_count);
		$word="";
		for($i=$start_word; $i<=$start_word+$word_count-1; $i++)
			$word.=$words[$i-1]." ";
		$word=trim($word);
		return str_replace($word, '<a href="'.$url.'">'.$word.'</a>', $content);		
	}
	return $content;
}
function put_random_tag($content, $tag){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	if(strpos($paragraph, '<')!==false){
		$raw_sentenses=explode("<", $paragraph);
		$sentenses=array();
		foreach($raw_sentenses as $k=>$v){
			$temp_sentense=rtrim(trim(substr($v, strpos($v, ">")), ">"));
			if($temp_sentense!="")
				$sentenses[]=$temp_sentense;
		}
		if(count($sentenses)>0){
			$sentense=rand(1, count($sentenses));
			$sentense=$sentenses[$sentense-1];
		}
	}
	if(isset($sentense)){
		$words=explode(" ", $sentense);
		if(count($words)>7)
			$total_words=7;
		else
			$total_words=count($words);
		$word_count=rand(1, $total_words);
		$start_word=rand(1, count($words)-$word_count);
		$word="";
		for($i=$start_word; $i<=$start_word+$word_count-1; $i++)
			$word.=$words[$i-1]." ";
		$word=trim($word);
		return str_replace($word, '<'.$tag.'>'.$word.'</'.$tag.'>', $content);		
	}
	return $content;
}
function put_random_video($content, $video){
	$paragraphs=explode("\n", $content);
	$paragraph=rand(1, count($paragraphs));
	$paragraph=$paragraphs[$paragraph-1];
	return str_replace($paragraph, $paragraph.'<div class="random_video">'.$video.'</div>', $content);		
}
function removedir($dir) {
   	$files = array_diff(scandir($dir), array('.','..'));
   	foreach ($files as $file) {
   		(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
function get_new_sort_order($table){
	global $dblink;
	$sort=dofetch(doquery("select count(id) from ".$table,$dblink));
	$sort=$sort["count(id)"];
	$sort=$sort+1;
	return $sort;
}
function get_fontawesome_icons(){
	$icons_array=array();
	$str=file_get_contents("css/font-awesome.css");
	if(preg_match_all('/fa-(.*):before\s*{\s*(.*)"/', $str, $matches)){
		$icons=$matches[1];
		$icon_codes=$matches[2];	
		for($i=0; $i<count($icons); $i++){
			$code=str_replace("content: \"\\", '', $icon_codes[$i]);
			$icons_array[]=array($icons[$i], $code);
		}
	}
	return $icons_array;
}
function update_meta($table, $table_id, $meta_key, $meta_value){
	global $dblink;
	$rs=doquery("select id from ".$table."_meta where ".$table."_id='".$table_id."' and meta_key='".slash($meta_key)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$id=$r["id"];
		doquery("update ".$table."_meta set meta_value='".slash($meta_value)."' where id='".$id."'", $dblink);
	}
	else{
		doquery("insert into ".$table."_meta(".$table."_id, meta_key, meta_value) values('".$table_id."', '".slash($meta_key)."', '".slash($meta_value)."')", $dblink);
	}
}
function get_meta($table, $table_id, $meta_key, $meta_value=""){
	global $dblink;
	$rtn="";
	$rs=doquery("select meta_value from ".$table."_meta where ".$table."_id='".$table_id."' and meta_key='".slash($meta_key)."'", $dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		$rtn=unslash($r["meta_value"]);
	}
	else{
		$rtn=$meta_value;
	}
	return $rtn;
}
function convert_number_to_words($number) {
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    if (!is_numeric($number)) {
        return false;
    }
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }
    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    $string = $fraction = null;
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    return $string;
}
function get_student_meta($student_id, $meta_key, $default_value=""){
	return get_object_meta('student', $student_id, $meta_key, $default_value);	
}
function set_student_meta($student_id, $meta_key, $meta_value){
	return set_object_meta('student', $student_id, $meta_key, $meta_value);		
}
function get_employee_meta($employee_id, $meta_key, $default_value=""){
	return get_object_meta('employee', $employee_id, $meta_key, $default_value);	
}
function set_employee_meta($employee_id, $meta_key, $meta_value){
	return set_object_meta('employee', $employee_id, $meta_key, $meta_value);		
}
function get_object_meta($object, $object_id, $meta_key, $default_value=""){
	global $dblink;
	if( substr( $meta_key, 0, 8) == 'balance_' && 0 ){
		$academic_year_id = str_replace( 'balance_', '', $meta_key );
		$rs=doquery("select balance from ".$object."_academic_year_balance where ".$object."_id='".$object_id."' and academic_year_id='".slash($academic_year_id )."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			return unslash($r["balance"]);
		}
		else {
			return 0;
		}
	}
	else {
		$rs=doquery("select meta_value from ".$object."_meta where ".$object."_id='".$object_id."' and meta_key='".slash($meta_key)."'", $dblink);
		if(numrows($rs)>0){
			$r=dofetch($rs);
			return unslash($r["meta_value"]);
		}
	}
	return $default_value;
}
function set_object_meta($object, $object_id, $meta_key, $meta_value){
	global $dblink;
	if( substr( $meta_key, 0, 8) == 'balance_' ){
		$academic_year_id = str_replace( 'balance_', '', $meta_key );
		$rs=doquery("select balance from ".$object."_academic_year_balance where ".$object."_id='".$object_id."' and academic_year_id='".slash($academic_year_id )."'", $dblink);
		if(numrows($rs)>0){
			doquery("update ".$object."_academic_year_balance set balance='".slash($meta_value)."' where ".$object."_id='".$object_id."' and academic_year_id='".slash($academic_year_id)."'", $dblink);
		}
		else {
			doquery("insert into ".$object."_academic_year_balance(".$object."_id, academic_year_id, balance) values('".$object_id."', '".slash($academic_year_id)."', '".slash($meta_value)."')", $dblink);
		}
	}
	else {
		$rs=doquery("select meta_value from ".$object."_meta where ".$object."_id='".$object_id."' and meta_key='".slash($meta_key)."'", $dblink);
		if(numrows($rs)>0)
			doquery("update ".$object."_meta set meta_value='".slash($meta_value)."' where ".$object."_id='".$object_id."' and meta_key='".slash($meta_key)."'", $dblink);
		else
			doquery("insert into ".$object."_meta(".$object."_id, meta_key, meta_value) values('".$object_id."', '".slash($meta_key)."', '".slash($meta_value)."')", $dblink);
	}
		
}
function get_total_active_students(){
	global $dblink;
	$rs=dofetch(doquery("select count(1) from student a inner join student_2_class b on a.id=b.student_id where a.status=1 and a.school_id = '".$_SESSION["current_school_id"]."' and b.status=1", $dblink));
	return $rs["count(1)"];
}
function get_total_active_employees(){
	global $dblink;
	$rs=dofetch(doquery("select count(1) from employee where status=1 and school_id = '".$_SESSION["current_school_id"]."'", $dblink));
	return $rs["count(1)"];
}
function current_academic_year() {
	global $dblink;
	$rs=dofetch(doquery("select * from academic_year where is_current_year=1 and school_id = '".$_SESSION["current_school_id"]."'", $dblink));
	return $rs;
}
function get_student_class( $student_id ) {
	global $dblink;
	$rs = doquery( "select c.class_name as class, b.title as section from student_2_class a left join class_section b on a.class_section_id = b.id left join class c on b.class_id = c.id where a.student_id = '".$student_id."' and a.status=1 ", $dblink );
	if( numrows( $rs ) > 0 ) {
		$rs = dofetch( $rs );
		return $rs[ "class" ]." ".$rs[ "section" ];
	}
	else{
		$rs = doquery( "select c.class_name as class, b.title as section from student_meta a left join class_section b on a.meta_value = b.id left join class c on b.class_id = c.id where a.student_id = '".$student_id."' and a.meta_key='current_class_section_id' ", $dblink );
		if( numrows( $rs ) > 0 ) {
			$rs = dofetch( $rs );
			return $rs[ "class" ]." ".$rs[ "section" ]." (LEFT)";
		}
		else{
			return 'LEFT';
		}
	}
}
function clean_date( $date ) {
	if(!empty($date)){
		$date_array= explode("-", $date);
		if(count($date_array)==3){
			if($date_array[2]<17) {
				$date_array[2]='20'.$date_array[2];
			}
			else {
				$date_array[2]='19'.$date_array[2];
			}
			echo $date_array[0]."/".$date_array[1]."/".$date_array[2]."<br />";
			return $date_array[2]."-".$date_array[1]."-".$date_array[0];
		}
	}
	return $date;
}
function get_next_student_registration_number(){
	global $dblink;
	$rs = doquery( "select student_registration_number from student order by abs(student_registration_number) desc limit 0,1", $dblink );
	if( numrows( $rs ) > 0 ) {
		$r = dofetch( $rs );
		$id = $r[ "student_registration_number" ]++;
	}
	else {
		$id = 1;
	}
	return $id;
}
function get_account_of_type( $type ){
	global $dblink;
	if( !isset( $GLOBALS[ "account_type" ][ $type ] ) ) {
		$r = doquery( "select id from account where account_type='".$type."'", $dblink );
		if( numrows( $r ) > 0 ) {
			$r = dofetch( $r );
			$GLOBALS[ "account_type" ][ $type ] = $r[ "id" ];
		}
		else {
			$GLOBALS[ "account_type" ][ $type ] = "";
		}
	}
	return $GLOBALS[ "account_type" ][ $type ];
}
function image_editor( $id, $img_src, $url, $extra_fields = array() ) {
	?>
	<div id="<?php echo $id?>-popup" style="display:none">
		<div class="image-editor" data-src="<?php echo $img_src?>" data-field="<?php echo $id?>_raw" data-img="<?php echo $id?>_img" data-url="<?php echo $url?>" data-extra_fields='<?php echo json_encode( (object) $extra_fields  )?>'>
			<input type="file" class="cropit-image-input" name="<?php echo $id?>" id="<?php echo $id?>" accept="image/*" capture="camera">
			<div class="cropit-preview"></div>
				<div class="image-size-label">
					Resize image
				</div>
				<input type="range" class="cropit-image-zoom-input">
			  
				<button class="rotate-ccw">Rotate <i class="fa fa-undo"></i></button>
				<button class="rotate-cw">Rotate <i class="fa fa-repeat"></i></button>
				<button class="image-editor-done">Done</button>
			</div>
	</div>
	<a class="image-editor-src fancybox inline" href="#<?php echo $id?>-popup">
		<img src="<?php echo $img_src?>" id="<?php echo $id?>_img" />
	</a>
	<?php
}
function get_balance( $student_id, $academic_year_id ){
	return get_student_meta( $student_id, 'balance' );
	global $dblink;
	$balance = 0;
	$rs = doquery( "select * from student_academic_year_balance where student_id = '".$student_id."' and academic_year_id = '".$academic_year_id."'", $dblink );
	if( numrows( $rs ) > 0 ) {
		$r = dofetch( $rs );
		$balance = $r[ "balance" ];
	}
	return $balance;
}
function get_months( $academic_year_id = '' ){
	global $dblink;
	$academic_year = current_academic_year();
	if( empty( $academic_year_id ) || $academic_year_id == $academic_year[ "id" ]) {
		$ts = strtotime( $academic_year[ "start_date" ] );
		$ts_end = strtotime( $academic_year[ "end_date" ] );
	}
	else {
		$academic_year = dofetch( doquery( "select * from academic_year where id = '".$academic_year_id."'", $dblink ) );
		$ts = strtotime( $academic_year[ "start_date" ] );
		$ts_end = strtotime( $academic_year[ "end_date" ] );
	}
	$year = date("Y", $ts_end)-date("Y", $ts);
	$total_months = ($year)*12+date("n", $ts_end)-date("n", $ts)+1;
	$months = array();
	for( $i = 0; $i < $total_months; $i++ ){
		$now = strtotime( "+".$i." months", $ts );
		$months[ date("Ym", $now ) ] = date("M Y", $now );
	}
	return $months;
}
function show_month( $month ){
	return date( "M Y", strtotime( $month."01" ));
}
function get_student_balance( $student_id, $date = "" ){
	global $dblink;
	if( empty( $date ) ) {
		$date = date( "Y-m-d" );
	}
	/*
	$month = date( "Ym", strtotime( $date ));
	$rs = dofetch( doquery( "select sum( fees_amount ) as total from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where month < '".$month."' and student_2_class_id in ( select id from student_2_class where student_id = '".$student_id."')", $dblink ) );
	$total = $rs[ "total" ];
	$academic_year = current_academic_year();
	$total += get_balance( $student_id, $academic_year[ "id" ] );
	$rs = dofetch( doquery( "select sum( amount ) as total from fees_chalan a inner join fees_chalan_receiving b on a.id = b.fees_chalan_id where month < '".$month."' and student_2_class_id in ( select id from student_2_class where student_id = '".$student_id."')", $dblink ) );
	$receiving = $rs[ "total" ];
	return $total - $receiving;*/
	$balance = 0;
	$month = date( "Ym", strtotime( $date ));
	$academic_year = current_academic_year();
	$academic_year_id = $academic_year[ "id" ];
	$student_2_class = doquery( "select a.*, b.title as section, c.class_name as class from student_2_class a inner join class_section b on a.class_section_id = b.id inner join class c on b.class_id = c.id where academic_year_id='".$academic_year_id."' and student_id='".$student_id."'", $dblink );
	if( numrows( $student_2_class ) > 0 ) {
		$student_2_class = dofetch( $student_2_class );
		$rs = doquery( "select * from fees_chalan where student_2_class_id = '".$student_2_class[ "id" ]."' order by month desc limit 0,1", $dblink );
		if( numrows( $rs ) > 0 ) {
			$chalan = dofetch( $rs );
			$chalan_details = get_chalan_details( $chalan );
			$balance = $chalan_details[ "total" ];
			if( $chalan_details[ "due_date" ] < date( "Y-m-d" ) ) {
				$balance += $chalan_details[ "late_fees" ];
			}
			$receiving = doquery( "select payment_date,amount from fees_chalan_receiving where fees_chalan_id = '".$chalan[ "id" ]."' and status=1", $dblink );
			if( numrows( $receiving ) > 0 ) {
				$receiving = dofetch( $receiving );
				$balance -= $receiving[ "amount" ];
				if( $chalan_details[ "due_date" ] >= $receiving[ "payment_date" ] ) {
					$balance -= $chalan_details[ "late_fees" ];
				}
			}
		}
	}
	return $balance;
	
}
function get_chalan_arrears( $chalan ){
	global $dblink;
	if( !is_array( $chalan ) ) {
		$chalan = dofetch( doquery( "select * from fees_chalan where id='".$chalan."'", $dblink ) );
	}
	// academic_year_id = '".$chalan[ "academic_year_id" ]."'  
	//academic_year_id = '".$chalan[ "academic_year_id" ]."' and 
	$rs = dofetch( doquery( "select sum( fees_amount ) as total from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where month < '".$chalan[ "month" ]."' and student_2_class_id in ( select id from student_2_class where student_id = (select student_id from student_2_class where id = '".$chalan[ "student_2_class_id" ]."'))", $dblink ) );
	$total = $rs[ "total" ];
	$student_id = get_field( $chalan[ "student_2_class_id" ], "student_2_class", "student_id" );
	$total += get_balance( $student_id, $chalan[ "academic_year_id" ] );
	$rs = dofetch( doquery( "select sum( amount ) as total from fees_chalan a inner join fees_chalan_receiving b on a.id = b.fees_chalan_id where month < '".$chalan[ "month" ]."' and student_2_class_id in ( select id from student_2_class where student_id = (select student_id from student_2_class where id = '".$chalan[ "student_2_class_id" ]."'))", $dblink ) );
	$receiving = $rs[ "total" ];
	$total = $total - $receiving;
	$rs = doquery( "select * from fees_chalan where month < '".$chalan[ "month" ]."' and student_2_class_id in ( select id from student_2_class where student_id = (select student_id from student_2_class where id = '".$chalan[ "student_2_class_id" ]."'))", $dblink );
	if( numrows( $rs ) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$rs2 = doquery( "select * from fees_chalan_receiving where fees_chalan_id = '".$r[ "id" ]."'", $dblink );
			if( numrows( $rs2 ) == 0 ) {
				$total += $r[ "late_fees" ];	
			}
			else{
				$r2 = dofetch( $rs2 );
				if( $r2[ "payment_date" ] > $r[ "due_date" ] ) {
					$total += $r[ "late_fees" ];
				}
			}
		}
	}
	return $total;
}
function get_chalan_details( $chalan ) {
	global $dblink;
	if( !is_array( $chalan ) ) {
		$chalan = dofetch( doquery( "select * from fees_chalan where id='".$chalan."'", $dblink ) );
	}
	$rs = doquery( "select * from fees_chalan_details a inner join fees b on a.fees_id = b.id where fees_chalan_id = '".$chalan[ "id" ]."' order by b.sortorder, a.id", $dblink );
	$chalan_details = array();
	$total = 0;
	if( numrows( $rs) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$chalan_details[] = array(
				"title" => unslash( $r[ "title" ] )." - ".date( "M Y", strtotime( $r[ "fees_month" ]."01" ) ),
				"amount" => $r[ "fees_amount" ]
			);
			$total += $r[ "fees_amount" ];
		}
	}
	$rs = doquery( "select a.*, b.fees_title from fees_chalan_details a inner join on_demand_fees b on a.on_demand_fees_id = b.id where fees_chalan_id	 = '".$chalan[ "id" ]."' order by b.date", $dblink );
	if( numrows( $rs) > 0 ) {
		while( $r = dofetch( $rs ) ) {
			$chalan_details[] = array(
				"title" => unslash( $r[ "fees_title" ] ),
				"amount" => $r[ "fees_amount" ]
			);
			$total += $r[ "fees_amount" ];
		}
	}
	$arrears = get_chalan_arrears( $chalan );
	if( $arrears != 0 ) {
		$chalan_details[] = array(
			"title" => "Arrears",
			"amount" => $arrears
		);
		$total += $arrears;
	}
	$ts = strtotime( $chalan[ "issue_date" ] );
	return array(
		"details" => $chalan_details,
		"late_fees" => $chalan[ "late_fees" ],
		"due_date" => $chalan[ "due_date" ],//date("j", $ts ) > 10? $chalan[ "issue_date" ] : date("Y-m-10", $ts ),
		"total" => $total,
		"total_after_due_date" => $total + $chalan[ "late_fees" ]
	);
}
function generate_chalan( $student_id, $student_2_class_id, $academic_year_id, $month, $issue_date, $academic_year_start_ts, $due_date = "", $month_from = '' ){
	global $dblink;
	$academic_year = dofetch( doquery( "select * from academic_year where id = '".$academic_year_id."'", $dblink ) );
	$academic_year_start_ts = strtotime( $academic_year[ "start_date" ] );
	$academic_year_end_ts = strtotime( $academic_year[ "end_date" ] );
	$total_months_in_year = 12-date( "n", $academic_year_start_ts )+date( "n", $academic_year_end_ts )+1;
	$late_fees = get_config( "late_fees_amount" );
	if( empty( $due_date ) ) {
		$ts = strtotime( $issue_date );
		$due_date =  date("j", $ts ) > 10? $chalan[ "issue_date" ] : date("Y-m-10", $ts );
	}
	$months_array = array();
	if( !empty( $month_from ) && $month_from < $month ) {
		$month_from_ts = strtotime( $month_from."01" );
		$fy = substr( $month_from, 0, 4);
		$fm = substr( $month_from, 4);
		$ty = substr( $month, 0, 4);
		$tm = substr( $month, 4);
		if( $fy != $ty ) {
			$total_months = 12-$fm+1;
			$total_months += 12 * ( $ty-$fy-1 );
			$total_months += $tm;
		}
		else {
			$total_months = $tm-$fm+1;
		}
		for( $i=0; $i < $total_months; $i++ ){
			$months_array[] = date( "Ym", strtotime( "+".$i." months", $month_from_ts));
		}
	}
	else{
		$months_array[] = $month;
	}
	doquery( "insert into fees_chalan(school_id, student_2_class_id, academic_year_id, month, issue_date, late_fees, due_date ) values('".$_SESSION["current_school_id"]."', '".$student_2_class_id."', '".$academic_year_id."', '".$month."', '".$issue_date."', '".$late_fees."', '".$due_date."' )", $dblink );
	$chalan_id = inserted_id();
	$fees = doquery( "select * from fees where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by sortorder", $dblink );
	if( numrows( $fees ) > 0 ) {
		while( $fee = dofetch( $fees ) ) {
			$charge_fees = 1;
			if( $fee[ "selected_students" ] ) {
				$charge_fees = get_student_meta($student_id, "fees_".$fee[ "id" ]."_required");
			}
			if( $charge_fees == 1 ) {
				if( $fee[ "has_discount" ] ) {
					$fees_amount = str_replace( ",", "", get_student_meta($student_id, "fees_".$fee[ "id" ]."_approved"));
				}
				else {
					$fees_amount = str_replace( ",", "", get_student_meta($student_id, "fees_".$fee[ "id" ]));
				}
				if( $fee[ "type" ] == 0 ) { // One Time
					if( numrows( doquery( "select * from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where student_2_class_id = '".$student_2_class_id."' and fees_id = '".$fee[ "id" ]."'", $dblink ) ) == 0 ) {
						 $fees_month = $month;
						 doquery( "insert into fees_chalan_details( fees_chalan_id, fees_id, fees_month, fees_amount ) values( '".$chalan_id."', '".$fee[ "id" ]."', '".$fees_month."', '".$fees_amount."' )", $dblink );
					}
				}
				else if( $fee[ "type" ] == 1 ) { // Annually
					if( numrows( doquery( "select * from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where student_2_class_id = '".$student_2_class_id."' and fees_id = '".$fee[ "id" ]."' and academic_year_id = '".$academic_year_id."'", $dblink ) ) == 0 ) {
						if( $charge_fees ) {
							doquery( "insert into fees_chalan_details( fees_chalan_id, fees_id, fees_month, fees_amount ) values( '".$chalan_id."', '".$fee[ "id" ]."', '".$fees_month."', '".$fees_amount."' )", $dblink );
						}								
					}
				}
				else {  
					if( $fee[ "type" ] == 2 ) { // Monthly
						$diff = 1;
					}
					else if( $fee[ "type" ] == 3 ) { // Every two months
						$diff = 2;
					}
					else if( $fee[ "type" ] == 3 ) { // Quarterly
						$diff = 3;
					}
					else if( $fee[ "type" ] == 4 ) { // Every four months
						$diff = 4;
					}
					else if( $fee[ "type" ] == 5 ) { // Bi-anually
						$diff = 6;
					}
					foreach( $months_array as $month ) {
						$fees_month = "";
						for( $k = 0; $k < $total_months_in_year; $k+=$diff ) {
							$temp = date( "Ym", strtotime( "+".$k." months", $academic_year_start_ts ) );
							if( $temp >= $month ){
								if( $temp > $month ) {
									$fees_month = date( "Ym", strtotime( "+".($k-1)." months", $academic_year_start_ts ) );
								}
								else{
									$fees_month = $temp;
								}
								break;
							}
						}
						if( numrows( doquery( "select * from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where student_2_class_id = '".$student_2_class_id."' and fees_id = '".$fee[ "id" ]."' and academic_year_id = '".$academic_year_id."' and fees_month >= '".$fees_month."'", $dblink ) ) == 0 ) {
							doquery( "insert into fees_chalan_details( fees_chalan_id, fees_id, fees_month, fees_amount ) values( '".$chalan_id."', '".$fee[ "id" ]."', '".$fees_month."', '".$fees_amount."' )", $dblink );
						}
					}
				}
			}
		}
	}
	$fees = doquery( "select * from on_demand_fees where status = 1 and academic_year_id = '".$academic_year_id."' and date >= '".date("Y-m-d", strtotime($months_array[0]."01") )."' and date <= '".date("Y-m-d", strtotime( "last day of this month", strtotime($months_array[0]."01")) )."' order by date", $dblink );
	if( numrows( $fees ) ){
		while( $fee = dofetch( $fees ) ){ 
			$charge_fees = 1;
			if( $fee[ "selected_classes" ] == 1 ) {
				if( numrows( doquery( "select * from on_demand_fees_classes where on_demand_fees_id = '".$fee[ "id" ]."' and class_section_id = (select class_section_id from student_2_class where id = '".$student_2_class_id."' )", $dblink ) ) == 0 ) {
					$charge_fees = 0;
				}
			}
			if( $fee[ "selected_students" ] == 1 ) {
				if( numrows( doquery( "select * from on_demand_fees_student where on_demand_fees_id = '".$fee[ "id" ]."' and student_id = '".$student_id."'", $dblink ) ) == 0 ) {
					$charge_fees = 0;
				}
			}
			if( $charge_fees && numrows( doquery( "select * from fees_chalan a inner join fees_chalan_details b on a.id = b.fees_chalan_id where student_2_class_id = '".$student_2_class_id."' and on_demand_fees_id = '".$fee[ "id" ]."' and academic_year_id = '".$academic_year_id."'", $dblink ) ) == 0 ) {
				doquery( "insert into fees_chalan_details( fees_chalan_id, on_demand_fees_id, fees_month, fees_amount ) values( '".$chalan_id."', '".$fee[ "id" ]."', '".date("Ym", strtotime( $fee[ "date" ] ))."', '".$fee[ "fees_amount" ]."' )", $dblink );
			}
		}
	}
	return dofetch( doquery( "select * from fees_chalan where id='".$chalan_id."'", $dblink ) );
}
function is_holiday( $date ){
	global $dblink;
	$holiday = false;
	$d = date( "w", strtotime( $date ) );
	if( $d==0 || $d==6 ) {
		$holiday = true;
		$rs = doquery( "select * from holidays where date='".$date."' and is_working_day=1", $dblink );
		if( numrows( $rs ) > 0 ) {
			$holiday = false;
		}
	}
	else {
		$rs = doquery( "select * from holidays where date='".$date."' and is_working_day=0", $dblink );
		if( numrows( $rs ) > 0 ) {
			$holiday = true;
		}
	}
	return $holiday;
}
function get_account_balance( $account_id, $datetime = "" ){
	global $dblink;
	if( empty( $datetime ) ) {
		$datetime = date( "Y-m-d H:i:s" );
	}
	else{
		$datetime = date( "Y-m-d H:i:s", strtotime( $datetime ) );
	}
	$account = dofetch( doquery( "select account_type=1, balance from account where id='".$account_id."'", $dblink ) );
	$balance = $account[ "balance" ];
	if( $account_id == get_config( 'bank_account_id' ) ) {
		$fees = dofetch( doquery( "select sum(amount) as total from fees_chalan_receiving where status=1 and payment_date<='".$datetime."'", $dblink ) );
		$balance += $fees[ "total" ];
	}
	$balance_transactions = dofetch( doquery( "select sum(amount) as balance from (SELECT id, amount as amount FROM `transaction` a where a.account_id='".$account_id."' and datetime_added<='".$datetime."' union select id, -amount from transaction b where b.reference_id='".$account_id."' and datetime_added<='".$datetime."') as transactions", $dblink ) );
	$balance = $balance + $balance_transactions[ "balance" ];
	$expense = dofetch( doquery( "select sum(amount) as total from expense where status=1 and account_id = '".$account_id."' and datetime_added<='".$datetime."'", $dblink ) );
	$balance -= $expense[ "total" ];
	$salary_payment = dofetch( doquery( "select sum(amount) as total from salary_payment where status=1 and account_id = '".$account_id."' and datetime_added<='".$datetime."'", $dblink ) );
	$balance -= $salary_payment[ "total" ];
	return $balance;
}
function get_max_marks( $exam_id, $subject_id ){
	global $dblink;
	$rs = doquery( "select * from examination_marks where exam_id = '".$exam_id."' and subject_id = '".$subject_id."'", $dblink );
	$marks = array(
		"max" => "",
		"min" => "",
	);
	if( numrows( $rs ) > 0 ) {
		$r = dofetch($rs);
		$marks[ "max" ] = round($r[ "max" ]);
		$marks[ "min" ] = round($r[ "min" ]);
	}
	return $marks;
}
function show_age( $birth_date, $now = "" ){
	$age = get_age( $birth_date, $now );
	echo ($age[0]>0?($age[0]." year".($age[0]>1?"s":"")." "):"");
	echo ($age[1]>0?($age[1]." month".($age[1]>1?"s":"")):"");
}
function get_age( $birth_date, $now = "" ){
	$date = new DateTime($birth_date);
	$now = new DateTime( $now );
	$interval = $now->diff($date);
	return array($interval->y, $interval->m);
}
function get_average_age($class_section_id, $year_id, $now = ''){
	global $dblink;
	$avg_age = '-';
	$ss = doquery( "select birth_date from student a inner join student_2_class b on a.id = b.student_id and academic_year_id = '".$year_id."' and b.status=1 and class_section_id = '".$class_section_id."' where a.status = 1", $dblink );
	$no_bd = 0;
	if( numrows( $ss ) > 0 ) {
		$total = 0;
		while( $s = dofetch( $ss ) ) {
			if( $s[ "birth_date" ] == '0000-00-00' ){
			 $no_bd++;
			}
			else{
				$age = get_age( $s[ "birth_date" ], $now );
				$total += $age[ 0 ]*12+$age[1];
			}
		}
		$t = numrows( $ss )-$no_bd;
		$age = $t>0?round($total/$t):'';
		$m = $age%12;
		$age = array(
			($age-$m)/12,
			$m
		);
		$avg_age = ($age[0]>0?($age[0]." year".($age[0]>1?"s":"")." "):"").($age[1]>0?($age[1]." month".($age[1]>1?"s":"")):"");
	}
	return $avg_age;
}
function get_result( $exam, $class_section, $student_id_get = 0, $subject_id = 0 ){
	global $dblink;
	if( !isset( $GLOBALS["exam_result"][$exam["id"]][$class_section["id"]] ) ){
		$students = array();
		$rs = doquery( "select student_id, group_id from student_2_class where class_section_id = '".$class_section[ "id" ]."' and academic_year_id = '".$exam[ "academic_year_id" ]."'", $dblink );
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				$students[ $r[ "student_id" ] ] = array(
					"id" => $r[ "student_id" ],
					"group" => $r[ "group_id" ],
					"max" => 0,
					"obtained" => 0,
					"subject" =>  array()
				);
			}
		}
		$class_tests = array();
		if( $exam[ "generate_marksheet" ] == 1 ) {
			$class_tests = array();
			$rs = doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and academic_year_id = '".$exam[ "academic_year_id" ]."' and result_date < '".$exam[ "result_date" ]."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$class_tests[] = $r[ "id" ];
				}
			}
		}
		if( $exam[ "generate_marksheet" ] == 2 ) {
			$mid_term_exam = dofetch( doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and generate_marksheet=1 and academic_year_id = '".$exam[ "academic_year_id" ]."'", $dblink ));
			foreach( $students as $student_id => $student ){
				$students[ $student_id ]["mid_term_title"] = $mid_term_exam[ "title" ];
				$students[ $student_id ]["mid_term"] = get_result( $mid_term_exam, $class_section, $student_id, $subject_id );
			}
			$class_tests = array();
			$rs = doquery( "select a.*, b.title, b.generate_marksheet from examination a left join examination_type b on a.examination_type_id = b.id where a.status = 1 and b.status = 1 and academic_year_id = '".$exam[ "academic_year_id" ]."' and result_date < '".$exam[ "result_date" ]."' and result_date > '".$mid_term_exam["result_date"]."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					$class_tests[] = $r[ "id" ];
				}
			}
		}
		$rs = doquery( "select * from subject where class_id = '".$class_section[ "class_id" ]."'".(!empty($subject_id)?" and id = '".$subject_id."'":"")." order by sortorder", $dblink );
		$class_test_max_total = 0;
		$class_test_obt_total = 0;
		$final_max_total = 0;
		$final_obt_total = 0;
		if( numrows( $rs ) > 0 ) {
			while( $r = dofetch( $rs ) ) {
				$class_tests_marks = array(
					"max" => 0,
					"min" => 0
				);
				foreach( $students as $student_id => $student ){
					if( !isset( $students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "marks" ] ) ) {
						$students[ $student_id ]["subject"][ $r[ "id" ] ][ "title" ] = unslash( $r["title"] );
						$students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ] = array(
							"max" => 0,
							"min" => 0,
							"marks" => 0,
						);
					}
				}
				foreach( $class_tests as $class_test ){
					$marks = get_max_marks( $class_test, $r[ "id" ] );
					$class_tests_marks[ "max" ] += $marks[ "max" ];
					$class_tests_marks[ "min" ] += $marks[ "min" ];
					foreach( $students as $student_id => $student ){
						if( !isset( $students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "marks" ] ) ) {
							$students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "marks" ] = 0;
						}
						$check = doquery( "select marks from examination_marks_students where exam_id = '".$class_test."' and student_id = '".$student_id."' and subject_id = '".$r[ "id" ]."'", $dblink );
						if( numrows( $check ) > 0 ) {
							$check = dofetch( $check );
							$students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "marks" ] += $check[ "marks" ];
						}
					}
				}
				foreach( $students as $student_id => $student ){
					if( !isset( $students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "marks" ] ) ) {
						$students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "marks" ] = 0;
					}
					$check = doquery( "select marks from examination_marks_students where exam_id = '".$exam[ "id" ]."' and student_id = '".$student_id."' and subject_id = '".$r[ "id" ]."'", $dblink );
					if( numrows( $check ) > 0 ) {
						$check = dofetch( $check );
						$students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "marks" ] += $check[ "marks" ];
					}
				}
				$marks = get_max_marks( $exam[ "id" ], $r[ "id" ] );
				$subject_marks = array();
				foreach( $students as $student_id => $student ){
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "max" ] = $class_tests_marks[ "max" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "min" ] = $class_tests_marks[ "min" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "max" ] = $marks[ "max" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "min" ] = $marks[ "min" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "min" ] = $students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "min" ] + $marks[ "min" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "max" ] = $students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "max" ] + $marks[ "max" ];
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "obtained" ] = $students[ $student_id ]["subject"][ $r[ "id" ] ][ "class_tests" ][ "marks" ] + $students[ $student_id ]["subject"][ $r[ "id" ] ][ "exam" ][ "marks" ];
					$students[ $student_id ][ "max" ] += $students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "max" ];
					$students[ $student_id ][ "obtained" ] += $students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "obtained" ];
					$subject_marks[] = $students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "obtained" ];
				}
				$subject_marks = array_values( array_unique( $subject_marks ) );
				arsort( $subject_marks );
				$subject_marks = array_values( $subject_marks );
				foreach( $students as $student_id => $student ){
					$students[ $student_id ]["subject"][ $r[ "id" ] ][ "rank" ] = array_search( $students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "obtained" ], $subject_marks )+1;
					$ch = doquery("select group_id from subject_2_group where subject_id = '".$r[ "id" ]."'", $dblink);
					if( numrows( $ch ) > 0 ) {
						$val = false;
						while( $ch = dofetch( $ch ) ) {
							if( $ch[ "group_id" ] == $students[ $student_id ][ "group" ] ) {
								$val = true;
							}
						}
						if( !$val ) { 
							$students[ $student_id ][ "max" ] -= $students[ $student_id ]["subject"][ $r[ "id" ] ][ "total" ][ "max" ];
							unset( $students[ $student_id ]["subject"][ $r[ "id" ] ] );
						}
					}
				}
			}
		}
		if( $exam[ "generate_marksheet" ] == 2 ) {
			foreach( $students as $student_id => $student ){
				$students[ $student_id ][ "max" ] += $students[ $student_id ][ "mid_term" ][ "max" ];
				$students[ $student_id ][ "obtained" ] += $students[ $student_id ][ "mid_term" ][ "obtained" ];
			}
		}
		$groups = array();
		foreach( $students as $student_id => $student ) {
			if( !isset( $groups[ $student[ "group" ] ] ) ) {
				$groups[ $student[ "group" ] ] = array();
			}
			$groups[ $student[ "group" ] ][] = $students[ $student_id ][ "obtained" ];
		}
		foreach( $groups as $k => $group ){
			$groups[ $k ] = array_values( array_unique( $groups[ $k ] ) );
			arsort( $groups[ $k ] );
			$groups[ $k ] = array_values( $groups[ $k ] );
		}
		foreach( $students as $student_id => $student ) {
			$students[ $student_id ][ "rank" ] = array_search( $students[ $student_id ][ "obtained" ], $groups[ $student[ "group" ] ] )+1;
		}
		usort( $students, function( $a, $b ) {
			 if ($a[ "rank" ] == $b[ "rank" ]) {
				return 0;
			}
			return ($a[ "rank" ] < $b[ "rank" ]) ? -1 : 1;
		});
		$GLOBALS["exam_result"][$exam["id"]][$class_section["id"]] = $students;
	}
	$students = $GLOBALS["exam_result"][$exam["id"]][$class_section["id"]];
	return !empty( $student_id_get )?search_by_key('id', $student_id_get, $students):$students;
}
function search_by_key( $key, $value, $array ){
	foreach ($array as $k => $v) {
       if ($v[$key] == $value) {
           return $v;
       }
   	}
   	return null;
}
function get_percent_grade($marks, $max){
	$percent = $max>0?round( $marks/$max*100):0;
	$grade = $percent >= 90 ? "A+" : ($percent >= 80 ? "A" : ( $percent >= 70 ? "B+" : ( $percent >= 60 ? "B" : ( $percent >= 50 ? "C" : ( $percent >= 40 ? "D" : ( $percent >= 34 ? "E" : "F"))))));
	return array(
		"percent" => $percent,
		"grade" => $grade
	);
	

}
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
function get_assessment( $v ){
	if( $v == 1 ) {
	 	return "E";
	} else if( $v == 2 ) {
	 	return "D";
	} else if( $v == 3 ) {
	 	return "S";
	} else if( $v == 4 ) {
	 	return "A";
	}
	return "Na";
}