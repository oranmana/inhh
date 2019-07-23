<?php

use App\Models\Common;
use App\Models\Calendar as Cal;

// Date Function //
function isdate($str) {
	return (DateTime::createFromFormat('Y-m-d G:i:s', $str) !== FALSE);
}
function edate($date=0, $format=0) {
	$d = strtotime($date ? $date : 'now');
	$tmp = date('d-M-Y', $d);
	if($format==1) $tmp = date('d-M-Y (H:i)', $d);
	if($format==3) $tmp = date('d-M-Y (D)', $d);
	if($format==7) $tmp = date('Y-m-d', $d);
	if($format==8) $tmp = date('Y-m-d (H:i:s)', $d);
	if($format==9) $tmp = date('Ymd', $d);
	if($format==10) $tmp = date('Y-m-d', $d);
	if($format==11) $tmp = date('j F, Y', $d);
	if($format==12) $tmp = date('d-M', $d);
	if($format==13) $tmp = date('d M', $d);
	if($format==14) $tmp = date('M d (D)', $d);
	if($format==21) $tmp = date('(d) H:i', $d);
	if($format==22) $tmp = date('(d/M) H:i', $d);
//	if($format==22) $tmp = date('d-M-Y (H:i)', $d);
	if (date('Y',$d) < 1971) $tmp='';
	return ($date ? $tmp : '');
}
function tdate($date, $format=0) {
	$d = strtotime($date);
	$mns = array(
		array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'),
		array('มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม')
	);
	$dn = date('j',$d);
	$mnm = date('n',$d);
	$mn = ($format < 10 ? $mns[0][$mnm-1] : $mns[1][$mnm-1]) ;
	$yn = date('Y',$d)+543;

	$tmp = $dn.' '.$mn.' '.$yn;
	return $tmp;
}

function age( $date1, $date2=0, $format=1) {
	if($date2==0) $date2 = date('Y-m-d');
	$d1 = explode('-', date('Y-m-d', strtotime($date1)));
	$d2 = explode('-', date('Y-m-d', strtotime($date2)));
	if (strtotime($date2) < strtotime($date1)) {
		$d3 = $d2;
		$d2 = $d1;
		$d1 = $d3;
	}
	$y = ($d2[0] - $d1[0] - ($d2[1] < $d1[1] ? 1 : 0));
	$m = ($d2[1] + ($d2[1] < $d1[1] ? 12 : 0) - $d1[1] - ($d2[2] < $d1[2] ? 1 : 0) );
	$d = ($d2[2] + ($d2[2] < $d1[2] ? 30 : 0) - $d1[2] );
	// day diff need to verify month of diff
	$tmp = array('y'=>$y,'m'=>$m,'d'=>$d );
	if ($format==1) $tmp = ($y ? $y.'y' : '').($m? $m.'m':'');
	if ($format==2) $tmp = $m;
	return $tmp;
}
// Times //
function mindiff($more, $less) {
	// $less and $more in strtotime()
	$val =  ($more && $less && $more > $less ? floor(($more - $less) / 60) : 0 );
	return $val;
}
// commons //
function cm($id,$name='name') {
	$cm = Common::where('id',$id)->first();
	return $cm[$name];
}
// number format //
function zero($num,$f) {
	return str_pad($num, $f, '0', STR_PAD_LEFT);
}
function fnum($num, $dc=0, $p=0, $dx=0) {
	// $num = number amount
	// $dc = decimal digit
	// $p = boolean percentage
	// $dx = maximum digit
	$tx = number_format(abs($num), ($dx > $dc ? $dx : $dc));
	$n = $dx;
	while ($n-- > $dc) {
		if (substr($tx,-1)=='0') $tx = substr($tx, 0, strlen($tx)-1);
	}
	if (substr($tx,-1)==".") $tx = substr($tx ,0, (strlen($tx)-1));
	if ($p) $tx .= '%';
	if ($num < 0) $tx = "(".$tx.")";
	if ($num==0) $tx = '-';
	return $tx;
}

function shifttime($shiftcode) {
	$tin = [
		3678=>['in'=>['08:30',0],'out'=>['17:30',0] ], 
		3679=>['in'=>['08:30',0],'out'=>['16:30',0] ],  
		3680=>['in'=>['16:30',0],'out'=>['00:30',1] ], 
		3681=>['in'=>['00:30',1],'out'=>['08:30',1] ]
	];
	return $tin[$shiftcode];
}

function otcode($sh,$ot1,$ot2,$ot3) {
	$common = \App\Models\Common::find($sh);
	return ($ot1 ? fnum($ot1,0,0,2) : '')
		. $common->code
		. ($ot3 ? ($ot2 ? fnum($ot2,0,0,2) . '/' . fnum($ot3,0,0,2) : '') 
			: $ot2 ? fnum($ot2,0,0,2) : '');
}

function worktime($dt,$sh,$ot1,$ot2,$ot3) {
	$baseday = date('d',strtotime($dt));
	$shift = shifttime($sh);
	$tbegin = strtotime('+'. $shift['in'][1] . ' day', strtotime($dt . ' ' . $shift['in'][0]) );
	$tend  = strtotime('+'. $shift['out'][1] . ' day', strtotime($dt . ' ' . $shift['out'][0]) );
	if ($ot1) {
		$tbegin = strtotime('-'. ($ot1*60) . ' min', $tbegin);
	}
	if ($ot2) {
		if ($ot3 > 0) {
			$tbegin2 = strtotime('+'. ($ot3*60) . ' min', $tend);
			$tend2 = strtotime('+'. ($ot2*60) . ' min', $tbegin2);
		} else {
			$tend = strtotime('+'. ($ot2*60) . ' min', $tend);
		}
	}
	return date('H:i'. (date('d',$tbegin)!=$baseday?' (d)':''), $tbegin) 
		. '-'. date('H:i'. (date('d',$tend)!=$baseday?' (d)':''), $tend) 
		. (isset($tbegin2) ? ' - ' 
			. date('H:i'. (date('d',$tbegin2)!=$baseday?' (d)':''), $tbegin2) .'-'
			. date('H:i'. (date('d',$tend2)!=$baseday?' (d)':''), $tend2) : '');
}
function cpday($ofdate, $date) {
	$fm = date('m', strtotime($ofdate));
	$tm = date('m', strtotime($date));
	return date('d', strtotime($date) ) . ($fm != $tm ? ($fm > $tm ? '-' : '+') : ''); 
}

function PayDate($mth,$cm,$wage) {
	$payon = strtotime($mth . (
		$cm == 3186 ? $wage ? 15 : date('t', strtotime($mth.'01')) : 21
	));            
	if ($mth < '201905') {
		$payon = strtotime($mth . (
			$cm == 3186 ? $wage ? 15 : date('t', strtotime($mth.'01')) : 28
		));
	}
	$cal = Cal::where('cldate', date('Y-m-d', $payon) )->first();
	while ( date('w', $payon) == 6 || $cal->holiday !=0 ) {
		$payon = strtotime('-1 day', $payon);
		$cal = Cal::where('cldate', date('Y-m-d', $payon))->first();
	}
	return $payon;
}

?>