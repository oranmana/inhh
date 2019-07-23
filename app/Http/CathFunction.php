<?

function tdate($format,$strtime) {
    global $fullmonth;
        $fullday = array('อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์');
        $shortday = array('อา','จ','อ','พ','พฤ','ศ','ส');
        $fullmonth = array('','มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
        $shortmonth = array('','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
        $txt = $format;
        $txt = str_replace('w', date('w', $strtime), $txt);
        $txt = str_replace('j', date('j', $strtime), $txt);
        $txt = str_replace('d', date('d', $strtime), $txt);
        $txt = str_replace('n', date('n', $strtime), $txt);
    
        $txt = str_replace('Y', date('Y', $strtime), $txt);
        $txt = str_replace('B', date('Y', $strtime)+543, $txt);
        $txt = str_replace('D', $fullday[date('w', $strtime)], $txt);
        $txt = str_replace('s', $shortday[date('w', $strtime)], $txt);
        $txt = str_replace('l', 'วัน'.$fullday[date('w', $strtime)], $txt);
        $txt = str_replace('F', $fullmonth[date('n', $strtime)], $txt);
        $txt = str_replace('m', $shortmonth[date('n', $strtime)], $txt);
        return $txt;
    }
    
function sunday($dd,$wk) {
	$w = date('w', $dd);
	$n = $wk * 7;
	return strtotime((($wk < 0 ? -$w : ($w?7-$w:0))+$n).' day', $dd);
}

?>