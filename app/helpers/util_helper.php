<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function debug_pr($obj){
	echo "<pre>";
	print_r($obj);
	echo "</pre>";
}

function is_set_num($obj){
	return ($obj!='')?true:false;
}

function objToArr($obj){
	if(!is_object($obj) && !is_array($obj)){
		return $obj;
	}
	if(is_object($obj)){
		$obj = get_object_vars($obj);
	}
	return array_map('objToArr',$obj);
}

// 타입별 목록개수 세팅 & 가져오기
function listitem($type,$s_rows)
{
	$CI =& get_instance();
	if($s_rows){
		if(!is_numeric($s_rows)) $s_rows=10;
		$CI->load->model('db_users');
		$CI->db_users->user_update_config($type, "listItem", $s_rows,"");
	}else{
		$s_rows = get_code_name("user_config",$type."_listItem");
		if(!$s_rows) $s_rows = 20;
	}
	return $s_rows;
}

function makedir($path){
	if(!is_dir($path)){
		@mkdir($path, 0755);
		@chown($path, "nobody");
		@chgrp($path, "nobody");
	}
}

function chk_trans_utf8($string){
	//$string = mb_check_encoding($string, 'UTF-8') ? $string : utf8_encode($string);
	$current_encoding = mb_detect_encoding($string, 'auto');
	$string = iconv($current_encoding, 'UTF-8//IGNORE', $string);
	return $string;
}


function cut_str_with_tag($msg,$cut_size,$tail="...")
{
	if($cut_size <= 0) return $msg;
	$msg = str_replace("&mp;quot;","\"",$msg);
	if(strlen($msg) <= $cut_size) return $msg;

	for($i=0;$i<$cut_size;++$i) if(ord($msg[$i])>127) $han++;
		else $eng++;
		if($han%2) $han--;

		$cut_size = $han + $eng;

	$tmp = substr($msg,0,$cut_size);
	$tmp .= $tail;
	return $tmp;
}

function cut_str($msg,$cut_size,$tail="...")
{
	if($cut_size <= 0) return $msg;
	$msg = strip_tags($msg);
	$msg = str_replace("&mp;quot;","\"",$msg);
	if(strlen($msg) <= $cut_size) return $msg;

	for($i=0;$i<$cut_size;++$i) if(ord($msg[$i])>127) $han++;
		else $eng++;
		if($han%2) $han--;

		$cut_size = $han + $eng;

	$tmp = substr($msg,0,$cut_size);
	$tmp .= $tail;
	return $tmp;
}

function change_size($size,$type="MB")
{

	return $tmp;
}
function get_email($str){
	if(strstr($str, "<")) {
		$name = getName_extract($str);
		$email = getEmail_extract($str);
		if(!trim($name)){
			$var['name'] = "";
			$var['email'] = trim(str_replace(">","",$email));
		}else{
			$var['name'] = trim($name);
			$var['email'] = trim(str_replace(">","",$email));
		}
	}else if(strstr($str, "&lt;")) {
		$name = getName_extract($str);
		$email = getEmail_extract($str);
		if(!trim($name)){
			$var['name'] = "";
			$var['email'] = trim(str_replace("&gt;","",$email));
		}else{
			$var['name'] = trim($name);
			$var['email'] = trim(str_replace("&gt;","",$email));
		}
	}else{
		$var['email'] = trim($r_name);
		$var['name'] = trim($r_name);
	}

	if(!trim($var['email'])){
		$var['email'] = $str;
		$var['name'] = $str;
	}
	return $var;
}

function save_gmt_change($org_timestamp)
{
	$gmt_gap = get_code_name("user_config","MAIL_timegmt") - 9;
	$gmt_gap = (int)($gmt_gap * 60 * 60 * -1);
	$return_timestamp = $org_timestamp+$gmt_gap;
	return $return_timestamp;
}

function cut_date($msg, $type, $contract_type = FALSE)
{
	$msg = trim($msg);

	if ($msg === '')
	{
		$tmp = '';
	}
	else if ($msg <= 0)
	{
		$tmp = '-';
	}
	else
	{
		// example $msg: 2016-05-04 12:34:56 //

		$format_arr = array(
			'2' => 'ymdH',				// 16050412

			'3' => 'Y'.langs('lang_pub', 36).' m'.langs('lang_pub', 37).' d'.langs('lang_pub', 38),
										// 2016년 05월 04일

			'4' => 'Y-m-d H:i:s',		// 2016-05-04 12:34:56
			'5' => 'Y-m-d',				// 2016-05-04
			'6' => 'Ymd',				// 20160504
			'7' => 'U',					// unix timestamp
			'8' => 'H:i',				// 12:34

			'9' => 'm'.langs('lang_pub', 37).' d'.langs('lang_pub', 38),
										// 05월 04일

			'10' => 'Y-m-d H:i',		// 2016-05-04 12:34
			'11' => 'YmdHis',			// 20160504123456
			'12' => 'y-m-d H:i:s',		// 16-05-04 12:34:56
			'13' => 'y.m.d H:i',		// 16.05.04 12:34
			'14' => 'y.m.d H:i:s',		// 16.05.04 12:34:56
			'15' => 'y.m.d',			// 16.05.04
			'16' => 'M d',				// May 04
			'17' => 'Y.m.d H:i',		// 2016.05.04 12:34
			'18' => 'Y.m.d',			// 2016.05.04
			'19' => 'm.d',				// 05.04
			'20' => 'H:i:s',			// 12:34:56
		);

		$start_of_date = date2time(time2date('Y-m-d'));
		$end_of_date = $start_of_date + 86400;

		if ( ! array_key_exists($type, $format_arr))
		{
			$tmp = '';
		}
		else
		{
			$tmp = time2date($format_arr[$type], $msg);
			if ($contract_type && $msg > $start_of_date && $msg < $end_of_date)
			{
				$tmp = time2date($format_arr[$contract_type], $msg);
			}
		}
	}

	return $tmp;
}

function long_cut($s, $l)
{
	if( strlen($s) <= ($l+3) )
		return $s;

	if( ord($s[$l-1]) > 127 )
	{
		$nc = 2;
		while( ord($s[$l-$nc]) > 127 )
			$nc++;
		$l -= !($nc & 1);
	}

	return substr($s, 0, $l) . "...";
}

function sort_link($col, $link_text, $query_string='', $flag='asc')
{
	$patterns = array();
	$patterns[0] = '/(\&|\?)(sst)=[^&]*/i';
	$patterns[1] = '/(\&|\?)(sod)=(desc|asc)/i';
	$replacements = array();
	$replacements[0] = '';
	$replacements[1] = '';
	//$query_string = preg_replace($patterns, $replacements, $query_string);
	$query_string = query_string(array('sst','sod'),$query_string);

	$q1 = "sst=$col";
	if ($flag == 'asc')
	{
		$q2 = 'sod=asc';
		$class_name = "down";
		if ($_REQUEST['sst'] == $col)
		{
			if (strtolower($_REQUEST['sod']) == 'asc')
			{
				$q2 = 'sod=desc';
				$class_name = "up";
			}
		}
	}
	else
	{
		$q2 = 'sod=desc';
		$class_name = "up";
		if ($_REQUEST['sst'] == $col)
		{
			if (strtolower($_REQUEST['sod']) == 'desc')
			{
				$q2 = 'sod=asc';
				$class_name = "down";
			}
		}
	}

	return "<a class='$class_name' href='{$_SERVER['PHP_SELF']}?".($query_string ? $query_string."&" : "")."$q1&$q2'>{$link_text}</a>";
}

function query_string($exclude = "", $query_string = "", $add = "")
{
	if(!$query_string)
	{
		$query_string = $_SERVER['QUERY_STRING'];
	}
	parse_str($query_string, $string_arr);

	if($exclude)
	{
		if(is_array($exclude))
		{
			foreach((array)$exclude as $out)
			{
				foreach($string_arr as $key => $val)
				{
					if($out == $key)
						unset($string_arr[$key]);
				}
			}
		}
		else
		{
			foreach($string_arr as $key => $val)
			{
				if($exclude == $key)
					unset($string_arr[$key]);
			}
		}
	}
	$query_string = http_build_query($string_arr, '=', '&');
	$query_string = preg_replace('/^(\&|\?)?/i', '', $query_string);

	$query_string = str_replace("/index.php","",$query_string);

	if($add)
	{
		return $query_string ? $query_string."&".$add : $add;
	}
	else
	{
		return $query_string;
	}
}

function concat(/*arg0=[glue], ..., argN*/)
{
	if(func_num_args() < 2)return "";
	$args = func_get_args();
	$glue = array_shift($args);

	return implode($glue, $args);
}

function get_code_list($cd_field, $sst="")
{
	$CI =& get_instance();
	return $CI->property->get_index($cd_field, $sst);
}

function get_code_name($index, $key, $default="")
{

	if($_COOKIE[$key] && $index == 'user_config'){
		$return = $_COOKIE[$key];
	}else if($_SESSION[$key] && $index == 'user_config'){
		$return = $_SESSION[$key];
	}else{
		$CI =& get_instance();
		if(!$CI->session->userdata('sess_cid') && ($index == 'user_config')){
			return ;
		}
		$return = $CI->property->get($index, $key);
		if($_SESSION['sess_cid']){
			if($index == 'user_config' || $index == 'config' || $index == 'server'){
				$CI->session->set_userdata(array($key => $return));
			}
		}
	}
	if(!is_array($return)){
		if(strlen($return) == 0) $return = $default;
	}
	return $return;
}
/*
 * 캐시 파일 체크
 * @access public
 * @param string $cache_file - 캐시 경로 및 파일이름
 * @param string $expire_timestamp - 캐시된 후로부터 새로 캐시 할 시간 default 하루
 * @return string - 캐시 경로 및 파일이름
 */
