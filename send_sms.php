<?php
include("inc/db.php");
include("inc/utility.php");
$smss = doquery( "select * from sms_bulk where status = 1 order by ts desc limit 0, 1", $dblink );
if( numrows( $smss ) > 0 ) {
	while( $sms = dofetch( $smss ) ) {
		doquery( "update sms_bulk set status = 0 where id = '".$sms[ "id" ]."'", $dblink );
		$r = array();
		$mobile_number = $sms[ "mobile_number" ];
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
		$r["mobile_number"] = $mobile_number;
		//$r["mobile_number"] = '923455055959';
		$r["text"]=unslash($sms[ "text" ]);
		//$sms[ "text" ] .= $sms[ "mobile_number" ];
		//$sms[ "mobile_number" ] = '923463891662';
		//sendsms( unslash( $sms[ "mobile_number" ] ), unslash( $sms[ "text" ] ) );
		echo json_encode( $r );
	}
}
else{
	echo "0";
}