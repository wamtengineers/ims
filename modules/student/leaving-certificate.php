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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>leaving</title>
</head>
<style>
@font-face {
    font-family: 'arizoniaregular';
    src: url('../fonts/arizonia-regular-webfont.woff2') format('woff2'),
         url('../fonts/arizonia-regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}
@font-face {
    font-family: 'poppinsregular';
    src: url('../fonts/poppins-regular-webfont.woff2') format('woff2'),
         url('../fonts/poppins-regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}
body{
	margin:0px;
}
.clearfix:before{
	display:block;
	content:"";
	clear:both;
}
.clearfix:after{
	display:block;
	content:"";
	clear:both;
}
.wrapper{
	max-width:1075px;
	width:100%;
	margin:0 auto;
}
.bg{
	background:url(../../images/leaving%20certificate-bg.jpg) no-repeat top center / cover;
	padding:100px;
	background-size: 100%;
}
.clr{
	clear:both;
}
.head-top{
	text-align:center;
    font-family: 'poppinsregular';
}
.logo{
	width:150px;
}
.logo,.certify {
	text-align:center;
	margin:0 auto;
}
.certify img{
	width:35%;
}
img{
	max-width:100%;
}
.content{
    font-family: 'arizoniaregular';
}
.date{
	width:30%;
	border-bottom:solid 1px rgba(0,0,0,1.00);	
	float: right;
	margin:0px 0px 20px 0px;
}
.wrapper h2{
	margin:0px;
	display:inline-block;
}
.wrapper .head-top h2 {
    margin-bottom: 10px;
}
.name{
	width:30%;
	border-bottom:solid 1px rgba(0,0,0,1.00);
	float: left;
	margin:0px 0px 20px 0px;
}
.footer{
	margin-bottom:20px;
}
.student{
	margin-bottom:20px;
	width:100%;
	border-bottom:solid 1px rgba(0,0,0,1.00);
}
.student h2{
	margin:0 15px 0 0;
	float:left;
}
.student h2.right{
	float:right;
	width:50%;
}
.student span {
    float: left;
	font-size:24px;
    display: block;
}
.text{
	text-align:center;
	margin-bottom:20px;
}
.text p{
	margin:0 0 55px 0;
	color:#eb5b35;
	font-size:40px;
}
.tea {
    border-bottom: 0px;
	border-top: solid 1px rgba(0,0,0,1.00);
	text-align:center;
    font-family: 'poppinsregular';
	font-size:12px;
	text-transform:uppercase;
	padding:5px 0px 0px 0px;
}
.student input[type="text"]{border: none;
    padding: 10px 5px;
    width: 100%;
}
@media print {
	.bg{
		background-size:99%;
	}
	* {-webkit-print-color-adjust:exact;}
	.logo a img{
		width:30%;
	}
	.content h2{
		font-size:16px;
	}
	.text p{
		font-size:30px;
		margin-bottom:50px;
	}
}
</style>
<body>
<?php
while( $student = dofetch( $students ) ) {
	$id = $student[ "id" ];
	?>
	<div class="wrapper">
        <div class="bg">
            <div class="header">
                <div class="head-top">
                    <?php echo get_config( 'fees_chalan_header' )?>
                </div>
                <div class="logo">
                    <img src="<?php echo $file_upload_url;?>config/<?php echo $school_logo?>">
                </div>
                <div class="certify">
                	<img src="../images/logo2.jpg">
                </div>
            </div>
            <div class="content">
                <div class="main">
                    <div class="name">
                        <h2>G.R No:</h2>
                        <span><?php echo get_student_meta($student["id"], "gr_no");?></span>
                    </div>
                    <div class="date">
                        <h2>Date:</h2>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="student clearfix">
                    <h2>Name of Student:</h2>
                    <span><?php echo unslash($student["student_name"]); ?></span>
                </div>
                <div class="student clearfix">
                    <h2>Father's / Guardian's Name:</h2>
                    <span><?php echo unslash($student["father_name"]); ?></span>
                </div>
                <div class="student clearfix">
                    <h2>Religion:</h2>
                    <span><?php echo get_student_meta($student["id"], "religion");?></span>
                    <h2 class="right">Cast:</h2>
                    <span><?php echo get_student_meta($student["id"], "cast");?></span>
                </div>
                <div class="student clearfix">
                    <h2>Date of Birth:</h2>
                    <span><?php echo date_convert($student["birth_date"]); ?></span>
                    <h2 class="right">Place of Birth:</h2>
                    <span><?php echo get_student_meta($student["id"], "birth_place");?></span>
                </div>
                <div class="student clearfix">
                    <h2>Date of Birth in Words:</h2>
                    <span></span>
                </div>
                <div class="student clearfix">
                    <h2>Last School Attended To:</h2>
                    <span><?php echo date_convert(get_student_meta($student["id"], "last_school_attended_to_date"));?></span>
                </div>
                <div class="student clearfix">
                    <h2>Date of Admission:</h2>
                    <span><?php echo date_convert($student["addmission_date"]);?></span>
                </div>
                <div class="student clearfix">
                    <h2>Last School Attended From:</h2>
                    <span><?php echo date_convert(get_student_meta($student["id"], "last_school_attended_from_date"));?></span>
                </div>
                <div class="student clearfix">
                    <h2>School Dues:</h2>
                </div>
                <div class="student clearfix">
                    <h2>Reason of Leaving School:</h2>
                </div>
                <div class="student clearfix">
                    <h2>Date of Leaving:</h2>
                </div>
                <div class="student clearfix">
                    <h2>Progress:</h2>
                    <span></span>
                    <h2 class="right">Conduct:</h2>
                    <span></span>
                </div>
                <div class="student clearfix">
                    <h2>Remarks for Student:</h2>
                    <span style="display:inline-block; width:675px;"><input type="text" /></span>
                </div>
                <div class="text">
                    <p>Certified that the above information is in accordcane with the School General Register and other Records</p>
                </div>
            </div>
            <div class="footer">
                <div class="name tea">
                    <h2>class teacher</h2>
                </div>
                <div class="date tea">
                    <h2>principal</h2>
                </div>
                <div class="clr"></div>
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