function cachefile_check($cache_file, $expire_timestamp = 86400)
{
	if ( ! $cache_file) // cache_file path 없다면
	{
		return FALSE;
	}

	// 개발 서버 예외 처리
	if (ENVIRONMENT == 'development')
	{
		$expire_timestamp = 60;
	}

	if (file_exists($cache_file)) // file 있다면
	{
		$filetime = filemtime($cache_file);
		if ( ! $filetime) // modify 시간 얻기 실패시
		{
			return FALSE;
		}
		else
		{
			if ($filetime < (time() - $expire_timestamp)) // cache 시간 만료
			{
				@unlink($cache_file);
				return FALSE;
			}
			else
			{
				include $cache_file;
				return $res;
			}
		}
	}

	return FALSE;
}

/*
 * 캐시 파일 만들기
 * @access public
 * @param string $cache_file - 캐시 경로 및 파일이름
 * @param string $expire_timestamp - 캐시된 후로부터 새로 캐시 할 시간 default 하루
 * @return string - 캐시 경로 및 파일이름
 */
function cachefile_make($cache_file, $data)
{
	if ( ! $cache_file)
	{
		return FALSE;
	}

	$handle = fopen($cache_file, 'w');
	$cache_list = "<?php\n\$res=".var_export($data, TRUE).str_replace(' ','', '? >');
	fwrite($handle, $cache_list);
	fclose($handle);
}

/*
 * 캐시 파일 삭제
 * @access public
 * @param string 디비 갱신으로 캐시 파일 삭제
 *
 * count 갱신
 */
function cachefile_unlink()
{
	$count_file_path = _TMPUSER."count_list";

	if (file_exists($count_file_path)) // file 있다면
	{
		@unlink($count_file_path);
	}
}

/*
 * ea_code 다국어 가져오기
 * @access public
 * @param string $key - 다국어 변수
 * @return string - 다국어 값
 */
function langs($index,$key="")
{
	$CI =& get_instance();
	$return = $CI->property->get($index, $key);
	return $return;
}

function vars($key)
{
	$CI =& get_instance();
	return $CI->config->item($key, 'vars');
}

function get_vars($key, $index)
{
	$CI =& get_instance();
	$tmp = $CI->config->item($key, 'vars');
	return $tmp[$index];
}

/*
 * ea_code 특정값 가져오기
 * @access public
 * @param string $co_name - co_name
 * @param mixed $co_value - co_value
 * @return mixed - 값
 */

function ea_code_name($co_name, $co_value)
{
	$cache_file = make_cachefile(_CACHE."eas-{$_COOKIE['lang']}.php");
	include($cache_file);

	$code = array(array());
	foreach ((array)$res['res'] as $row)
	{
		if (!isset($code[$row['co_name']][$row['co_value']]))
		{
			$code[$row['co_name']][$row['co_value']] = $row['co_msg'];
		}
	}

	return $code[$co_name][$co_value];
}

/*
 * ea_code 특정값 리스트
 * @access public
 * @param string $co_name - co_name
 * @param string $sst - 정렬 옵션
 * @return array - 배열 값
 */

function ea_code_list($co_name, $sst)
{
	$cache_file = make_cachefile(_CACHE."eas-{$_COOKIE['lang']}.php");
	include($cache_file);

	$code_list = array(array());
	foreach ((array)$res['res'] as $row)
	{
		if (!isset($code_list[$row['co_name']][$row['co_value']]))
		{
			$code_list[$row['co_name']][$row['co_value']] = $row['co_msg'];
		}
	}
	switch ($sst)
	{
		case "1":
			ksort($code_list[$co_name]);
			break;
		case "2":
			asort($code_list[$co_name]);
			break;
		case "3":
			krsort($code_list[$co_name]);
			break;
		default :
			arsort($code_list[$co_name]);
			break;
	}

	return $code_list[$co_name];
}

function cr_code($key)
{
	$CI =& get_instance();
	if(_DOMAIN == 'mx16.wiro.kr')
		return $key;
	else{
		return $CI->property->get_cr_code($key);
	}
}

function array_filter_recursive($input, $callback = null)
{
	foreach ($input as &$value)
	{
		if (is_array($value))
		{
			$value = array_filter_recursive($value, $callback);
		}
	}

	if( is_null($callback) )
	{
		return array_filter($input);
	}
	else
	{
		return array_filter($input, $callback);
	}
}

function is_serialized($val)
{
	if (!is_string($val))return false;
	if (trim($val) == "")return false;
	if (preg_match("/^(i|s|a|o|d|b):(.*);/si",$val))return true;
	return false;
}

// 같은 컨트롤러 인지 비교
function same_controller($controller)
{
	$tmp_controller = explode("/",$_SERVER["REQUEST_URI"]);
	if($controller == $tmp_controller[2])
		return true;
	else
		return false;
}

function callback_base64_encode(&$item, $key)
{
	switch($key)
	{
	case "org_name":
		$item = base64_encode($item);
		break;
	case "arc":
		$item = str_replace("arc", "", $item);
		$item = str_replace("N", "", $item);
		break;
	case "arc_quota":
		$item = $item*1024;
		if($item == 0) $item = "";
		break;
	default:
		$item = $item;
		break;
	}
}

function s_cookie($name,$value){

	$CI =& get_instance();

	$CI->input->set_cookie(array(
		'name'   => $name,
		'value'  => $value,
		'expire' => strtotime("2038-01-01")-time(),
		'domain' => '.'._DOMAIN,
		'path'   => '/',
		'prefix' => ''
	));
}
function r_cookie($name){

	$CI =& get_instance();

	$CI->input->set_cookie(array(
		'name'   => $name,
		'expire' => 0,
		'domain' => '.'._DOMAIN,
		'path'   => '/',
		'prefix' => ''
	));
}
function g_cookie($name){
	$CI =& get_instance();
	return $CI->input->cookie($name);
}

function callback_base64_decode(&$item, $key)
{
	switch($key)
	{
	case "org_name":
		$item = addslashes(base64_decode($item));
		break;
	case "arc":
		$item = str_replace("N", "", $item);
		if($item != "")
			$item = "arc".$item;
		else
			$item = "";
		break;
	case "arc_quota":
		$item = $item/1024;
		if($item == 0) $item = "";
		break;
	default:
		$item = $item;
		break;
	}
}

function check_encoding($str,$type="UTF-8")
{
	//$arrEncode = array("UTF-8", "EUC-KR", "JIS", "SHIFT-JIS", "BIG5", "GB2312");
	$arrEncode = array("EUC-KR");
	$chk =  mb_detect_encoding($str, $arrEncode, true);
	if( $chk != $type ){
		$new_str = iconv($chk, $type."//IGNORE" , $str);
		if(trim($new_str) == '') return $str;
		else return $new_str;
	}
	else{
		return $str;
	}
}
function _decode($encoding,$user_id,$num)
{

	$CI =& get_instance();
	$CI->load->model('dbu_index');
	$mail_info = $CI->dbu_index-mail_info(array(
		"select" => "file_id",
		"num" => $num
	));
	$msg_fname = $mail_info["file_id"];
	$home_root = $_DOMAIN."/".$user_id;
}

function callback_check(&$item, $key)
{
	$item = str_replace("&","＆", $item);
	$item = str_replace("'","’", $item);
	$item = str_replace(","," ", $item);
	$item = str_replace('"',' ', $item);
	$item = str_replace('}',' ', $item);
	$item = str_replace('{',' ', $item);
	if($key!='receiver'){
		$item = str_replace(';',' ', $item);
	}
	$item = str_replace(':',' ', $item);
}
function file_size($size)
{
	$filesizename = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
	return $size ? @round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0B';
}


// SMS 발송
function sms_send($data)
{
	$CI =& get_instance();
	$CI->load->library('api_sms');
	if($CI->api_sms->sms($data))
	{
		return true;
	}
	else
	{
		return false;
	}
}

// 남은 날수 계산
function date_diff_count($type = 'M', $start, $end)
{
	list($sy, $sm, $sd) = explode("-",$start);
	$from = $start;
	$to = $end;
	$nodays=(strtotime($to) - strtotime($from))/ (60 * 60 * 24); //it will count no. of days
	$nodays=$nodays+1;
	$year = -1;
	$week = -1;
	$day = -1;
	$month = -1;
	$day2 = -1;
	for($i=0;$i<$nodays;++$i)
	{
		$p=0;
		list($y, $m, $d) = explode("-",$from);
		$datetime = strtotime("$y-$m-$d");
		$nextday = date('Y-m-d',strtotime("+1 day", $datetime));  //this will add one day in from date (from date + 1)
		if($i==0)
			$p=date('w',strtotime($from));
		else
			$p=date('w',strtotime($nextday));
		if($p==0){	// check whethere value is 0 then its sunday
			$week++;	//count variable of sunday
		}
		list($y2, $m2, $d2) = explode("-",$nextday);
		if($m!=$m2){
			$month++;
		}
		if($y!=$y2){
			$year++;
		}
		if($m==$sm && $y==$sy){
			$day2++;
		}
		$from = $nextday;
		$p++;
		$day++;
	}
	if($type == 'D') {
		return $day;
	}
	if($type == 'M') {
		return $month;
	}
	if($type == 'W') {
		return $week;
	}
	if($type == 'T') {
		return $day2;
	}
	if($type == 'Y') {
		return $year;
	}
}
function log_userid()
{
	$CI =& get_instance();
	if(!$login_id)
		$login_id = $CI->session->userdata('adm_id');
	if(!$login_id)
		$login_id = $CI->session->userdata('partner_id');
	if(!$login_id)
		$login_id = $CI->session->userdata('sess_mb_userid');

	if(!$login_name)
		$login_name = $CI->session->userdata('adm_name');
	if(!$login_name)
		$login_name = $CI->session->userdata('partner_name');
	if(!$login_name)
		$login_name = $CI->session->userdata('sess_mb_name');

	return $login_id."(".$login_name.")";
}

