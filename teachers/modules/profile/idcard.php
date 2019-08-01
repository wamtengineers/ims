<?php
 if(!defined("APP_START")) die("No Direct Access");
 if(isset($_GET["id"]) && !empty($_GET["id"])){
	$employees=doquery("select * from employee where id='".slash($_GET["id"])."'", $dblink);
 }
 else{
	$employees=doquery($sql, $dblink);
 }
 if( numrows( $employees ) > 0 ){
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Employee ID Card</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i');
body{ font-family: 'Lato', sans-serif; margin:0px; text-align: center;max-width: 8.5in;padding: 20px 0;}
.wrapper{ 
	display: inline-block;
    margin: 20px 10px;
    text-align: left;
	max-width: 370px; 
	background: url(images/idcard-bg.png) no-repeat;
	position: relative; border: solid 1px #333;
	background-position: right; 
	background-size: 60% 100%;
	padding: 5px 0;
	height:192px;
}
@font-face {
font-family: 'free3of9';
src: url('fonts/free3of9.eot');
src: url('fonts/free3of9.eot') format('embedded-opentype'),
	 url('fonts/free3of9.woff2') format('woff2'),
	 url('fonts/free3of9.woff') format('woff'),
	 url('fonts/free3of9.ttf') format('truetype'),
	 url('fonts/free3of9.svg#free3of9') format('svg');
}

.card_heading{ width:100%; float:left;}
.heading{ margin-bottom:5px;}
.heading h1::after {
    border-color: transparent transparent transparent #018987;
    border-style: solid;
    border-width: 26px 0 0 20px;
    content: "";
    height: 0;
    padding: 0;
    position: absolute;
    right: -20px;
    width: 0;
}
.heading h1 {
    background: #018987;
    color: #fff;
    font-size: 12px;
    line-height: 26px;
    padding: 0 7px;
    position: relative;
	margin-top:0;
}
.card_heading {
    float: left;
    width: 30%;
}
.card_logo img {
    width: 100%;
	margin:0 auto;
}
.card_logo {
    padding: 0 8px 0 8px;
}

.card_heading p {
    font-size: 10px;
    font-weight: normal;
    line-height: 10px;
    margin: 0;
    padding: 0 0 0 6px;
    text-align: center;
}
.card_logo {
    margin-bottom: 5px;
}
.logo_area {
}
.card_image {
    float: right;
    width: 26%;
    margin-top: 10px;
}
.image {
    background: #000;
    border: 3px solid #dedcdd;
    height: 78px;
    margin-bottom: 10px;
    margin-left: -12%;
    margin-top: 10px;
    position: relative;
    width: 80px;
    border-radius: 100%;
    overflow: hidden;
}
.image img{ width:100%; height:100%; object-fit: cover;}
.student_id {
    color: #fff;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
	margin-bottom:3px;
	text-shadow: 3px 2px 4px #666;
}
.img_box {
    padding: 0 7px;
    margin-left: -40px;
    text-align: center;
}
.phone {
    color: #fff;
    font-size: 11px;
    font-weight: bold;
    padding: 0px 0 5px 0px;
    text-shadow: 3px 2px 4px #666;
}
.card_details {
    box-sizing: border-box;
    float: left;
    margin-top: 38px;
    padding: 0 11px 0 6px;
    width: 43%;
}
.detail {
    font-size: 12px;
    font-weight: normal;
    margin-bottom: 4px;
}
.detail > p {
    margin: 6px 0px ;
}

.detail > span {
    color: #0167aa;
}
.barcode p{ 
	font-family: 'free3of9';
}

.barcode {
	position: relative;
    text-align: center;
    margin-top: 10px;
    margin-left: -50px;
}
.barcode img{
	width: 110px;
}
.barcode p {
    font-family: "free3of9";
    line-height: 32px;
    font-size: 40px;
    margin: 0;
}
.barcode span {
    display: block;
    font-size: 12px;
	color:#000;
}
.emp-name{
	color: #fff;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
	text-align:center;
}
</style>
<body>
<?php
while( $employee = dofetch( $employees ) ) {
	$id = $employee[ "employee_code" ];
	$barcode = str_repeat('0', 7-strlen($id)).$id;
	$employee_image = get_employee_meta($employee['id'], "employee_image");
	$academic_year = current_academic_year();
	?>
<div class="wrapper">
	<div class="card_heading">
    	<div class="heading <?php echo $idcard_color;?>"><h1>Employee ID Card</h1></div>
        <div class="logo_area">
            <div class="card_logo">
                <img src="<?php echo $file_upload_root;?>config/<?php echo $school_logo?>" alt="image" />
            </div>
            <p><?php echo nl2br($school_address);?></p>
        </div>
    </div>
    <div class="card_details">
    	<div class="detail-box">
        	
            <div class="detail"><span>Date of Birth : </span><?php echo date_convert(get_employee_meta($employee["id"], "date_of_birth")); ?> </div>
            <div class="detail"><span>Issued On : </span><?php echo date_convert( $academic_year[ "start_date" ] )?></div>
            <div class="detail"><span>Expiry : </span><?php echo date_convert( $academic_year[ "end_date" ] )?></div>
            <div class="detail"><span> Address :</span> <?php echo get_employee_meta($employee["id"], "address"); ?> </div>
            <div class="barcode"><img src="barcode.php?text=<?php echo $barcode?>" /><span style="
                text-align: center;
                letter-spacing:7px;
                margin-left: 8px;
            "><?php echo $barcode = str_repeat('0', 7-strlen($id)).$id;?></span></div>
        </div>
    </div>
    <div class="card_image">
    	<div class="img_box">
        	<div class="emp-name"><?php echo unslash($employee["name"]); ?></div>
            
        </div>
    	<div class="image"><img src="<?php echo !empty($employee_image)?$file_upload_url."employee/".$employee_image:"images/placeholder.jpg"?>" /></div>
        <div class="img_box">
        	
            <div class="student_id"><span>Employee ID: </span><?php echo $employee["id"]?></div>
            <div class="phone">Ph:<?php $val=get_employee_meta($employee["id"], "mobile_number"); echo !empty($val)?$val:"--" ?></div>
        </div>
    </div>
    <div style="clear:both"></div>
</div>
<?php
}
?>
</body>
</html>
<?php
die();
}


