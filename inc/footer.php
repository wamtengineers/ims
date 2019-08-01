
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
                <span class="bigger-120">
                    <span class="blue bolder"><?php echo $site_title; ?></span> Application &copy; <?php echo date("Y");?>
                </span>
            </div>
        </div>
    </div>
    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div><!-- /.main-container -->
<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="assets/js/moment.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/jquery-ui.custom.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="assets/js/jquery.easypiechart.min.js"></script>
<script src="assets/js/jquery.sparkline.index.min.js"></script>
<script src="assets/js/jquery.flot.min.js"></script>
<script src="assets/js/jquery.flot.pie.min.js"></script>
<script src="assets/js/jquery.flot.resize.min.js"></script>
<script src="assets/js/ace-elements.min.js"></script>
<script src="assets/js/ace.min.js"></script>
<link href="js/chosen/chosen.css" type="text/css" rel="stylesheet" />
<script src="js/chosen/chosen.jquery.js"></script>
<script type="text/javascript">
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
	jQuery(function($) {
		$( ".datepicker" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: false,
			//isRTL:true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'dd/mm/yy'
		});
		$('.date-timepicker').datetimepicker({
			"format": 'DD/MM/YYYY hh:mm A'
		})
		$('.date-picker').datetimepicker({
			"format": 'DD/MM/YYYY'
		})
		.next().on(ace.click_event, function(){
			$(this).prev().focus();
		});
		$( ".tabs" ).tabs();
		$( ".tabs-inner" ).tabs();
		$("#entry_form").submit(function(e){
			e.preventDefault();
			$.post("index.php", $("#entry_form").serialize(), function($res){
				$res=JSON.parse($res);
				$result=$("#entry_form .result");
				$result.empty();
				if($res.status="1"){
					$result.html('<div class="alert-success">'+$res.msg+'</div>');
				}
				else{
					$result.html('<div class="alert-danger">'+$res.msg+'</div>');
				}
				$("#entry_form")[0].reset();
			});
		});
		$("#entry_form input[name=entry_number]").keydown(function(e){
			if($(this).val()>10){
				console.log($(this).val());
				//$("#entry_form").submit();
			}
		});
		$("#employee_check_in, #employee_check_out").click(function(e){
			type = $(this).attr( "id" ) == 'employee_check_out'?'out':'in';
			e.preventDefault();
			$.post("index.php", {'entry_number_2': $("input[name=entry_number_2]").val(), 'type': type}, function($res){
				$res=JSON.parse($res);
				$result=$("#entry_form_2 .result");
				$result.empty();
				if($res.status="1"){
					$result.html('<div class="alert-success">'+$res.msg+'</div>');
				}
				else{
					$result.html('<div class="alert-danger">'+$res.msg+'</div>');
				}
			});	
		});
		if($(".select_multiple").length>0) $(".select_multiple").chosen();
		$("#select_all").change(function(){
			var chckedstatus=this.checked;
			$(".list :checkbox").each(function(){
				this.checked=chckedstatus;
			});
		});
		$("#apply_bulk_action").click(function(){
			$slectedvalue=$('#bulk_action option:selected').val();
			if($slectedvalue != "null"){
				$IDs='';
				$(".list :checkbox").each(function(){
					if(this.checked)
						if(this.value!=0)
							$IDs+=this.value+',';
				});
				$IDs=$IDs.substring(0,$IDs.length-1);
				var pagename= window.location.pathname;
				pagename=pagename.substring(pagename.lastIndexOf("/") + 1);
				window.location.href=pagename+'?tab=bulk_action&action='+$slectedvalue+'&Ids='+$IDs+'&pid='+getUrlVars()["pid"]+'&ppid='+getUrlVars()["ppid"];
			}
		});
	})