function p_r($array="")
{
	if($array=="") $array = $_REQUEST;
	echo "<div style='index-z:100000;left:0px;top:0px;'><pre>";
	if(is_array($array))
		print_r($array);
	else
		echo $array;
	echo "</pre></div>";
}

function getFileSize($size,$unit="",$out="Y") {
	$tmpvalue = $size;
	$ncount = 0;
	$ncount_limit = 3;
	if($unit=="KB") $ncount_limit = 1;
	if($unit=="MB") $ncount_limit = 2;
	if($unit=="GB") $ncount_limit = 3;

	while($tmpvalue){
		$tmpvalue = $tmpvalue / 1024;
		$ncount++;
		if($ncount >= $ncount_limit)
			break;
		if($unit=="" && $tmpvalue < 1024)
			break;
	}
	$addment = "B";
	if($ncount == 1) $addment = "KB";
	if($ncount == 2) $addment = "MB";
	if($ncount == 3) $addment = "GB";

	if($out == "Y")
		return number_format(round($tmpvalue, 2)).$addment;
	else
		return round($tmpvalue, 2);
}

function split_index_get($string,$index,$standard = "."){

	$st_array = explode($standard,$string);
	if($index=='last'){
		$index = count($st_array)-1;
	}

	return $st_array[$index];
}

/*
 * 키를 서브배열의 값으로 변경
 */
function array_key_change($array,$field){
	$array_remake = array();
	if(is_array($array) && count($array)>0){
		foreach($array as $key => $value){
			$array_remake[$value[$field]] = $value;
		}
	}
	return $array_remake;
}

/*
 * 키 네임 변경
 */
function array_key_name_change($array=array(), $change_kv=array() ){
	foreach($array as $num => $sub_array){
		foreach($sub_array as  $key => $value){
			if(isset($change_kv[$key])){
				$array[$num][$change_kv[$key]] = $value;
				unset($array[$num][$key]);
			}
		}
	}
	return $array;
}



// http://kr1.php.net/manual/en/function.array-column.php
if (!function_exists('array_column')) {
	function array_column($input, $column_key, $index_key = null)
	{
		if ($index_key !== null) {
			// Collect the keys
			$keys = array();
			$i = 0; // Counter for numerical keys when key does not exist

			foreach ((array)$input as $row) {
				if (array_key_exists($index_key, (array)$row)) {
					// Update counter for numerical keys
					if (is_numeric($row[$index_key]) || is_bool($row[$index_key])) {
						$i = max($i, (int) $row[$index_key] + 1);
					}

					// Get the key from a single column of the array
					$keys[] = $row[$index_key];
				} else {
					// The key does not exist, use numerical indexing
					$keys[] = $i++;
				}
			}
		}

		if ($column_key !== null) {
			// Collect the values
			$values = array();
			$i = 0; // Counter for removing keys

			foreach ((array)$input as $row) {
				if (array_key_exists($column_key, (array)$row)) {
					// Get the values from a single column of the input array
					$values[] = $row[$column_key];
					$i++;
				} elseif (isset($keys)) {
					// Values does not exist, also drop the key for it
					array_splice($keys, $i, 1);
				}
			}
		} else {
			// Get the full arrays
			$values = array_values($input);
		}

		if ($index_key !== null) {
			return array_combine($keys, $values);
		}

		return $values;
	}
}

// uri체크 (My_Controller.php에서 사용)
function chk_page_auth($uri,$chk_array)
{
	$return = false;
	foreach((array)$chk_array as $key => $chk_uri){
		$chk_uri = str_replace("/","\\/",$chk_uri);
		if(preg_match("/{$chk_uri}/i", $uri)){
			$return = true;
		}
	}
	return $return;
}
function make_log_kind($repl_uri, $data){
	if($data['mode']){
		$kind = "{$repl_uri}_{$data['mode']}";
	}else if($data['kind']){
		if($data['kind'] == 'auto_save_chk') return false;
		$kind = "{$repl_uri}_{$data['kind']}";
	}else if($data['d_kind']){
		$kind = "{$repl_uri}_{$data['d_kind']}";
	}else if($data['operation']){
		$kind = "{$repl_uri}_{$data['operation']}";
	}else{
		$kind = $repl_uri;
	}
	return strtoupper($kind);
}

function array_diff_chk($array1,$array2)
{
	$result1 = array_diff((array)$array1, (array)$array2);
	$result2 = array_diff((array)$array2, (array)$array1);
	if(count($result1) + count($result2) > 0){
		return true;
	}
	else{
		return false;
	}

}

function get_user_agent()
{

	$CI =& get_instance();
	$CI->load->library('user_agent');
	if($CI->agent->is_browser()){
		$agent = $CI->agent->browser().' '.$CI->agent->version();
	}
	elseif($CI->agent->is_robot()){
		$agent = $CI->agent->robot();
	}
	elseif($CI->agent->is_mobile()){
		$agent = $CI->agent->mobile();
	}
	else{
		$agent = 'Unidentified User Agent';
	}
	return $agent;
}

function get_platform()
{

	$CI =& get_instance();
	$CI->load->library('user_agent');
	$platform = $CI->agent->platform();
	return $platform;
}

function get_ext($file,$LOWER="LOWER"){
	//$file=eregi_replace("^.+\.([^\.]{1,})$","\\1",$file);
	$file=preg_replace("/^.+\.([^\.]{1,})$/","$1",$file);
	if($LOWER=="LOWER"){
		$file = strtolower($file);
	}
	return $file;
}

// 실제경로 가져오기(/home/mail/h0X/domain)
function getRealPath($domain){
	$realPath = readlink($this->homePath.$domain);
	$realPath = str_replace("../", $this->mailPath, $realPath);

	return $realPath;
}

// 주도메인인지, 멀티도메인인지
function getDomainKind($domain){
	$realPath = $this->getRealPath($domain);
	if(is_link($realPath)){
		return false;
	}else{
		return true;
	}
}

// 멀티도메인의 주도메인 가져오기
function getMainDomain($domain){
	if($this->getDomainKind($domain)){
		return $domain;
	}else{
		$hx = str_replace($domain,"",$this->getRealPath($domain));
		return str_replace($hx,"",readlink($this->getRealPath($domain)));
	}
}

function editor_font_info($font_key,$return_type){

	$FONT_STRING = array(
		"dotum"          => array(
			"name"  => "돋움",
			"style" => "돋움,dotum"
		),
		"dotumche"       => array(
			"name"  => "돋움체",
			"style" => "돋움체,dotumche,applegothic"
		),
		"gulim"          => array(
			"name"  => "굴림",
			"style" => "굴림,gulim"
		),
		"gulimche"       => array(
			"name"  => "굴림체",
			"style" => "굴림체,gulimche"
		),
		"batang"         => array(
			"name"  => "바탕",
			"style" => "바탕,batang,applemyungjo"
		),
		"batangche"      => array(
			"name"  => "바탕체",
			"style" => "바탕체,batangche"
		),
		"gungsuh"        => array(
			"name"  => "궁서",
			"style" => "궁서,gungsuh,gungseo"
		),
		"arial"          => array(
			"name"  => "arial",
			"style" => "arial"
		),
		"tahoma"         => array(
			"name"  => "tahoma",
			"style" => "tahoma"
		),
		"timesnewroman"  => array(
			"name"  => "times new roman",
			"style" => "times new roman"
		),
		"verdana"        => array(
			"name"  => "verdana",
			"style" => "verdana"
		),
		"couriernew"     => array(
			"name"  => "courier new",
			"style" => "courier new"
		),
		"mspgothic"      => array(
			"name"  => "Pゴシック",
			"style" => "ms pgothic, sans-serif"
		),
		"mspmincho"      => array(
			"name"  => "P明朝",
			"style" => "ms pmincho, serif"
		),
		"msgothic"       => array(
			"name"  => "ゴシック",
			"style" => "ms gothic, monospace"
		),
		"nsimsun"        => array(
			"name"  => "宋体",
			"style" => "nsimsun, monospace"
		),
		"fangsong"       => array(
			"name"  => "仿宋",
			"style" => "fangsong, monospace"
		),
		"microsoftyahei" => array(
			"name"  => "微软雅黑",
			"style" => "microsoft yahei, monospace"
		),
		"malgungothic" => array(
			"name"  => "맑은 고딕",
			"style" => "맑은 고딕, Malgun Gothic"
		),
		"nanumgothic" => array(
			"name"  => "나눔고딕",
			"style" => "나눔고딕, NanumGothic"
		)
	);

	if(!array_key_exists($font_key, $FONT_STRING)){
		$font_key = "dotum";
	}

	if($return_type=="name"){
		return $FONT_STRING[$font_key]["name"];
	}else if($return_type=="style"){
		return $FONT_STRING[$font_key]["style"];
	}
}

function cheditor1($id, $width='100%', $height='250')
{
	if(_SERVER_TYPE == 'PRU'){
		return "
		<script type='text/javascript'>
		var ed_{$id} = new cheditor('ed_{$id}');
		ed_{$id}.config.editorHeight = '{$height}';
		ed_{$id}.config.editorWidth  = '{$width}';
		ed_{$id}.config.editorFontName = '굴림';
		ed_{$id}.config.editorFontSize = '10pt';
		ed_{$id}.config.useMap = false;
		ed_{$id}.inputForm = 'tx_{$id}';
		ed_{$id}.editorPath = '../cheditor';
		</script>";
	}else{
		return "
		<script type='text/javascript'>
		var ed_{$id} = new cheditor('ed_{$id}');
		ed_{$id}.config.editorHeight = '{$height}';
		ed_{$id}.config.editorWidth  = '{$width}';
		ed_{$id}.config.editorFontName = '굴림';
		ed_{$id}.config.editorFontSize = '10pt';
		ed_{$id}.inputForm = 'tx_{$id}';
		</script>";
	}

	//ed_{$id}.config.editorFontName = '궁서';
}

