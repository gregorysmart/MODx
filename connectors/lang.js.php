<?php
ob_start();
require_once dirname(__FILE__).'/index.php';
ob_clean();

if (isset($_REQUEST['action']) && isset($_REQUEST['ctx'])
    && $_REQUEST['ctx'] != 'mgr' && $_REQUEST['ctx'] != ''
    && $_REQUEST['action'] != '' && isset($modx->actionMap[$_REQUEST['action']])) {

    $action = $modx->actionMap[$_REQUEST['action']];
}

if (isset($_REQUEST['topic']) && $_REQUEST['topic'] != '') {
    $topics = explode(',',$_REQUEST['topic']);
    foreach($topics as $topic) $modx->lexicon->load($topic);
}

$_lang = $modx->lexicon->fetch();
echo '
var _ = function(s,v) {
	var _lang = {';
	$s = '';
	while (list($k,$v) = each ($_lang)) {
		$s .= "'$k': ".'"'.esc($v).'",';
	}
	$s = trim($s,',');
	echo $s.'
	};
	if (v != null && typeof(v) == "object") {
        var t = ""+_lang[s];
        for (var k in v) {
            t = t.replace("[[+"+k+"]]",v[k]);
        }
        return t;
    } else return _lang[s];
}';

function esc($s) {
	return strtr($s,array('\\'=>'\\\\',"'"=>"\\'",'"'=>'\\"',"\r"=>'\\r',"\n"=>'\\n','</'=>'<\/'));
}

// now force caching if the size is the same

// generate unique ID
$output = ob_get_contents();
ob_end_clean();

$hash = md5($output);
$headers = $modx->request->getHeaders();
// if Browser sent ID, we check if they match
if (isset($headers['If-None-Match']) && ereg($hash, $headers['If-None-Match'])) {
	header('HTTP/1.1 304 Not Modified');
} else {
	header("ETag: \"{$hash}\"");
	header('Accept-Ranges: bytes');
	header('Content-Length: '.strlen($output));
	header('Content-Type: application/x-javascript');

    echo $output;
}