</script>
<script type="text/javascript" src="assets/js/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		$(".fancybox.iframe").fancybox({
			type: "iframe",
		});
		$(".fancybox.inline").fancybox({
			type: "inline",
		});
	})
	$(".filter-btn").click(function(){
		$(".open-filter").slideToggle();
		$(this).toggleClass("active");
	});
	$(".print-view").click(function(){
		$("#for-print").slideToggle();
		$(this).toggleClass("active");
	});
	$(".links-btn").click(function(){
		$(".links-open").slideToggle();
		$(this).toggleClass("active");
	});
	$(".reset_search").click(function(){
		$form = $(this).parents("form");
		$form.find('input[type=text], select, textarea').val('');
		$form.submit();
	});
	$( ".students_filter select" ).change(function(){
		students_filter_redirect();
	});
	$( ".students_filter #search" ).keypress(function(e){
		if(e.which == 13){
			students_filter_redirect();
		}
	});
	function students_filter_redirect(){
		window.location.href='student_manage.php?tab=edit&year_id='+$( ".students_filter #year_id" ).val()+'&class_section_id='+$( ".students_filter #class_section_id" ).val()+'&student_status='+$(".students_filter  #student_status").val()+'&q='+$(".students_filter  #search").val();
	}
	$username = '<?php echo get_config('sms_provider_username');?>'
	$password = '<?php echo get_config('sms_provider_password');?>'
	$sender = '<?php echo get_config('sms_masking');?>'
	$session = '<?php
	$session = "";
	if( file_exists( "telenor.txt" ) ) {
		$sessions = explode( "|", file_get_contents( "telenor.txt" ) );
		if( isset( $sessions[1] ) && time()-$sessions[1] < 3600  ) {
			$session = $sessions[0];
		}
	}
	echo $session;
	?>'
	$(document).ready(function(){
		$( "#advanced_search_trigger" ).click(function(){
			var val = $( "#is_advanced_search" ).val();
			if( val == "0" ) {
				$( "#is_advanced_search" ).val( "1" );
				$( "#advanced_search_container" ).slideDown();
			}
			else {
				$( "#is_advanced_search" ).val( "0" );
				$( "#advanced_search_container" ).slideUp();
			}
		});
		setTimeout( function(){ 
			$.get("https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn="+$username+"&password="+$password, function( response ){
				if( response.getElementsByTagName("corpsms")[0].childNodes[1].nodeName == 'data' ){
					$session = response.getElementsByTagName("corpsms")[0].childNodes[1].textContent;
					//file_put_contents( "telenor.txt", $session."|".time() );	
					smscron();
				}
			});
		}, 3000 );
	});
	
	function smscron(){
		if( $session ) {
			$.get('<?php echo $site_url?>/send_sms<?php echo $_SESSION[ "current_school_id" ]?>.php', function( response ){
				if( response != "0" ) {
					$sms = JSON.parse( response );
					if( $sms.mobile_number && $sms.mobile_number.length == 12 ) {
						$.get('https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id='+$session+'&to='+$sms.mobile_number+'&text='+encodeURI($sms.text)+($sender?'&mask='+encodeURI($sender):''), function(){
							smscron();
						});
					}
					else{
						smscron();
					}
					//setTimeout( function(){ smscron(); }, 3000 );
				}
			});		
		}
	}