function cheditor2($id, $content='')
{
	return "
	<textarea name='{$id}' id='tx_{$id}' style='display:none;'>{$content}</textarea>
	<script type='text/javascript'>
	ed_{$id}.run();
	</script>";
}

function cheditor3($id)
{
	return "document.getElementById('tx_{$id}').value = ed_{$id}.outputBodyHTML().replace(/\uFEFF/g,'');";
}

// 배열 초기값 설정
function array_setting($Array ) {
	array_walk_recursive($Array,'array_setting2');
	return $Array;
}
function array_setting2(&$item) {
	if(!is_array($item)){
		if($item == ''){
			$item = "-";
		}
	}
}

// 다운로드 파일명 변경
function down_file_name($filename, $zip="") {
	$USER_AGENT = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(preg_match('/iPhone/',$USER_AGENT)){
		$equipment = "m";
	}else if(preg_match('/Android|Mobile|samsung/',$USER_AGENT)){
		$equipment = "a";
	}
	$urlencode = "";
	$nclang = "";
	switch($_SERVER['HTTP_ACCEPT_LANGUAGE'])
	{
		case "ja":
			$nclang = "shift-jis//IGNORE";
			break;
		case "zh-TW":
			$nclang = "big5//IGNORE";
			break;
		case "zh-CN":
			$nclang = "gb2312//IGNORE";
			break;
		case "zh-tw":
			$nclang = "big5//IGNORE";
			break;
		case "zh-cn":
			$nclang = "gb2312//IGNORE";
			break;
		default: //"ko"
			$nclang = "euc-kr//IGNORE";
	}
	if($zip){
		//IE
		/*
		 * mac 에서 보낸 파일명 처리를 위한 함수
		 * if (!normalizer_is_normalized($filename)) {
		 * 	$filename = normalizer_normalize($filename);
		 * }
		 *
		 * php-intl library 를 서버에 설치 해야하고 고객문의가 적어 일단 보류
		 * */
		if(preg_match('/trident/',$USER_AGENT) || preg_match('/msie/',$USER_AGENT)){
			$tmp_filename =  iconv("UTF-8", $nclang, $filename);
			$str = "1. {$nclang} // {$filename} // {$tmp_filename} ";
		}else{
			$tmp_filename = iconv("UTF-8", $nclang, $filename);
			$str = "2. {$nclang} // {$filename} // {$tmp_filename} ";
		}
	}else{
		//IE
		if(preg_match('/trident/',$USER_AGENT) || preg_match('/msie/',$USER_AGENT)){
			$tmp_filename = rawurlencode($filename);
			//$tmp_filename = iconv("UTF-8", $nclang, $filename);
			//$tmp_filename = $filename;
			$str = "3. {$nclang} // {$filename} // {$tmp_filename} ";
		}else{
			// Edge 예외 처리
			if (strpos($_SERVER['HTTP_USER_AGENT'], "Edge") == TRUE)
			{
				$tmp_filename = iconv("UTF-8", $nclang, $filename);
			}else{
				$tmp_filename = $filename;
			}
			$str = "4. {$nclang} // {$filename} // {$tmp_filename} ";
		}
	}
	//echo $str;
	return $tmp_filename;
}

function gmtPrint($gmt,$lang){
	if(!$lang) $lang = "kr";
	if($lang == "kr"){
		switch($gmt){
			case "-12" : $gmtMent = "GMT -12:00 (날짜 변경선 서쪽)";break;
			case "-11" : $gmtMent = "GMT -11:00 (미드웨이 아일랜드, 사모아)";break;
			case "-10" : $gmtMent = "GMT -10:00 (하와이)";break;
			case "-9.5" : $gmtMent = "GMT -09:30 (마퀘사스)";break;
			case "-9" : $gmtMent = "GMT -09:00 (알래스카)";break;
			case "-8.5" : $gmtMent = "GMT -08:30 (피칸)";break;
			case "-8" : $gmtMent = "GMT -08:00 (태평양 표준시)";break;
			case "-7" : $gmtMent = "GMT -07:00 (산지 표준시_미국/캐나다)";break;
			case "-6" : $gmtMent = "GMT -06:00 (중부 표준시_미국/캐나다)";break;
			case "-5" : $gmtMent = "GMT -05:00 (동부 표준시_미국/캐나다)";break;
			case "-4.5" : $gmtMent = "GMT -04:30 (카라카스)";break;
			case "-4" : $gmtMent = "GMT -04:00 (대서양 표준시)";break;
			case "-3.5" : $gmtMent = "GMT -03:30 (뉴펀들랜드)";break;
			case "-3" : $gmtMent = "GMT -03:00 (부에노스아이레스)";break;
			case "-2" : $gmtMent = "GMT -02:00 (중앙 대서양)";break;
			case "-1" : $gmtMent = "GMT -01:00 (아조레스)";break;
			case "0" : $gmtMent = "GMT +00:00 (그리니치 표준시_런던)";break;
			case "1" : $gmtMent = "GMT +01:00 (암스테르담, 베를린, 빈)";break;
			case "2" : $gmtMent = "GMT +02:00 (아테네, 카이로)";break;
			case "3" : $gmtMent = "GMT +03:00 (모스크바, 바그다드)";break;
			case "3.5" : $gmtMent = "GMT +03:30 (테헤란)";break;
			case "4" : $gmtMent = "GMT +04:00 (바쿠, 무스카트)";break;
			case "4.5" : $gmtMent = "GMT +04:30 (카불)";break;
			case "5" : $gmtMent = "GMT +05:00 (카라치)";break;
			case "5.5" : $gmtMent = "GMT +05:30 (뉴델리, 뭄바이)";break;
			case "5.75" : $gmtMent = "GMT +05:45 (카트만두)";break;
			case "6" : $gmtMent = "GMT +06:00 (아스타나, 다카)";break;
			case "6.5" : $gmtMent = "GMT +06:30 (양곤)";break;
			case "7" : $gmtMent = "GMT +07:00 (방콕, 하노이, 자카르타)";break;
			case "8" : $gmtMent = "GMT +08:00 (베이징, 싱가폴, 타이베이)";break;
			case "9" : $gmtMent = "GMT +09:00 (서울, 도쿄)";break;
			case "9.5" : $gmtMent = "GMT +09:30 (다윈, 아델라이드)";break;
			case "10" : $gmtMent = "GMT +10:00 (괌, 캔버라, 시드니)";break;
			case "11" : $gmtMent = "GMT +11:00 (뉴 칼레도니아)";break;
			case "11.5" : $gmtMent = "GMT +11:30 (노포크 아일랜드)";break;
			case "12" : $gmtMent = "GMT +12:00 (오클랜드,피지)";break;
			case "-12" : $gmtMent = "GMT -12:00 (International Date Line West)";break;
		}
	}else{
		switch($gmt){
			case "-11" : $gmtMent = "GMT -11:00 (Midway Island, American Samoa)";break;
			case "-10" : $gmtMent = "GMT -10:00 (Hawaii)";break;
			case "-9.5" : $gmtMent = "GMT -09:30 (Marquesas)";break;
			case "-9" : $gmtMent = "GMT -09:00 (Alaska)";break;
			case "-8.5" : $gmtMent = "GMT -08:30 (Pecans)";break;
			case "-8" : $gmtMent = "GMT -08:00 (PT)";break;
			case "-7" : $gmtMent = "GMT -07:00 (Mountain Time US / Canada)";break;
			case "-6" : $gmtMent = "GMT -06:00 (CT USA / Canada)";break;
			case "-5" : $gmtMent = "GMT -05:00 (ET US / Canada)";break;
			case "-4.5" : $gmtMent = "GMT -04:30 (Caracas)";break;
			case "-4" : $gmtMent = "GMT -04:00 (AST)";break;
			case "-3.5" : $gmtMent = "GMT -03:30 (Newfoundland)";break;
			case "-3" : $gmtMent = "GMT -03:00 (Buenos Aires)";break;
			case "-2" : $gmtMent = "GMT -02:00 (Mid-Atlantic)";break;
			case "-1" : $gmtMent = "GMT -01:00 (Azores)";break;
			case "0" : $gmtMent = "GMT +00:00 (London GMT)";break;
			case "1" : $gmtMent = "GMT +01:00 (Amsterdam, Berlin, Vienna)";break;
			case "2" : $gmtMent = "GMT +02:00 (Athens, Cairo)";break;
			case "3" : $gmtMent = "GMT +03:00 (Moscow, Baghdad)";break;
			case "3.5" : $gmtMent = "GMT +03:30 (Tehran)";break;
			case "4" : $gmtMent = "GMT +04:00 (Baku, Muscat)";break;
			case "4.5" : $gmtMent = "GMT +04:30 (Kabul)";break;
			case "5" : $gmtMent = "GMT +05:00 (Karachi)";break;
			case "5.5" : $gmtMent = "GMT +05:30 (New Delhi, Mumbai)";break;
			case "5.75" : $gmtMent = "GMT +05:45 (Kathmandu)";break;
			case "6" : $gmtMent = "GMT +06:00 (Astana, Dhaka)";break;
			case "6.5" : $gmtMent = "GMT +06:30 (Yangon)";break;
			case "7" : $gmtMent = "GMT +07:00 (Bangkok, Hanoi, Jakarta)";break;
			case "8" : $gmtMent = "GMT +08:00 (Beijing, Singapore, Chinese Taipei)";break;
			case "9" : $gmtMent = "GMT +09:00 (Seoul, Tokyo)";break;
			case "9.5" : $gmtMent = "GMT +09:30 (Darwin, Adelaide)";break;
			case "10" : $gmtMent = "GMT +10:00 (Guam, Canberra, Sydney)";break;
			case "11" : $gmtMent = "GMT +11:00 (New Caledonia)";break;
			case "11.5" : $gmtMent = "GMT +11:30 (Norfolk Island)";break;
			case "12" : $gmtMent = "GMT +12:00 (Auckland, Fiji)";break;
		}
	}

	return $gmtMent;
}


