<?php
 if(!defined("APP_START")) die("No Direct Access");
 if(isset($_GET["id"]) && !empty($_GET["id"])){
	$students=doquery("select * from student where id='".slash($_GET["id"])."'", $dblink);
 }
 else{
	$students=doquery($sql, $dblink);
 }
 if( numrows( $students ) > 0 ){
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>Student Envelope</title>
</head>
<link type="text/css" rel="stylesheet" href="css/idcard.css" />
<style>
.detail{ margin-bottom:2px;}
.wrapper {
    display: inline-block;
    margin: 20px 10px;
    text-align: left;
	height: auto;
}
.card_heading {
    width: 38%;
	margin-top:15px;
}
.heading h1::after{
	right: -14%;
}
body {
    text-align: center;
    max-width: 8.5in;
	padding:20px 0;
}
.addr{
    text-overflow: ellipsis;
}
.card_details {
    margin-top: 5px;
	width:61%;
}
.heading {
    margin-bottom: 0;
}

.detail.name span {
	font-weight: normal;
}
.detail.name {
    font-weight: bold;
}
.detail{
	font-size: 15px;
	line-height:20px;
	margin-bottom:8px;
}
.barcode {
    bottom: 4px;
}
.blue .card_logo {
    padding-left: 4px;
    padding-right: 11px;
}

.blue .logo_area p {line-height: 12px;margin-top: 10px;}

.logo_area {}

.blue .heading h1 {
    background: #3882e7;
}

.blue .heading h1::after {
    border-color: transparent transparent transparent #3882e7;
}

.heading h1::after {
    border-width: 26px 0 0 18px;
}

.blue .card_image {
    background-color: #3882e7;
}
.img_box{ padding:0; padding-left:5px;}
.detail-box h1{
	font-size:16px;
	margin:0;
}
.card_details > p {
    margin: 0;
    font-size: 12px;
    text-align: right;
}
</style>
<body>
<?php
while( $student = dofetch( $students ) ) {
	$id = $student[ "id" ];
	?>
    <div class="wrapper">
        <div class="card_heading">
            <div class="logo_area">
                <div class="card_logo" style="margin-bottom:5px;">
                    <img src="<?php echo $file_upload_url;?>config/<?php echo $school_logo?>" alt="image" />
                </div>
            </div>
        </div>
        <div class="card_details">
        	<p>SID: <?php echo $id;?></p>
            <div class="detail-box">
            	<h1>To,</h1>
                <div class="detail name"><?php echo unslash($student["father_name"]); ?></div>
                <div class="detail addr"><?php echo empty($student["address"])?"--":unslash($student["address"]); ?></div>
                <div class="detail"><span>Phone: </span><?php echo get_student_meta($student["id"], "phone"); ?></div>
            </div>
        </div>
    </div>
    <?php
}
?>
</body>
</html>
<?php
die();
}