</script>
<script src="js/cropit.js"></script>
<div style="display:none;">
	<div id="fees_chalan_generate_confirm" class="confirm-popup">
    	<form action="" target="_blank">
        	<input type="hidden" name="tab" value="fees_chalan" />
            <input type="hidden" name="id" value="" />
            <input type="hidden" name="academic_year_id" value="<?php if( isset( $year_id ) && !empty( $year_id ) ) { echo $year_id; } else { if( isset( $academic_year_id ) && !empty( $academic_year_id ) ) { echo $academic_year_id; } }?>" />
            <h3>Generate Fees Chalan</h3>
            <span class="multiple-selector"><i class="fa fa-check-square"></i><i class="fa fa-square-o"></i> Multiple Months?</span>
            <div class="form-fields form-month">
                <label>From Month: </label>
                <input type="text" name="fee_chalan_year_from" value="<?php echo date( "Y" )?>"  />
                <select name="fee_chalan_month_from">
                    <?php
                    for( $i = 1; $i <= 12; $i++ ) {
                        ?>
                        <option value="<?php echo $i?>"<?php echo date( "n" )==$i?" selected":""?>><?php echo $month_array[ $i-1 ]?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-fields">
                <label><span>To </span>Month: </label>
                <input type="text" name="fee_chalan_year" value="<?php echo date( "Y" )?>"  />
                <select name="fee_chalan_month">
                    <?php
                    for( $i = 1; $i <= 12; $i++ ) {
                        ?>
                        <option value="<?php echo $i?>"<?php echo date( "n" )==$i?" selected":""?>><?php echo $month_array[ $i-1 ]?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-fields">
                <label>Due Date</label>
                <input type="text" name="due_date" class="datepicker" value="<?php echo date("j")>10?date( "d/m/Y" ):date( "10/m/Y" )?>" />
            </div>
            <input id="confirm_chalan" type="submit" value="Confirm" />
       	</form>
    </div>
    <a id="generate_chalan_confirm_link" href="#fees_chalan_generate_confirm"></a>
    <div id="marksheet_confirm" class="confirm-popup">
    	<form action="" target="_blank">
        	<input type="hidden" name="tab" value="marksheet" />
            <input type="hidden" name="id" value="" />
            <h3>Print Marksheet</h3>
            <div class="form-fields">
                <label>Academic Year</label>
                <select name="academic_year_id">
                	<?php
                    $rs = doquery( "select * from academic_year where status = 1 and school_id = '".$_SESSION["current_school_id"]."' order by start_date desc", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							?>
							<option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "is_current_year" ]?' selected':''?>><?php echo unslash( $r[ "title" ] )?></option>
							<?php
						}
					}
					?>
                </select>
            </div>
            <div class="form-fields">
                <label>Examination</label>
                <select name="examination_type_id">
                	<?php
                    $rs = doquery( "select * from examination_type where status = 1 and school_id = '".$_SESSION["current_school_id"]."'", $dblink );
					if( numrows( $rs ) > 0 ) {
						while( $r = dofetch( $rs ) ) {
							?>
							<option value="<?php echo $r[ "id" ]?>"><?php echo unslash( $r[ "title" ] )?></option>
							<?php
						}
					}
					?>
                </select>
            </div>
            <input id="confirm_chalan" type="submit" value="Confirm" />
       	</form>
    </div>
    <a id="marksheet_confirm_link" href="#marksheet_confirm"></a>
    <style>
    .confirm-popup h3 {
    margin: 0 0 20px;
    font-size: 18px;
}

.confirm-popup {
    width: 320px;
}

.confirm-popup .form-fields {
    margin-bottom: 10px;
}

.confirm-popup .form-fields input, .confirm-popup .form-fields select {
    width:  120px;
    float: right;
	margin-left: 5px;
	height:35px;
}

.confirm-popup .form-fields label {
    float:  left;
	line-height:35px;
}
.confirm-popup .form-fields label span{ display:none;}
.confirm-popup.multiple-months .form-fields label span{ display:inline;} 
.confirm-popup.multiple-months .form-month{ display:block;}
.confirm-popup .form-fields:after {
    content:  "";
    clear: both;
    display:  block;
}
span.multiple-selector {
    display:  block;
    border: solid 1px #ddd;
    padding:  5px;
    margin-bottom: 10px;
    border-radius: 5px;
    cursor:  pointer;
}

span.multiple-selector .fa-check-square, span.multiple-selector.checked .fa-square-o {
    display: none;
}

span.multiple-selector:hover, span.multiple-selector.checked {
    background-color: #eee;
}

span.multiple-selector.checked .fa-check-square {
    display: inline-block;
}
.form-fields.form-month {
    display: none;
}
    </style>