//상대경로 만들기
function str_url_path() {
	$CI =& get_instance();
	//echo $CI->uri->uri_string();
	$url_cnt = explode("/",$CI->uri->uri_string());
	$url_path = '';
	$url_cnt = count($url_cnt) - 2;

	for( $i = 0 ; $i < $url_cnt ; ++$i ){
		if($i == 0)
			$url_path .= "..";
		else
			$url_path .= "/..";
	}
	if($url_cnt < 1)
		$url_path = ".";
	return $url_path;
}

// 사용자 이미지 경로 찾기
function user_photo($id,$photo) {
	$no_img = "/asset/eas/images/myphoto_default.gif";
	$tmp = explode("|", $photo);
	// 유저이미지 파일이동
	$ext = get_ext(_HOMEROOT.$tmp[0],"NO");
	$photo_url = _HOMEROOT."userImg/"._DOMAIN."/avatar/".$id.".".$ext;
	if(file_exists($photo_url))
		$photo_url = _USERIMGPATH."avatar/".$id.".".$ext;
	else
		$photo_url = $no_img;

	//$photo_url = str_url_path().$photo_url;

	return $photo_url;
}

// get header function
if(!function_exists('getallheaders')) {
	function getallheaders() {
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) != 'HTTP_') { continue; }
			$headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = trim($value);
		}
		return $headers;
	}
}

if (!function_exists('sqlite_escape_string')) {
	function sqlite_escape_string($string) {
		return SQLite3::escapeString($string);
	}
}

function tidy_conv($string, $doc_trim = 'Y') {
	if(extension_loaded('tidy')) {
		/*
		echo "<pre><textarea style='width:800px;height:800px;'>";
		echo $string;
		echo "</textarea></pre>";*/
		//$string = preg_replace("/<!--.*(mso|vml).*-->/si", "", $string);
		if($doc_trim == 'Y'){
			$tidy_config = array(
						'indent'           => 'auto',
						'clean'            => false,
						'show-body-only'   => false,
						'input-encoding'   => 'utf8',
						'output-encoding'  => 'utf8',
						'char-encoding'    => 'utf8',
						'language'         => 'kr',
						'wrap'             => 200);
			// Tidy
			$tidy = new tidy;
			$tidy->parseString($string, $tidy_config, 'utf8');
			$tidy->cleanRepair();
			$string = $tidy;

			$pattern = array(
				'/<!DOCTYPE[^\>]*>/i',
				'/<html>/i',
				'/<head>/i',
				'/<title><\/title>/i',
				'/<\/html>/i',
				'/<\/head>/i',
				'/<body>/i',
				'/<\/body>/i',
				'/<\/?textarea[^>]*>/i',
				'/<!--[^>]*-->/i'
				//'/<style>.*<\/style>/i'
			);
			$replace = array_fill(0, sizeof($pattern) - 1, "");
			$string = preg_replace($pattern, $replace, $string);
		}else{
			$tidy_config = array(
						'indent'           => 'auto',
						'clean'            => true,
						'show-body-only'   => false,
						'input-encoding'   => 'utf8',
						'output-encoding'  => 'utf8',
						'char-encoding'    => 'utf8',
						'language'         => 'kr',
						'wrap'             => 200);
			// Tidy
			$tidy = new tidy;
			$tidy->parseString($string, $tidy_config, 'utf8');
			$tidy->cleanRepair();
			$string = $tidy;

			$pattern = array(
				'/<!DOCTYPE[^\>]*>/i',
				'/<html>/i',
				'/<head>/i',
				'/<title><\/title>/i',
				'/<\/html>/i',
				'/<\/head>/i',
				'/<body>/i',
				'/<\/body>/i',
				'/<\/?textarea[^>]*>/i'
				//'/<style>.*<\/style>/i'
			);
			$replace = array_fill(0, sizeof($pattern) - 1, "");
			$string = preg_replace($pattern, $replace, $string);

		}
		/*
		echo "<pre><textarea style='width:800px;height:800px;'>";
		echo $string;
		echo "</textarea></pre>";
		exit;
		*/
	}
	return $string;
}

// 메일 주소 형식 체크
function email_chk($emailaddress){
	//여러명에게 보낼 때 공백이 포함된 주소가 있음
	$emailaddress = trim($emailaddress);
	// 이메일 형식일때
	if(preg_match("/^[-A-Za-z0-9_!]+[-A-Za-z0-9_.#&]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,20}$/", $emailaddress)){
		return true;
	}
	// 이메일 형식이 아닐때
	else{
		return false;
	}
}
function email_check($email){
	return filter_var($email , FILTER_VALIDATE_EMAIL);
}

//메신져 알림기능
function post_curl($data,$notitype="notify")
{
//echo "<pre>"; print_r($data); echo "</pre>";

	//ob_start();
	//echo $notitype."\n";
	//print_r($data);
	//$out1= ob_get_contents();
	//ob_end_clean();
	//file_put_contents("/tmp/jinwoo",$out1, FILE_APPEND);

/*
	$curl_data['type'] = "mail"; // mail,board,cal
	$curl_data['key'] = "sdfdsasds";
	$curl_data['action'] = "sdfdsasds"; //  C R U D
	$curl_data['to'] = $rec_id."@".$ju_domain;
	$curl_data['from'] = $mail_from;
	$curl_data['date'] = $mail_date;
	$curl_data['ref_url'] = urlencode("/webmail/lists?s_fnum=all_mail#view=".$mail_index);
	$curl_data['subject'] = $subject;
*/
	//echo json_encode($data,true);
	$url = "http://cpx21.in.mailplug.co.kr/".$notitype;
	$str = "curl -s --connect-timeout 1 --max-time 1 -X POST {$url} -d '".json_encode($data,true)."' -H \"Content-Type: application/json\" > /dev/null & ";
	if(!file_exists(_SCHEMA."etc/NOMSG")){
		exec($str);
	}

}

//scav 변경하기
function scanv_check($file_path,$fwdsave=""){

	if(file_exists($file_path)){

		//$offsend_target  = config_get($config_path , 'offsend_target'  ) ;
		//$offsend_setting = config_get($config_path , 'offsend_setting' ) ;
		//$forward_copy    = config_get($config_path , 'forward_copy'    ) ;
		//$offsend_start   = config_get($config_path , 'offsend_start'   ) ;
		//$offsend_end     = config_get($config_path , 'offsend_end'     ) ;
		//$forward_email   = config_get($config_path , 'forward_email'   ) ;
		//$spam_level      = config_get($config_path , 'spam_level'      ) ;
		//$spam_save_due   = config_get($config_path , 'spam_save_due'   ) ;


		$config_path = str_replace(".qmail", "config.dbs", $file_path);

		$dbconn = new SQLite3($config_path);
		$dbconn->busyTimeout(1000);
		$userQuery = $dbconn->query("select * from config where name in ('offsend_target','offsend_setting','forward_copy','offsend_start','offsend_end','forward_email','spam_level','spam_save_due')");
		if($userQuery){
			$data = array();
			while($row = $userQuery->fetchArray()){
				$data[$row['name']] = $row['value'];
			}
		}
		$dbconn->close();


		$offsend_target  = $data['offsend_target'];
		$offsend_setting = $data['offsend_setting'];
		$forward_copy    = $data['forward_copy'];
		$offsend_start   = $data['offsend_start'];
		$offsend_end     = $data['offsend_end'];
		$forward_email   = $data['forward_email'];
		$spam_level      = $data['spam_level'];
		$spam_save_due   = $data['spam_save_due'];

		if(!$spam_level) $spam_level = 'medium';
		if(!$spam_save_due) $spam_save_due = '7';
		if($spam_save_due == '0') $spam_save_due = 'delete';

		if(!$forward_copy) $forward_copy = 'N';

		$forward_email2 = str_replace("|","\n",$forward_email);

		$current = file_get_contents($file_path);
		$tmp = explode("\n",$current);

		// |/usr/local/bin/php /usr/local/bin/scanv.php
		//$tmp2 = explode(" ",$tmp[0]);
		$tmp2 = array();
		$tmp2[0] = "|/usr/local/bin/php";
		$tmp2[1] = "/usr/local/bin/scanv.php";
		$tmp2[2] = $spam_level;
		$tmp2[3] = $spam_save_due;


		if(trim($forward_email2) != '' && $forward_copy == 'N'){
			$tmp2[4] = "fwd";
		}
		else{
			$tmp2[4] = "no";
		}

		$tmp2[5] = $offsend_start;
		$tmp2[6] = $offsend_end;
		if($offsend_start)
			$tmp2[7] = ".vbody";

		$scanv = implode(" ",$tmp2);
		file_put_contents($file_path, $scanv."\n".$forward_email2);
		exec("chown -R nobody:nobody ".$file_path);
		chmod($file_path,0600);
	}
}

function gzipencode($str) {
	return '!Z!' . base64_encode(gzcompress($str));
}

function gzipdecode($str) {
	if (substr($str, 0, 3) == '!Z!')
		return gzuncompress(base64_decode(substr($str, 3)));
	return $str;
}


// 캐시파일 만들기
function file_cache_make($cache_file,$expire_timestamp){
	if(!file_exists($cache_file)) {
		$cache_fwrite = true;
	} else {
		$filetime = filemtime($cache_file);
		if($filetime && $filetime < (time() - $expire_timestamp)) {
			@unlink($cache_file);
			$cache_fwrite = true;
		}
	}
	if(ENVIRONMENT == 'development'){
		$cache_fwrite = true;
	}
	if($cache_fwrite){
		return false;
	}else{
		$result = file_get_contents($cache_file);
		return $result;
	}

}
// 메일 백업 User-Agent
function file_get($url){
	$opts = array(
		'http'   => array(
			'header' => "User_Agent: Mailplug_mailbackup \r\n"
		)
	);
	$context = stream_context_create($opts);
	$lists = @file_get_contents($url, false, $context);
	return $lists;
}

