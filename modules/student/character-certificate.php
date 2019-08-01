<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>
<style>
@import url('https://fonts.googleapis.com/css?family=Courgette');
.certificate{
	background:#F7E6B9;
    font-family: arial;
    margin: 0 auto;
    max-width: 640px;
    padding: 20px;
	border: 5px solid rgb(0, 172, 233);
}
.logo {
    float: left;
    margin-right: 20px;
    width: 110px;
}

.logo img {
    height: auto;
    width: 100%;
}
h1 {
    font-size: 40px;
    letter-spacing: 1px;
    margin: 12px 0;
	font-family: 'Courgette', cursive;
}
h2 {
    background: #fff none repeat scroll 0 0;
    border-bottom: 2px solid #00ace9;
    display: inline;
    font-size: 22px;
    letter-spacing: 1px;
    margin: 10px 0;
    padding: 5px;
	font-family: 'Courgette', cursive;
}
.certificate_inner{
	text-align:left;
	margin-top:20px;
	font-family: 'Courgette', cursive;
}

.border-btm {
    border-bottom: 1px dotted #000;
}
p {
    margin: 10px 0;
}
.text-center{
	text-align:center;
}
span{
	display:inline-block;
}
.left-col{
	float:left;
}
.right-col{
	float:right;
}
.clearfix:after,.clearfix:before{
	content:"";
	clear:both;
	display:block;
}
.btm-row {
    margin-top: 10%;
}
.inner-row {
    margin: 15px 0;
}
</style>
<body>
<div class="certificate">
	<div class="clearfix">
        <div class="logo">
            <img src="images/logo.png">
        </div>
        <h1>The Educators</h1>
        <h2>School Leaving Certificate</h2>
    </div>
    <div class="certificate_inner">
        <div>
            <p class="text-center" style="margin:30px 0;">has completed Secondary Education at this School during the Academic years 20......... to 20.........</p>
        </div>
        <div class="inner-row">
        	<p><span style="width:26%">This is to certify that</span> <span style="width:73%" class="border-btm"></span></p>
        </div>
        <div class="inner-row">
        	<p width="25%"><span style="width:15%">of Standard</span> <span class="border-btm" style="width:35%"></span> <span style="width:13%">Student of</span> <span class="border-btm" style="width:34%"></span></p>
        </div>
        <div class="inner-row">
        	<p><span style="width:10%">rank in</span><span class="border-btm" style="width:25%"></span><span style="width:46%">in The Educator Public School in the year</span><span class="border-btm" style="width:18%"></span></p>
        </div>
        <div class="btm-row clearfix">
        	<div class="left-col">
            	<h3>Head of Department</h3>
            </div>
            <div class="right-col">
            	<h3>Principal</h3>
            </div>
        </div>
	</div>
</body>
</html>
<?php
die();?>