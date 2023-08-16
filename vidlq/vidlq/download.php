<?php
//session_start();
date_default_timezone_set('Europe/Moscow');

require('./settings.php');
$c = 0;
$file = './counter.txt';
$ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

$os_array = array(
	'/windows nt 10/i'     	=>  'Windows 10',
	'/windows nt 6.3/i'     =>  'Windows 8.1',
	'/windows nt 6.2/i'     =>  'Windows 8',
	'/windows nt 6.1/i'     =>  'Windows 7',
	'/windows nt 6.0/i'     =>  'Windows Vista',
	'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
	'/windows nt 5.1/i'     =>  'Windows XP',
	'/windows xp/i'         =>  'Windows XP',
	'/windows nt 5.0/i'     =>  'Windows 2000',
	'/windows me/i'         =>  'Windows ME',
	'/win98/i'              =>  'Windows 98',
	'/win95/i'              =>  'Windows 95',
	'/win16/i'              =>  'Windows 3.11',
	'/macintosh|mac os x/i' =>  'Mac OS X',
	'/mac_powerpc/i'        =>  'Mac OS 9',
	'/linux/i'              =>  'Linux',
	'/ubuntu/i'             =>  'Ubuntu',
	'/iphone/i'             =>  'iPhone',
	'/ipod/i'               =>  'iPod',
	'/ipad/i'               =>  'iPad',
	'/android/i'            =>  'Android',
	'/blackberry/i'         =>  'BlackBerry',
	'/webos/i'              =>  'Mobile'
);
$os_platform = 'Unknown device';
foreach ($os_array as $regex => $value) {
	if (preg_match($regex, $_SERVER["HTTP_USER_AGENT"])) {
		$os_platform = $value;
	}
}

function getDevice() {
	$tablet_browser = 0;
	$mobile_browser = 0;

	if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$tablet_browser++;
	}

	if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
	}

	if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}

	$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');

	if (in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}

	if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
		$mobile_browser++;
		$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
			$tablet_browser++;
		}
	}

	if ($tablet_browser > 0) {
		return 'Tablet';
	}
	else if ($mobile_browser > 0) {
		return 'Mobile';
	}
	else {
		return 'Computer';
	}   
}

$info = $ip.'|'.date('Y-m-d H:i:s').'|'.($_SERVER["HTTP_REFERER"] ?? 'Refer is empty').'|'.$_SERVER["HTTP_USER_AGENT"].'|'.$os_platform.'|'.getDevice();
file_put_contents("mycss/stats.txt", $info."\n", FILE_APPEND);

try {
	$data = [
		'chat_id' => $chatId,
		'text' => $info
	];
	$response = @file_get_contents("https://api.telegram.org/bot$token/sendMessage?".http_build_query($data));
} catch (Exception $e) {
	//
}

if(file_exists($file)) {
	$count = file_get_contents($file);
	file_put_contents($file, $count+1);
} else {
	file_put_contents($file, 1);
}

header('Location: '.$title);