function token_get($cid,$domain)
{
	$token = md5($cid.$_SERVER['REMOTE_ADDR'].time().$domain);
	foreach(range(1,3) as $k) {
		$token = hash("sha256", $token."%$#@!");
	}
	return $token;
}

function token_set($cid, $domain, $time_salt)
{
	$token = md5($cid.$_SERVER['REMOTE_ADDR'].$time_salt.$domain);
	foreach(range(1,3) as $k) {
		$token = hash("sha256", $token."%$#@!");
	}
	return $token;
}

// 10 동안만 유효성 체크
function token_check($cid, $domain, $token)
{
	$time_salt = time();
	$chk = false;
	foreach(range(0,10) as $k) {
		if(token_set($cid, $domain, ($time_salt-$k)) == $token){
			$chk = true;
			break;
		}
	}
	return $chk;
}

function salt_add()
{
	$intermediateSalt = md5(uniqid(rand()));
	$salt = substr($intermediateSalt, 0, 25);
	return $salt;
}

function pwd_change($id, $pwd, $salt="")
{
	//$this->dbh->debug=true;

	if(!trim($id)){
		return;
		exit;
	}
	if(!trim($pwd)){
		return;
		exit;
	}
	if($salt == "")
	{
		$CI =& get_instance();
		$CI->load->model('db_users');
		$user_info = $CI->db_users->get($id);
		$salt = $user_info['crypt'];
	}
	$pwd = md5($pwd);
	foreach(range(1,3) as $k) {
		$pwd = hash("sha256", $pwd.$salt."%$#@!");
		//echo "<br>{$pwd}";
	}
	//echo "<br>{$id} {$salt} {$pwd}";
	return $pwd;
}

function get_campaign($type="banner", $lang = "kr", $cache_name="", $bn_code="")
{
	if (ENVIRONMENT == 'development') {
		$expire_timestamp = '60';
		//return '';
	} else {
		$expire_timestamp = '3600';
	}

	if($lang != "kr")
		$lang = "en";
	$CI =& get_instance();

	if($type == "banner"){
		if($bn_code == "")
			return '';
		$url = "_".$bn_code;
	}else{
		$url = "/".$CI->uri->segment('1')."/".$CI->uri->segment('2');
	}
	if(!$cache_name)
		$cache_name = $url;
	$cache_file = _TMPDOMAIN.$type.str_replace("/","_",$cache_name)."_".$lang;
	if(!file_exists($cache_file)) {
		$cache_fwrite = true;
	} else {
		$filetime = filemtime($cache_file);
		if($filetime && $filetime < (time() - $expire_timestamp)) {
			@unlink($cache_file);
			$cache_fwrite = true;
		}
	}
	if(is_dir(_DOMAINPATH)){
		$goods = get_code_name('server','goods');
		$users = get_code_name('server','users');
		$user_quota = get_code_name('server','user_quota');
	}else{
		$goods = "MAX2009";
		$users = "5";
		$user_quota = "1024";
	}

	if($cache_fwrite){
		$campaign_data = file_get_contents( _CAMPAIGN_PATH."?type=".$type."&bn_code=".$bn_code."&host="._HOST."&lang=".$lang."&domain="._DOMAIN."&goods=".$goods."&users=".$users."&user_quota=".$user_quota."&referer=".urlencode($url));
		if(is_dir(_DOMAINPATH)){
			$handle = fopen($cache_file, 'w');
			fwrite($handle, $campaign_data);
			fclose($handle);
		}
	}else{
		$campaign_data = file_get_contents($cache_file);
	}
	return $campaign_data;
}

// 악성태그 변환
function bad_tag_convert($code)
{
	return preg_replace("/\<([\/]?)(script|iframe)([^\>]*)\>/i", "&lt;$1$2$3&gt;", $code);
}

//배포된 고객과 배포되지 않은 고객 company 캘린더에 추가된 사용자가 없으면 보여주지 않음
function company_cal_unset($cal_array){
	$CI =& get_instance();
	$CI->load->model('db_calendars');
	$comp_cal_auth = $CI->db_calendars->calendar_auth_list(array(
		"calendar_seq"  => '1'
	));
	if(!$comp_cal_auth){
		$get_schedule = $CI->db_calendars->get_schedule(array(
			"where" => array("calendar_seq"  => "1")
		));
		if(count($get_schedule)){
			$CI->db_calendars->calendar_auth_insert(array(
				'calendar_seq' => '1',
				'auth_type' => 'all'
			));
		}else{
			foreach((array)$cal_array as $key => $val){
				if($val['owner_seq'] == 1 && $val['owner_user_id'] == 'postmaster'){
					unset($cal_array[$key]);
				}
			}

			// Left Menu 배열 숫자 0부터 따지므로 추가
			$i=0;
			foreach((array)$cal_array as $key => $val){
				unset($cal_array[$key]);
				$new_key = $i;
				$cal_array[$new_key] = $val;
				$i++;
			}
		}
	}
	return $cal_array;
}

// 첨부 파일 저장 하기
function attach_sha1_link($tmp_file){
	$sha1_name = sha1_file($tmp_file);
	$sub1 = substr($sha1_name,0,2);
	//$sub2 = substr($sha1_name,2,2);
	//sha1 값 못 구함
	if(!trim($sha1_name) || !file_exists($tmp_file)){
		return false;
	}
	//디렉토리 생성
	if(!is_dir(_ATTACHPATH.$sub1."/")){
		@exec("mkdir -p "._ATTACHPATH.$sub1."/");
	}
	//파일 복사
	if(!file_exists(_ATTACHPATH.$sub1."/".$sha1_name)){
		@rename($tmp_file, _ATTACHPATH.$sub1."/".$sha1_name);
	}else{
		@unlink($tmp_file);
	}
	if(file_exists(_ATTACHPATH.$sub1."/".$sha1_name))
		return $sha1_name;
	else
		return false;
}

// 첨부 파일 지우기
function attach_sha1_del($link_db, $type, $id){
	//sha1 값 못 구함
	if(!$link_db || !$type || !$id){
		return false;
	}
	$sub1 = substr($link_db,0,2);
	//$sub2 = substr($link_db,2,2);
	$sid = get_code_name('config','CUSTOM_sid');
	if(!$sid){
		$sid = file_get_contents(_SID_URL._HOST."/"._DOMAIN);
		if($sid > 0){
			$CI =& get_instance();
			$CI->load->model('db_domain');
			$CI->db_domain->update_config("CUSTOM", "sid", $sid,"");
		}else{
			echo "sid error";
			exit;
		}
	}
	//링크 삭제
	$link_name = $link_db."-".$type."-".$sid."-".$id;
	$link_src = _ATTACHPATH."{$sub1}/{$link_name}";
	@unlink($link_src);
	$tmp_name = explode("-",$link_db);
	$link_cnt = glob(_ATTACHPATH."{$sub1}/{$tmp_name[0]}-*");
	//php-fpm log 에 no such file 계속 쌓여서 변경함
	//@exec("ls "._ATTACHPATH.$sub1."/".$sub2."/".$tmp_name[0]."-* | wc -l", $link_cnt);
	if(count($link_cnt) == 0 && !is_link($link_src)){
		@unlink(_ATTACHPATH.$sub1."/".$tmp_name[0]);
	}
	if(is_link($link_src))
		return false;
	else
		return true;
}

//이중화 관련 모든 파일 읽을때 경로 만들기
function make_file_path($filename){
	$sub1 = substr($filename,0,2);
	$sub2 = substr($filename,2,2);
	$tmp_name = explode("-", $filename);
	$path[0] = _DOMAINPATH."/_ATTACH/{$sub1}/{$tmp_name[0]}";
	$path[1] = _DOMAINPATH."/_ATTACH/{$sub1}/{$sub2}/hashes/{$tmp_name[0]}";
	$path[2] = _DOMAINPATH."/:Attach:/{$sub1}/{$tmp_name[0]}";
	$path[3] = _DOMAINPATH."/_ATTACHTMP/{$sub1}/{$tmp_name[0]}";
	$path[4] = _DOMAINPATH."/_ATTACHTMP/{$sub1}/{$sub2}/hashes/{$tmp_name[0]}";
	foreach((array)$path as $val){
		if(file_exists($val)){
			$filepath = $val;
			break;
		}
	}
	return $filepath;
}

function file_upload_html5_agent($type=""){
	$user_agent = get_user_agent();
	if(strstr($user_agent,"Internet Explorer")){
		$user_agent = str_replace("Internet Explorer","",$user_agent);
		if($user_agent == '7.0') { $user_agent = trident_check(); }
		$variable_num = ($type == 'skin'? 8 : 9);
		if(trim($user_agent) <= $variable_num){
			return true;
		}
	}
	return false;
}

function file_upload_html5_select(){
	if( get_code_name("user_config","UI_topMenuSkin") > '9' || get_code_name('user','us_account') == "postmaster" ){
		if(file_upload_html5_agent()){
			$file_upload = "../skin11_common/file_upload.html";
		}else{
			$file_upload = "../skin11_common/file_upload_html5.html";
		}
	}else{
		$file_upload = "../common/file_upload.html";
	}
	return $file_upload;
}

function trident_check(){
	preg_match('/Trident\/\d{1,2}.\d{1,2};/', $_SERVER['HTTP_USER_AGENT'], $matches);
	$trident = preg_replace("@Trident\/|;@","",$matches[0]);
	switch($trident){
		case '4.0':
			$user_agent = '8.0';
			break;
		case '5.0':
			$user_agent = '8.0';
			break;
		case '6.0':
			$user_agent = '10.0';
			break;
		case '7.0':
			$user_agent = '11.0';
			break;
		default:
			$user_agent = '7.0';
			break;
	}
	return $user_agent;
}

function get_auth_type(){
	$goods = get_code_name('server', 'goods');
	switch($goods){
		case "TYPE3_DEDI":
			$type = "auth";
			break;
		default:
			$type = "normal";
			break;
	}
	return $type;
}