</div>
<script>
$(function() {
	$( "#generate_chalan_confirm_link,#marksheet_confirm_link" ).fancybox( {type: "inline"} );
	$( ".fees_chalan_generate" ).click( function(e){
		e.preventDefault();
		var url = $(this).attr( "href" );
		$( "#fees_chalan_generate_confirm form" ).attr( "action", url);
		id = "";
		if( url.indexOf( 'id=' ) !== -1 ){
			url = url.split( 'id=' );
			id = url[1];
		}
		$( "#fees_chalan_generate_confirm form input[name='id']" ).val( id );
		$( "#generate_chalan_confirm_link" ).click();
	});
	$( ".marksheet_generate" ).click( function(e){
		e.preventDefault();
		var url = $(this).attr( "href" );
		$( "#marksheet_confirm form" ).attr( "action", url);
		id = "";
		if( url.indexOf( 'id=' ) !== -1 ){
			url1 = url.split( 'id=' );
			id = url1[1];
		}
		$( "#marksheet_confirm form input[name='id']" ).val( id );
		tab = "";
		if( url.indexOf( 'tab=' ) !== -1 ){
			url1 = url.split( 'tab=' );
			tab = url1[1].split( '&' );;
			tab = tab[0];
		}
		console.log(tab);
		$( "#marksheet_confirm form input[name='tab']" ).val( tab );
		$( "#marksheet_confirm_link" ).click();
	});
	$( ".multiple-selector" ).click(function(){
		$(this).toggleClass( 'checked' );
		$( "#fees_chalan_generate_confirm" ).toggleClass( "multiple-months" );
		
	});
	$('.image-editor').each(function(){
		var image_editor = $(this);
		image_editor.cropit({
			imageState: {
				src: image_editor.data( 'src' ),
			},
		});
	});
	$('.rotate-cw').click(function() {
		var image_editor = $(this).parents( '.image-editor' );
		image_editor.cropit('rotateCW');
	});
	$('.rotate-ccw').click(function() {
		var image_editor = $(this).parents( '.image-editor' );
		image_editor.cropit('rotateCCW');
	});
	$('.image-editor-done').click(function() {
		var image_editor = $(this).parents( '.image-editor' );
		image_editor.find( 'input[type=file]' ).val( '' );
		var imageData = image_editor.cropit('export');
		var data = image_editor.data( "extra_fields" );
		data.img = imageData;
		$.post( image_editor.data( "url" ), data, function(){
			
		});
		$( "#"+image_editor.data( "img" ) ).attr( "src", imageData );
		$.fancybox.close();
	});
});
</script>
<style>
a.image-editor-src {
	position: relative;
	display: block;
	width:100%;
	max-width: 120px;
	margin:0 auto;
}

a.image-editor-src:before {
	display: block;
	content: "";
	padding-top: 100%;
}

.image-editor-src > img {
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
	left: 0;
	object-fit: cover;
	display: block;
}
.cropit-preview {
	background-color: #f8f8f8;
	background-size: cover;
	border: 1px solid #ccc;
	border-radius: 3px;
	margin-top: 7px;
	width: 250px;
	height: 250px;
  }
  .cropit-preview-image-container {
	cursor: move;
  }
  .image-size-label {
	margin-top: 10px;
  }
  .image-editor button {
	margin-top: 10px;
  }
</style>
<script src="js/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".timing").inputmask({"mask": "99:99"});
});
</script>
<link rel="stylesheet" href="css/flipclock.css">
<script src="js/flipclock.js"></script>
<script type="text/javascript">
		var clock;
		$(document).ready(function() {
			var clock = $('.clock').FlipClock({
				clockFace: 'TwelveHourClock',
			});
		});
</script>
<?php
if( isset( $_GET[ "done" ] ) ) {
	?>
	<script>
    $(document).ready( function(){
		parent.window.location.reload();
		parent.$.fancybox.close();		
	});
    </script>
	<?php
}
?>
<script type="text/javascript" src="js/angular.min.js"></script>
<script type="text/javascript" src="js/angular-animate.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="js/angular-moment.min.js"></script>
<script type="text/javascript" src="js/attendance.angular.js"></script>
<script type="text/javascript" src="js/chalan.angular.js"></script>
<script type="text/javascript" src="js/notification.angular.js"></script>
<script type="text/javascript" src="js/employeenotification.angular.js"></script>
<?php include("inc/upload_center.php");?>
</body>
</html>