// 전체 권한 가져오기
function get_auth_user($mode, $user_seq="")
{
	$CI =& get_instance();
	if( ! $user_seq)
	{
		$user_seq = $CI->session->userdata('sess_id');
	}

	if( ! $user_seq)
	{
		return FALSE;
	}

	$CI->load->model('ea_auth');
	$auth = $CI->ea_auth->get_auth($user_seq, 'user');

	// postmaster
	if ($CI->session->userdata('sess_priv') == 'postmaster')
	{
		$auth['au_master'] = '1';
	}
	return $auth[$mode];
}

// 사용자의 timezone이 GMT와 얼마나 차이나는지를 return
function get_gmt_diff()
{
	$CI =& get_instance();

	$res = array();

	// mktime과 gmmktime에 사용되는 날짜는 아무 값이나 상관없다 (단, 동일한 날짜를 입력해야 함)
	$res['server_offset'] = (gmmktime(0, 0, 0, 9, 9, 2016) - mktime(0, 0, 0, 9, 9, 2016)) / 60;

	$uid = $CI->session->userdata('sess_cid');
	if($uid == "" OR $uid == "postmaster")
	{
		// local timezone 사용

		$gmt = new DateTimeZone("GMT");
		$tz_local = new DateTimeZone(ini_get("date.timezone"));
		$offset = $tz_local->getOffset(new DateTime("now", $gmt));

		$res['client_offset'] = $offset / 60;
	}
	else
	{
		// 사용자설정 화면에서 저장한 timezone 사용

		$CI->load->model('db_users');

		$tz_config = $CI->db_users->user_get_config("timegmt", "CONFIG");
		$offset = $tz_config['value'];

		$res['client_offset'] = $offset * 60;
	}

	return $res;
}

/**
 * unix timestamp를 datetime 형태로 변환
 *
 * @param	string	$date_format	datetime의 format
 * @param	int	$unix_timestamp	변환하려는 timestamp 값; 생략하면 현재 시각의 timestamp를 사용함
 * @return	datetime
 */
function time2date($date_format, $unix_timestamp = FALSE)
{
	if ($unix_timestamp === FALSE)
	{
		$unix_timestamp = time();
	}

	$gmt_diff = get_gmt_diff();

	$offset_diff = $gmt_diff['client_offset'] - $gmt_diff['server_offset'];
	$tz_correction = "0 minute";

	if ($offset_diff < 0)
	{
		$tz_correction = "{$offset_diff} minute";
	}
	else
	{
		$tz_correction = "+{$offset_diff} minute";
	}

	return date($date_format, strtotime($tz_correction, $unix_timestamp));
}

/**
 * datetime을 unix timestamp 형태로 변환
 *
 * @param	datetime	$datetime
 * @return	unix timestamp
 */
function date2time($datetime)
{
	// strtotime 함수에서 [Y.m.d] 형식 날짜를 인식하지 못해서, 구분자를 '-'로 수정
	$datetime = preg_replace("/\./", "-", $datetime);

	$gmt_diff = get_gmt_diff();

	$offset_diff = $gmt_diff['server_offset'] - $gmt_diff['client_offset'];
	$tz_correction = "0 minute";

	if ($offset_diff < 0)
	{
		$tz_correction = "{$offset_diff} minute";
	}
	else
	{
		$tz_correction = "+{$offset_diff} minute";
	}

	return strtotime($tz_correction, strtotime($datetime));
}

//알림 발송
function notify_send($do_id, $us_id, $num, $sender="", $from="")
{
	$CI =& get_instance();
	$CI->load->library('notify');
	$send_data = array(
			'do_id'  => $do_id,
			'us_id'  => $us_id,
			'num'    => $num,
			'sender' => $sender,
			'from' => $from,
		);
	$CI->notify->notify_all_send($send_data);
}

if ( ! function_exists('notify_mail'))
{
	function notify_mail($data = array())
	{
		$CI =& get_instance();
		$CI->load->library('notify');

		$CI->notify->send2mail($data);
	}
}

//array 에 0 이 있을 경우엔 제거 하지 않음
function null_filter($arr = array())
{
	return ($arr !== NULL && $arr !== FALSE && $arr !== '');
}

// base64 encode, decode
function get_base64_convert($data, $flag, $key = "")
{
	$CI =& get_instance();
	if(!$key)
		$key = $CI->config->item('encryption_key');

	if ($flag == "encode")
	{
		$res = base64_encode(openssl_encrypt($data, "aes-256-cbc", md5($key), true, str_repeat(chr(0), 16)));
	}
	else
	{
		$res = openssl_decrypt(base64_decode($data), "aes-256-cbc", md5($key), true, str_repeat(chr(0), 16));
	}

	return $res;
}

/**
 * 문자열 비교 후 일치시 원하는 문자열 반환
 *
 * @param	String	$base_value			기준 문자열
 * @param	String	$compare_value		비교 대상 문자열
 * @param	String	$equal_value		기준과 비교 대상이 같을 경우 출력할 문자열
 * @param	String	$not_equal_value	기준과 비교 대상이 다를 경우 출력할 문자열
 */
if ( ! function_exists('equal2print'))
{
	function equal2print($base_value = '', $compare_value = '', $equal_value = '', $not_equal_value = '', $alter_value = '')
	{
		$base_str = (gettype($base_value) === 'string') ? $base_value : (string)$base_value;
		$compare_str = (gettype($compare_value) === 'string') ? $compare_value : (string)$compare_value;
		$alter_str = (gettype($alter_value) === 'string') ? $alter_value : (string)$alter_value;

		if ($base_str === $compare_str)
		{
			return $equal_value;
		}
		else
		{
			if ($alter_str && ($base_str === $alter_str))
			{
				return $equal_value;
			}

			return $not_equal_value;
		}
	}
}

function equal2print_new($uri, $standard_str, $replace_str, $postfix = '')
{
	$res = "";
	$left_top_menu = "";

	$uri_part = explode("/", $uri);
	$left_top_menu = $uri_part[0];
	$type = str_replace("doc_list_", "", $uri_part[1]);

	$res = $uri_part[0]."/".$type."/";

	if ( $uri_part[2] )
	{
		if ($postfix)
		{
			$res = $left_top_menu."/".$postfix."_".$uri_part[2]."/";
		}
		else
		{
			$res = $left_top_menu."/".$uri_part[2]."/";
		}
	}

	if ( $res == $standard_str )
	{
		$res = $replace_str;
	}

	return $res;
}

/**
 * 문자열 비교 후 일치시 원하는 문자열 반환 ( request 버전 )
 *
 * @param	String	$field				GET param의 필드값
 * @param	String	$compare_value		비교 대상 문자열
 * @param	String	$equal_value		기준과 비교 대상이 같을 경우 출력할 문자열
 * @param	String	$not_equal_value	기준과 비교 대상이 다를 경우 출력할 문자열
 */
if ( ! function_exists('equal2print_request'))
{
	function equal2print_request($field = '', $compare_value = '', $equal_value = '', $not_equal_value = '')
	{
		$return_value;

		if (gettype($_REQUEST[$field]) === 'string')
		{
			$base_str = (gettype($_REQUEST[$field]) === 'string') ? $_REQUEST[$field] : (string)$_REQUEST[$field];
			$compare_str = (gettype($compare_value) === 'string') ? $compare_value : (string)$compare_value;

			$return_value = $not_equal_value;
			if (isset($_REQUEST[$field]) && ($base_str === $compare_str))
			{
				$return_value = $equal_value;
			}
		}
		else if (is_array($_REQUEST[$field])) {

			$return_value = $not_equal_value;

			if (in_array($compare_value, $_REQUEST[$field]))
			{
				$return_value = $equal_value;
			}
		}

		return $return_value;
	}
}

/**
 * 문자열 비교 후 일치시 원하는 문자열 반환 ( request 버전 )
 *
 * @param	String	$field				GET param의 필드값
 * @param	String	$exist_value		GET param의 필드값이 존재할 경우 출력할 문자열
 * @param	String	$not_exist_value	GET param의 필드값이 존재하지 않을 경우 출력할 문자열
 */
if ( ! function_exists('exist2print_request'))
{
	function exist2print_request($field = '', $exist_value = '', $not_exist_value = '')
	{
		$return_value = $not_exist_value;

		if (isset($_REQUEST[$field]))
		{
			$return_value = $exist_value;
		}

		return $return_value;
	}
}

/**
 * string 문자열 escape (postgres style)
 *
 * @param	String		$str			escape할 원본 문자열
 */
if ( ! function_exists('pg_escape_string'))
{
	function pg_escape_string($str)
	{
		$str = str_replace("'", "''", $str);

		return $str;
	}
}

/**
 * string 문자열 escape
 *
 * @param	String|Array	$str			escape할 원본 문자열|배열로 들어오면 recursive
 * @param	Boolean			$reverse		값이 없으면 escape, True이면 escape 풀기
 * @return	String|Array	$str|$str_arr	처리된 결과값
 */
if ( ! function_exists('escape_str'))
{
	function escape_str($str, $reverse = FALSE, $like = FALSE)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str_arr[$key] = escape_str($val, $reverse, $like);
			}

			return $str_arr;
		}

		if ( ! $reverse)
		{
			if (strpos(str_replace("\'","",$str),"'") !== FALSE)
			{
				if (function_exists('pg_escape_string'))
				{
					$str = pg_escape_string($str);
				}
				else
				{
					$str = addslashes($str);
				}

				if ($like === TRUE) // escape LIKE condition wildcards
				{
					$str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);
				}
			}
		}
		else
		{
			if (function_exists('pg_escape_string'))
			{
				$str = str_replace("''", "'", $str);
			}
			else
			{
				$str = stripcslashes($str);
			}

			if ($like === TRUE) // escape LIKE condition wildcards
			{
				$str = str_replace(array('\\%', '\\_'), array('%', '_'), $str);
			}
		}

		return $str;
	}
}

/**
 * 해당 사용자 권한 얻어오기
 */
if ( ! function_exists('get_auth_helper'))
{
	function get_auth_helper($data = array())
	{
		$auth = array();
		if(( ! $data['seq']) || ( ! $data['type'])) // 필수값 체크
		{
			return $auth;
		}

		$CI =& get_instance();
		$CI->load->model('ea_auth');
		$auth = $CI->ea_auth->get_auth($data['seq'], $data['type']);

		if ($CI->session->userdata('sess_priv') == 'postmaster') // postmaster 일 경우 관리자 권한 강제로 열어줌
		{
			$auth['au_master'] = TRUE;
		}

		return $auth;
	}
}

/**
 * time converter
 *
 * 조건에 따라 현재시간 or 입력된 시간을 기준으로 interval이 적용된 시간을 가져온다. (client X -> server O)
 *
 * @param	String	$timestamp		'now' 이면 function time() 으로 timestamp 적용
 * @param	String	$interval		결과 timestamp에 적용될 interval
 * @param	String	$date_format	결과 timestamp에 적용될 date format
 */
if ( ! function_exists('time_converter'))
{
	function time_converter($timestamp = "now", $interval = "now", $date_format = "Y.m.d")
	{
		// param filter
		if ($timestamp == "")
		{
			$timestamp = "now";
		}

		if ($interval == "")
		{
			$interval = "now";
		}

		if ($date_format == "")
		{
			$date_format = "Y.m.d";
		}

		// set base timestamp
		$base_timestamp = time();
		if ($timestamp != "now")
		{
			$base_timestamp = $timestamp;
		}

		return date($date_format, strtotime($interval, $base_timestamp));
	}
}

/**
 * 검색 범위를 systemtime을 기준으로 생성해준다
 *
 * 한 주의 기준 : 일요일 ~ 토요일, 한달의 기준 : 1일 ~ 말일
 * @param	String	$format		date 출력 포맷
 * @param	String	$str_date	ex) '2016-09-04'
 */
if ( ! function_exists('search_date_range_helper'))
{
	function search_date_range_helper($format = "Y.m.d", $str_date = "")
	{
		$search_date_range_arr = get_code_list('search_date_range');
		$search_date_range = array();

		// $str_date = "2016-09-04"; // For Test
		$str2timestamp = ($str_date != "") ? date2time($str_date) : time();
		// echo "-- 오늘 : '" . $str_date . "' (" . time_converter($str2timestamp, 'now', 'l') . ")<br />"; // For Test

		if (count($search_date_range_arr) > 1) // prevent code : 'search_date_range'
		{
			foreach ($search_date_range_arr as $code => $txt)
			{
				$search_date_range[$code]['txt'] = $txt;

				// 계산 : 일 단위
				if(($code == 1) || ($code == 2))
				{
					$base_timestamp = strtotime(time2date('Y-m-d H:i:s', $str2timestamp));
					$str_interval = "today";	// 금일
					if ($code == 2)				// 전일
					{
						$str_interval .= " -1 day";
					}

					$search_date_range[$code]['s_date'] = time_converter($base_timestamp, $str_interval, $format);
					$search_date_range[$code]['e_date'] = time_converter($base_timestamp, $str_interval, $format);
				}

				// 계산 : 주 단위
				if(($code == 3) || ($code == 4))
				{
					$base_timestamp = strtotime(time2date('Y-m-d H:i:s', $str2timestamp));
					$str_interval = "this week monday";	// 금주
					if ($code == 4)						// 전주
					{
						$str_interval .= " -1 week";
					}

					$search_date_range[$code]['s_date'] = time_converter($base_timestamp, "{$str_interval} -1 day", $format);
					$search_date_range[$code]['e_date'] = time_converter($base_timestamp, "{$str_interval} +5 day", $format);
				}

				// 계산 : 월 단위
				if (($code == 5) || ($code == 6))
				{
					$base_timestamp = strtotime(time2date('Y-m-01 00:00:00', $str2timestamp));
					$str_interval = "this month";	// 금월
					if ($code == 6)					// 전월
					{
						$str_interval .= " -1 month";
					}

					$search_date_range[$code]['s_date'] = time_converter($base_timestamp, $str_interval, $format);
					$search_date_range[$code]['e_date'] = time_converter($base_timestamp, "{$str_interval} +1 month -1 day", $format);
				}

				// 계산 : 년 단위
				if (($code == 7) || ($code == 8))
				{
					$base_timestamp = strtotime(time2date('Y-m-d H:i:s', $str2timestamp));
					$str_interval = "1st";	// 금년
					if ($code == 8)			// 전년
					{
						$str_interval .= " -1 year";
					}

					$search_date_range[$code]['s_date'] = time_converter($base_timestamp, "jan {$str_interval}", $format);
					$search_date_range[$code]['e_date'] = time_converter($base_timestamp, "dec 3{$str_interval}", $format);
				}
			}
		}

		return $search_date_range;
	}
}

/**
 * mime type에 mapping되는 약속된 string을 반환해준다
 *
 * @param	String	$type		file의 mime type
 * @param	String	$check_res	ex) image/png -> img
 */
if ( ! function_exists('mime_type_check'))
{
	function mime_type_check($type = "") {
		$check_res = "";

		switch ($type) {
			case "image/png" : 																	// png
			case "image/gif" : 																	// gif
			case "image/jpeg" : 																// jpeg, jpg, jpe
			case "image/pjpeg" : 																// jpeg, jpg, jpe
				$check_res = "img";
				break;
			case "application/pdf" : 															// pdf
			case "application/download" :
			case "application/x-download" :														// pdf
			case "application/force-download" :													// pdf
			case "binary/octet-stream" :														// pdf

				$check_res = "pdf";
				break;
			case "application/x-mspowerpoint" : 												// ppt, pot, pps, ppa
			case "application/vnd.ms-powerpoint" : 												// ppt
			case "application/vnd.openxmlformats-officedocument.presentationml.presentation" : 	// pptx, pptm
			case "application/vnd.openxmlformats-officedocument.presentationml.template" : 		// potx
			case "application/vnd.openxmlformats-officedocument.presentationml.slideshow" : 	// ppsx
			case "application/vnd.ms-powerpoint.addin.macroEnabled.12" : 						// ppam
			case "application/vnd.ms-powerpoint.presentation.macroEnabled.12" : 				// pptm
			case "application/vnd.ms-powerpoint.template.macroEnabled.12" : 					// potm
			case "application/vnd.ms-powerpoint.slideshow.macroEnabled.12" : 					// ppsm
				$check_res = "ppt";
				break;
			case "application/msword" : 														// doc, word, dot
			case "application/vnd.openxmlformats-officedocument.wordprocessingml.document" : 	// docx, docm
			case "application/vnd.openxmlformats-officedocument.wordprocessingml.template" : 	// dotx
			case "application/vnd.ms-word.document.macroEnabled.12" : 							// docm
			case "application/vnd.ms-word.template.macroEnabled.12" : 							// dotm
				$check_res = "word";
				break;
			case "application/xls" :
			case "application/excel" : 															// xl
			case "application/msexcel" :
			case "application/x-excel" :
			case "application/x-msexcel" : 														// xls
			case "application/x-ms-excel" :
			case "application/x-dos_ms_excel" :
			case "application/vnd.ms-excel" : 													// xlsx, xls, xlt, xla
			case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" : 			// xlsx, xlsm
			case "application/vnd.openxmlformats-officedocument.spreadsheetml.template" : 		// xlts
			case "application/vnd.ms-excel.sheet.macroEnabled.12" : 							// xlsm
			case "application/vnd.ms-excel.template.macroEnabled.12" : 							// xltm
			case "application/vnd.ms-excel.addin.macroEnabled.12" : 							// xlam
				$check_res = "excel";
				break;
			case "application/x-hwp" : 															// hwp
			case "application/haansofthwp" : 													// hwp
			case "application/vnd.hancom.hwp" : 												// hwp
			case "application/x-hwt" : 															// hwt
			case "application/haansofthwt" : 													// hwt
			case "application/vnd.hancom.hwt" : 												// hwt
			case "application/vnd.hancom.hml" : 												// hml
			case "application/haansofthml" : 													// hml
			case "application/vnd.hancom.hwpx": 												// hwpx
				$check_res = "han";
				break;
			default :
				$check_res = "etc";
				break;
		}

		return $check_res;
	}
}

/**
 * get_token
 */
if ( ! function_exists('get_token'))
{
	function get_token()
	{
		$CI =& get_instance();

		$token_name = $CI->security->get_csrf_token_name();
		$token = $CI->input->cookie($token_name);

		if ( ! $token)
		{
			$token = $CI->security->get_csrf_hash();
		}

		return $token;
	}
}

/**
 * Get year range
 */
if ( ! function_exists('get_interval_range'))
{
	function get_interval_range($data)
	{
		if (!$data)
		{
			return False;
		}

		$current_year = ($data["year"])?$data["year"]:date("Y");
		$s_year = (int)$data["s_year"] - 1;
		$e_year = (int)$data["e_year"] + 1;

		$year_range = range($s_year, $e_year);

		$res = array(
			"current_year" => $current_year,
			"year_range" => $year_range,
		);

		return $res;
	}
}

/**
 * 휴가 종류의 value를 한글에서 영문으로 변환
 *
 * 다국어와 무관하게 한글->영문 으로 바꾸는 용도
 * 결재문서 종결 시 스크립트에서 사용
 */
if ( ! function_exists('convert_vacation_type'))
{
	function convert_vacation_type($vacation_type)
	{
		$convert_map = array(
			'연차' => 'annual',
			'포상휴가' => 'reward',
			'경조휴가' => 'family-event',
			'생리휴가' => 'menstrual',
			'기타휴가' => 'others',
		);

		return $convert_map[$vacation_type];
	}
}

/* End of file MY_form_helper.php */
/* Location: ./app/helpers/My_form_helper.php */
